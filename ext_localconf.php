<?php

defined('TYPO3') or die('Access denied.');

// Few xclasses to make TYPO3 to create copy of images even if not needed.
// This way we can make optimization on copies always to not destroy original images.
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Core\Resource\Service\FileProcessingService::class] = [
    'className' => SourceBroker\Imageopt\Xclass\FileProcessingService::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class] = [
    'className' => SourceBroker\Imageopt\Xclass\ContentObjectRenderer::class
];
