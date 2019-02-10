<?php

namespace SourceBroker\Imageopt\Command;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Service\OptimizeImagesFalService;
use SourceBroker\Imageopt\Service\OptimizeImagesFolderService;
use SourceBroker\Imageopt\Utility\CliDisplayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class ImageoptCommandController
 */
class ImageoptCommandController extends CommandController
{

    /**
     * Optimize FAL processed images
     *
     * @param int $numberOfImagesToProcess The number of images to process on single task call.
     * @param int $rootPageForTsConfig The page uid for which the TSconfig is parsed. If not set then first found root page will be used.
     * @throws \Exception
     */
    public function optimizeFalProcessedImagesCommand($numberOfImagesToProcess = 20, $rootPageForTsConfig = null)
    {
        $configurator = GeneralUtility::makeInstance(Configurator::class);
        $configurator->setConfigByPage($rootPageForTsConfig);
        $configurator->init();

        $optimizeImagesFalService = GeneralUtility::makeInstance(ObjectManager::class)->get(
            OptimizeImagesFalService::class,
            $configurator->getConfig()
        );
        $filesToProcess = $optimizeImagesFalService->getFalProcessedFilesToOptimize($numberOfImagesToProcess);
        if (!empty($filesToProcess)) {
            foreach ($filesToProcess as $fileToProcess) {
                $optimizationResults = $optimizeImagesFalService->optimizeFalProcessedFile($fileToProcess);
                foreach ($optimizationResults as $optimizationResult) {
                    $this->outputLine(CliDisplayUtility::displayOptimizationOptionResult($optimizationResult));
                }
            }
        } else {
            $this->outputLine('No images found that can be optimized.');
        }
    }

    /**
     * Optimize images in folders
     *
     * @param int $numberOfImagesToProcess The number of images to process on single task call.
     * @param int $rootPageForTsConfig The page uid for which the TSconfig is parsed. If not set then first found root page will be used.
     * @throws \Exception
     */
    public function optimizeFolderImagesCommand($numberOfImagesToProcess = 20, $rootPageForTsConfig = null)
    {
        $configurator = GeneralUtility::makeInstance(Configurator::class);
        $configurator->setConfigByPage($rootPageForTsConfig);
        $configurator->init();
        $optimizeImagesFolderService = GeneralUtility::makeInstance(ObjectManager::class)->get(
            OptimizeImagesFolderService::class,
            $configurator->getConfig()
        );
        $filesToProcess = $optimizeImagesFolderService->getFilesToOptimize($numberOfImagesToProcess);
        if (!empty($filesToProcess)) {
            foreach ($filesToProcess as $fileToProcess) {
                $optimizationResults = $optimizeImagesFolderService->optimizeFolderFile($fileToProcess);
                foreach ($optimizationResults as $optimizationResult) {
                    $this->outputLine(CliDisplayUtility::displayOptimizationOptionResult($optimizationResult));
                }
            }
        } else {
            $this->outputLine('No images found that can be optimized.');
        }
    }

    /**
     * Reset optimized flag for FAL processed images so all files can be optimized once more.
     * Can be useful for testing.
     *
     * @param int $rootPageForTsConfig The page uid for which the TSconfig is parsed. If not set then first found root page will be used.
     *
     * @throws \Exception
     */
    public function resetOptimizationFlagForFalCommand($rootPageForTsConfig = null)
    {
        $configurator = GeneralUtility::makeInstance(Configurator::class);
        $configurator->setConfigByPage($rootPageForTsConfig);
        $configurator->init();
        $optimizeImagesFalService = GeneralUtility::makeInstance(ObjectManager::class)->get(
            OptimizeImagesFalService::class,
            $configurator->getConfig()
        );
        $optimizeImagesFalService->resetOptimizationFlag();
    }

    /**
     * Reset optimized flag for folders images so all files can be optimized once more.
     * Can be useful for testing or for first time permission normalistation.
     *
     * @param int $rootPageForTsConfig The page uid for which the TSconfig is parsed. If not set then first found root page will be used.
     * @throws \Exception
     */
    public function resetOptimizationFlagForFoldersCommand($rootPageForTsConfig = null)
    {
        $configurator = GeneralUtility::makeInstance(Configurator::class);
        $configurator->setConfigByPage($rootPageForTsConfig);
        $configurator->init();
        $optimizeImagesFolderService = GeneralUtility::makeInstance(ObjectManager::class)->get(
            OptimizeImagesFolderService::class,
            $configurator->getConfig()
        );
        $optimizeImagesFolderService->resetOptimizationFlag();
    }
}
