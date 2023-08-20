<?php

namespace SourceBroker\Imageopt\Domain\Model;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

class ExecutorResult extends AbstractBaseResult
{
    protected string $command = '';

    protected string $commandOutput = '';

    protected string $commandStatus = '';

    protected string $errorMessage = '';

    public function getCommand(): string
    {
        return $this->command;
    }

    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    public function getCommandOutput(): string
    {
        return $this->commandOutput;
    }

    public function setCommandOutput(string $commandOutput): void
    {
        $this->commandOutput = $commandOutput;
    }

    public function getCommandStatus(): string
    {
        return $this->commandStatus;
    }

    public function setCommandStatus(string $commandStatus): void
    {
        $this->commandStatus = $commandStatus;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }
}
