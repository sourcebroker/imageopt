<?php

/***************************************************************
 *  Copyright notice
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


namespace SourceBroker\Imageopt\Service;

use SourceBroker\Imageopt\Configuration\Configurator;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Optimize single image using multiple Image Manipulation Providers. The best win!
 */
class OptimizeImageService
{
    /**
     * Plugin configuration
     *
     * @var Configurator
     */
    public $configurator;

    /**
     * Allowed file extensions
     *
     * @var array
     */
    private $allowExtensions = [
        'png',
        'jpeg',
        'jpg',
        'gif'
    ];

    /**
     * Temp file Prefix
     *
     * @var string
     */
    private $tempFilePrefix = 'tx_imageopt';

    /**
     * Is registered shutdown function
     *
     * @var bool
     */
    private $isRegisteredShutdownFunction = false;

    /**
     * List of extensions that will be normalized
     *
     * @var array
     */
    private $fileExtensionNormalisation = [
        'gif' => 'gif',
        'jpeg' => 'jpg',
        'jpg' => 'jpg',
        'png' => 'png',
    ];

    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for ImageManipulationService class');
        }
        $this->configurator = GeneralUtility::makeInstance(Configurator::class);
        $this->configuratorGlobal = GeneralUtility::makeInstance(Configurator::class, $config);

    }

    /**
     * Optimize image using chained Image Manipulation Providers
     *
     * @param $inputImageAbsolutePath
     * @return array
     * @throws \Exception
     */
    public function optimize($inputImageAbsolutePath)
    {
        $imageOptimizationProviderResults = [];
        $imageOptimizationProviderWinnerKey = null;
        $fileType = strtolower(pathinfo($inputImageAbsolutePath)['extension']);
        if (in_array($fileType, $this->allowExtensions)
            && file_exists($inputImageAbsolutePath) && filesize($inputImageAbsolutePath)
        ) {
            $fileType = $this->fileExtensionNormalisation[$fileType];
            $fileSizeBeforeOptimization = filesize($inputImageAbsolutePath);
            $theBestOptimizedImage = $this->createTempFile();
            $imageManipulationProviderChain = [];
            /* @var \SourceBroker\Imageopt\Providers\ImageManipulationProvider $imageManipulationProvider */
            while (is_object($imageManipulationProvider = GeneralUtility::makeInstanceService('ImageOptimization' . ucfirst($fileType),
                '', $imageManipulationProviderChain))) {
                $providerConfig = $this->configuratorGlobal->getOption('providers.' . $imageManipulationProvider->getFileType() . '.' . $imageManipulationProvider->getName());
                if (count($providerConfig)) {
                    $imageManipulationProvider->getConfigurator()->setConfig($providerConfig);
                } else {
                    throw new \Exception('No configuration for provider found for: "providers.' . $imageManipulationProvider->getFileType() . '.' . $imageManipulationProvider->getName() . '"');
                }

                $imageManipulationProviderKey = $imageManipulationProvider->getServiceKey();
                // add to $imageManipulationProvider[] to exclude this service for next while loop
                $imageManipulationProviderChain[] = $imageManipulationProviderKey;
                if ($imageManipulationProvider->isEnabled()) {
                    $providerOptimizationResult = $imageManipulationProvider->optimize($inputImageAbsolutePath);
                    $providerOptimizationResult['providerClass'] = $imageManipulationProviderKey;
                    $providerOptimizationResult['serviceError'] = implode('; ',
                        $imageManipulationProvider->getErrorMsgArray());
                    $providerOptimizationResult['optimizedFileSize'] = filesize($providerOptimizationResult['optimizedFileAbsPath']);
                    $providerOptimizationResult['winner'] = false;

                    if ($providerOptimizationResult['success']) {
                        // if optimized image has better optimization result than previous provider then store it for final return
                        if ((filesize($theBestOptimizedImage) === 0 && $fileSizeBeforeOptimization >= filesize($providerOptimizationResult['optimizedFileAbsPath']))
                            || (filesize($providerOptimizationResult['optimizedFileAbsPath']) < filesize($theBestOptimizedImage))
                        ) {
                            rename($providerOptimizationResult['optimizedFileAbsPath'], $theBestOptimizedImage);
                            $providerOptimizationResult['optimizedFileAbsPath'] = $theBestOptimizedImage;
                            $imageOptimizationProviderWinnerKey = $imageManipulationProviderKey;
                        }
                    }
                    //collect the optimizations statuses for debug
                    ksort($providerOptimizationResult);
                    $imageOptimizationProviderResults[$imageManipulationProviderKey] = $providerOptimizationResult;
                }
                // Unset current $imageManipulationProvider to free resources. This will unset all temporary images of provider.
                unset($imageManipulationProvider);
            }
            if ($imageOptimizationProviderWinnerKey !== null) {
                $imageOptimizationProviderResults[$imageOptimizationProviderWinnerKey]['winner'] = true;
            }
        }
        return [
            'providerOptimizationResults' => $imageOptimizationProviderResults,
            'providerOptimizationWinnerKey' => $imageOptimizationProviderWinnerKey
        ];
    }


    /**
     * Create temporary file and register shoutdown function
     *
     * @return string $tempFile Name of temporary file
     */
    protected function createTempFile()
    {
        $tempFile = GeneralUtility::tempnam($this->tempFilePrefix);

        if (!$this->isRegisteredShutdownFunction) {
            register_shutdown_function([$this, 'unlinkTempFiles']);
            $this->isRegisteredShutdownFunction = true;
        }

        return $tempFile;
    }

    /**
     * Delete all temporary files
     *
     * @return void
     */
    public function unlinkTempFiles()
    {
        $typo3temp = PATH_site . 'typo3temp/';
        foreach (glob($typo3temp . $this->tempFilePrefix . '*') as $tempFile) {
            @unlink($tempFile);
        }
    }

}