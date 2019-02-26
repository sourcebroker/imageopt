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
use SourceBroker\Imageopt\Domain\Model\OptionResult;
use SourceBroker\Imageopt\Domain\Repository\OptionResultRepository;
use SourceBroker\Imageopt\Resource\ProcessedFileRepository;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Optimize FAL images
 */
class OptimizeImagesFalService
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

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
     * @var OptionResultRepository
     */
    private $optionResultRepository;

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
        $this->optionResultRepository = $this->objectManager->get(OptionResultRepository::class);
    }

    /**
     * @param $notOptimizedFileRaw array $notOptimizedProcessedFileRaw,
     * @return OptionResult|null
     * @throws \Exception
     */
    public function optimizeFalProcessedFile($notOptimizedFileRaw)
    {
        $fileDoesNotExistOrNotReadable = false;
        $optimizationResultInfo = '';
        $optionResults = [];

        /** @var ProcessedFile $processedFal */
        $processedFal = $this->falProcessedFileRepository->findByIdentifier($notOptimizedFileRaw['uid']);
        $sourceFile = $processedFal->getForLocalProcessing(false);

        if (file_exists($sourceFile)) {
            if (is_readable($sourceFile)) {
                $optionResults = $this->optimizeImageService->optimize($sourceFile);

                $defaultOptimizationResult = isset($optionResults['default'])
                    ? $optionResults['default']
                    : reset($optionResults);

                foreach ($optionResults as $optionResult) {
                    $this->optionResultRepository->add($optionResult);
                }

                if ($defaultOptimizationResult->isExecutedSuccessfully()) {
                    if ($defaultOptimizationResult->getSizeBefore() > $defaultOptimizationResult->getSizeAfter()) {
                        $processedFal->updateWithLocalFile($sourceFile);
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
        }

        if ($fileDoesNotExistOrNotReadable) {
            $optionResult = $this->objectManager->get(OptionResult::class)
                ->setFileRelativePath(substr($sourceFile, strlen(PATH_site)))
                ->setExecutedSuccessfully(false)
                ->setInfo($optimizationResultInfo);

            $this->objectManager->get(OptionResultRepository::class)
                ->add($optionResult);

            $optionResults[] = $optionResult;
        }

        $this->objectManager->get(PersistenceManager::class)->persistAll();

        return $optionResults;
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
