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

use TYPO3\CMS\Core\Utility\CommandUtility;

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

        $selectedQuality = '';
        $quality = (int)$this->configuration->getOption('options.quality');
        $qualityOptions = $this->configuration->getOption('options.qualityOptions');
        if ($quality && is_array($qualityOptions)) {
            if (isset($qualityOptions[$quality])) {
                $selectedQuality = $qualityOptions[$quality];
            } else {
                foreach ($qualityOptions as $qualityOptionKey => $qualityOption) {
                    if ((int)$qualityOptionKey > $quality) {
                        $selectedQuality = $qualityOption;
                        break;
                    }
                }
            }
        }

        $exec = $this->getServiceInfo()['exec'];
        $this->optimizationResult['success'] = false;
        $this->optimizationResult['providerName'] = $exec;

        if ($this->shellExecutableCommand !== null) {
            $temporaryFileToBeOptimized = $this->createTemporaryCopy($inputImageAbsolutePath);
            if ($temporaryFileToBeOptimized) {
                $executable = CommandUtility::getCommand($exec);
                if ($executable) {
                    $shellCommand = str_replace(
                        ['{executable}', '{tempFile}', '{quality}'],
                        [$executable, escapeshellarg($temporaryFileToBeOptimized), $selectedQuality],
                        $this->shellExecutableCommand
                    );

                    exec($shellCommand, $out, $commandStatus);

                    $this->optimizationResult['optimizedFileAbsPath'] = $temporaryFileToBeOptimized;
                    $this->optimizationResult['providerCommand'] = $shellCommand;

                    if ($commandStatus === 0) {
                        $this->optimizationResult['success'] = true;
                    } else {
                        $this->optimizationResult['providerError'] = $out;
                    }
                } else {
                    $this->optimizationResult['providerError'] = $exec . ' can\'t be found.';
                }
            }
        } else {
            $this->optimizationResult['providerError'] = 'Can\t find shell executable command';
        }

        return $this->optimizationResult;
    }
}