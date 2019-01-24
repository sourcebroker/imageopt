<?php

namespace SourceBroker\Imageopt\Domain\Model;

/***
 *
 * This file is part of the "imageopt" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017
 *
 ***/

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class OptimizationOptionResult extends AbstractEntity
{
    /**
     * @var string
     */
    protected $fileRelativePath = '';

    /**
     * @var string
     */
    protected $optimizationMode = '';

    /**
     * @var string
     */
    protected $sizeBefore = '';

    /**
     * @var string
     */
    protected $sizeAfter = '';

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
     * @var string
     */
    protected $info = '';

    /**
     * @var ObjectStorage<OptimizationStepResult>
     */
    protected $optimizationStepResults;

    public function __construct()
    {
        $this->optimizationStepResults = new ObjectStorage();
    }

    /**
     * @return string $fileRelativePath
     */
    public function getFileRelativePath()
    {
        return $this->fileRelativePath;
    }

    /**
     * @param string $fileRelativePath
     * @return static
     */
    public function setFileRelativePath($fileRelativePath)
    {
        $this->fileRelativePath = $fileRelativePath;
        return $this;
    }

    /**
     * @return string $fileRelativePath
     */
    public function getOptimizationMode()
    {
        return $this->optimizationMode;
    }

    /**
     * @param string $optimizationMode
     * @return static
     */
    public function setOptimizationMode($optimizationMode)
    {
        $this->optimizationMode = $optimizationMode;
        return $this;
    }

    /**
     * Returns the sizeBefore
     *
     * @return string $sizeBefore
     */
    public function getSizeBefore()
    {
        return $this->sizeBefore;
    }

    /**
     * Sets the sizeBefore
     *
     * @param string $sizeBefore
     * @return static
     */
    public function setSizeBefore($sizeBefore)
    {
        $this->sizeBefore = $sizeBefore;
        return $this;
    }

    /**
     * Returns the sizeAfter
     *
     * @return string $sizeAfter
     */
    public function getSizeAfter()
    {
        return $this->sizeAfter;
    }

    /**
     * Sets the sizeAfter
     *
     * @param string $sizeAfter
     * @return static
     */
    public function setSizeAfter($sizeAfter)
    {
        $this->sizeAfter = $sizeAfter;
        return $this;
    }

    /**
     * Returns the optimizationBytes
     *
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
     * Returns the optimizationPercentage
     *
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
     * Sets the executedSuccessfully
     *
     * @param bool $executedSuccessfully
     * @return static
     */
    public function setExecutedSuccessfully($executedSuccessfully)
    {
        $this->executedSuccessfully = $executedSuccessfully;
        return $this;
    }

    /**
     * Returns the boolean state of executedSuccessfully
     *
     * @return bool
     */
    public function isExecutedSuccessfully()
    {
        return $this->executedSuccessfully;
    }

    /**
     * @param OptimizationStepResult $providersResult
     * @return static
     */
    public function addOptimizationStepResult(OptimizationStepResult $optimizationStepResult)
    {
        $this->optimizationStepResults->attach($optimizationStepResult);
        return $this;
    }

    /**
     * @return ObjectStorage<OptimizationStepResult>
     */
    public function getOptimizationStepResults()
    {
        return $this->optimizationStepResults;
    }

    /**
     * Returns number of successfully runned executors
     *
     * @return int
     */
    public function getExecutedSuccessfullyNum()
    {
        $num = 0;
        foreach ($this->optimizationStepResults as $result) {
            if ($result->isExecutedSuccessfully()) {
                ++$num;
            }
        }
        return $num;
    }

    /**
     * Returns the info
     *
     * @return string $info
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Sets the info
     *
     * @param string $info
     * @return static
     */
    public function setInfo($info)
    {
        $this->info = $info;
        return $this;
    }
}
