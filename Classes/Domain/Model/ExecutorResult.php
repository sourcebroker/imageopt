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
 * ExecutorResult
 */
class ExecutorResult extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * sizeBefore
     *
     * @var int
     */
    protected $sizeBefore = 0;

    /**
     * sizeAfter
     *
     * @var int
     */
    protected $sizeAfter = 0;

    /**
     * command
     *
     * @var string
     */
    protected $command = '';

    /**
     * commandOutput
     *
     * @var string
     */
    protected $commandOutput = '';

    /**
     * commandStatus
     *
     * @var string
     */
    protected $commandStatus = '';

    /**
     * errorMessage
     *
     * @var string
     */
    protected $errorMessage = '';

    /**
     * executedSuccessfully
     *
     * @var bool
     */
    protected $executedSuccessfully = false;

    /**
     * Returns the sizeBefore
     *
     * @return int $sizeBefore
     */
    public function getSizeBefore()
    {
        return $this->sizeBefore;
    }

    /**
     * Sets the sizeBefore
     *
     * @param int $sizeBefore
     * @return void
     */
    public function setSizeBefore($sizeBefore)
    {
        $this->sizeBefore = $sizeBefore;
    }

    /**
     * Returns the sizeAfter
     *
     * @return int $sizeAfter
     */
    public function getSizeAfter()
    {
        return $this->sizeAfter;
    }

    /**
     * Sets the sizeAfter
     *
     * @param int $sizeAfter
     * @return void
     */
    public function setSizeAfter($sizeAfter)
    {
        $this->sizeAfter = $sizeAfter;
    }

    /**
     * Returns the command
     *
     * @return string $command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Sets the command
     *
     * @param string $command
     * @return void
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * Returns the commandOutput
     *
     * @return string $commandOutput
     */
    public function getCommandOutput()
    {
        return $this->commandOutput;
    }

    /**
     * Sets the commandOutput
     *
     * @param string $commandOutput
     * @return void
     */
    public function setCommandOutput($commandOutput)
    {
        $this->commandOutput = $commandOutput;
    }

    /**
     * Returns the commandStatus
     *
     * @return string $commandStatus
     */
    public function getCommandStatus()
    {
        return $this->commandStatus;
    }

    /**
     * Sets the commandStatus
     *
     * @param string $commandStatus
     * @return void
     */
    public function setCommandStatus($commandStatus)
    {
        $this->commandStatus = $commandStatus;
    }

    /**
     * Returns the errorMessage
     *
     * @return string errorMessage
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Sets the errorMessage
     *
     * @param string $errorMessage
     * @return void
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
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
}
