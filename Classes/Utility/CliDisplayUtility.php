<?php

namespace SourceBroker\Imageopt\Utility;

use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use SourceBroker\Imageopt\Domain\Model\OptimizationOptionResult;
use SourceBroker\Imageopt\Domain\Model\OptimizationStepResult;
use SourceBroker\Imageopt\Domain\Model\ProviderResult;

class CliDisplayUtility
{

    /**
     * Displays optimization result in CLI window
     *
     * @param OptimizationOptionResult $optimizationResult
     * @return string
     */
    public static function displayOptimizationOptionResult(OptimizationOptionResult $optimizationResult)
    {
        $stepProvidersInfo = [];

        /** @var OptimizationStepResult $stepResults[] */
        $stepResults = $optimizationResult->getOptimizationStepResults()->toArray();

        foreach ($stepResults as $stepKey => $stepResult) {
            $providers = [];
            $providersScore = [];

            $providerResults = $stepResult->getProvidersResults();
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

                    $limit = 49 - strlen($providerResult->getName());
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
            if (!empty($providers)) {
                $statsInfo = "Step ". ($stepKey + 1) ."\t| "
                    . $stepResult->getExecutedSuccessfullyNum() . ' out of ' . $stepResult->getProvidersResults()->count()
                    . ' providers finished successfully:' . "\n";
            }

            $stepProvidersInfo[] = $statsInfo .
                "\t| " . implode("\n\t| ", $providers);
        }

        return
            '---------------------------------' . "\n" .
            "File\t| " . $optimizationResult->getFileRelativePath() ."\n" .
            "Mode\t| " . $optimizationResult->getOptimizationMode() .
            implode("\n\n\t| ", explode("\n", wordwrap($optimizationResult->getInfo(), 70))) . "\n\n" .
            implode("\n\n", $stepProvidersInfo) . "\n\n";
    }
}
