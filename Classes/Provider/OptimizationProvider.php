<?php

namespace SourceBroker\Imageopt\Provider;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use Exception;
use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use SourceBroker\Imageopt\Domain\Model\ProviderResult;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizationProvider
{
    /**
     * @throws Exception
     */
    public function optimize(string $image, Configurator $providerConfigurator): ProviderResult
    {
        $executorsDone = 0;
        $executorsSuccessful = 0;
        $providerResult = GeneralUtility::makeInstance(ProviderResult::class);
        $providerResult->setSizeBefore(filesize($image));
        foreach ((array)$providerConfigurator->getOption('executors') as $executor) {
            $executorsDone++;
            if (isset($executor['class']) && class_exists($executor['class'])) {
                $imageOptimizationProvider = GeneralUtility::makeInstance($executor['class']);
                /** @var $executorResult ExecutorResult */
                $executorResult = $imageOptimizationProvider->optimize(
                    $image,
                    GeneralUtility::makeInstance(Configurator::class, $executor)
                );
                $providerResult->addExecutorsResult($executorResult);
                if ($executorResult->isExecutedSuccessfully()) {
                    $executorsSuccessful++;
                } else {
                    $providerResult->setExecutedSuccessfully(false);
                    break;
                }
            } else {
                throw new Exception('No class found: ' . $executor['class'], 1500994839981);
            }
        }

        $providerResult->setName($providerConfigurator->getOption('providerKey'));
        if ($executorsSuccessful === $executorsDone) {
            $providerResult->setExecutedSuccessfully(true);
        }
        clearstatcache(true, $image);
        $providerResult->setSizeAfter(filesize($image));

        return $providerResult;
    }
}
