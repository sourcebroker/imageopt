<?php

namespace SourceBroker\Imageopt\Utility;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Domain\Model\ModeResult;
use SourceBroker\Imageopt\Domain\Model\ProviderResult;
use SourceBroker\Imageopt\Domain\Model\StepResult;

class CliDisplayUtility
{
    /**
     * Displays optimization result in CLI window
     */
    public static function displayOptionResult(ModeResult $modeResult, array $config): string
    {
        $output = '---------------------------------------------------------------------' . "\n" .
            "File \t\t| In : " . $modeResult->getFileAbsolutePath() . "\n";
        $outputFile = $modeResult->getOutputFilename();
        if ($outputFile !== '') {
            $output .= "\t\t| Out: " . $outputFile . "\n\n";
        }

        if ($modeResult->getName()) {
            $output .= self::getModeInfo($modeResult);
            $stepProvidersInfo = self::getStepResult($modeResult, $config);
            if (!empty($stepProvidersInfo)) {
                $output .= implode("\n", $stepProvidersInfo);
            }
        }

        $output .= self::getResultInfo($modeResult);
        return $output . "\n";
    }

    protected static function getModeInfo(ModeResult $modeResult): string
    {
        return "Mode\t\t| Name: " . $modeResult->getName() . "\n" .
            "\t\t| Description: " . $modeResult->getDescription() . "\n" .
            "\t\t| Number of steps: " . $modeResult->getStepResults()->count() . "\n\n";
    }

    protected static function getResultInfo(ModeResult $modeResult): string
    {
        $output = "Result\t\t| ";
        if ($modeResult->isExecutedSuccessfully()) {
            $output .= 'All steps executed successfully. File is smaller by '
                . round($modeResult->getOptimizationPercentage(), 2) . '%';
        } elseif ($modeResult->getInfo() !== '') {
            $output .= implode("\t\t| ", array_map(function ($line): string {
                return trim($line) . "\n";
            }, explode("\n", wordwrap($modeResult->getInfo(), 100))));
        } else {
            $output .= 'One of the steps failed. Image is not optimized.';
        }
        return $output;
    }

    protected static function getStepResult(ModeResult $modeResult, array $config): array
    {
        $stepProvidersInfo = [];
        /** @var StepResult[] $stepResults */
        $stepResults = $modeResult->getStepResults()->toArray();
        foreach ($stepResults as $stepKey => $stepResult) {
            $stepProvidersInfo[] = self::getStatusInfo($modeResult, $config, $stepKey, $stepResult) . "\n";
        }
        return $stepProvidersInfo;
    }

    protected static function getStatusInfo(
        ModeResult $modeResult,
        array $config,
        int $stepKey,
        StepResult $stepResult
    ): string {
        $providers = [];
        $providersScore = [];

        /** @var ProviderResult[] $providerResults */
        $providerResults = $stepResult->getProvidersResults()->toArray();
        self::providersInfo($providerResults, $providers, $providersScore);

        uksort($providers, static function ($a, $b) use ($providersScore): int {
            if ($a === null) {
                return 1;
            }
            if ($b === null) {
                return -1;
            }
            return $providersScore[$a] > $providersScore[$b] ? -1 : 1;
        });

        $providers = array_values($providers);

        foreach ($providers as &$provider) {
            $provider = '* ' . $provider;
        }

        if (
            $stepResult->getProvidersResults()->count() > 0 &&
            $stepResult->getProvidersResults()->count() === $stepResult->getExecutedSuccessfullyNum()
        ) {
            $stepResultFinal = 'All providers executed successfully.';
        } else {
            $stepResultFinal = 'Not all providers executed successfully so this step failed.';
        }

        $providerType = $config['mode'][$modeResult->getName()]['step'][$stepResult->getName()]['providerType'];
        $fileType = strtolower(explode(
            '/',
            image_type_to_mime_type(getimagesize($modeResult->getFileAbsolutePath())[2])
        )[1]);

        $statsInfo = 'Step ' . ($stepKey + 1) . "\t\t| Description: " . $stepResult->getDescription() . "\n"
            . "\t\t| Providers to find for this step: \"" . $providerType
            . '" for file type "' . $fileType . "\".\n"
            . "\t\t| Found " . $stepResult->getProvidersResults()->count()
            . ' provider' . ($stepResult->getProvidersResults()->count() > 1 ? 's' : '')
            . ' for "' . $providerType . '" and file type "' . $fileType . '". ';

        if (!empty($providers)) {
            $statsInfo .= "Running found providers:\n"
                . "\t\t| " . implode("\n\t\t| ", $providers);
        }
        $statsInfo .= "\n\t\t| " . $stepResultFinal . "\n"
            . "\t\t| " . $stepResult->getInfo() . "\n";
        if ($stepKey !== $modeResult->getStepResults()->count() - 1) {
            $statsInfo .= "\t\t| Passing the output file of this step to Step " . ($stepKey + 2) . '.';
        }

        return $statsInfo;
    }

    /**
     * @param ProviderResult[] $providerResults
     */
    protected static function providersInfo(array $providerResults, array &$providers, array &$providersScore): void
    {
        foreach ($providerResults as $providerResult) {
            if ($providerResult->isExecutedSuccessfully()) {
                $providers[] = $providerResult->getName() . ': ' . round(
                    $providerResult->getOptimizationPercentage(),
                    2
                ) . '%';
                $providersScore[] = $providerResult->getOptimizationPercentage();
            } else {
                $providers[] = self::getProviderError($providerResult);
                $providersScore[] = null;
            }
        }
    }

    protected static function getProviderError(ProviderResult $providerResult): string
    {
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

        return $providerResult->getName() . ' - failed: ' . $errors;
    }
}
