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
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use SourceBroker\Imageopt\Domain\Model\OptimizationResult;
use SourceBroker\Imageopt\Domain\Model\ProviderResult;
use SourceBroker\Imageopt\Service\OptimizeImagesFalService;
use SourceBroker\Imageopt\Service\OptimizeImagesFolderService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class ImageoptCommandController
 */
class ImageoptCommandController extends CommandController
{

    /**
     * @var object|Configurator
     */
    protected $configurator;

    /**
     * @var object|ObjectManager
     */
    protected $objectManager;

    /**
     * ImageoptCommandController constructor.
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        // TODO: read UserTS - not PageTS
        $serviceConfig = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService')
            ->convertTypoScriptArrayToPlainArray(BackendUtility::getPagesTSconfig(1));
        $this->configurator = GeneralUtility::makeInstance(Configurator::class);
        $this->configurator->setConfig(isset($serviceConfig['tx_imageopt']) ? $serviceConfig['tx_imageopt'] : []);
    }

    /**
     * @return object|Configurator
     */
    public function getConfigurator()
    {
        return $this->configurator;
    }

    /**
     * @param object|Configurator $configurator
     */
    public function setConfigurator($configurator)
    {
        $this->configurator = $configurator;
    }

    /**
     * Optimize fal processed images
     *
     * @param int $numberOfImagesToProcess The number of images to process on single task call
     */
    public function optimizeFalProcessedImagesCommand($numberOfImagesToProcess = 20)
    {
        $optimizeImagesFalService = $this->objectManager->get(
            OptimizeImagesFalService::class,
            $this->getConfigurator()->getConfig()
        );
        $filesToProcess = $optimizeImagesFalService->getFalProcessedFilesToOptimize($numberOfImagesToProcess);
        if (!empty($filesToProcess)) {
            foreach ($filesToProcess as $fileToProcess) {
                $optimizationResult = $optimizeImagesFalService->optimizeFalProcessedFile($fileToProcess);
                $this->outputLine($this->showResult($optimizationResult));
            }
        } else {
            $this->outputLine('No images found that can be optimized.');
        }
    }

    /**
     * Optimize images in folders
     *
     * @param int $numberOfImagesToProcess
     */
    public function optimizeFolderImagesCommand($numberOfImagesToProcess = 20)
    {
        $optimizeImagesFolderService = $this->objectManager->get(
            OptimizeImagesFolderService::class,
            $this->getConfigurator()->getConfig()
        );
        $filesToProcess = $optimizeImagesFolderService->getFilesToOptimize($numberOfImagesToProcess);
        if (!empty($filesToProcess)) {
            foreach ($filesToProcess as $fileToProcess) {
                $optimizationResult = $optimizeImagesFolderService->optimizeFolderFile($fileToProcess);
                $this->outputLine($this->showResult($optimizationResult));
            }
        } else {
            $this->outputLine('No images found that can be optimized.');
        }
    }

    /**
     * @param $optimizationResult
     * @return string
     * @throws \Exception
     */
    public function showResult($optimizationResult)
    {
        if ($optimizationResult instanceof OptimizationResult) {
            /** @var OptimizationResult $optimizationResult */
            $providersScore = [];
            $success = $percentageWinner = $percentage = $noWinner = $nr = 0;
            /** @var ProviderResult $providerResult */
            foreach ($optimizationResult->getProvidersResults()->toArray() as $providerResult) {
                $nr++;
                if ($providerResult->isExecutedSuccessfully()) {
                    $success++;
                    $percentage = round((
                            $providerResult->getSizeBefore() - $providerResult->getSizeAfter()) * 100
                        / $providerResult->getSizeBefore(), 2);

                    $providersScore[] = $nr . ') ' . $providerResult->getName() . ': ' . $percentage . '%';
                } else {
                    /** @var ExecutorResult $executorResult */
                    $error = [];
                    foreach ($providerResult->getExecutorsResults()->toArray() as $executorResult) {
                        if (!$executorResult->isExecutedSuccessfully()) {
                            $error[] = $executorResult->getCommandStatus();
                        }
                    }
                    $providersScore[] = $nr . ') ' . $providerResult->getName() . ' - failed - ' . implode(' ', $error);
                }
            }
            return
                '---------------------------------' . "\n" .
                "File\t\t| " . $optimizationResult->getFileRelativePath() . "\n" .
                "Info\t\t| " . $optimizationResult->getInfo() . "\n" .
                "Provider stats\t| " . $success . ' out of ' . $optimizationResult->getProvidersResults()->count() . ' providers finished successfully:' . "\n" .
                "\t\t| " . implode("\n\t\t| ", $providersScore) . "\n";
        } else {
            throw new \Exception('Result in not an object of: ' . OptimizationResult::class);
        }
    }

    /**
     * Reset optimized flag for FAL processed images so all files can be optimized once more.
     * Can be useful for testing.
     */
    public function resetOptimizationFlagForFalCommand()
    {
        $optimizeImagesFalService = $this->objectManager->get(
            OptimizeImagesFalService::class,
            $this->getConfigurator()->getConfig()
        );
        $optimizeImagesFalService->resetOptimizationFlag();
    }

    /**
     * Reset optimized flag for folders images so all files can be optimized once more.
     * Can be useful for testing or for first time permission normalistation.
     */
    public function resetOptimizationFlagForFoldersCommand()
    {
        $optimizeImagesFolderService = $this->objectManager->get(
            OptimizeImagesFolderService::class,
            $this->getConfigurator()->getConfig()
        );
        $optimizeImagesFolderService->resetOptimizationFlag();
    }
}
