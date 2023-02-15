<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_mailgroup',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title',
        'iconfile' => 'EXT:wp_mailworkflow/Resources/Public/Icons/tx_wpmailworkflow_domain_model_mailgroup.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'title, mails, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, '],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_mailgroup.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'default' => ''
            ],
        ],
        'mails' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_mailgroup.mails',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_wpmailworkflow_domain_model_mail',
                'foreign_field' => 'mailgroup',
                'foreign_sortby' => 'sorting',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'useSortable' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],

        ],
    
    ],
];
