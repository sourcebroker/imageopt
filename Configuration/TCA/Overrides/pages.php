<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0100.tsconfig',
    'ext:imageopt [LOCAL] Good standard. mozjpeg / pngquant / gifsicle & loosless finishers.'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0110.tsconfig',
    'ext:imageopt [LOCAL] Default as low quality & additional image with good quality & webp.'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0120.tsconfig',
    'ext:imageopt [LOCAL] Only webp.'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0200.tsconfig',
    'ext:imageopt [REMOTE] Kraken loosless.'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0210.tsconfig',
    'ext:imageopt [REMOTE] Kraken intelligent lossy.'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0220.tsconfig',
    'ext:imageopt [REMOTE] Kraken loosless & imagemagick webp.'
);

ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0230.tsconfig',
    'ext:imageopt [REMOTE] Kraken intelligent lossy & imagemagick webp.'
);
