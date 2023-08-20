<?php

namespace SourceBroker\Imageopt\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

class StepResult extends AbstractBaseResult
{
    protected string $name = '';

    protected string $description = '';

    protected string $providerWinnerName = '';

    /**
     * @var ObjectStorage<ProviderResult>
     */
    protected ObjectStorage $providersResults;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): StepResult
    {
        $this->description = $description;
        return $this;
    }

    protected string $info = '';

    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    protected function initStorageObjects(): void
    {
        $this->providersResults = new ObjectStorage();
    }

    public function addProvidersResult(ProviderResult $providersResult): StepResult
    {
        $this->providersResults->attach($providersResult);
        return $this;
    }

    /**
     * @return ObjectStorage<ProviderResult>
     */
    public function getProvidersResults(): ObjectStorage
    {
        return $this->providersResults;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): StepResult
    {
        $this->name = $name;
        return $this;
    }

    public function getProviderWinnerName(): string
    {
        return $this->providerWinnerName;
    }

    public function setProviderWinnerName($providerWinnerName): StepResult
    {
        $this->providerWinnerName = $providerWinnerName;
        return $this;
    }

    public function getExecutedSuccessfullyNum(): int
    {
        $num = 0;
        foreach ($this->providersResults as $result) {
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

    public function setInfo(string $info): StepResult
    {
        $this->info = $info;
        return $this;
    }
}
