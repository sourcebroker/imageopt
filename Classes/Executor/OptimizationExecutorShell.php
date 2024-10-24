<?php

namespace SourceBroker\Imageopt\Executor;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Dto\Image;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizationExecutorShell extends OptimizationExecutorBase
{
    /**
     * Optimize image using shell executable
     * Return the temporary file path
     */
    public function optimize(string $imageAbsolutePath, Image $image, Configurator $configurator): ExecutorResult
    {
        $executorResult = GeneralUtility::makeInstance(ExecutorResult::class);
        $executorResult->setExecutedSuccessfully(false);
        if (!empty($configurator->getOption('command.exec'))) {
            if (is_readable($imageAbsolutePath)) {
                $execDeclared = (string)$configurator->getOption('command.exec');
                if (pathinfo($execDeclared, PATHINFO_DIRNAME) !== '.') {
                    $execDetected = $execDeclared;
                } else {
                    $execDetected = CommandUtility::getCommand($execDeclared);
                }
                if ($execDetected !== false) {
                    $executorResult->setSizeBefore(filesize($imageAbsolutePath));
                    $shellCommand = str_replace(
                        ['{executable}', '{tempFile}', '{quality}'],
                        [
                            $execDetected,
                            escapeshellarg($imageAbsolutePath),
                            $this->getExecutorQuality($configurator),
                        ],
                        $configurator->getOption('command.mask')
                    );
                    $successfulStatuses = [0];
                    if (!empty($configurator->getOption('command.successfulExitStatus'))) {
                        $successfulStatuses = array_merge(
                            $successfulStatuses,
                            explode(',', (string)$configurator->getOption('command.successfulExitStatus'))
                        );
                    }
                    exec($shellCommand, $output, $commandStatus);
                    clearstatcache(true, $imageAbsolutePath);
                    $executorResult->setSizeAfter(filesize($imageAbsolutePath));
                    $executorResult->setCommand($shellCommand);
                    $executorResult->setCommandStatus($commandStatus);
                    $executorResult->setCommandOutput(implode("\n", $output));
                    $executorResult->setExecutedSuccessfully(
                        in_array($commandStatus, $successfulStatuses, true)
                    );
                } else {
                    $executorResult->setErrorMessage($execDeclared . ' can\'t be found.');
                }
            } else {
                $executorResult->setErrorMessage('Can not read file to optimize:' . $imageAbsolutePath);
            }
        } else {
            $executorResult->setErrorMessage('Variable "command" can not be found in executor configuration.');
        }
        return $executorResult;
    }
}
