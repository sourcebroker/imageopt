<?php

namespace SourceBroker\Imageopt\Service;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use Exception;
use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ModeResult;
use SourceBroker\Imageopt\Domain\Model\StepResult;
use SourceBroker\Imageopt\Provider\OptimizationProvider;
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Optimize single image using multiple Image Optmization Provider.
 * The best optimization wins!
 */
class OptimizeImageService
{
    /**
     * @var Configurator
     */
    public $configurator;

    private TemporaryFileUtility $temporaryFile;

    /**
     * OptimizeImageService constructor.
     * @throws Exception
     */
    public function __construct(array $config = null)
    {
        if ($config === null) {
            throw new Exception('Configuration not set for OptimizeImageService class');
        }

        $this->configurator = GeneralUtility::makeInstance(Configurator::class, $config);
        $this->configurator->init();

        $this->temporaryFile = GeneralUtility::makeInstance(TemporaryFileUtility::class);
    }

    /**
     * Optimize image using chained Image Optimization Provider
     *
     * @return ModeResult[]
     * @throws Exception
     */
    public function optimize(string $sourceFile): array
    {
        // create original image copy - it may vary (provider may overwrite original image)
        $sourceFileTemporary = $this->temporaryFile->createTemporaryCopy($sourceFile);

        $modeResults = [];
        foreach ((array)$this->configurator->getOption('mode') as $modeKey => $modeConfig) {
            $modeResults[$modeKey] = $this->optimizeSingleMode(
                $modeConfig,
                $sourceFileTemporary,
                $sourceFile
            );
        }
        return $modeResults;
    }

    /**
     * @throws Exception
     */
    protected function optimizeSingleMode(
        array $modeConfig,
        string $sourceFileTemporary,
        string $sourceFile
    ): ModeResult {
        $modeResult = GeneralUtility::makeInstance(ModeResult::class)
            ->setFileAbsolutePath($sourceFile)
            ->setName($modeConfig['name'])
            ->setDescription($modeConfig['description'])
            ->setSizeBefore(filesize($sourceFileTemporary))
            ->setExecutedSuccessfully(false);

        if (!file_exists($sourceFile)) {
            $modeResultInfo = 'The file does not exists.';
            $modeResult->setFileDoesNotExist(true);
            $modeResult->setInfo($modeResultInfo);
            return $modeResult;
        }

        if (!is_readable($sourceFile)) {
            $modeResultInfo = 'The file exists but is not readable for imageopt process.';
            $modeResult->setInfo($modeResultInfo);
            return $modeResult;
        }

        if (isset($modeConfig['fileRegexp'])) {
            $regexp = '@' . $modeConfig['fileRegexp'] . '@';
            if (!preg_match($regexp, $sourceFile)) {
                $modeResult->setInfo('File does not match regexp: ' . $regexp . ' File: ' . $sourceFile);
                return $modeResult;
            }
        }

        $chainImagePath = $this->temporaryFile->createTemporaryCopy($sourceFileTemporary);

        foreach ($modeConfig['step'] as $stepKey => $stepConfig) {
            $stepResult = GeneralUtility::makeInstance(StepResult::class)
                ->setExecutedSuccessfully(false)
                ->setSizeBefore(filesize($chainImagePath))
                ->setSizeAfter(filesize($chainImagePath))
                ->setName($stepKey)
                ->setDescription(!empty($stepConfig['description']) ? $stepConfig['description'] : $stepKey);

            $providers = $this->configurator->getProviders(
                $stepConfig['providerType'],
                strtolower(explode('/', image_type_to_mime_type(getimagesize($sourceFile)[2]))[1])
            );
            $this->optimizeWithBestProvider($stepResult, $chainImagePath, $providers);
            $modeResult->addStepResult($stepResult);
        }
        if ($modeResult->getExecutedSuccessfullyNum() === $modeResult->getStepResults()->count()) {
            $modeResult->setExecutedSuccessfully(true);
        }

        clearstatcache(true, $chainImagePath);
        $modeResult->setSizeAfter(filesize($chainImagePath));

        $pathInfo = pathinfo($sourceFile);
        $outputFile = str_replace(
            ['{dirname}', '{basename}', '{extension}', '{filename}'],
            [$pathInfo['dirname'], $pathInfo['basename'], $pathInfo['extension'], $pathInfo['filename']],
            $modeConfig['outputFilename']
        );
        copy($chainImagePath, $outputFile);
        $modeResult->setOutputFilename($outputFile);

        return $modeResult;
    }

    /**
     * @throws Exception
     */
    protected function optimizeWithBestProvider(
        StepResult $stepResult,
        string $chainImagePath,
        array $providers
    ): void {
        clearstatcache(true, $chainImagePath);

        $providerExecutedSuccessfullyCounter = 0;
        $providerEnabledCounter = 0;

        // work on chain image copy
        $tmpBestImagePath = $this->temporaryFile->createTemporaryCopy($chainImagePath);

        foreach ($providers as $providerKey => $providerConfig) {
            $providerConfig['providerKey'] = $providerKey;
            $providerConfigurator = GeneralUtility::makeInstance(Configurator::class, $providerConfig);

            if (empty($providerConfigurator->getOption('enabled'))) {
                continue;
            }

            $providerEnabledCounter++;

            $tmpWorkingImagePath = $this->temporaryFile->createTemporaryCopy($chainImagePath);
            $optimizationProvider = GeneralUtility::makeInstance(OptimizationProvider::class);

            $providerResult = $optimizationProvider->optimize($tmpWorkingImagePath, $providerConfigurator);

            if ($providerResult->isExecutedSuccessfully()) {
                $providerExecutedSuccessfullyCounter++;
                clearstatcache(true, $tmpWorkingImagePath);

                if (filesize($tmpWorkingImagePath) < filesize($tmpBestImagePath)) {
                    // overwrite current (in chain link) best image
                    $tmpBestImagePath = $tmpWorkingImagePath;
                    $stepResult->setProviderWinnerName($providerKey);
                    $stepResult->setSizeAfter(filesize($tmpBestImagePath));
                }
            }
            $stepResult->addProvidersResult($providerResult);
        }

        if ($providerEnabledCounter === 0) {
            $stepResult->setInfo('No providers enabled (or defined).');
            $stepResult->setExecutedSuccessfully(true);
        } elseif ($providerExecutedSuccessfullyCounter === 0) {
            $stepResult->setInfo('No winner. All providers in this step were unsuccessfull.');
        } else {
            $stepResult->setExecutedSuccessfully(true);
            if ($stepResult->getOptimizationBytes() === 0) {
                $stepResult->setInfo('No winner of this step. Non of the optimized images were smaller than original.');
            } else {
                if ($stepResult->getProviderWinnerName()) {
                    $stepResult->setInfo('Winner is ' . $stepResult->getProviderWinnerName() .
                        ' with optimized image smaller by: ' .
                        round($stepResult->getOptimizationPercentage(), 2) . '%');
                }
                clearstatcache(true, $tmpBestImagePath);
                // overwrite chain image with current best image
                copy($tmpBestImagePath, $chainImagePath);
            }
        }
        clearstatcache(true, $chainImagePath);
    }
}
