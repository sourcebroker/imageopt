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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class OptimizationExecutorShell
 */
class OptimizationExecutorBase implements OptimizationExecutorInterface
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
        return $executorResult;
    }

    /**
     * @param Configurator $configurator
     * @return string
     */
    public function getExecutorQuality(Configurator $configurator)
    {
        $executorQuality = '';
        if (!empty($configurator->getOption('options.quality.options')) && !empty($configurator->getOption('options.quality.value'))) {
            $closestQualityKey = null;
            $quality = (int)$configurator->getOption('options.quality.value');
            $options = $configurator->getOption('options.quality.options');

            if (isset($options[$quality])) {
                $executorQuality = $options[$quality];
            } else {
                foreach (array_keys($options) as $optionKey) {
                    if ($closestQualityKey == null || abs((int)$quality - $closestQualityKey) > abs($optionKey - (int)$quality)) {
                        $closestQualityKey = $optionKey;
                    }
                }
                $executorQuality = $options[$closestQualityKey];
            }
        }
        return $executorQuality;
    }
}
