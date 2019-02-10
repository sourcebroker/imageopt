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
class ExecutorResult extends AbstractBaseResult
{
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
}
