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


namespace SourceBroker\Imageopt\Service;

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Resource\OptimizedFileRepository;
use SourceBroker\Imageopt\Resource\ProcessedFileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Image Manipulation Service
 */
class ImageManipulationService
{
    /**
     * The FAL processed file repository
     *
     * @var ProcessedFileRepository
     */
    protected $falProcessedFileRepository;

    /**
     * Plugin configuration
     *
     * @var Configurator
     */
    public $configurator;

    /**
     * Injection of Image Manipulation Service Object
     *
     * @var OptimizedFileRepository
     */
    protected $optimizedFileRepository;

    /**
     * Allowed file extensions
     *
     * @var array
     */
    private $allowExtensions = [
        'png',
        'jpeg',
        'jpg',
        'gif'
    ];

    /**
     * Temp file Prefix
     *
     * @var string
     */
    private $tempFilePrefix = 'tx_imageopt';

    /**
     * Is registered shutdown function
     *
     * @var bool
     */
    private $isRegisteredShutdownFunction = false;

    /**
     * List of extensions that will be normalized
     *
     * @var array
     */
    private $fileExtensionNormalisation = [
        'gif' => 'gif',
        'jpeg' => 'jpg',
        'jpg' => 'jpg',
        'png' => 'png',
    ];

    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for ImageManipulationService class');
        }
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->optimizedFileRepository = $objectManager->get(OptimizedFileRepository::class);
        $this->configurator = $objectManager->get(Configurator::class);
        $this->configuratorGlobal = $objectManager->get(Configurator::class, $config);
        $this->falProcessedFileRepository = $objectManager->get(ProcessedFileRepository::class);
    }

    /**
     * Images optimization
     *
     * @param integer $numberOfImagesToProcess Limit of images on a single run
     */
    public function optimizeImages($numberOfImagesToProcess = 50)
    {
        $this->optimizeFalProcessedFiles($numberOfImagesToProcess);
        $this->optimizeFilesInFolders($numberOfImagesToProcess);
    }

    /**
     * Delete all temporary files
     *
     * @return void
     */
    public function unlinkTempFiles()
    {
        $typo3temp = PATH_site . 'typo3temp/';
        foreach (glob($typo3temp . $this->tempFilePrefix . '*') as $tempFile) {
            @unlink($tempFile);
        }
    }

    /**
     * Optimize image using chained ImageManipulationProviders
     *
     * @param $inputImageAbsolutePath
     * @return array
     */
    public function optimize($inputImageAbsolutePath)
    {
        $imageOptimizationProviderResults = [];
        $imageOptimizationProviderWinnerKey = null;

        $fileType = strtolower(pathinfo($inputImageAbsolutePath)['extension']);
        if (in_array($fileType,
                $this->allowExtensions) && file_exists($inputImageAbsolutePath) && filesize($inputImageAbsolutePath)
        ) {
            $fileType = $this->fileExtensionNormalisation[$fileType];
            $fileSizeBeforeOptimization = filesize($inputImageAbsolutePath);
            $imageManipulationProviderChain = [];
            /* @var \SourceBroker\Imageopt\Providers\ImageManipulationProvider $imageManipulationProvider */
            while (is_object($imageManipulationProvider = GeneralUtility::makeInstanceService('ImageOptimization' . ucfirst($fileType),
                '', $imageManipulationProviderChain))) {
                $providerConfig = $this->configuratorGlobal->getOption('providers.' . $imageManipulationProvider->getFileType() . '.' . $imageManipulationProvider->getName());
                if (count($providerConfig)) {
                    $this->configurator->setConfig($providerConfig);
                } else {
                    throw new \Exception('No configuration for provider found for: "providers.' . $imageManipulationProvider->getFileType() . '.' . $imageManipulationProvider->getName() . '"');
                }

                $imageManipulationProviderKey = $imageManipulationProvider->getServiceKey();
                // add to $imageManipulationProvider[] to exclude this service for next while loop
                $imageManipulationProviderChain[] = $imageManipulationProviderKey;
                if ($imageManipulationProvider->isEnabled()) {
                    $theBestOptimizedImage = $this->createTempFile();
                    $providerOptimizationResult = $imageManipulationProvider->optimize($inputImageAbsolutePath);
                    $providerOptimizationResult['providerClass'] = $imageManipulationProviderKey;
                    $providerOptimizationResult['serviceError'] = implode('; ',
                        $imageManipulationProvider->getErrorMsgArray());
                    $providerOptimizationResult['optimizedFileSize'] = filesize($providerOptimizationResult['optimizedFileAbsPath']);
                    $providerOptimizationResult['winner'] = false;

                    if ($providerOptimizationResult['success']) {
                        // if optimized image has better optimization result than previous provider then store it for final return
                        if ((filesize($theBestOptimizedImage) === 0 && $fileSizeBeforeOptimization >= filesize($providerOptimizationResult['optimizedFileAbsPath']))
                            || (filesize($providerOptimizationResult['optimizedFileAbsPath']) < filesize($theBestOptimizedImage))
                        ) {
                            rename($providerOptimizationResult['optimizedFileAbsPath'], $theBestOptimizedImage);
                            $providerOptimizationResult['optimizedFileAbsPath'] = $theBestOptimizedImage;
                            $imageOptimizationProviderWinnerKey = $imageManipulationProviderKey;
                        }
                    }
                    //collect the optimizations statuses for debug
                    ksort($providerOptimizationResult);
                    $imageOptimizationProviderResults[$imageManipulationProviderKey] = $providerOptimizationResult;
                }
                // Unset current $imageManipulationProvider to free resources. This will unset all temporary images of provider.
                unset($imageManipulationProvider);
            }
            if ($imageOptimizationProviderWinnerKey !== null) {
                $imageOptimizationProviderResults[$imageOptimizationProviderWinnerKey]['winner'] = true;
            }
        }
        return [
            'providerOptimizationResults' => $imageOptimizationProviderResults,
            'providerOptimizationWinnerKey' => $imageOptimizationProviderWinnerKey
        ];
    }

    /**
     * @param int $numberOfImagesToProcess
     */
    public function optimizeFalProcessedFiles($numberOfImagesToProcess)
    {
        $notOptimizedProcessedFilesRaw = $this->falProcessedFileRepository->findNotOptimizedRaw($numberOfImagesToProcess);
        foreach ($notOptimizedProcessedFilesRaw as $notOptimizedProcessedFileRaw) {
            $this->optimizeFalProcessedFile($notOptimizedProcessedFileRaw);
        }
    }

    /**
     * @param $notOptimizedFileRaw array $notOptimizedProcessedFileRaw,
     */
    public function optimizeFalProcessedFile($notOptimizedFileRaw)
    {
        $processedFal = $this->falProcessedFileRepository->getDomainObject($notOptimizedFileRaw);
        $sourceFile = $processedFal->getForLocalProcessing(false);
        if (file_exists($sourceFile)) {
            $fileSizeBeforeOptimization = filesize($sourceFile);
            $optimizationResults = $this->optimize($sourceFile);

            // default values for $this->optimizedFileRepository->add
            $fileSizeAfterOptimization = null;
            $fileSizeAfterOptimization = $fileSizeBeforeOptimization;
            $providerWinner = '';
            $theBestOptimizedImage = 'Not optimized.';

            // $optimizationResults['imageOptimizationProviderWinnerKey'] !== null
            // Means that at least one provider succeeded and returned file smaller than original.
            // If non of the provider returned smaller image or all provider failed then do nothing but store log.
            if ($optimizationResults['providerOptimizationWinnerKey'] !== null) {
                $theBestOptimizedImage = $optimizationResults['providerOptimizationResults'][$optimizationResults['providerOptimizationWinnerKey']]['optimizedFileAbsPath'];
                list($width, $height) = getimagesize($theBestOptimizedImage);
                if ($width > 0 && $height > 0) {
                    $fileSizeAfterOptimization = filesize($theBestOptimizedImage);
                    $processedFal->updateWithLocalFile($theBestOptimizedImage);
                    $providerWinner = $optimizationResults['providerOptimizationWinnerKey'];
                    $theBestOptimizedImage = $processedFal->getPublicUrl();
                }
            }
            // We set optimize always even if there was no real gain. Otherwise we'll try to optimize it in next loop.
            $processedFal->updateProperties([
                'tx_imageopt_optimized' => 1
            ]);
            $this->falProcessedFileRepository->update($processedFal);

            $this->optimizedFileRepository->add(
                $theBestOptimizedImage,
                $fileSizeBeforeOptimization,
                $fileSizeAfterOptimization,
                $providerWinner,
                true,
                $optimizationResults
            );
        } else {
            $processedFal->delete();
        }
    }

    /**
     * Optimize files from directories
     * List of directories sets in extension configuration
     *
     * For example fileadmin/user_upload*jpg|jpeg where fileadmin/user_upload is directory, jpg|jpeg - list of file extensions separated |
     * Directory separator is ','
     *
     * @param int $numberOfImagesToProcess
     */
    public function optimizeFilesInFolders($numberOfImagesToProcess)
    {
        $directories = explode(',', preg_replace('/\s+/', '', $this->configurator->getOption('directories')));

        $countOfFilesFound = 0;

        foreach ($directories as $directoryWithExtensions) {
            if ($directoryWithExtensions != '') {
                if (strpos($directoryWithExtensions, '*') !== false) {
                    list($directory, $stringExtensions) = explode('*', $directoryWithExtensions);

                    if (is_dir(PATH_site . $directory)) {
                        $directoryIterator = new \RecursiveDirectoryIterator(PATH_site . $directory);
                        $iterator = new \RecursiveIteratorIterator($directoryIterator);
                        $regexIterator = new \RegexIterator($iterator,
                            '/\.(' . strtolower($stringExtensions) . '|' . strtoupper($stringExtensions) . ')$/');
                        $regexIterator->setFlags(\RegexIterator::USE_KEY);

                        foreach ($regexIterator as $file) {
                            $perms = fileperms($file->getPathname());

                            //(($perms & 0x0040) ? (($perms & 0x0800) ? false : true) : false)//owner
                            //(($perms & 0x0008) ? (($perms & 0x0400) ? false : true) : false)//group
                            //(($perms & 0x0001) ? (($perms & 0x0200) ? false : true) : false)//other
                            if (!(($perms & 0x0040) ? (($perms & 0x0800) ? false : true) : false)) {
                                $fileSizeBeforeOptimization = filesize($file->getPathname());
                                $optimizationResults = $this->optimize($file->getPathname());

                                // default values for $this->optimizedFileRepository->add
                                $fileSizeAfterOptimization = null;
                                $optimizeSuccess = false;
                                $fileSizeAfterOptimization = $fileSizeBeforeOptimization;
                                $providerWinner = '';

                                if ($optimizationResults['providerOptimizationWinnerKey'] !== null) {
                                    $theBestOptimizedImage = $optimizationResults['providerOptimizationResults'][$optimizationResults['providerOptimizationWinnerKey']]['optimizedFileAbsPath'];
                                    list($width, $height) = getimagesize($theBestOptimizedImage);
                                    if ($width > 0 && $height > 0) {
                                        rename($theBestOptimizedImage, $file->getPathname());
                                        exec('chmod u+x ' . escapeshellarg($file->getPathname()), $out, $status);

                                        $providerWinner = $optimizationResults['providerOptimizationWinnerKey'];
                                        $fileSizeAfterOptimization = filesize($file->getPathname());

                                        if ($status == 0) {
                                            $countOfFilesFound++;
                                        }
                                    }
                                }

                                $this->optimizedFileRepository->add(
                                    $file->getPathname(),
                                    $fileSizeBeforeOptimization,
                                    $fileSizeAfterOptimization,
                                    $providerWinner,
                                    $optimizeSuccess,
                                    $optimizationResults
                                );
                            }

                            if ($countOfFilesFound == $numberOfImagesToProcess) {
                                break 2;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Create temporary file and register shoutdown function
     *
     * @return string $tempFile Name of temporary file
     */
    protected function createTempFile()
    {
        $tempFile = GeneralUtility::tempnam($this->tempFilePrefix);

        if (!$this->isRegisteredShutdownFunction) {
            register_shutdown_function([$this, 'unlinkTempFiles']);
            $this->isRegisteredShutdownFunction = true;
        }

        return $tempFile;
    }


    /**
     *
     */
    public function resetOptimizationFlag()
    {
        $this->falProcessedFileRepository->resetOptimizationFlag();
        // TODO - reset optmalisation flag for folder files
    }
}