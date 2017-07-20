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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Optimize images from defined folders (for FAL images use OptimizeImagesFalService)
 * NOTE! This is not well tested yet.
 */
class OptimizeImagesFolderService
{
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

    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for ImageManipulationService class');
        }
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->optimizedFileRepository = $objectManager->get(OptimizedFileRepository::class);
        $this->configurator = $objectManager->get(Configurator::class);
        $this->configuratorGlobal = $objectManager->get(Configurator::class, $config);
    }

    /**
     * Images optimization
     *
     * @param int $numberOfImagesToProcess Limit of images on a single run
     */
    public function optimizeImages($numberOfImagesToProcess = 50)
    {
        $this->optimizeFilesInFolders($numberOfImagesToProcess);
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
}
