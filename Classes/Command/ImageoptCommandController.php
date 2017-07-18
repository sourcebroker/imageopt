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
use SourceBroker\Imageopt\Resource\OptimizedFileRepository;
use SourceBroker\Imageopt\Service\OptimizeImagesFalService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class ImageoptCommandController extends BaseCommandController
{
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

    protected $configurator;

    /*
    * @var \SourceBroker\Imageopt\Service\OptimizeImagesFalService
    */
    private $optimizeImagesFalService;

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
        $this->configurator = GeneralUtility::makeInstance(Configurator::class);
        $this->configurator->setConfig($serviceConfig);
        $this->optimizedFileRepository = $objectManager->get(OptimizedFileRepository::class);
        $this->optimizeImagesFalService = $objectManager->get(
            OptimizeImagesFalService::class,
            $this->getConfigurator()->getConfig()
        );

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
    public function optimizeFalProcessedFilesCommand($numberOfImagesToProcess = 20)
    {
        $this->optimizeImagesFalService->optimizeFalProcessedFiles($numberOfImagesToProcess);
        $results = $this->optimizedFileRepository->getAllExecutedFrom($this->taskExecutionStartTime);
        $message = [];
        if (count($results)) {
            foreach ((array)$results as $result) {
                $providersResults = unserialize($result['provider_results']);
                $providersScore = [];
                $success = $percentageWinner = $percentage = $noWinner = 0;
                $percentageWinnerName = '';
                foreach ((array)$providersResults['providerOptimizationResults'] as $optimizationResult) {
                    if ((int)$optimizationResult['success'] === 1) {
                        $success++;
                        $percentage = number_format(round((
                                $result['file_size_before'] - $optimizationResult['optimizedFileSize']) * 100
                            / $result['file_size_before'], 2), 2, '.', '');
                        $providersScore[] = $success . ') ' . $optimizationResult['providerName'] . ': ' . $percentage . '%';
                    } else {
                        $providersScore[] = $success . ') ' . $optimizationResult['providerName'] . ' - failed';
                    }
                    if ((int)$optimizationResult['winner'] === 1) {
                        $percentageWinner = $percentage;
                        $percentageWinnerName = $optimizationResult['providerName'];
                    }
                }
                if ($providersResults['providerOptimizationWinnerKey'] === null && $success === 0
                ) {
                    $winnerText = 'No winner. All providers was unsuccessfull.';
                }
                if ($providersResults['providerOptimizationWinnerKey'] === null && $success > 0) {
                    $winnerText = 'No winner. Non of the optimized images was smaller than original.';
                }
                if ($providersResults['providerOptimizationWinnerKey'] !== null) {
                    $winnerText = "Winner is '$percentageWinnerName'  with result: " . $percentageWinner . "%";
                }
                $message[] =
                    "---------------------------------\n" .
                    "File\t\t| " . $result['path'] . "\n" .
                    "Winner\t\t| " . (isset($winnerText) ? $winnerText : '') . "\n".
                    "Providers stats\t| " . $success . ' out of ' . count($providersResults['providerOptimizationResults']) . ' finished successfully:' . "\n" .
                    "\t\t| " . implode("\n\t\t| ", $providersScore) . "\n";
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
        $this->optimizeImagesFalService->resetOptimizationFlag();
    }
}
