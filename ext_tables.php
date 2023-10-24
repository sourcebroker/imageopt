<?php

defined('TYPO3') or die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_imageopt_domain_model_providerresult',
            'EXT:imageopt/Resources/Private/Language/locallang_csh_tx_imageopt_domain_model_providerresult.xlf'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_imageopt_domain_model_executorresult',
            'EXT:imageopt/Resources/Private/Language/locallang_csh_tx_imageopt_domain_model_executorresult.xlf'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_imageopt_domain_model_optimizationoptionresult',
            'EXT:imageopt/Resources/Private/Language/locallang_csh_tx_imageopt_domain_model_optimizationresult.xlf'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            'tx_imageopt_domain_model_optimizationstepresult',
            'EXT:imageopt/Resources/Private/Language/locallang_csh_tx_imageopt_domain_model_optimizationresult.xlf'
        );
    }
);
