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
 * ProviderResult
 */
class ProviderResult extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Provider name
     *
     * @var string
     */
    protected $name = '';

    /**
     * File size before optimization
     *
     * @var string
     */
    protected $sizeBefore = '';

    /**
     * File size after optimization
     *
     * @var string
     */
    protected $sizeAfter = '';

    /**
     * Boolean if all executors was succesfully finished
     *
     * @var bool
     */
    protected $executedSuccessfully = false;

    /**
     * Boolean if provider is winner
     *
     * @var bool
     */
    protected $winner = false;

    /**
     * executorsResults
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\ExecutorResult>
     * @cascade remove
     */
    protected $executorsResults = null;

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
        $this->executorsResults = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a ExecutorResult
     *
     * @param \SourceBroker\Imageopt\Domain\Model\ExecutorResult $executorsResult
     * @return void
     */
    public function addExecutorsResult(\SourceBroker\Imageopt\Domain\Model\ExecutorResult $executorsResult)
    {
        $this->executorsResults->attach($executorsResult);
    }

    /**
     * Removes a ExecutorResult
     *
     * @param \SourceBroker\Imageopt\Domain\Model\ExecutorResult $executorsResultToRemove The ExecutorResult to be removed
     * @return void
     */
    public function removeExecutorsResult(\SourceBroker\Imageopt\Domain\Model\ExecutorResult $executorsResultToRemove)
    {
        $this->executorsResults->detach($executorsResultToRemove);
    }

    /**
     * Returns the executorsResults
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\ExecutorResult> $executorsResults
     */
    public function getExecutorsResults()
    {
        return $this->executorsResults;
    }

    /**
     * Sets the executorsResults
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\ExecutorResult> $executorsResults
     * @return void
     */
    public function setExecutorsResults(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $executorsResults)
    {
        $this->executorsResults = $executorsResults;
    }

    /**
     * Returns the sizeBefore
     *
     * @return string sizeBefore
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
     * Returns the winner
     *
     * @return bool $winner
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Sets the winner
     *
     * @param bool $winner
     * @return void
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }

    /**
     * Returns the boolean state of winner
     *
     * @return bool
     */
    public function isWinner()
    {
        return $this->winner;
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the executedSuccessfully
     *
     * @return bool executedSuccessfully
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
}
