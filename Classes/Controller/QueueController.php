<?php

declare(strict_types=1);

namespace WEBprofil\WpMailworkflow\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use WEBprofil\WpMailworkflow\Domain\Model\Queue;
use WEBprofil\WpMailworkflow\Domain\Repository\QueueRepository;

/**
 * This file is part of the "Mail Workflow" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 Gernot Ploiner <gp@webprofil.at>, WEBprofil - Gernot Ploiner e.U.
 */

/**
 * QueueController
 */
class QueueController extends ActionController
{

    public function __construct(
        private readonly QueueRepository       $queueRepository
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
        $limit = (int)$this->settings['queue']['limit'];
        $queues = $this->queueRepository->findLast($limit);
        $this->view->assign('queues', $queues);
        $this->view->assign('limit', $limit);
        return $this->htmlResponse();
    }

    /**
     * action delete
     *
     * @param Queue $queue
     * @return void
     * @throws StopActionException
     * @throws IllegalObjectTypeException
     */
    public function deleteAction(Queue $queue): void
    {
        $this->queueRepository->remove($queue);
        $this->redirect('list');
    }

}
