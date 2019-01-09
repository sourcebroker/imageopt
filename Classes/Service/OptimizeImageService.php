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
use SourceBroker\Imageopt\Domain\Model\OptimizationResult;
use SourceBroker\Imageopt\Provider\OptimizationProvider;
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Optimize single image using multiple Image Optmization Provider.
 * The best optimization wins!
 */
class OptimizeImageService
{
    /**
     * @var object|Configurator
     */
    public $configurator;

    /**
     * @var object|TemporaryFileUtility
     */
    private $temporaryFile;

    /**
     * OptimizeImageService constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for OptimizeImageService class');
        }
        $this->configurator = GeneralUtility::makeInstance(Configurator::class, $config);
        $this->configurator->init();

        $this->temporaryFile = GeneralUtility::makeInstance(TemporaryFileUtility::class);
    }

    /**
     * Optimize image using chained Image Optimization Provider
     *
     * @param string $workingImagePath
     * @param string $originalImagePath
     * @return OptimizationResult Optimization result
     * @throws \Exception
     */
    public function optimize($workingImagePath, $originalImagePath)
    {
        $optimizationResult = GeneralUtility::makeInstance(OptimizationResult::class);
        $optimizationResult->setFileRelativePath(substr($workingImagePath, strlen(PATH_site)));
        $optimizationResult->setExecutedSuccessfully(false);
        clearstatcache(true, $workingImagePath);
        if (file_exists($workingImagePath) && filesize($workingImagePath)) {
            $optimizationResult->setSizeBefore(filesize($workingImagePath));
            $temporaryBestOptimizedImageAbsolutePath = $this->temporaryFile->createTemporaryCopy($workingImagePath);
            $imageOpimalizationsProviders = $this->findProvidersForFile($originalImagePath);
            if (!empty($imageOpimalizationsProviders)) {
                $providerExecutedCounter = $providerExecutedSuccessfullyCounter = $providerEnabledCounter = 0;
                foreach ($imageOpimalizationsProviders as $providerKey => $imageOpimalizationsProviderConfig) {
                    $imageOpimalizationsProviderConfig['providerKey'] = $providerKey;
                    $providerConfigurator = GeneralUtility::makeInstance(Configurator::class,
                        $imageOpimalizationsProviderConfig);
                    if (!empty($providerConfigurator->getOption('enabled'))) {
                        $providerEnabledCounter++;
                        $providerExecutedCounter++;
                        $temporaryProviderOptimizedImageAbsolutePath = $this->temporaryFile->createTemporaryCopy($workingImagePath);
                        $optimizationProvider = GeneralUtility::makeInstance(OptimizationProvider::class);
                        $providerResult = $optimizationProvider->optimize(
                            $temporaryProviderOptimizedImageAbsolutePath,
                            $providerConfigurator
                        );
                        $optimizationResult->addProvidersResult($providerResult);
                        if ($providerResult->isExecutedSuccessfully()) {
                            $providerExecutedSuccessfullyCounter++;
                            clearstatcache(true, $temporaryProviderOptimizedImageAbsolutePath);
                            clearstatcache(true, $temporaryBestOptimizedImageAbsolutePath);
                            $filesizeAfterProviderOptimization = filesize($temporaryProviderOptimizedImageAbsolutePath);
                            if (filesize($temporaryBestOptimizedImageAbsolutePath) > $filesizeAfterProviderOptimization) {
                                rename($temporaryProviderOptimizedImageAbsolutePath,
                                    $temporaryBestOptimizedImageAbsolutePath);
                                $optimizationResult->setProviderWinnerName($providerKey);
                            }
                        }
                    }
                }
                if ($providerEnabledCounter === 0) {
                    $optimizationResult->setInfo('No providers enabled (or defined).');
                } elseif ($providerExecutedSuccessfullyCounter === 0) {
                    $optimizationResult->setInfo('No winner. All providers were unsuccessfull.');
                } else {
                    $optimizationResult->setExecutedSuccessfully(true);
                    clearstatcache(true, $temporaryBestOptimizedImageAbsolutePath);
                    $optimizationResult->setSizeAfter(filesize($temporaryBestOptimizedImageAbsolutePath));
                    $optimizationResult->setOptimizationBytes(
                        $optimizationResult->getSizeBefore() - $optimizationResult->getSizeAfter()
                    );
                    $optimizationResult->setOptimizationPercentage(round(
                            $optimizationResult->getOptimizationBytes() / $optimizationResult->getSizeBefore() * 100, 2)
                    );
                    if ($optimizationResult->getOptimizationBytes() === 0) {
                        $optimizationResult->setInfo('No winner. Non of the optimized images was smaller than original.');
                    } else {
                        $optimizationResult->setInfo('Winner is ' . $optimizationResult->getProviderWinnerName() .
                            ' with optimized image smaller by: ' . $optimizationResult->getOptimizationPercentage() . '%');
                        rename($temporaryBestOptimizedImageAbsolutePath,
                            $workingImagePath);
                    }
                }
            } else {
                $optimizationResult->setInfo('No suitable provider with proper optimization mode found for given file');
            }
        } else {
            $optimizationResult->setInfo('Can not read file to optimize. File: "' . $workingImagePath . '"');
        }
        return $optimizationResult;
    }

    /**
     * Finds all providers available for given type of file
     *
     * @param string $imagePath
     * @return array
     */
    protected function findProvidersForFile($imagePath)
    {
        $fileType = strtolower(explode('/', image_type_to_mime_type(getimagesize($imagePath)[2]))[1]);

        $suitableProviders = [];

        $optimizeEntries = $this->configurator->getOption('optimize');
        while ($optimizeEntry = array_shift($optimizeEntries)) {
            $pattern = '@' . $optimizeEntry['fileRegexp'] . '@i';
            if (!preg_match($pattern, $imagePath)) {
                continue;
            }

            $providers = $this->configurator->getProviders($optimizeEntry['providerType']);
            if (!empty($providers)) {
                foreach ($providers as $name => $provider) {
                    $fileTypes = explode(',', $provider['fileType']);

                    if (in_array($fileType, $fileTypes)) {
                        $suitableProviders[$name] = $provider;
                    }
                }
            }

            if (!empty($suitableProviders)) {
                break;
            }
        }

        return $suitableProviders;
    }
}
