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

        // create original image copy - it may vary (provider may overwrite original image)
        $sourceImagePath = $this->temporaryFile->createTemporaryCopy($originalImagePath);

        $optimizationOptionResults = [];
        foreach ((array)$this->configurator->getOption('optimize') as $optimizeOptionName => $optimizeOption) {
            $regexp = '@' . $optimizeOption['fileRegexp'] . '@';
            if (!preg_match($regexp, $originalImagePath)) {
                continue;
            }

            $optimizationOptionResults[$optimizeOptionName] = $this->optimizeSingleOption(
                $optimizeOption,
                $sourceImagePath,
                $originalImagePath
            );
        }

        return $optimizationOptionResults;
    }

    /**
     * @param array $optimizeOption
     * @param string $sourceImagePath Path to original image COPY (default optimization mode will overwrite original image)
     * @param string $originalImagePath Path to original image
     * @return OptimizationOptionResult
     * @throws \Exception
     */
    protected function optimizeSingleOption($optimizeOption, $sourceImagePath, $originalImagePath)
    {
        $optimizationOptionResult = $this->objectManager->get(OptimizationOptionResult::class)
            ->setFileRelativePath(substr($originalImagePath, strlen(PATH_site)))
            ->setOptimizationMode($optimizeOption['name'])
            ->setSizeBefore(filesize($sourceImagePath))
            ->setExecutedSuccessfully(false);

        $chainImagePath = $this->temporaryFile->createTemporaryCopy($sourceImagePath);

        // execute all providers in chain
        foreach ($optimizeOption['chain'] as $chainLinkName => $chainLink) {
            $providers = $this->findProvidersForFile($originalImagePath, $chainLink['providerType']);
            if (empty($providers)) {
                // skip this chain link - no providers for this type of image
                continue;
            }

            $optimizationStepResult = $this->optimizeWithBestProvider($chainImagePath, $providers);
            $optimizationStepResult->setName($chainLinkName);

            $optimizationOptionResult->addOptimizationStepResult($optimizationStepResult);
        }

        if ($optimizationOptionResult->getExecutedSuccessfullyNum() == $optimizationOptionResult->getOptimizationStepResults()
                ->count()) {
            $optimizationOptionResult->setExecutedSuccessfully(true);
        }

        clearstatcache(true, $chainImagePath);
        $optimizationOptionResult
            ->setSizeAfter(filesize($chainImagePath));

        // save under defined output path
        $pathInfo = pathinfo($originalImagePath);
        copy($chainImagePath, str_replace(
            ['{dirname}', '{basename}', '{extension}', '{filename}'],
            [$pathInfo['dirname'], $pathInfo['basename'], $pathInfo['extension'], $pathInfo['filename']],
            $optimizeOption['outputFilename']
        ));

        return $optimizationOptionResult;
    }

    /**
     * @param string $chainImagePath
     * @param array $providers
     * @return OptimizationStepResult
     * @throws \Exception
     */
    protected function optimizeWithBestProvider($chainImagePath, $providers)
    {
        clearstatcache(true, $chainImagePath);
        $optimizationStepResult = $this->objectManager->get(OptimizationStepResult::class)
            ->setExecutedSuccessfully(false)
            ->setSizeBefore(filesize($chainImagePath));

        $providerExecutedCounter = 0;
        $providerExecutedSuccessfullyCounter = 0;
        $providerEnabledCounter = 0;

        // work on chain image copy
        $tmpBestImagePath = $this->temporaryFile->createTemporaryCopy($chainImagePath);

        foreach ($providers as $providerKey => $providerConfig) {
            $providerConfig['providerKey'] = $providerKey;
            $providerConfigurator = $this->objectManager->get(Configurator::class, $providerConfig);

            if (empty($providerConfigurator->getOption('enabled'))) {
                continue;
            }

            $providerEnabledCounter++;
            $providerExecutedCounter++;

            $tmpWorkingImagePath = $this->temporaryFile->createTemporaryCopy($chainImagePath);
            $optimizationProvider = $this->objectManager->get(OptimizationProvider::class);

            $providerResult = $optimizationProvider->optimize($tmpWorkingImagePath, $providerConfigurator);

            if ($providerResult->isExecutedSuccessfully()) {
                $providerExecutedSuccessfullyCounter++;
                clearstatcache(true, $tmpWorkingImagePath);

                if (filesize($tmpWorkingImagePath) < filesize($tmpBestImagePath)) {
                    // overwrite current (in chain link) best image
                    $tmpBestImagePath = $tmpWorkingImagePath;
                    $optimizationStepResult->setProviderWinnerName($providerKey);
                }
            }

            $optimizationStepResult->addProvidersResult($providerResult);
        }

        if ($providerEnabledCounter === 0) {
            $optimizationStepResult->setInfo('No providers enabled (or defined).');
        } elseif ($providerExecutedSuccessfullyCounter === 0) {
            $optimizationStepResult->setInfo('No winner. All providers were unsuccessfull.');
        } else {
            if ($optimizationStepResult->getOptimizationBytes() === 0) {
                $optimizationStepResult->setInfo('No winner. Non of the optimized images was smaller than original.');

                $optimizationStepResult
                    ->setExecutedSuccessfully(true)
                    ->setSizeAfter(filesize($chainImagePath));
            } else {
                $optimizationStepResult->setInfo('Winner is ' . $optimizationStepResult->getProviderWinnerName() .
                    ' with optimized image smaller by: ' . $optimizationStepResult->getOptimizationPercentage() . '%');

                clearstatcache(true, $tmpBestImagePath);
                $optimizationStepResult
                    ->setExecutedSuccessfully(true)
                    ->setSizeAfter(filesize($tmpBestImagePath));

                // overwrite chain image with current best image
                copy($tmpBestImagePath, $chainImagePath);
            }
        }

        clearstatcache(true, $chainImagePath);

        return $optimizationStepResult;
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
