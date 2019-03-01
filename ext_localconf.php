<?php

defined('TYPO3_MODE') || die('Access denied.');

if (TYPO3_MODE !== 'FE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][]
        = \SourceBroker\Imageopt\Command\ImageoptCommandController::class;
}

if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 8007000) {
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['EXTCONF']['imageopt']['database'] = \SourceBroker\Imageopt\Database\Database76::class;
} else {
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['EXTCONF']['imageopt']['database'] = \SourceBroker\Imageopt\Database\Database87::class;
}

// Few xclasses to make TYPO3 to create copy of images even if not needed.
// This way we can make optimization on copies always to not destroy original images.
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Core\Resource\Service\FileProcessingService::class] = [
    'className' => SourceBroker\Imageopt\Xclass\FileProcessingService::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class] = [
    'className' => SourceBroker\Imageopt\Xclass\ContentObjectRenderer::class
];
