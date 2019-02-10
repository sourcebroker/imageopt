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
class OptimizationStepResult extends AbstractBaseResult
{

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $providerWinnerName = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SourceBroker\Imageopt\Domain\Model\ProviderResult>
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
