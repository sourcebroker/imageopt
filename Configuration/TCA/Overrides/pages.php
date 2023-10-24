<?php

defined('TYPO3') or die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0100.tsconfig',
    'ext:imageopt [LOCAL] Good standard. mozjpeg / pngquant / gifsicle & loosless finishers.'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0110.tsconfig',
    'ext:imageopt [LOCAL] Default as low quality & additional image with good quality & webp.'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0200.tsconfig',
    'ext:imageopt [REMOTE] Kraken loosless.'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0210.tsconfig',
    'ext:imageopt [REMOTE] Kraken intelligent lossy.'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0220.tsconfig',
    'ext:imageopt [REMOTE] Kraken loosless & imagemagick webp.'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'imageopt',
    'Configuration/TsConfig/Page/tx_imageopt__0230.tsconfig',
    'ext:imageopt [REMOTE] Kraken intelligent lossy & imagemagick webp.'
);
