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

/**
 * ImageOptimization
 */
class OptimizationResult extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * fileRelativePath
     *
     * @var string
     */
    protected $fileRelativePath = '';

    /**
     * sizeBefore
     *
     * @var string
     */
    protected $sizeBefore = '';

    /**
     * sizeAfter
     *
     * @var string
     */
    protected $sizeAfter = '';

    /**
     * optimizationBytes
     *
     * @var string
     */
    protected $optimizationBytes = '';

    /**
     * optimizationPercentage
     *
     * @var string
     */
    protected $optimizationPercentage = '';

    /**
     * providerWinnerName
     *
     * @var string
     */
    protected $providerWinnerName = '';

    /**
     * executedSuccessfully
     *
     * @var bool
     */
    protected $executedSuccessfully = false;

    /**
     * providersResults
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\ProviderResult>
     * @cascade remove
     */
    protected $providersResults = null;

    /**
     * info
     *
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
     * @return void
     */
    public function addProvidersResult(\SourceBroker\Imageopt\Domain\Model\ProviderResult $providersResult)
    {
        $this->providersResults->attach($providersResult);
    }

    /**
     * Removes a ProviderResult
     *
     * @param \SourceBroker\Imageopt\Domain\Model\ProviderResult $providersResultToRemove The ProviderResult to be removed
     * @return void
     */
    public function removeProvidersResult(\SourceBroker\Imageopt\Domain\Model\ProviderResult $providersResultToRemove)
    {
        $this->providersResults->detach($providersResultToRemove);
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
     * Sets the providersResults
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\ProviderResult> $providersResults
     * @return void
     */
    public function setProvidersResults(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $providersResults)
    {
        $this->providersResults = $providersResults;
    }

    /**
     * Returns the fileRelativePath
     *
     * @return string $fileRelativePath
     */
    public function getFileRelativePath()
    {
        return $this->fileRelativePath;
    }

    /**
     * Sets the fileRelativePath
     *
     * @param string $fileRelativePath
     * @return void
     */
    public function setFileRelativePath($fileRelativePath)
    {
        $this->fileRelativePath = $fileRelativePath;
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
     * @return void
     */
    public function setSizeBefore($sizeBefore)
    {
        $this->sizeBefore = $sizeBefore;
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
     * @return void
     */
    public function setSizeAfter($sizeAfter)
    {
        $this->sizeAfter = $sizeAfter;
    }

    /**
     * Returns the optimizationBytes
     *
     * @return string $optimizationBytes
     */
    public function getOptimizationBytes()
    {
        return $this->optimizationBytes;
    }

    /**
     * Sets the optimizationBytes
     *
     * @param string $optimizationBytes
     * @return void
     */
    public function setOptimizationBytes($optimizationBytes)
    {
        $this->optimizationBytes = $optimizationBytes;
    }

    /**
     * Returns the optimizationPercentage
     *
     * @return string $optimizationPercentage
     */
    public function getOptimizationPercentage()
    {
        return $this->optimizationPercentage;
    }

    /**
     * Sets the optimizationPercentage
     *
     * @param string $optimizationPercentage
     * @return void
     */
    public function setOptimizationPercentage($optimizationPercentage)
    {
        $this->optimizationPercentage = $optimizationPercentage;
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
     * @return void
     */
    public function setProviderWinnerName($providerWinnerName)
    {
        $this->providerWinnerName = $providerWinnerName;
    }

    /**
     * Returns the executedSuccessfully
     *
     * @return bool $executedSuccessfully
     */
    public function getExecutedSuccessfully()
    {
        return $this->executedSuccessfully;
    }

    /**
     * Sets the executedSuccessfully
     *
     * @param bool $executedSuccessfully
     * @return void
     */
    public function setExecutedSuccessfully($executedSuccessfully)
    {
        $this->executedSuccessfully = $executedSuccessfully;
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
     * @return void
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }
}
