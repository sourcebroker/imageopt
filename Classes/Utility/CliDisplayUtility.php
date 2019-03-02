<?php

namespace SourceBroker\Imageopt\Utility;

use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use SourceBroker\Imageopt\Domain\Model\ModeResult;
use SourceBroker\Imageopt\Domain\Model\ProviderResult;
use SourceBroker\Imageopt\Domain\Model\StepResult;

class CliDisplayUtility
{

    /**
     * Displays optimization result in CLI window
     *
     * @param ModeResult $modeResult
     * @return string
     */
    public static function displayOptionResult(ModeResult $modeResult, $config)
    {
        $stepProvidersInfo = [];
        /** @var StepResult[] $stepResults */
        $stepResults = $modeResult->getStepResults()->toArray();

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
                $provider = '* ' . $provider;
            }

            $providerType = $config['mode'][$modeResult->getName()]['step'][$stepResult->getName()]['providerType'];
            if ($stepResult->getProvidersResults()->count() > 0 &&
                $stepResult->getProvidersResults()->count() == $stepResult->getExecutedSuccessfullyNum()) {
                $stepResultFinal = 'All providers executed sucessfuly.';
            } else {
                $stepResultFinal = 'Not all providers executed sucessfuly so this step failed.';
            }

            $fileType = strtolower(explode('/',
                image_type_to_mime_type(getimagesize($modeResult->getFileAbsolutePath())[2]))[1]);

            $statsInfo = 'Step ' . ($stepKey + 1) . "\t\t| Description: " . $stepResult->getDescription() . "\n"
                . "\t\t| Providers to find for this step: \"" . $providerType . '" for file type "' . $fileType . "\".\n"
                . "\t\t| Found " . $stepResult->getProvidersResults()->count() . ' provider' . ($stepResult->getProvidersResults()->count() > 1 ? 's' : '')
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
            $stepProvidersInfo[] = $statsInfo . "\n";
        }
        $pathInfo = pathinfo($modeResult->getFileAbsolutePath());
        $outputFile = str_replace(
            ['{dirname}', '{basename}', '{extension}', '{filename}'],
            [$pathInfo['dirname'], $pathInfo['basename'], $pathInfo['extension'], $pathInfo['filename']],
            $config['mode'][$modeResult->getName()]['outputFilename']
        );

        $output = '---------------------------------------------------------------------' . "\n" .
            "File \t\t| In : " . $modeResult->getFileAbsolutePath() . "\n";

        if($outputFile) {
            $output .= "     \t\t| Out: " . $outputFile . "\n\n";
        }

        if ($modeResult->getName()) {
            $output .= "Mode\t\t| Name: " . $modeResult->getName() . "\n" .
                "\t\t| Description: " . $modeResult->getDescription() . "\n" .
                "\t\t| Number of steps: " . $modeResult->getStepResults()->count() . "\n\n";
        }
        if (!empty($stepProvidersInfo)) {
            $output .= implode("\n", $stepProvidersInfo);
        }
        $output .= "Result\t\t| ";
        if ($modeResult->isExecutedSuccessfully()) {
            $output .= 'All steps executed sucesfully. File is smaller by ' . round($modeResult->getOptimizationPercentage(),
                    2) . '%';
        } else {
            if (strlen($modeResult->getInfo())) {
                $output .= implode("\t\t| ", array_map(function ($line) {
                    return trim($line) . "\n";
                }, explode("\n", wordwrap($modeResult->getInfo(), 100))));
            } else {
                $output .= 'One of the steps failed. Image is not optimized.';
            }
        }
        return $output . "\n";
    }
}
