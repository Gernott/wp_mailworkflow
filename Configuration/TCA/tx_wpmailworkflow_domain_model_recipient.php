<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient',
        'label' => 'start',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'searchFields' => 'first_name,last_name,email,parameter1,parameter2,parameter3,parameter4,parameter5',
        'iconfile' => 'EXT:wp_mailworkflow/Resources/Public/Icons/tx_wpmailworkflow_domain_model_recipient.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'start, first_name, last_name, email, parameter1, parameter2, parameter3, parameter4, parameter5, mail_group'],
    ],
    'columns' => [

        'start' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.start',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date,required',
                'default' => null,
            ],
        ],
        'first_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.first_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'last_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.last_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,email,required',
                'default' => ''
            ]
        ],
        'parameter1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.parameter1',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'parameter2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.parameter2',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'parameter3' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.parameter3',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'parameter4' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.parameter4',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'parameter5' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.parameter5',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'mail_group' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_db.xlf:tx_wpmailworkflow_domain_model_recipient.mail_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_wpmailworkflow_domain_model_mailgroup',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
    
    ],
];
