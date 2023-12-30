<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Mail Workflow',
    'description' => 'Create a set of mails with a timeline to send for each user',
    'category' => 'module',
    'author' => 'Gernot Ploiner',
    'author_email' => 'gp@webprofil.at',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
