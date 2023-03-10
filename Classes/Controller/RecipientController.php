<?php

declare(strict_types=1);

namespace WEBprofil\WpMailworkflow\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use WEBprofil\WpMailworkflow\Domain\Model\Queue;
use WEBprofil\WpMailworkflow\Domain\Model\Recipient;
use WEBprofil\WpMailworkflow\Domain\Repository\MailGroupRepository;
use WEBprofil\WpMailworkflow\Domain\Repository\QueueRepository;
use WEBprofil\WpMailworkflow\Domain\Repository\RecipientRepository;

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
class RecipientController extends ActionController
{

    public function __construct(
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
        $recipients = $this->recipientRepository->findAll();
        $this->view->assign('recipients', $recipients);
        return $this->htmlResponse();
    }

    /**
     * action new
     *
     * @return ResponseInterface
     */
    public function newAction(): ResponseInterface
    {
        $mailGroups = $this->mailGroupRepository->findAll();
        $this->view->assign('mailGroups', $mailGroups);
        return $this->htmlResponse();
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
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("recipient")
     * @return ResponseInterface
     */
    public function editAction(Recipient $recipient): ResponseInterface
    {
        $mailGroups = $this->mailGroupRepository->findAll();
        $this->view->assign('mailGroups', $mailGroups);
        $this->view->assign('recipient', $recipient);
        return $this->htmlResponse();
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
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
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
}
