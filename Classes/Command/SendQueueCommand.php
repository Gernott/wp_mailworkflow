<?php

namespace WEBprofil\WpMailworkflow\Command;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\ServerRequestFactory;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use WEBprofil\WpMailworkflow\Domain\Model\Mail;
use WEBprofil\WpMailworkflow\Domain\Model\Queue;
use WEBprofil\WpMailworkflow\Domain\Model\Recipient;
use WEBprofil\WpMailworkflow\Domain\Repository\QueueRepository;

/**
 * GenerateInvoicesCommandController
 */
class SendQueueCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ?QueueRepository $queueRepository = null;

    private ?MailerInterface $mailer = null;

    public function injectQueueRepository(QueueRepository $queueRepository): void
    {
        $this->queueRepository = $queueRepository;
    }

    public function injectMailer(MailerInterface $mailer): void
    {
        $this->mailer = $mailer;
    }

    public function __construct(
        private readonly ViewFactoryInterface $viewFactory
    ) {
        parent::__construct();
    }

    public function configure()
    {
        $this->setDescription('Send mails from the queue.')
            ->addArgument(
                'templatesPath',
                InputArgument::REQUIRED,
                'Path to mail templates: EXT:wp_mailworkflow/Resources/Private/Backend/Templates/'
            )
            ->addArgument(
                'partialsPath',
                InputArgument::REQUIRED,
                'Path to mail partials: EXT:wp_mailworkflow/Resources/Private/Backend/Partials/'
            )
            ->addArgument(
                'layoutsPath',
                InputArgument::REQUIRED,
                'Path to mail layouts: EXT:wp_mailworkflow/Resources/Private/Backend/Layouts/',
            )
            ->addArgument(
                'templateName',
                InputArgument::REQUIRED,
                'Template name for mails: Email'
            )
            ->addArgument(
                'senderMail',
                InputArgument::REQUIRED,
                'E-Mail address of the Mails sender'
            )
            ->addArgument(
                'senderName',
                InputArgument::REQUIRED,
                'Name of the Mails sender'
            );
    }

    /**
     *  GenerateInvoicesCommand
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);

        // Materialize the query result: the queue state is persisted while iterating.
        $queues = iterator_to_array($this->queueRepository->findToSend());
        $sent = 0;
        $failed = 0;

        foreach ($queues as $queue) {
            /** @var Queue $queue */
            if (!$queue->getMail() instanceof Mail || !$queue->getRecipient() instanceof Recipient) {
                $failed++;
                $this->logger?->error('Queue entry is missing its mail or recipient record, skipping.', [
                    'queue' => $queue->getUid(),
                ]);
                continue;
            }

            try {
                $email = $this->createMailMessage($queue, $input);
            } catch (\Throwable $exception) {
                // A single broken mail must never abort the whole queue run, otherwise
                // the already sent entries stay unmarked and are sent again and again.
                $failed++;
                $this->logger?->error('Could not render queued mail, skipping.', [
                    'queue' => $queue->getUid(),
                    'mail' => $queue->getMail()->getUid(),
                    'recipient' => $queue->getRecipient()->getEmail(),
                    'exception' => $exception,
                ]);
                continue;
            }

            try {
                $this->mailer->send($email);
            } catch (\Throwable $exception) {
                // Not delivered, so the entry stays in the queue and is retried next run.
                $failed++;
                $this->logger?->error('Could not send queued mail, retrying on next run.', [
                    'queue' => $queue->getUid(),
                    'recipient' => $queue->getRecipient()->getEmail(),
                    'exception' => $exception,
                ]);
                continue;
            }

            try {
                // Persist per entry, directly after delivery: whatever happens with the
                // remaining entries, this mail can never be sent a second time.
                $queue->setIsSent(true);
                $queue->setSent(new \DateTime());
                $this->queueRepository->update($queue);
                $persistenceManager->persistAll();
            } catch (\Throwable $exception) {
                $this->logger?->critical('Mail was delivered but the queue entry could not be marked as sent. Aborting to prevent duplicate deliveries.', [
                    'queue' => $queue->getUid(),
                    'recipient' => $queue->getRecipient()->getEmail(),
                    'exception' => $exception,
                ]);
                $io->error('Queue entry ' . $queue->getUid() . ' could not be marked as sent, aborting.');
                return Command::FAILURE;
            }

            $sent++;
        }

        $io->writeln(sprintf('Sent %d mail(s), %d failure(s).', $sent, $failed));

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    private function createMailMessage(Queue $queue, InputInterface $input): MailMessage
    {
        $body = $this->generateBody($queue->getMail()->getMailtext(), $queue, $input);
        $email = GeneralUtility::makeInstance(MailMessage::class);
        $email->from(new Address($input->getArgument('senderMail'), $input->getArgument('senderName')))
            ->to(new Address($queue->getRecipient()->getEmail(), $queue->getRecipient()->getFirstName()))
            ->subject($queue->getMail()->getSubject())
            ->html($body);
        if ($queue->getMail()->getAttachment()) {
            $filePath = Environment::getPublicPath() . $queue->getMail()->getAttachment()->getOriginalResource()->getPublicUrl();
            $email->attachFromPath($filePath);
        }

        return $email;
    }

    /**
     * @param string $mailtext
     * @param Queue $queue
     * @param InputInterface $input
     * @return string
     */
    private function generateBody(string $mailtext, Queue $queue, InputInterface $input): string
    {
        $bodytext = str_replace(array(
            "{firstName}",
            "{lastName}",
            "{email}",
            "{parameter1}",
            "{parameter2}",
            "{parameter3}",
            "{parameter4}",
            "{parameter5}"
        ), array(
            $queue->getRecipient()->getFirstName(),
            $queue->getRecipient()->getLastName(),
            $queue->getRecipient()->getEmail(),
            $queue->getRecipient()->getParameter1(),
            $queue->getRecipient()->getParameter2(),
            $queue->getRecipient()->getParameter3(),
            $queue->getRecipient()->getParameter4(),
            $queue->getRecipient()->getParameter5()
        ), $mailtext);

        // Erstellen einer ServerRequest-Instanz für CLI:
        /** @var ServerRequestInterface $request */
        $request = GeneralUtility::makeInstance(ServerRequestFactory::class)->createServerRequest('GET', '/');
        $request = $request->withAttribute('applicationType', 2);

        // ViewFactoryInterface (v13/v14-compatible replacement for StandaloneView):
        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: [GeneralUtility::getFileAbsFileName($input->getArgument('templatesPath'))],
            partialRootPaths: [GeneralUtility::getFileAbsFileName($input->getArgument('partialsPath'))],
            layoutRootPaths: [GeneralUtility::getFileAbsFileName($input->getArgument('layoutsPath'))],
            request: $request,
        );
        $view = $this->viewFactory->create($viewFactoryData);
        $view->assign('bodytext', $bodytext);

        // ViewHelpers that resolve links (e.g. <f:transform.html>) fall back to the global
        // request: TYPO3\CMS\Frontend\Typolink\LinkFactory::createUri() hands
        // $GLOBALS['TYPO3_REQUEST'] to ContentObjectRenderer::setRequest(), which does not
        // accept null. On CLI that global is never set, so the rendering has to provide it.
        // An existing request (scheduler run inside the backend) is kept untouched and
        // restored afterwards.
        $globalRequestIsMissing = !($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface;
        if ($globalRequestIsMissing) {
            $GLOBALS['TYPO3_REQUEST'] = $request;
        }

        try {
            return (string)$view->render($input->getArgument('templateName'));
        } finally {
            if ($globalRequestIsMissing) {
                unset($GLOBALS['TYPO3_REQUEST']);
            }
        }
    }

}
