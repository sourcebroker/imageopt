<?php

defined('TYPO3_MODE') || die('Access denied.');

if (TYPO3_MODE !== 'FE') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:imageopt/Configuration/TsConfig/Page/tx_imageopt.tsconfig">'
    );
}

// For TYPO3 9.0+ use the scheduler task "Execute console commands"
if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) <= 9000000) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\SourceBroker\Imageopt\Task\OptimizeFalProcessedImagesTask::class] = [
        'extension' => 'imageopt',
        'title' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:task.OptimizeFalProcessedImagesTask.title',
        'description' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:task.OptimizeFalProcessedImagesTask.description',
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\SourceBroker\Imageopt\Task\OptimizeFolderImagesTask::class] = [
        'extension' => 'imageopt',
        'title' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:task.OptimizeFolderImagesTask.title',
        'description' => 'LLL:EXT:imageopt/Resources/Private/Language/locallang_db.xlf:task.OptimizeFolderImagesTask.description',
    ];
}

// Few xclasses to make TYPO3 to create copy of images even if not needed.
// This way we can make optimization on copies always to not destroy original images.
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Core\Resource\Service\FileProcessingService::class] = [
    'className' => SourceBroker\Imageopt\Xclass\FileProcessingService::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class] = [
    'className' => SourceBroker\Imageopt\Xclass\ContentObjectRenderer::class
];
