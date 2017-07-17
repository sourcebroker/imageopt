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

use SourceBroker\Imageopt\Resource\OptimizedFileRepository;
use SourceBroker\Imageopt\Service\ImageManipulationService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class ImageoptCommandController extends BaseCommandController
{
    /**
     * Injection of Image Manipulation Service Object
     *
     * @var ImageManipulationService
     */
    protected $imageManipulationService;

    /**
     * Injection of Image Manipulation Service Object
     *
     * @var OptimizedFileRepository
     */
    protected $optimizedFileRepository;

    /**
     * The time of starting command
     * @var integer
     */
    protected $taskExecutionStartTime = null;

    public function __construct()
    {
        $this->taskExecutionStartTime = $GLOBALS['EXEC_TIME'];
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        $serviceConfig = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService')
            ->convertTypoScriptArrayToPlainArray(BackendUtility::getPagesTSconfig(1));
        if (isset($serviceConfig['tx_imageopt'])) {
            $serviceConfig = $serviceConfig['tx_imageopt'];
        } else {
            $serviceConfig = [];
        }
        $this->imageManipulationService = $objectManager->get(ImageManipulationService::class, $serviceConfig);
        $this->optimizedFileRepository = $objectManager->get(OptimizedFileRepository::class);
    }

    /**
     * Optimise all TYPO3 processed images
     * @param int $numberOfImagesToProcess The number of images to process on single task call
     */
    public function optimizeImagesCommand($numberOfImagesToProcess = 20)
    {
        $this->imageManipulationService->optimizeImages($numberOfImagesToProcess);
        $results = $this->optimizedFileRepository->getAllExecutedFrom($this->taskExecutionStartTime);
        $message = [];
        if (count($results)) {
            foreach ((array)$results as $result) {
                $providersResults = unserialize($result['provider_results']);
                $providersScore = [];
                $success = $percentageWinner = $percentage = 0;
                foreach ((array)$providersResults['providerOptimizationResults'] as $optimizationResult) {
                    if ((int)$optimizationResult['success'] === 1) {
                        $success++;
                        $percentage = number_format(round(($result['file_size_before'] - $optimizationResult['optimizedFileSize']) * 100 / $result['file_size_before'],
                            2), 2, '.', '');
                        $providersScore[] = $optimizationResult['providerName'] . ' - ' . $percentage . '%';
                    } else {
                        $providersScore[] = $optimizationResult['providerName'] . ' - failed';
                    }
                    if ((int)$optimizationResult['winner'] === 1) {
                        $percentageWinner = $percentage;
                    }
                }
                $providersScore = implode(', ', $providersScore);
                $message[] = $percentageWinner . '% - ' . $result['path'] . ' | Providers Status: ' . $success . ' out of ' . count($providersResults['providerOptimizationResults']) . ' finished successfully (' . $providersScore . ')';
            }
        } else {
            $message[] = 'All images are optimized.';
        }
        $this->setSchedulerTaskMessage($message, 'Result');
        $this->setConsoleTaskMessage($message);
    }

    /**
     * Clear optimized stat so all files can be optimized once more.
     * Can be useful for testing.
     *
     */
    public function resetOptimizationFlagCommand()
    {
        $this->imageManipulationService->resetOptimizationFlag();
    }
}
