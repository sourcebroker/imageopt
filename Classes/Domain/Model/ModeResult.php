<?php

namespace SourceBroker\Imageopt\Domain\Model;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class ModeResult extends AbstractBaseResult
{
    protected string $fileAbsolutePath = '';

    protected string $name = '';

    protected string $description = '';

    protected string $info = '';

    protected string $outputFilename = '';

    protected bool $fileDoesNotExist = false;

    /**
     * @var ObjectStorage<StepResult>
     */
    protected ObjectStorage $stepResults;

    public function __construct()
    {
        $this->stepResults = new ObjectStorage();
    }

    public function getFileAbsolutePath(): string
    {
        return $this->fileAbsolutePath;
    }

    public function setFileAbsolutePath(string $fileAbsolutePath): ModeResult
    {
        $this->fileAbsolutePath = $fileAbsolutePath;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ModeResult
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): ModeResult
    {
        $this->description = $description;
        return $this;
    }

    public function addStepResult(StepResult $stepResult): ModeResult
    {
        $this->stepResults->attach($stepResult);
        return $this;
    }

    /**
     * @return ObjectStorage<StepResult>
     */
    public function getStepResults(): ObjectStorage
    {
        return $this->stepResults;
    }

    public function getExecutedSuccessfullyNum(): int
    {
        $num = 0;
        foreach ($this->stepResults as $result) {
            if ($result->isExecutedSuccessfully()) {
                ++$num;
            }
        }
        return $num;
    }

    public function getInfo(): string
    {
        return $this->info;
    }

    public function setInfo(string $info): ModeResult
    {
        $this->info = $info;
        return $this;
    }

    public function getFileDoesNotExist(): bool
    {
        return $this->fileDoesNotExist;
    }

    public function setFileDoesNotExist(bool $fileDoesNotExist): ModeResult
    {
        $this->fileDoesNotExist = $fileDoesNotExist;
        return $this;
    }

    public function getOutputFilename(): string
    {
        return $this->outputFilename;
    }

    public function setOutputFilename(string $outputFilename): ModeResult
    {
        $this->outputFilename = $outputFilename;
        return $this;
    }
}
