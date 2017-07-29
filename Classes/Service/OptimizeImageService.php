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
 * Optimize single image using multiple Image Optmization Provider. The best optimization wins!
 */
class OptimizeImageService
{
    /** @var object|Configurator */
    public $configurator;

    /** @var object|TemporaryFileUtility */
    private $temporaryFile;

    /**
     * OptimizeImageService constructor.
     * @param null $config
     * @throws \Exception
     */
    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for OptimizeImageService class');
        }
        $this->configurator = GeneralUtility::makeInstance(Configurator::class, $config);
        $this->temporaryFile = GeneralUtility::makeInstance(TemporaryFileUtility::class);
    }

    /**
     * Optimize image using chained Image Optimization Provider
     *
     * @param string $inputImageAbsolutePath
     * @return OptimizationResult Optimization result
     * @throws \Exception
     */
    public function optimize($inputImageAbsolutePath)
    {
        $optimizationResult = GeneralUtility::makeInstance(OptimizationResult::class);
        $optimizationResult->setExecutedSuccessfully(false);
        $optimizationResult->setFileRelativePath($inputImageAbsolutePath);
        clearstatcache(true, $inputImageAbsolutePath);
        $fileType = strtolower(explode('/', image_type_to_mime_type(exif_imagetype($inputImageAbsolutePath)))[1]);
        if (file_exists($inputImageAbsolutePath) && filesize($inputImageAbsolutePath)) {
            $optimizationResult->setSizeBefore(filesize($inputImageAbsolutePath));
            $temporaryBestOptimizedImageAbsolutePath = $this->temporaryFile->createTemporaryCopy($inputImageAbsolutePath);
            $imageOpimalizationsProviders = $this->configurator->getOption('providers.' . $fileType);
            if (!empty($imageOpimalizationsProviders)) {
                $providerExecuted = $providerExecutedSuccessfuly = 0;
                foreach ($imageOpimalizationsProviders as $providerKey => $imageOpimalizationsProviderConfig) {
                    if ($imageOpimalizationsProviderConfig['enabled']) {
                        $providerExecuted++;
                        $temporaryProviderOptimizedImageAbsolutePath = $this->temporaryFile->createTemporaryCopy($inputImageAbsolutePath);
                        $imageOpimalizationsProviderConfig['providerKey'] = $providerKey;
                        $optimizationProvider = GeneralUtility::makeInstance(OptimizationProvider::class);
                        $providerResult = $optimizationProvider->optimize(
                            $temporaryProviderOptimizedImageAbsolutePath,
                            GeneralUtility::makeInstance(Configurator::class, $imageOpimalizationsProviderConfig)
                        );
                        $optimizationResult->addProvidersResult($providerResult);
                        if ($providerResult->isExecutedSuccessfully()) {
                            $providerExecutedSuccessfuly++;
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

//                if ($providersResults['providerOptimizationWinnerKey'] === null && $success === 0
//                ) {
//                    $winnerText = 'No winner. All providers was unsuccessfull.';
//                }
//                if ($providersResults['providerOptimizationWinnerKey'] === null && $success > 0) {
//                    $winnerText = 'No winner. Non of the optimized images was smaller than original.';
//                }
//                if ($providersResults['providerOptimizationWinnerKey'] !== null) {
//                    $winnerText = "Winner is '$percentageWinnerName' with optimized image smaller by: " . $percentageWinner . '%';
//                }

                if ($providerExecuted === $providerExecutedSuccessfuly) {
                    $optimizationResult->setExecutedSuccessfully(true);
                    clearstatcache(true, $temporaryBestOptimizedImageAbsolutePath);
                    $optimizationResult->setSizeAfter(filesize($temporaryBestOptimizedImageAbsolutePath));
                    $optimizationResult->setOptimizationBytes(
                        $optimizationResult->getSizeBefore() - $optimizationResult->getSizeAfter()
                    );
                    $optimizationResult->setOptimizationPercentage(
                        $optimizationResult->getOptimizationBytes() / $optimizationResult->getSizeBefore() * 100
                    );
                    rename($temporaryBestOptimizedImageAbsolutePath,
                        $inputImageAbsolutePath);
                }
            } else {
                echo('f');
                // TODO: put info here that there is no provider for such extension
            }
        } else {
            $optimizationResult->setInfo('Can not read file to optimize');
        }
        return $optimizationResult;
    }
}
