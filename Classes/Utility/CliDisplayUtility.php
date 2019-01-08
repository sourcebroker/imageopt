<?php

namespace SourceBroker\Imageopt\Utility;

use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use SourceBroker\Imageopt\Domain\Model\OptimizationResult;
use SourceBroker\Imageopt\Domain\Model\ProviderResult;

class CliDisplayUtility
{

    /**
     * Displays optimization result in CLI window
     *
     * @param OptimizationResult $optimizationResult
     * @return string
     */
    public static function displayOptimizationResult(OptimizationResult $optimizationResult)
    {
        /** @var  $optimizationResult */
        $providers = [];
        $providersScore = [];

        /** @var ProviderResult $providerResult */
        $providerResults = $optimizationResult->getProvidersResults()->toArray();
        foreach ($providerResults as $providerResult) {
            if ($providerResult->isExecutedSuccessfully()) {
                $percentage = round((
                        $providerResult->getSizeBefore() - $providerResult->getSizeAfter()) * 100
                    / $providerResult->getSizeBefore(), 2);

                $providers[] = $providerResult->getName() . ': ' . $percentage . '%';
                $providersScore[] = ($providerResult->getSizeBefore() - $providerResult->getSizeAfter()) / $providerResult->getSizeBefore();
            } else {
                /** @var ExecutorResult $executorResult */
                $error = [];
                foreach ($providerResult->getExecutorsResults()->toArray() as $executorResult) {
                    if (!$executorResult->isExecutedSuccessfully()) {
                        $error[] = $executorResult->getErrorMessage();
                    }
                }

                $limit = 41 - strlen($providerResult->getName());
                $errors = implode(', ', $error);
                if (strlen($errors) > $limit) {
                    $errors = substr($errors, 0, $limit - 2) . '..';
                }

                $providers[] = $providerResult->getName() . ' - failed: ' . $errors;
                $providersScore[] = -99999;
            }
        }

        uksort($providers, function ($a, $b) use ($providersScore) {
            return $providersScore[$a] > $providersScore[$b] ? -1 : 1;
        });

        $providers = array_values($providers);

        foreach ($providers as $i => &$provider) {
            $provider = ($i + 1) . ') ' . $provider;
        }

        $statsInfo = '';
        if ($optimizationResult->getProvidersResults()->count() !== 0) {
            $statsInfo = "Provider stats\t| " . $optimizationResult->getExecutedSuccessfullyNum() . ' out of ' . $optimizationResult->getProvidersResults()->count() . ' providers finished successfully:' . "\n";
        }

        return
            '---------------------------------' . "\n" .
            "File\t\t| " . $optimizationResult->getFileRelativePath() . "\n" .
            "Info\t\t| " . implode("\n\t\t| ", explode("\n", wordwrap($optimizationResult->getInfo(), 70))) . "\n" .
            $statsInfo .
            "\t\t| " . implode("\n\t\t| ", $providers) . "\n";
    }
}
