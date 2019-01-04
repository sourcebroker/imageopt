<?php

namespace SourceBroker\Imageopt\Utility;

use SourceBroker\Imageopt\Domain\Model\OptimizationResult;

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
        $providersScore = [];
        $success = $nr = 0;
        /** @var ProviderResult $providerResult */
        foreach ($optimizationResult->getProvidersResults()->toArray() as $providerResult) {
            $nr++;
            if ($providerResult->isExecutedSuccessfully()) {
                $success++;
                $percentage = round((
                        $providerResult->getSizeBefore() - $providerResult->getSizeAfter()) * 100
                    / $providerResult->getSizeBefore(), 2);

                $providersScore[] = $nr . ') ' . $providerResult->getName() . ': ' . $percentage . '%';
            } else {
                /** @var ExecutorResult $executorResult */
                $error = [];
                foreach ($providerResult->getExecutorsResults()->toArray() as $executorResult) {
                    if (!$executorResult->isExecutedSuccessfully()) {
                        $error[] = $executorResult->getCommandStatus();
                    }
                }
                $providersScore[] = $nr . ') ' . $providerResult->getName() . ' - failed - ' . implode(' ', $error);
            }
        }
        return
            '---------------------------------' . "\n" .
            "File\t\t| " . $optimizationResult->getFileRelativePath() . "\n" .
            "Info\t\t| " . implode("\n\t\t| ", explode("\n", wordwrap($optimizationResult->getInfo(), 70))) . "\n" .
            "Provider stats\t| " . $success . ' out of ' . $optimizationResult->getProvidersResults()->count() . ' providers finished successfully:' . "\n" .
            "\t\t| " . implode("\n\t\t| ", $providersScore) . "\n";
    }
}
