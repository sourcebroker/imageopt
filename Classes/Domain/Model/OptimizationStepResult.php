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

class OptimizationStepResult extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var string
     */
    protected $sizeBefore = '';

    /**
     * @var string
     */
    protected $sizeAfter = '';

    /**
     * @var string
     */
    protected $providerWinnerName = '';

    /**
     * @var bool
     */
    protected $executedSuccessfully = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\ProviderResult>
     * @cascade remove
     */
    protected $providersResults = null;

    /**
     * @var string
     */
    protected $info = '';

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->providersResults = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a ProviderResult
     *
     * @param \SourceBroker\Imageopt\Domain\Model\ProviderResult $providersResult
     * @return static
     */
    public function addProvidersResult(\SourceBroker\Imageopt\Domain\Model\ProviderResult $providersResult)
    {
        $this->providersResults->attach($providersResult);
        return $this;
    }

    /**
     * Returns the providersResults
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\ProviderResult> $providersResults
     */
    public function getProvidersResults()
    {
        return $this->providersResults;
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
        return $this->sizeBefore - $this->sizeBefore;
    }

    /**
     * Returns the optimizationPercentage
     *
     * @return string $optimizationPercentage
     */
    public function getOptimizationPercentage()
    {
        return ($this->sizeBefore - $this->sizeBefore) / $this->sizeBefore * 100;
    }

    /**
     * Returns the providerWinnerName
     *
     * @return string $providerWinnerName
     */
    public function getProviderWinnerName()
    {
        return $this->providerWinnerName;
    }

    /**
     * Sets the providerWinnerName
     *
     * @param string $providerWinnerName
     * @return static
     */
    public function setProviderWinnerName($providerWinnerName)
    {
        $this->providerWinnerName = $providerWinnerName;
        return $this;
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
     * Returns number of successfully runned executors
     *
     * @return int
     */
    public function getExecutedSuccessfullyNum()
    {
        $num = 0;
        foreach ($this->providersResults as $result) {
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
