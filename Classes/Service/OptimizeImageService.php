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
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Optimize single image using multiple Image Optmization Providers. The best optimization wins!
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
     * OptimizeImageService constructor.
     * @param null $config
     * @throws \Exception
     */
    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for ImageOptimizationService class');
        }
        $this->configurator = GeneralUtility::makeInstance(Configurator::class);
        $this->configuratorGlobal = GeneralUtility::makeInstance(Configurator::class, $config);
        $this->temporaryFile = GeneralUtility::makeInstance(TemporaryFileUtility::class);
    }

    /**
     * Optimize image using chained Image Optimization Providers
     *
     * @param string $inputImageAbsolutePath
     * @return array
     * @throws \Exception
     */
    public function optimize($inputImageAbsolutePath)
    {
        $imageOptimizationProviderResults = [];
        $imageOptimizationProviderWinnerKey = null;
        $fileType = strtolower(explode('/', image_type_to_mime_type(exif_imagetype($inputImageAbsolutePath)))[1]);
        if (file_exists($inputImageAbsolutePath) && filesize($inputImageAbsolutePath)) {
            $fileSizeBeforeOptimization = filesize($inputImageAbsolutePath);
            $theBestOptimizedImage = $this->temporaryFile->createTempFile();
            $imageOpimalizationsProviders = $this->configuratorGlobal->getOption('providers.' . $fileType);
            foreach ($imageOpimalizationsProviders as $imageOpimalizationsProviderKey => $imageOpimalizationsProviderConfig) {
                $executorsSuccesfull = $executorsDone = 0;
                if ($imageOpimalizationsProviderConfig['enabled']) {
                    $providerOptimizationResultCumulated = [];
                    $temporaryOptimizedImageAbsolutePath = null;
                    foreach ($imageOpimalizationsProviderConfig['executors'] as $executorKey => $executor) {
                        if ($executor['enabled']) {
                            $executorsDone++;
                            if (isset($executor['class']) && class_exists($executor['class'])) {
                                $imageOptimizationProvider = GeneralUtility::makeInstance($executor['class']);
                                $executorResult = $imageOptimizationProvider->optimize(
                                    $temporaryOptimizedImageAbsolutePath !== null ? $temporaryOptimizedImageAbsolutePath : $inputImageAbsolutePath,
                                    GeneralUtility::makeInstance(
                                        Configurator::class,
                                        $this->configuratorGlobal->getOption('providers.' . $fileType . '.' . $imageOpimalizationsProviderKey . '.executors.' . $executorKey)
                                    )
                                );
                                $providerOptimizationResultCumulated[] = $executorResult;
                                if ($executorResult['success'] === true) {
                                    $executorsSuccesfull++;
                                    $temporaryOptimizedImageAbsolutePath = $executorResult['optimizedFileAbsPath'];
                                } else {
                                    break;
                                }
                            } else {
                                throw new \Exception('No class found: ' . $executor['class'], 1500994839981);
                            }
                        }
                    }

                    $providerOptimizationResult['executors'] = $providerOptimizationResultCumulated;
                    $providerOptimizationResult['provider'] = $imageOpimalizationsProviderKey;
                    if ($executorsSuccesfull == $executorsDone) {
                        $providerOptimizationResult['success'] = true;
                        $providerOptimizationResult['optimizedFileSize'] = filesize($temporaryOptimizedImageAbsolutePath);
                        $providerOptimizationResult['optimizedFileAbsPath'] = $temporaryOptimizedImageAbsolutePath;
                    } else {
                        $providerOptimizationResult['success'] = false;
                        $providerOptimizationResult['optimizedFileSize'] = false;
                    }
                    $providerOptimizationResult['winner'] = false;
                    if ($providerOptimizationResult['success']) {
                        // if optimized image has better optimization result than previous provider then store it for final return
                        $optimizedImageFilesize = filesize($providerOptimizationResult['optimizedFileAbsPath']);
                        if ($optimizedImageFilesize < $fileSizeBeforeOptimization
                            ||
                            $optimizedImageFilesize < filesize($theBestOptimizedImage)
                        ) {
                            rename($providerOptimizationResult['optimizedFileAbsPath'], $theBestOptimizedImage);
                            $providerOptimizationResult['optimizedFileAbsPath'] = $theBestOptimizedImage;
                            $imageOptimizationProviderWinnerKey = $imageOpimalizationsProviderKey;
                        }
                    }
                    //collect the optimizations statuses for debug
                    ksort($providerOptimizationResult);
                    $imageOptimizationProviderResults[$imageOpimalizationsProviderKey] = $providerOptimizationResult;
                }
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
}
