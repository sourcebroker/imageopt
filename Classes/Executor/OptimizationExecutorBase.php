<?php

namespace SourceBroker\Imageopt\Executor;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizationExecutorBase implements OptimizationExecutorInterface
{
    /**
     * Optimize image using shell executable
     * Return the temporary file path
     */
    public function optimize(string $imageAbsolutePath, Configurator $configurator): ExecutorResult
    {
        $executorResult = GeneralUtility::makeInstance(ExecutorResult::class);
        $executorResult->setExecutedSuccessfully(false);
        return $executorResult;
    }

    public function getExecutorQuality(Configurator $configurator): string
    {
        $executorQuality = '';
        if (!empty($configurator->getOption('options.quality.options')) && !empty($configurator->getOption('options.quality.value'))) {
            $quality = $configurator->getOption('options.quality.value');
            $options = $configurator->getOption('options.quality.options');
            if (isset($options[$quality])) {
                $executorQuality = $options[$quality];
            }
        }
        return $executorQuality;
    }
}
