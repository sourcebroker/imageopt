<?php

namespace SourceBroker\Imageopt\Domain\Model;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

abstract class AbstractBaseResult extends AbstractEntity
{
    protected string $sizeBefore = '';

    protected string $sizeAfter = '';

    protected int $optimizationBytes;

    protected float $optimizationPercent;

    protected bool $executedSuccessfully = false;

    public function getSizeBefore(): string
    {
        return $this->sizeBefore;
    }

    public function setSizeBefore(string $sizeBefore): AbstractBaseResult
    {
        $this->sizeBefore = $sizeBefore;
        return $this;
    }

    public function getSizeAfter(): string
    {
        return $this->sizeAfter;
    }

    public function setSizeAfter(string $sizeAfter): AbstractBaseResult
    {
        $this->sizeAfter = $sizeAfter;
        return $this;
    }

    public function getOptimizationBytes(): int
    {
        return (int)$this->sizeBefore - (int)$this->sizeAfter;
    }

    public function getOptimizationPercentage()
    {
        if (!$this->sizeBefore) {
            return 0;
        }
        return ((int)$this->sizeBefore - (int)$this->sizeAfter) / (float)$this->sizeBefore * 100;
    }

    public function isExecutedSuccessfully(): bool
    {
        return $this->executedSuccessfully;
    }

    public function setExecutedSuccessfully(bool $executedSuccessfully): AbstractBaseResult
    {
        $this->executedSuccessfully = $executedSuccessfully;
        return $this;
    }
}
