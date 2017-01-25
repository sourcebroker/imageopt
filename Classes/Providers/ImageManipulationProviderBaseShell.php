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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Service\AbstractService;

/**
 * Class ImageManipulationProviderBaseShell
 */
class ImageManipulationProviderBaseShell extends ImageManipulationProvider
{
    /**
     * Shell executable command. Plz use markers with {executable} and {tempCopy}
     *
     * @var null|string
     */
    protected $shellExecutableCommand = null;

    /**
     * Optimize image using shell executable
     * Return the temporary file path
     *
     * @param $inputImageAbsolutePath string Absolute path/file with image to be optimized
     * @return array Optimization result array
     */
    public function optimize($inputImageAbsolutePath)
    {
        if ($this->configuration->getOption('command') != '') {
            $this->shellExecutableCommand = $this->configuration->getOption('command');
        }

        if ($this->shellExecutableCommand !== null) {
            $temporaryFileToBeOptimized = $this->createTemporaryCopy($inputImageAbsolutePath);

            if ($temporaryFileToBeOptimized) {
                $shellCommand = str_replace(
                    ['{executable}', '{tempFile}'],
                    [$this->getServiceInfo()['exec'], escapeshellarg($temporaryFileToBeOptimized)],
                    $this->shellExecutableCommand);

                exec($shellCommand, $out, $commandStatus);

                $this->optimizationResult['optimizedFileAbsPath'] = $temporaryFileToBeOptimized;
                $this->optimizationResult['providerCommand'] = $shellCommand;

                if ($commandStatus === 0) {
                    $this->optimizationResult['success'] = true;
                } else {
                    $this->optimizationResult['success'] = false;
                    $this->optimizationResult['providerError'] = $out;
                }
            }
        } else {
            $this->optimizationResult['providerError'] = 'Can\t find shell executable command';
        }

        return $this->optimizationResult;
    }
}