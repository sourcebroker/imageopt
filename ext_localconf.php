<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// For performance issues do not do anything on FE context
if (TYPO3_MODE !== 'FE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \SourceBroker\Imageopt\Command\ImageoptCommandController::class;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationGif',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderGifGifsicle::class,
        [
            'title' => 'Optimize gif image with command line executable "gifsicle"',
            'description' => 'Optimize gif image with command line executable "gifsicle" so it will take less space.',
            'available' => true,
            'priority' => 90,
            'quality' => 80,
            'os' => '',
            'exec' => 'gifsicle',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderGifGifsicle::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationGif',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderGifTinypng::class,
        [
            'title' => 'Optimize gif image with tinypng.com',
            'description' => 'Optimize gif image with tinypng.com so it will take less space.',
            'available' => true,
            'priority' => 80,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderGifTinypng::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationGif',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderGifKraken::class,
        [
            'title' => 'Optimize gif image with Kraken.io',
            'description' => 'Optimize gif image with Kraken.io so it will take less space.',
            'available' => true,
            'priority' => 70,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderGifKraken::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationGif',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderGifImageoptim::class,
        [
            'title' => 'Optimize png image with Imageoptim.com',
            'description' => 'Optimize png image with Imageoptim.com so it will take less space.',
            'available' => true,
            'priority' => 60,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderGifImageoptim::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationJpeg',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegJpegoptim::class,
        [
            'title' => 'Optimize jpeg image with command line executable "jpegoptim"',
            'description' => 'Optimize jpeg image with command line executable "jpegoptim" so it will take less space.',
            'available' => true,
            'priority' => 90,
            'quality' => 80,
            'os' => '',
            'exec' => 'jpegoptim',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegJpegoptim::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationJpeg',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegJpegrescan::class,
        [
            'title' => 'Optimize jpeg image with command line executable "jpegrescan"',
            'description' => 'Optimize jpeg image with command line executable "jpegrescan" so it will take less space.',
            'available' => true,
            'priority' => 90,
            'quality' => 80,
            'os' => '',
            'exec' => 'jpegrescan',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegJpegrescan::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationJpeg',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegJpegtran::class,
        [
            'title' => 'Optimize jpeg image with command line executable "jpegtran"',
            'description' => 'Optimize jpeg image with command line executable "jpegtran" so it will take less space.',
            'available' => true,
            'priority' => 90,
            'quality' => 80,
            'os' => '',
            'exec' => 'jpegtran',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegJpegtran::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationJpeg',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegTinypng::class,
        [
            'title' => 'Optimize jpeg image with tinypng.com',
            'description' => 'Optimize jpeg image with tinypng.com so it will take less space.',
            'available' => true,
            'priority' => 100,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegTinypng::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationJpeg',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegKraken::class,
        [
            'title' => 'Optimize jpeg image with Kraken.io',
            'description' => 'Optimize jpeg image with Kraken.io so it will take less space.',
            'available' => true,
            'priority' => 70,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegKraken::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationJpeg',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegImageoptim::class,
        [
            'title' => 'Optimize png image with Imageoptim.com',
            'description' => 'Optimize png image with Imageoptim.com so it will take less space.',
            'available' => true,
            'priority' => 60,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegImageoptim::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationPng',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngOptipng::class,
        [
            'title' => 'Optimize png image with command line executable "optipng"',
            'description' => 'Optimize png image with command line executable "optipng" so it will take less space.',
            'available' => true,
            'priority' => 90,
            'quality' => 80,
            'os' => '',
            'exec' => 'optipng',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngOptipng::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationPng',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngPngcrush::class,
        [
            'title' => 'Optimize png image with command line executable "pngcrush"',
            'description' => 'Optimize png image with command line executable "pngcrush" so it will take less space.',
            'available' => true,
            'priority' => 90,
            'quality' => 80,
            'os' => '',
            'exec' => 'pngcrush',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngPngcrush::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationPng',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngPngquant::class,
        [
            'title' => 'Optimize png image with command line executable "pngquant"',
            'description' => 'Optimize png image with command line executable "pngquant" so it will take less space.',
            'available' => true,
            'priority' => 90,
            'quality' => 80,
            'os' => '',
            'exec' => 'pngquant',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngPngquant::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationPng',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngTinypng::class,
        [
            'title' => 'Optimize png image with tinypng.com',
            'description' => 'Optimize png image with tinypng.com so it will take less space.',
            'available' => true,
            'priority' => 80,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngTinypng::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationPng',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngKraken::class,
        [
            'title' => 'Optimize png image with Kraken.io',
            'description' => 'Optimize png image with Kraken.io so it will take less space.',
            'available' => true,
            'priority' => 70,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngKraken::class
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        $_EXTKEY,
        'ImageOptimizationPng',
        \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngImageoptim::class,
        [
            'title' => 'Optimize png image with Imageoptim.com',
            'description' => 'Optimize png image with Imageoptim.com so it will take less space.',
            'available' => true,
            'priority' => 60,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \SourceBroker\Imageopt\Providers\ImageManipulationProviderPngImageoptim::class
        ]
    );

    // The way to add new property to registered service
    //$GLOBALS['T3_SERVICES']['ImageOptimizationJpeg'][\SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegJpegtran::class]['enable'] = true;

    // The way to deactivate service
    //$GLOBALS['T3_SERVICES']['ImageOptimizationJpeg'][\SourceBroker\Imageopt\Providers\ImageManipulationProviderJpegJpegtran::class]['available'] = false;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:Configuration/TsConfig/Page/imageopt.tsconfig">');
}

/*  @var $dispatcher \TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class */
// $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
//example of working dispatcher \TYPO3\CMS\Core\Resource\OnlineMedia\Processing\PreviewProcessing::class
//$dispatcher->connect(
//    TYPO3\CMS\Core\Resource\ResourceStorage::class,
//    \TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PreFileProcess,
//    ,
//    \SourceBroker\Imageopt\Slot\FileProcessingService1::class,
//    'processFilePre'
//);
// unset($dispatcher);

// dispatcher did not worked so xclass for now
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Resource\\Service\\FileProcessingService'] = [
    'className' => 'SourceBroker\\Imageopt\\Xclass\\FileProcessingService'
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Frontend\\Imaging\\GifBuilder'] = [
    'className' => 'SourceBroker\\Imageopt\\Xclass\\GifBuilder'
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer'] = [
    'className' => 'SourceBroker\\Imageopt\\Xclass\\ContentObjectRenderer'
];
