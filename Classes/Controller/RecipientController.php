<?php

declare(strict_types=1);

namespace WEBprofil\WpMailworkflow\Controller;

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use WEBprofil\WpMailworkflow\Domain\Model\Queue;
use WEBprofil\WpMailworkflow\Domain\Model\Recipient;
use WEBprofil\WpMailworkflow\Domain\Repository\MailGroupRepository;
use WEBprofil\WpMailworkflow\Domain\Repository\QueueRepository;
use WEBprofil\WpMailworkflow\Domain\Repository\RecipientRepository;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;

/**
 * This file is part of the "Mail Workflow" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 Gernot Ploiner <gp@webprofil.at>, WEBprofil - Gernot Ploiner e.U.
 */

/**
 * RecipientController
 */
#[AsController]
class RecipientController extends ActionController
{

    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        protected readonly IconFactory $iconFactory,
        private readonly RecipientRepository   $recipientRepository,
        private readonly QueueRepository       $queueRepository,
        private readonly MailGroupRepository   $mailGroupRepository
    )
    {
    }

    /**
     * action list
     *
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
    {
        // use TYPO3 BE-Layout:
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        // load data:
        $recipients = $this->recipientRepository->findAll();
        $this->view->assign('recipients', $recipients);

        $this->shortcutButton($buttonBar);
        $this->queueButton($buttonBar);

        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * action new
     *
     * @return ResponseInterface
     */
    public function newAction(): ResponseInterface
    {
        // use TYPO3 BE-Layout:
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        // load data:
        $mailGroups = $this->mailGroupRepository->findAll();
        $this->view->assign('mailGroups', $mailGroups);

        $this->shortcutButton($buttonBar);
        $this->queueButton($buttonBar);

        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * action create
     *
     * @param Recipient $newRecipient
     */
    public function createAction(Recipient $newRecipient)
    {
        $this->recipientRepository->add($newRecipient);
        $persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $persistenceManager->persistAll();
        $this->createQueue($newRecipient);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param Recipient $recipient
     * @IgnoreValidation("recipient")
     * @return ResponseInterface
     */
    public function editAction(Recipient $recipient): ResponseInterface
    {
        // use TYPO3 BE-Layout:
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        // load data:
        $mailGroups = $this->mailGroupRepository->findAll();
        $this->view->assign('mailGroups', $mailGroups);
        $this->view->assign('recipient', $recipient);

        $this->shortcutButton($buttonBar);
        $this->queueButton($buttonBar);

        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * action update
     *
     * @param Recipient $recipient
     */
    public function updateAction(Recipient $recipient)
    {
        $this->recipientRepository->update($recipient);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param Recipient $recipient
     */
    public function deleteAction(Recipient $recipient): void
    {
        $queues = $this->queueRepository->findByRecipient($recipient);
        foreach ($queues as $queue) {
            /** @var Queue $queue */
            if (!$queue->getIsSent()) {
                $this->queueRepository->remove($queue);
            }
        }
        $this->recipientRepository->remove($recipient);
        $this->redirect('list');
    }

    /**
     * @param Recipient $recipient
     * @return void
     * @throws IllegalObjectTypeException
     */
    private function createQueue(Recipient $recipient): void
    {
        $mailGroup = $recipient->getMailGroup();
        foreach ($mailGroup->getMails() as $mail) {
            $hour = 0;
            $minute = 0;
            $sendAt = clone $recipient->getStart();
            $sendAt->modify('+ ' . $mail->getDaysToSend() . ' days');
            if ($mail->getSendTime()) {
                $hour = (int)$mail->getSendTime()->format('H');
                $minute = (int)$mail->getSendTime()->format('i');
            }
            $sendAt->setTime($hour, $minute);
            $queue = GeneralUtility::makeInstance(Queue::class);
            $queue->setRecipient($recipient);
            $queue->setMail($mail);
            $queue->setSendAt($sendAt);
            $this->queueRepository->add($queue);
        }
    }


    // shortcut menu Button:
    private function shortcutButton($buttonBar)
    {
        $shortcutButton = $buttonBar->makeShortcutButton()
            ->setRouteIdentifier('wp_mailworkflow')
            ->setDisplayName('Mailworkflow');
        $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }

    private function queueButton($buttonBar)
    {
        // Queue Button:
        $url = $this->uriBuilder->reset()->uriFor('list', [], 'Queue');
        $list = $buttonBar->makeLinkButton()
            ->setHref($url)
            ->setTitle('Queue')
            ->setShowLabelText('Link')
            ->setIcon($this->iconFactory->getIcon('actions-heart', Icon::SIZE_SMALL));
        $buttonBar->addButton($list, ButtonBar::BUTTON_POSITION_LEFT, 1);
    }

}
