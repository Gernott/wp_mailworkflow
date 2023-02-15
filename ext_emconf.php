<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Mail Workflow',
    'description' => 'Create a set of mails with a timeline to send for each user',
    'category' => 'module',
    'author' => 'Gernot Ploiner',
    'author_email' => 'gp@webprofil.at',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
