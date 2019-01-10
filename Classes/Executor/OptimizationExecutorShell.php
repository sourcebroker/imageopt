<?php

/***************************************************************
 *  Copyright notice
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace SourceBroker\Imageopt\Executor;

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class OptimizationExecutorShell
 */
class OptimizationExecutorShell extends OptimizationExecutorBase
{
    /**
     * Optimize image using shell executable
     * Return the temporary file path
     *
     * @param string $inputImageAbsolutePath Absolute path/file with image to be optimized. It will be replaced with optimized version.
     * @param Configurator $configurator Executor configurator
     * @return ExecutorResult Executor Result
     */
    public function optimize($inputImageAbsolutePath, Configurator $configurator)
    {
        $executorResult = GeneralUtility::makeInstance(ExecutorResult::class);
        $executorResult->setExecutedSuccessfully(false);
        if (!empty($configurator->getOption('command.exec'))) {
            if (is_readable($inputImageAbsolutePath)) {
                $execDeclared = (string)$configurator->getOption('command.exec');
                if (pathinfo($execDeclared, PATHINFO_DIRNAME) !== '.') {
                    $execDetected = $execDeclared;
                } else {
                    $execDetected = CommandUtility::getCommand($execDeclared);
                }
                if ($execDetected !== false) {
                    $executorResult->setSizeBefore(filesize($inputImageAbsolutePath));
                    $shellCommand = str_replace(
                        ['{executable}', '{tempFile}', '{quality}'],
                        [
                            $execDetected,
                            escapeshellarg($inputImageAbsolutePath),
                            $this->getExecutorQuality($configurator)
                        ],
                        $configurator->getOption('command.mask')
                    );
                    $successfulStatuses = [0];
                    if (!empty($configurator->getOption('command.successfulExitStatus'))) {
                        $successfulStatuses = array_merge($successfulStatuses,
                            explode(',', (string)$configurator->getOption('command.successfulExitStatus'))
                        );
                    }
                    exec($shellCommand, $output, $commandStatus);
                    clearstatcache(true, $inputImageAbsolutePath);
                    $executorResult->setSizeAfter(filesize($inputImageAbsolutePath));
                    $executorResult->setCommand($shellCommand);
                    $executorResult->setCommandStatus($commandStatus);
                    $executorResult->setCommandOutput($output);
                    $executorResult->setExecutedSuccessfully(
                        in_array($commandStatus, $successfulStatuses) ? true : false
                    );
                } else {
                    $executorResult->setErrorMessage($execDeclared . ' can\'t be found.');
                }
            } else {
                $executorResult->setErrorMessage('Can not read file to optimize:' . $inputImageAbsolutePath);
            }
        } else {
            $executorResult->setErrorMessage('Variable "command" can not be found in executor configuration.');
        }
        return $executorResult;
    }
}
