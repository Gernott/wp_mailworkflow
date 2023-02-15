<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'WpMailworkflow',
        'tools',
        'mailworkflow',
        '',
        [
            \WEBprofil\WpMailworkflow\Controller\RecipientController::class => 'list, new, create, edit, update, delete',
            \WEBprofil\WpMailworkflow\Controller\QueueController::class => 'list, delete',
            
        ],
        [
            'access' => 'user,group',
            'icon'   => 'EXT:wp_mailworkflow/Resources/Public/Icons/user_mod_mailworkflow.svg',
            'labels' => 'LLL:EXT:wp_mailworkflow/Resources/Private/Language/locallang_mailworkflow.xlf',
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_wpmailworkflow_domain_model_mailgroup', 'EXT:wp_mailworkflow/Resources/Private/Language/locallang_csh_tx_wpmailworkflow_domain_model_mailgroup.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wpmailworkflow_domain_model_mailgroup');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_wpmailworkflow_domain_model_mail', 'EXT:wp_mailworkflow/Resources/Private/Language/locallang_csh_tx_wpmailworkflow_domain_model_mail.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wpmailworkflow_domain_model_mail');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_wpmailworkflow_domain_model_recipient', 'EXT:wp_mailworkflow/Resources/Private/Language/locallang_csh_tx_wpmailworkflow_domain_model_recipient.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wpmailworkflow_domain_model_recipient');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_wpmailworkflow_domain_model_queue', 'EXT:wp_mailworkflow/Resources/Private/Language/locallang_csh_tx_wpmailworkflow_domain_model_queue.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_wpmailworkflow_domain_model_queue');
})();
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder