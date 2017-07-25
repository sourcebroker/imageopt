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

namespace SourceBroker\Imageopt\Providers;

use SourceBroker\Imageopt\Configuration\Configurator;
use TYPO3\CMS\Core\Utility\CommandUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ImageOptimizationProviderShell
 */
class ImageOptimizationProviderShell extends ImageOptimizationProvider
{
    /**
     * Optimize image using shell executable
     * Return the temporary file path
     *
     * @param $inputImageAbsolutePath string Absolute path/file with image to be optimized
     * @param Configurator $configurator
     * @return array Optimization result array
     */
    public function optimize($inputImageAbsolutePath, Configurator $configurator)
    {
        $temporaryFileUtility = GeneralUtility::makeInstance(\SourceBroker\Imageopt\Utility\TemporaryFileUtility::class);
        $closestQualityKey = null;
        $quality = (int)$configurator->getOption('options.quality.value');
        foreach (array_keys((array)$configurator->getOption('options.quality.options')) as $optionKey) {
            if ($closestQualityKey == null || abs((int)$quality - $closestQualityKey) > abs($optionKey - (int)$quality)) {
                $closestQualityKey = $optionKey;
            }
        }
        $executorQuality = $configurator->getOption('options.quality.options')[$closestQualityKey];
        $result['success'] = false;
        if ($configurator->getOption('command') !== null) {
            $temporaryFileToBeOptimized = $temporaryFileUtility->createTemporaryCopy($inputImageAbsolutePath);
            if ($temporaryFileToBeOptimized) {
                $execDeclared = $configurator->getOption('exec');
                $execDetected = CommandUtility::getCommand($execDeclared);
                if ($execDetected !== false) {
                    $shellCommand = str_replace(
                        ['{executable}', '{tempFile}', '{quality}'],
                        [$execDetected, escapeshellarg($temporaryFileToBeOptimized), $executorQuality],
                        $configurator->getOption('command')
                    );
                    exec($shellCommand, $out, $commandStatus);
                    $result['name'] = $execDeclared;
                    $result['optimizedFileAbsPath'] = $temporaryFileToBeOptimized;
                    $result['command'] = $shellCommand;
                    if ($commandStatus === 0) {
                        $result['success'] = true;
                    } else {
                        $result['error'] = $out;
                    }
                } else {
                    $result['error'] = $execDeclared . ' can\'t be found.';
                }
            }
        }
        return $result;
    }
}
