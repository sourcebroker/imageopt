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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class OptionResult extends AbstractBaseResult
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
    protected $info = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\StepResult>
     */
    protected $stepResults;

    public function __construct()
    {
        $this->stepResults = new ObjectStorage();
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
     * @param \SourceBroker\Imageopt\Domain\Model\StepResult $stepResult
     * @return static
     */
    public function addStepResult(StepResult $stepResult)
    {
        $this->stepResults->attach($stepResult);
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\StepResult>
     */
    public function getStepResults()
    {
        return $this->stepResults;
    }

    /**
     * Returns number of successfully runned executors
     *
     * @return int
     */
    public function getExecutedSuccessfullyNum()
    {
        $num = 0;
        foreach ($this->stepResults as $result) {
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
