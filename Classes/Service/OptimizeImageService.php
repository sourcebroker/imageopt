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
use SourceBroker\Imageopt\Domain\Model\OptimizationOptionResult;
use SourceBroker\Imageopt\Domain\Model\OptimizationStepResult;
use SourceBroker\Imageopt\Provider\OptimizationProvider;
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Optimize single image using multiple Image Optmization Provider.
 * The best optimization wins!
 */
class OptimizeImageService
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var object|Configurator
     */
    public $configurator;

    /**
     * @var TemporaryFileUtility
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
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        $this->configurator = $this->objectManager->get(Configurator::class, $config);
        $this->configurator->init();

        $this->temporaryFile = $this->objectManager->get(TemporaryFileUtility::class);
    }

    /**
     * Optimize image using chained Image Optimization Provider
     *
     * @param string $originalImagePath
     * @return OptimizationOptionResult[]
     * @throws \Exception
     */
    public function optimize($originalImagePath)
    {
        if (!file_exists($originalImagePath) || !filesize($originalImagePath)) {
            throw new \Exception('Can not read file to optimize. File: "' . $originalImagePath . '"');
        }

        $optimizationOptionResults = [];
        foreach ((array)$this->configurator->getOption('optimize') as $optimizeOptionName => $optimizeOption) {
            if (!preg_match($optimizeOption['fileRegexp'], $originalImagePath))
                continue;

            $optimizationOptionResults[$optimizeOptionName] = $this->optimizeSingleOption($optimizeOption, $originalImagePath);
        }

        return $optimizationOptionResults;
    }

    /**
     * @param $optimizeOption
     * @param $originalImagePath
     * @return OptimizationOptionResult
     * @throws \Exception
     */
    protected function optimizeSingleOption($optimizeOption, $originalImagePath)
    {
        $optimizationOptionResult = $this->objectManager->get(OptimizationOptionResult::class)
            ->setFileRelativePath(substr($originalImagePath, strlen(PATH_site)))
            ->setSizeBefore(filesize($originalImagePath))
            ->setExecutedSuccessfully(false);

        $chainImagePath = $this->temporaryFile->createTemporaryCopy($originalImagePath);
        $providerExecutedCounter = $providerExecutedSuccessfullyCounter = $providerEnabledCounter = 0;

        // execute all providers in chain
        foreach ($optimizeOption['chain'] as $chainLink) {
            $providers = $this->findProvidersForFile($originalImagePath, $chainLink['providerType']);
            if (empty($providers)) {
                continue;
            }

            $optimizationStepResult = $this->objectManager->get(OptimizationStepResult::class)
                ->setExecutedSuccessfully(false)
                ->setSizeBefore(filesize($chainImagePath));

            // work on chain image copy
            $tmpBestImagePath = $this->temporaryFile->createTemporaryCopy($chainImagePath);

            foreach ($providers as $providerKey => $providerConfig) {
                $providerConfig['providerKey'] = $providerKey;
                $providerConfigurator = $this->objectManager->get(Configurator::class, $providerConfig);
                if (!empty($providerConfigurator->getOption('enabled'))) {
                    $providerEnabledCounter++;
                    $providerExecutedCounter++;

                    $tmpWorkingImagePath = $this->temporaryFile->createTemporaryCopy($chainImagePath);
                    $optimizationProvider = $this->objectManager->get(OptimizationProvider::class);

                    $providerResult = $optimizationProvider->optimize($tmpWorkingImagePath, $providerConfigurator);

                    if ($providerResult->isExecutedSuccessfully()) {
                        $providerExecutedSuccessfullyCounter++;
                        clearstatcache(true, $tmpWorkingImagePath);
                        clearstatcache(true, $tmpBestImagePath);

                        $tmpWorkingImagePath = filesize($tmpWorkingImagePath);
                        $tmpBestImageFilesize = filesize($tmpBestImagePath);
                        if ($tmpWorkingImagePath < $tmpBestImageFilesize) {
                            // overwrite current (in chain link) best image
                            $tmpBestImagePath = $tmpWorkingImagePath;
                            $optimizationStepResult->setProviderWinnerName($providerKey);
                        }
                    }

                    $optimizationStepResult->addProvidersResult($providerResult);
                }
            }

            if ($providerEnabledCounter === 0) {
                $optimizationStepResult->setInfo('No providers enabled (or defined).');
            } elseif ($providerExecutedSuccessfullyCounter === 0) {
                $optimizationStepResult->setInfo('No winner. All providers were unsuccessfull.');
            } else {
                clearstatcache(true, $tmpBestImagePath);
                $optimizationStepResult
                    ->setExecutedSuccessfully(true)
                    ->setSizeAfter(filesize($tmpBestImagePath));

                if ($optimizationStepResult->getOptimizationBytes() === 0) {
                    $optimizationStepResult->setInfo('No winner. Non of the optimized images was smaller than original.');
                } else {
                    $optimizationStepResult->setInfo('Winner is ' . $optimizationStepResult->getProviderWinnerName() .
                        ' with optimized image smaller by: ' . $optimizationStepResult->getOptimizationPercentage() . '%');

                    // overwrite chain image with current best image
                    $chainImagePath = $tmpBestImagePath;
                }
            }

            $optimizationOptionResult->addOptimizationStepResult($optimizationStepResult);
        }

        clearstatcache(true, $originalImagePath);
        $optimizationOptionResult
            ->setSizeAfter(filesize($originalImagePath))
            ->setExecutedSuccessfully(true);

        // save under defined output path
        $pathInfo = pathinfo($originalImagePath);
        copy($chainImagePath, str_replace(
            [ '{dirname}', '{basename}', '{extension}', '{filename}' ],
            [ $pathInfo['dirname'],  $pathInfo['basename'], $pathInfo['extension'], $pathInfo['filename'] ],
            $optimizeOption['outputFilename']
        ));

        return $optimizationOptionResult;
    }


    public function optimizeOLD($workingImagePath, $originalImagePath)
    {
        $optimizationResult = null;
        foreach ((array)$this->configurator->getOption('optimize') as $optimisationChain) {
            $optimizationResult = GeneralUtility::makeInstance(OptimizationResult::class);
            $optimizationResult->setFileRelativePath(substr($workingImagePath, strlen(PATH_site)));
            $optimizationResult->setExecutedSuccessfully(false);
            clearstatcache(true, $workingImagePath);

            if (file_exists($workingImagePath) && filesize($workingImagePath)) {
                $optimizationResult->setSizeBefore(filesize($workingImagePath));
                $temporaryBestOptimizedImage = $this->temporaryFile->createTemporaryCopy($workingImagePath);
                $imageOpimalizationsProviders = $this->findProvidersForFile($originalImagePath, $optimisationChain);
                if (!empty($imageOpimalizationsProviders)) {
                    $providerExecutedCounter = $providerExecutedSuccessfullyCounter = $providerEnabledCounter = 0;
                    foreach ($imageOpimalizationsProviders as $providerKey => $imageOpimalizationsProviderConfig) {
                        $imageOpimalizationsProviderConfig['providerKey'] = $providerKey;
                        $providerConfigurator = GeneralUtility::makeInstance(Configurator::class,
                            $imageOpimalizationsProviderConfig);
                        if (!empty($providerConfigurator->getOption('enabled'))) {
                            $providerEnabledCounter++;
                            $providerExecutedCounter++;
                            $temporaryImageToOptimize = $this->temporaryFile->createTemporaryCopy($workingImagePath);
                            $optimizationProvider = GeneralUtility::makeInstance(OptimizationProvider::class);
                            $providerResult = $optimizationProvider->optimize(
                                $temporaryImageToOptimize,
                                $providerConfigurator
                            );
                            $optimizationResult->addProvidersResult($providerResult);
                            if ($providerResult->isExecutedSuccessfully()) {
                                $providerExecutedSuccessfullyCounter++;
                                clearstatcache(true, $temporaryImageToOptimize);
                                clearstatcache(true, $temporaryBestOptimizedImage);
                                $filesizeAfterProviderOptimization = filesize($temporaryImageToOptimize);
                                if (filesize($temporaryBestOptimizedImage) > $filesizeAfterProviderOptimization || 1 == 1) {
                                    rename($temporaryImageToOptimize,
                                        $temporaryBestOptimizedImage);
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
                        clearstatcache(true, $temporaryBestOptimizedImage);
                        $optimizationResult->setSizeAfter(filesize($temporaryBestOptimizedImage));
                        $optimizationResult->setOptimizationBytes(
                            $optimizationResult->getSizeBefore() - $optimizationResult->getSizeAfter()
                        );
                        $optimizationResult->setOptimizationPercentage(round(
                                $optimizationResult->getOptimizationBytes() / $optimizationResult->getSizeBefore() * 100,
                                2)
                        );
                        if ($optimizationResult->getOptimizationBytes() === 0) {
                            $optimizationResult->setInfo('No winner. Non of the optimized images was smaller than original.');
                        } else {
                            $optimizationResult->setInfo('Winner is ' . $optimizationResult->getProviderWinnerName() .
                                ' with optimized image smaller by: ' . $optimizationResult->getOptimizationPercentage() . '%');

                            if (!empty($optimisationChain['outputFilename'])
                            && $optimisationChain['outputFilename'] != '{dirname}/{filename}.{extension}') {
                                $pathInfo = pathinfo($originalImagePath);
                                copy($temporaryBestOptimizedImage, str_replace(
                                    [
                                        '{dirname}',
                                        '{basename}',
                                        '{extension}',
                                        '{filename}'
                                    ],
                                    [
                                        $pathInfo['dirname'],
                                        $pathInfo['basename'],
                                        $pathInfo['extension'],
                                        $pathInfo['filename']
                                    ],
                                    $optimisationChain['outputFilename']
                                ));
                            } else {
                                rename($temporaryBestOptimizedImage, $workingImagePath);
                            }
                        }
                    }
                } else {
                    $optimizationResult->setInfo('No suitable provider with proper optimization mode found for file ext: "'
                        . strtolower(explode('/', image_type_to_mime_type(getimagesize($originalImagePath)[2]))[1]) .
                        '" and providerType: "' . $optimisationChain['providerType'] . '"');
                }
            } else {
                $optimizationResult->setInfo('Can not read file to optimize. File: "' . $workingImagePath . '"');
            }
        }
        return $optimizationResult;
    }

    /**
     * Finds all providers available for given type of file
     *
     * @param string $imagePath
     * @param string $providerType
     * @return array
     */
    protected function findProvidersForFile($imagePath, $providerType)
    {
        $fileType = strtolower(explode('/', image_type_to_mime_type(getimagesize($imagePath)[2]))[1]);
        return $this->configurator->getProviders($providerType, $fileType);
    }
}
