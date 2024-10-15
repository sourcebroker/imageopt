<?php

namespace SourceBroker\Imageopt\Service;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use Exception;
use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Repository\ModeResultRepository;
use SourceBroker\Imageopt\Resource\ProcessedFileRepository;
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
use TYPO3\CMS\Core\Resource\ProcessedFile;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Optimize FAL images
 */
class OptimizeImagesFalService
{
    private ProcessedFileRepository $falProcessedFileRepository;

    private Configurator $configurator;

    private OptimizeImageService $optimizeImageService;

    private ModeResultRepository $modeResultRepository;

    private PersistenceManager $persistenceManager;

    private TemporaryFileUtility $temporaryFileUtility;

    /**
     * OptimizeImagesFalService constructor.
     * @throws Exception
     */
    public function __construct(
        Configurator $configurator,
        OptimizeImageService $optimizeImageService,
        ModeResultRepository $modeResultRepository,
        ProcessedFileRepository $falProcessedFileRepository,
        PersistenceManager $persistenceManager,
        TemporaryFileUtility $temporaryFileUtility,
    ) {
        $this->configurator = $configurator;
        $this->optimizeImageService = $optimizeImageService;
        $this->modeResultRepository = $modeResultRepository;
        $this->falProcessedFileRepository = $falProcessedFileRepository;
        $this->persistenceManager = $persistenceManager;
        $this->temporaryFileUtility = $temporaryFileUtility;
    }

    /**
     * @param $notOptimizedFileRaw array $notOptimizedProcessedFileRaw,
     * @throws Exception
     */
    public function optimizeFalProcessedFile(array $notOptimizedFileRaw): array
    {
        /** @var ProcessedFile $processedFal */
        $processedFal = $this->falProcessedFileRepository->findByIdentifier($notOptimizedFileRaw['uid']);
        $sourceFile = $processedFal->getForLocalProcessing(false);

        $modeResults = $this->optimizeImageService->optimize($sourceFile);

        foreach ($modeResults as $modeResult) {
            if ($modeResult->getFileDoesNotExist()) {
                $modeResult->setInfo('The file does not exists but exists as reference in "sys_file_processedfile" ' .
                    'database table. Seems like it was processed in past but the processed file does not exist now. ' .
                    'The record has been deleted from "sys_file_processedfile" table.');
                $processedFal->delete();
            }
        }

        $executedSuccessfully = true;
        foreach ($modeResults as $modeResult) {
            if ($modeResult->isExecutedSuccessfully()) {
                if ($modeResult->getOutputFilename() === $sourceFile && (int)$modeResult->getSizeBefore() > (int)$modeResult->getSizeAfter()) {
                    // Modes can create files with different names than original like example.jpg -> example.jpg.webp, example.jpg.avif, etc.
                    // We need to use updateWithLocalFile only for the name that match the original file name
                    $processedFal->updateWithLocalFile(
                        $this->temporaryFileUtility->createTemporaryCopy($sourceFile)
                    );
                }
            } else {
                $executedSuccessfully = false;
            }

            if ($this->configurator->getOption('log.enable')) {
                $this->modeResultRepository->add($modeResult);
            }
        }

        if ($executedSuccessfully) {
            $processedFal->updateProperties(['tx_imageopt_executed_successfully' => 1]);
        }

        $processedFal->updateProperties(['tx_imageopt_executed' => 1]);
        $this->falProcessedFileRepository->update($processedFal);

        $this->persistenceManager->persistAll();

        return $modeResults;
    }

    public function getFalProcessedFilesToOptimize(int $numberOfImagesToProcess, array $extensions): array
    {
        return $this->falProcessedFileRepository->findNotOptimizedRaw($numberOfImagesToProcess, $extensions);
    }

    public function resetOptimizationFlag(): void
    {
        $this->falProcessedFileRepository->resetOptimizationFlag();
    }
}
