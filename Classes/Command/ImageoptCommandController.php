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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use SourceBroker\Imageopt\Resource\OptimizedFileRepository;

/**
 * @package SourceBroker\OptimiseImages\Command
 */
class ImageoptCommandController extends CommandController
{
    /**
     * Injection of Image Manipulation Service Object
     *
     * @var \SourceBroker\Imageopt\Service\ImageManipulationService
     * @inject
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
        $this->optimizedFileRepository = new OptimizedFileRepository();
    }

    /**
     * Optimise all TYPO3 processed images
     * @param int $numberOfImagesToProcess The number of images to process on single task call
     */
    public function optimizeImagesCommand($numberOfImagesToProcess = 20)
    {
        $this->imageManipulationService->optimizeImages($numberOfImagesToProcess);
        $message = $this->getTaskMessage();
        $this->setSchedulerTaskMessage($message, 'Result');
        $this->setConsoleTaskMessage($message);
    }

    /**
     * Method to prepare content of final results message
     * @return array
     */
    protected function getTaskMessage()
    {
        $results = $this->optimizedFileRepository->getAllExecutedFrom($this->taskExecutionStartTime);
        $message = [];
        if (count($results)) {
            foreach ((array)$results as $result) {
                $providersResults = unserialize($result['provider_results']);
                $providersScore = $this->countSuccessOptimization($providersResults['providerOptimizationResults']);
                $percentage = number_format(round(($result['file_size_before'] - $result['file_size_after']) * 100 / $result['file_size_before'],
                    2), 2, '.', '');
                $message[] = $percentage . '% - ' . $result['path'] . ' | Providers Status: ' . $providersScore['success'] . ' out of ' . $providersScore['total'] . ' finished successfully';
            }
        } else {
            $message[] = 'All images are optimized.';
        }
        return $message;
    }

    /**
     * Count results of providers
     * @param array $optimizationResults
     * @return array
     */
    protected function countSuccessOptimization($optimizationResults)
    {
        $success = 0;
        $total = count($optimizationResults);
        if ($total > 0) {
            foreach ($optimizationResults as $optimizationResult) {
                if ((int)$optimizationResult['success'] === 1) {
                    $success++;
                }
            }
        }
        return ['success' => $success, 'total' => $total];
    }

    /**
     * Set Task Message in Scheduler
     * @param $message
     * @param $title
     * @param int $status
     */
    protected function setSchedulerTaskMessage($message, $title, $status = FlashMessage::INFO)
    {
        if (count($message) > 0) {
            /** @var FlashMessage $flashMessage */
            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                implode("<br/>", $message),
                $title,
                $status
            );

            /** @var $flashMessageService FlashMessageService */
            $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
            /** @var $defaultFlashMessageQueue FlashMessageQueue */
            $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
            $defaultFlashMessageQueue->enqueue($flashMessage);
        }
    }

    /**
     * Set Task Message in Console Window
     * @param $message
     */
    protected function setConsoleTaskMessage($message)
    {
        if (count($message) > 0) {
            $this->outputLine(implode("\n", $message));
        }
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
