<?php

namespace SourceBroker\Imageopt\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

abstract class AbstractBaseResult extends AbstractEntity
{
    /**
     * @var int
     */
    protected $sizeBefore = 0;

    /**
     * @var int
     */
    protected $sizeAfter = 0;

    /**
     * @var int
     */
    protected $optimizationBytes;

    /**
     * @var float
     */
    protected $optimizationPercent;

    /**
     * @var bool
     */
    protected $executedSuccessfully = false;

    /**
     * @return int sizeBefore
     */
    public function getSizeBefore()
    {
        return $this->sizeBefore;
    }

    /**
     * @param int $sizeBefore
     * @return static
     */
    public function setSizeBefore($sizeBefore)
    {
        $this->sizeBefore = $sizeBefore;
        return $this;
    }

    /**
     * @return int $sizeAfter
     */
    public function getSizeAfter()
    {
        return $this->sizeAfter;
    }

    /**
     * @param int $sizeAfter
     * @return static
     */
    public function setSizeAfter($sizeAfter)
    {
        $this->sizeAfter = $sizeAfter;
        return $this;
    }

    /**
     * @return string $optimizationBytes
     */
    public function getOptimizationBytes()
    {
        if ($this->optimizationBytes === null) {
            $this->optimizationBytes = (int)$this->sizeBefore - (int)$this->sizeAfter;
        }
        return $this->optimizationBytes;
    }

    /**
     * @return string $optimizationPercentage
     */
    public function getOptimizationPercentage()
    {
        if (!$this->sizeBefore) {
            return 0;
        }

        if ($this->optimizationPercent === null) {
            $this->optimizationPercent = ((int)$this->sizeBefore - (int)$this->sizeAfter) / (float)$this->sizeBefore * 100;
        }
        return $this->optimizationPercent;
    }

    /**
     * @return bool
     */
    public function isExecutedSuccessfully()
    {
        return $this->executedSuccessfully;
    }

    /**
     * @param bool $executedSuccessfully
     * @return static
     */
    public function setExecutedSuccessfully($executedSuccessfully)
    {
        $this->executedSuccessfully = $executedSuccessfully;
        return $this;
    }
}
