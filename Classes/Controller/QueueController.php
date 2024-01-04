<?php

declare(strict_types=1);

namespace WEBprofil\WpMailworkflow\Controller;

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use WEBprofil\WpMailworkflow\Domain\Model\Queue;
use WEBprofil\WpMailworkflow\Domain\Repository\QueueRepository;
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
 * QueueController
 */
#[AsController]
class QueueController extends ActionController
{

    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        protected readonly IconFactory $iconFactory,
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
        // use TYPO3 BE-Layout:
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();

        // load data:
        $limit = (int)$this->settings['queue']['limit'];
        $queues = $this->queueRepository->findLast($limit);
        $this->view->assign('queues', $queues);
        $this->view->assign('limit', $limit);

        $this->shortcutButton($buttonBar);
        $this->recipientButton($buttonBar);

        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    /**
     * action delete
     *
     * @param Queue $queue
     * @return ForwardResponse
     * @throws IllegalObjectTypeException
     */
    public function deleteAction(Queue $queue): ForwardResponse
    {
        $this->queueRepository->remove($queue);
        return (new ForwardResponse('list'));
    }

    /**
     * shortcut menu Button
     *
     * @param $buttonBar
     * @return void
     */
    private function shortcutButton($buttonBar): void
    {
        $shortcutButton = $buttonBar->makeShortcutButton()
            ->setRouteIdentifier('wp_mailworkflow')
            ->setDisplayName('Mailworkflow');
        $buttonBar->addButton($shortcutButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }

    /**
     * recipient menu Button
     *
     * @param $buttonBar
     * @return void
     */
    private function recipientButton($buttonBar): void
    {
        // Queue Button:
        $url = $this->uriBuilder->reset()->uriFor('list', [], 'Recipient');
        $list = $buttonBar->makeLinkButton()
            ->setHref($url)
            ->setTitle('Recipient')
            ->setShowLabelText('Link')
            ->setIcon($this->iconFactory->getIcon('actions-heart', Icon::SIZE_SMALL));
        $buttonBar->addButton($list, ButtonBar::BUTTON_POSITION_LEFT, 1);
    }

}
