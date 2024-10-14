<?php

declare(strict_types=1);

namespace SourceBroker\Imageopt\Service;

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Repository\ModeResultRepository;
use SourceBroker\Imageopt\Resource\ProcessedFileRepository;
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class OptimizeImageServiceFactory
{
    public function __construct(
        private readonly ModeResultRepository $modeResultRepository,
        private readonly ProcessedFileRepository $falProcessedFileRepository,
        private readonly PersistenceManager $persistenceManager,
        private readonly TemporaryFileUtility $temporaryFileUtility
    ) {
    }

    public function create(Configurator $configurator): OptimizeImageService
    {
        return GeneralUtility::makeInstance(
            OptimizeImageService::class,
            $configurator,
            $this->temporaryFileUtility
        );
    }

    public function createFalService(Configurator $configurator): OptimizeImagesFalService
    {
        return GeneralUtility::makeInstance(
            OptimizeImagesFalService::class,
            $configurator,
            $this->create($configurator),
            $this->modeResultRepository,
            $this->falProcessedFileRepository,
            $this->persistenceManager,
            $this->temporaryFileUtility
        );
    }

    public function createFolderService(Configurator $configurator): OptimizeImagesFolderService
    {
        return GeneralUtility::makeInstance(
            OptimizeImagesFolderService::class,
            $configurator,
            $this->create($configurator),
            $this->modeResultRepository,
            $this->persistenceManager
        );
    }
}
