<?php

declare(strict_types=1);

namespace SourceBroker\Imageopt\Domain\Dto;

use TYPO3\CMS\Core\Resource\ProcessedFile;

class Image
{
    protected string $localImagePath;

    protected ?string $originalImagePath = null;

    protected array $processingConfiguration;

    protected ProcessedFile $processedFile;

    public function __construct() {}

    public static function createFromPath($path): self
    {
        if (!file_exists($path) || !filesize($path)) {
            throw new \Exception('Can not read file to optimize. File: "' . $path . '"');
        }

        $image = new self();
        $image->setLocalImagePath($path);

        return $image;
    }

    public static function createFromProcessedFile(ProcessedFile $processedFile): self
    {
        $localFile = $processedFile->getForLocalProcessing(false);
        if (!file_exists($localFile) || !filesize($localFile)) {
            throw new \Exception('Can not read file to optimize. File: "' . $localFile . '"');
        }

        $image = new self();
        $image->setLocalImagePath($localFile);
        $image->setOriginalImagePath($processedFile->getOriginalFile()->getForLocalProcessing(false));
        $image->setProcessingConfiguration($processedFile->getProcessingConfiguration());
        $image->setProcessedFile($processedFile);

        return $image;

    }

    public function matchExtension($regexp): bool
    {
        return (bool)preg_match($regexp, $this->localImagePath);
    }

    public function getLocalImagePath(): string
    {
        return $this->localImagePath;
    }

    public function setLocalImagePath(string $localImagePath): void
    {
        $this->localImagePath = $localImagePath;
    }

    public function getOriginalImagePath(): ?string
    {
        return $this->originalImagePath;
    }

    public function setOriginalImagePath(?string $originalImagePath): void
    {
        $this->originalImagePath = $originalImagePath;
    }

    public function getProcessingConfiguration(): array
    {
        return $this->processingConfiguration;
    }

    public function hasProcessingConfiguration(): bool
    {
        return !empty($this->processingConfiguration);
    }

    public function setProcessingConfiguration(array $processingConfiguration): void
    {
        $this->processingConfiguration = $processingConfiguration;
    }

    public function getProcessedFile(): ProcessedFile
    {
        return $this->processedFile;
    }

    public function setProcessedFile(ProcessedFile $processedFile): void
    {
        $this->processedFile = $processedFile;
    }
}
