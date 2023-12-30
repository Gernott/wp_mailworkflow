<?php

declare(strict_types=1);

return [
    'web_module' => [
        'parent' => 'tools',
        'access' => 'user',
        'workspaces' => 'live',
        'extensionName' => 'WpMailworkflow',
        'iconIdentifier' => 'module-mailworkflow',
        'labels' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_mailworkflow.xlf',
        'controllerActions' => [
            \WEBprofil\WpMailworkflow\Controller\RecipientController::class => [
                'list', 'new', 'create', 'edit', 'update', 'delete'
            ],
            \WEBprofil\WpMailworkflow\Controller\QueueController::class => [
                'list', 'delete'
            ],
        ],
    ],
];
