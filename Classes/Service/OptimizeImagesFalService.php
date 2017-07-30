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
use SourceBroker\Imageopt\Domain\Repository\OptimizationResultRepository;
use SourceBroker\Imageopt\Resource\ProcessedFileRepository;
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Optimize FAL images
 */
class OptimizeImagesFalService
{

    /**
     * The FAL processed file repository
     *
     * @var ProcessedFileRepository
     */
    protected $falProcessedFileRepository;

    /**
     * @var Configurator
     */
    protected $configurator;

    /**
     * @var OptimizeImageService
     */
    private $optimizeImageService;

    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for ImageOptimizationService class');
        }
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->falProcessedFileRepository = $objectManager->get(ProcessedFileRepository::class);
        $this->optimizeImageService = $objectManager->get(OptimizeImageService::class, $config);
    }

    /**
     * @param int $numberOfImagesToProcess
     * @return array
     */
    public function getFalProcessedFilesToOptimize($numberOfImagesToProcess)
    {
        return $this->falProcessedFileRepository->findNotOptimizedRaw($numberOfImagesToProcess);
    }

    /**
     * @param $notOptimizedFileRaw array $notOptimizedProcessedFileRaw,
     * @return OptimizationResult|null
     * @throws \Exception
     */
    public function optimizeFalProcessedFile($notOptimizedFileRaw)
    {
        $optimizationResult = null;
        /** @var \TYPO3\CMS\Core\Resource\ProcessedFile $processedFal */
        $processedFal = $this->falProcessedFileRepository->findByIdentifier($notOptimizedFileRaw['uid']);
        $sourceFile = $processedFal->getForLocalProcessing(false);
        if (file_exists($sourceFile)) {
            if (is_readable($sourceFile)) {
                $tmpForOptimizing = GeneralUtility::makeInstance(TemporaryFileUtility::class)
                    ->createTemporaryCopy($sourceFile);
                $optimizationResult = $this->optimizeImageService->optimize($tmpForOptimizing);
                $optimizationResult->setFileRelativePath(substr($sourceFile, strlen(PATH_site)));
                $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
                $objectManager->get(OptimizationResultRepository::class)->add($optimizationResult);
                $objectManager->get(PersistenceManager::class)->persistAll();
                if ($optimizationResult->isExecutedSuccessfully()
                    && $optimizationResult->getSizeBefore() > $optimizationResult->getSizeAfter()) {
                    $processedFal->updateWithLocalFile($tmpForOptimizing);
                }
                // We set optimized flag always even if there was no real gain. Otherwise we'd need to optimize it in next loop.
                $processedFal->updateProperties(['tx_imageopt_optimized' => 1]);
                $this->falProcessedFileRepository->update($processedFal);
            } else {
                throw new \Exception('Can not read file: ' . $sourceFile);
            }
        } else {
            $processedFal->delete();
        }
        return $optimizationResult;
    }

    /**
     *
     */
    public function resetOptimizationFlag()
    {
        $this->falProcessedFileRepository->resetOptimizationFlag();
    }
}
