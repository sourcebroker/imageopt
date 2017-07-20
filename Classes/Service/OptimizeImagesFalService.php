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
use SourceBroker\Imageopt\Resource\OptimizedFileRepository;
use SourceBroker\Imageopt\Resource\ProcessedFileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Optimize FAL images
 */
class OptimizeImagesFalService
{

    /**
     * Injection of Image Manipulation Service Object
     *
     * @var OptimizedFileRepository
     */
    protected $optimizedFileRepository;

    /**
     * The FAL processed file repository
     *
     * @var ProcessedFileRepository
     */
    protected $falProcessedFileRepository;

    /**
     * Plugin configuration
     *
     * @var Configurator
     */
    public $configurator;

    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for ImageManipulationService class');
        }
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->falProcessedFileRepository = $objectManager->get(ProcessedFileRepository::class);
        $this->optimizedFileRepository = $objectManager->get(OptimizedFileRepository::class);
        $this->configurator = $objectManager->get(Configurator::class);
        $this->configuratorGlobal = $objectManager->get(Configurator::class, $config);
        $this->optimizeImageService = $objectManager->get(OptimizeImageService::class, $config);
    }

    /**
     * @param int $numberOfImagesToProcess
     */
    public function optimizeFalProcessedFiles($numberOfImagesToProcess)
    {
        $notOptimizedProcessedFilesRaw = $this->falProcessedFileRepository->findNotOptimizedRaw($numberOfImagesToProcess);
        foreach ($notOptimizedProcessedFilesRaw as $notOptimizedProcessedFileRaw) {
            $this->optimizeFalProcessedFile($notOptimizedProcessedFileRaw);
        }
    }

    /**
     * @param $notOptimizedFileRaw array $notOptimizedProcessedFileRaw,
     */
    public function optimizeFalProcessedFile($notOptimizedFileRaw)
    {
        $processedFal = $this->falProcessedFileRepository->getDomainObject($notOptimizedFileRaw);
        $sourceFile = $processedFal->getForLocalProcessing(false);
        if (file_exists($sourceFile)) {
            $fileSizeBeforeOptimization = filesize($sourceFile);
            $fileSizeAfterOptimization = $fileSizeBeforeOptimization;
            $optimizationResults = $this->optimizeImageService->optimize($sourceFile);
            $theBestOptimizedImageRelativePath = substr($sourceFile, strlen(PATH_site));
            // If providerOptimizationWinnerKey !== null means that at least one provider succeeded and returned file smaller than original.
            // If non of the provider returned smaller image or all provider failed then do not update FAL but store log.
            if ($optimizationResults['providerOptimizationWinnerKey'] !== null) {
                $theBestOptimizedImageTmpFile = $optimizationResults['providerOptimizationResults'][$optimizationResults['providerOptimizationWinnerKey']]['optimizedFileAbsPath'];
                list($width, $height) = getimagesize($theBestOptimizedImageTmpFile);
                if ($width > 0 && $height > 0) {
                    $fileSizeAfterOptimization = filesize($theBestOptimizedImageTmpFile);
                    $processedFal->updateWithLocalFile($theBestOptimizedImageTmpFile);
                    $providerWinner = $optimizationResults['providerOptimizationWinnerKey'];
                    $theBestOptimizedImageRelativePath = $processedFal->getPublicUrl();
                }
            }
            // We set optimized flag always even if there was no real gain. Otherwise we'd need to optimize it in next loop.
            $processedFal->updateProperties(['tx_imageopt_optimized' => 1]);
            $this->falProcessedFileRepository->update($processedFal);

            $this->optimizedFileRepository->add(
                $theBestOptimizedImageRelativePath,
                $fileSizeBeforeOptimization,
                isset($fileSizeAfterOptimization) ? $fileSizeAfterOptimization : $fileSizeBeforeOptimization,
                isset($providerWinner) ? $providerWinner : '',
                $optimizationResults
            );
        } else {
            $processedFal->delete();
        }
    }

    /**
     *
     */
    public function resetOptimizationFlag()
    {
        $this->falProcessedFileRepository->resetOptimizationFlag();
    }
}
