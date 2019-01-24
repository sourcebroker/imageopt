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
     * @param OptimizationOptionResult $result
     * @return string
     */
    public static function displayOptimizationOptionResult(OptimizationOptionResult $result)
    {
        $stepProvidersInfo = [];

        /** @var OptimizationStepResult $stepResults [] */
        $stepResults = $result->getOptimizationStepResults()->toArray();

        foreach ($stepResults as $stepKey => $stepResult) {
            $providers = [];
            $providersScore = [];

            /** @var ProviderResult[] $providerResults */
            $providerResults = $stepResult->getProvidersResults();
            foreach ($providerResults as $providerResult) {
                if ($providerResult->isExecutedSuccessfully()) {
                    $providers[] = $providerResult->getName() . ': ' . round($providerResult->getOptimizationPercentage(),
                            2) . '%';
                    $providersScore[] = $providerResult->getOptimizationPercentage();
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
                    $providersScore[] = null;
                }
            }

            uksort($providers, function ($a, $b) use ($providersScore) {
                if ($a === null) {
                    return 1;
                } elseif ($b === null) {
                    return -1;
                }
                return $providersScore[$a] > $providersScore[$b] ? -1 : 1;
            });

            $providers = array_values($providers);

            foreach ($providers as $i => &$provider) {
                $provider = ($i + 1) . ') ' . $provider;
            }

            $statsInfo = '';
            if (!empty($providers)) {
                $statsInfo = 'Step ' . ($stepKey + 1) . "\t| "
                    . $stepResult->getExecutedSuccessfullyNum() . ' out of ' . $stepResult->getProvidersResults()->count()
                    . ' providers finished successfully:' . "\n";
            }

            $stepProvidersInfo[] = $statsInfo .
                "\t| " . implode("\n\t| ", $providers);
        }

        $output = '---------------------------------' . "\n" .
            "File\t| " . $result->getFileRelativePath() . "\n" .
            "Mode\t| " . $result->getOptimizationMode() . "\n" .
            "Result\t| ";

        if ($result->isExecutedSuccessfully()) {
            $output .= 'OK ' . round($result->getOptimizationPercentage(), 2) . '%';
        } else {
            $output .= 'Failed';
        }

        $output .= implode("\n\n\t| ", explode("\n", wordwrap($result->getInfo(), 70))) . "\n\n" .
            implode("\n\n", $stepProvidersInfo) . "\n\n";

        return $output;
    }
}
