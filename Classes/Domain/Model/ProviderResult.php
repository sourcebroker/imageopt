<?php

namespace SourceBroker\Imageopt\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

class ProviderResult extends AbstractBaseResult
{
    protected string $name = '';

    /**
     * @var ObjectStorage<ExecutorResult>
     */
    protected ObjectStorage $executorsResults;

    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    protected function initStorageObjects(): void
    {
        $this->executorsResults = new ObjectStorage();
    }

    public function addExecutorsResult(ExecutorResult $executorsResult): void
    {
        $this->executorsResults->attach($executorsResult);
    }

    public function removeExecutorsResult(ExecutorResult $executorsResultToRemove): void
    {
        $this->executorsResults->detach($executorsResultToRemove);
    }

    /**
     * @return ObjectStorage<ExecutorResult> $executorsResults
     */
    public function getExecutorsResults(): ObjectStorage
    {
        return $this->executorsResults;
    }

    /**
     * @param ObjectStorage<ExecutorResult> $executorsResults
     */
    public function setExecutorsResults(ObjectStorage $executorsResults): void
    {
        $this->executorsResults = $executorsResults;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
