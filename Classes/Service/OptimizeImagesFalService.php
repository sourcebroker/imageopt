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

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * OptimizeImagesFalService constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for OptimizeImagesFalService class');
        }

        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->falProcessedFileRepository = $this->objectManager->get(ProcessedFileRepository::class);

        $this->optimizeImageService = $this->objectManager->get(OptimizeImageService::class, $config);
    }

    /**
     * @param $notOptimizedFileRaw array $notOptimizedProcessedFileRaw,
     * @return OptimizationResult|null
     * @throws \Exception
     */
    public function optimizeFalProcessedFile($notOptimizedFileRaw)
    {
        $fileDoesNotExistOrNotReadable = false;
        $optimizationResultInfo = '';
        $optimizationResult = null;

        /** @var \TYPO3\CMS\Core\Resource\ProcessedFile $processedFal */
        $processedFal = $this->falProcessedFileRepository->findByIdentifier($notOptimizedFileRaw['uid']);
        $sourceFile = $processedFal->getForLocalProcessing(false);
        if (file_exists($sourceFile)) {
            if (is_readable($sourceFile)) {
                $tmpForOptimizing = $this->objectManager->get(TemporaryFileUtility::class)->createTemporaryCopy($sourceFile);
                $optimizationResult = $this->optimizeImageService->optimize($tmpForOptimizing, $sourceFile);
                $optimizationResult->setFileRelativePath(substr($sourceFile, strlen(PATH_site)));
                $this->objectManager->get(OptimizationResultRepository::class)->add($optimizationResult);
                if ($optimizationResult->isExecutedSuccessfully()) {
                    if ($optimizationResult->getSizeBefore() > $optimizationResult->getSizeAfter()) {
                        $processedFal->updateWithLocalFile($tmpForOptimizing);
                    }
                    $processedFal->updateProperties(['tx_imageopt_executed_successfully' => 1]);
                    $this->falProcessedFileRepository->update($processedFal);
                }
            } else {
                $fileDoesNotExistOrNotReadable = true;
                $optimizationResultInfo = 'The file above exists but is not readable for imageopt process.';
            }
        } else {
            $fileDoesNotExistOrNotReadable = true;
            $optimizationResultInfo = 'The file does not exists but exists as reference in "sys_file_processedfile" ' .
                'database table. Seems like it was processed in past but the processed file does not exist now. ' .
                'The record has been deleted from "sys_file_processedfile" table.';
            $processedFal->delete();
            $this->objectManager->get(PersistenceManager::class)->persistAll();
        }
        if ($fileDoesNotExistOrNotReadable) {
            $optimizationResult = GeneralUtility::makeInstance(OptimizationResult::class);
            $optimizationResult->setExecutedSuccessfully(false);
            $optimizationResult->setFileRelativePath(substr($sourceFile, strlen(PATH_site)));
            $optimizationResult->setInfo($optimizationResultInfo);
            $this->objectManager->get(OptimizationResultRepository::class)->add($optimizationResult);
        }

        return $optimizationResult;
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
     *
     */
    public function resetOptimizationFlag()
    {
        $this->falProcessedFileRepository->resetOptimizationFlag();
    }
}
