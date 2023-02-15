<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_queue',
        'label' => 'is_sent',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'searchFields' => '',
        'iconfile' => 'EXT:wp_mailworkflow/Resources/Public/Icons/tx_wpmailworkflow_domain_model_queue.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'is_sent, send_at, sent, recipient, mail'],
    ],
    'columns' => [

        'is_sent' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_queue.is_sent',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
                'default' => 0,
            ]
        ],
        'send_at' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_queue.send_at',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime,required',
                'default' => time()
            ],
        ],
        'sent' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_queue.sent',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'recipient' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_queue.recipient',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_wpmailworkflow_domain_model_recipient',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
        'mail' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_queue.mail',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_wpmailworkflow_domain_model_mail',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
    
    ],
];
