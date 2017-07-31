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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Optimize images from defined folders (for FAL images use OptimizeImagesFalService)
 */
class OptimizeImagesFolderService
{
    /**
     * @var Configurator
     */
    public $configurator;

    /**
     * @var OptimizeImageService
     */
    private $optimizeImageService;

    public function __construct($config = null)
    {
        if ($config === null) {
            throw new \Exception('Configuration not set for OptimizeImagesFolderService class');
        }
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->configurator = $objectManager->get(Configurator::class, $config);
        $this->optimizeImageService = $objectManager->get(OptimizeImageService::class, $config);
    }

    /**
     * @param $numberOfFiles
     * @return array
     */
    public function getFilesToOptimize($numberOfFiles = 20)
    {
        $filesToOptimize = [];
        $directories = explode(',', preg_replace('/\s+/', '', $this->configurator->getOption('directories')));
        foreach ($directories as $directoryWithExtensions) {
            if ($directoryWithExtensions != '') {
                if (strpos($directoryWithExtensions, '*') !== false) {
                    list($directory, $stringExtensions) = explode('*', $directoryWithExtensions);
                    if (is_dir(PATH_site . $directory)) {
                        $directoryIterator = new \RecursiveDirectoryIterator(PATH_site . $directory);
                        $iterator = new \RecursiveIteratorIterator($directoryIterator);
                        $regexIterator = new \RegexIterator(
                            $iterator,
                            '/\.(' . strtolower($stringExtensions) . '|' . strtoupper($stringExtensions) . ')$/'
                        );
                        foreach ($regexIterator as $file) {
                            $perms = fileperms($file->getPathname());
                            // Get only 6xx becase 7xx are arleady optimized.
                            if (!(($perms & 0x0040) ? (($perms & 0x0800) ? false : true) : false)) {
                                $filesToOptimize[] = $file->getPathname();
                            }
                            if (count($filesToOptimize) > $numberOfFiles) {
                                break 2;
                            }
                        }
                    }
                }
            }
        }
        return $filesToOptimize;
    }

    /**
     * @param $absoluteFilePath
     * @return array
     */
    public function optimizeFolderFile($absoluteFilePath)
    {
        $optimizationResult = $this->optimizeImageService->optimize($absoluteFilePath);
        if ($optimizationResult->isExecutedSuccessfully()) {
            // Temporary resized images are created by default with permission 644.
            // We set the "execute" bit of permission for optimized images (to have 744).
            // This way we know what files are still there to be optimized or already optimized.
            // If you have better idea how to do it then create issue on github.
            exec('chmod u+x ' . escapeshellarg($absoluteFilePath), $out, $status);
            if ($status !== 0) {
                $optimizationResult->setInfo('Error executing chmod u+x. Error code: '
                    . $status . ' Error message: ' . $out);
            }
        }
        return $optimizationResult;
    }

    public function resetOptimizationFlag()
    {
        $directories = explode(',', preg_replace('/\s+/', '', $this->configurator->getOption('directories')));
        foreach ($directories as $directoryWithExtensions) {
            if (strpos($directoryWithExtensions, '*') !== false) {
                $directory = trim(explode('*', $directoryWithExtensions)[0], '/\\');
                if (is_dir(PATH_site . $directory)) {
                    exec('find ' . PATH_site . $directory . ' -type f -exec chmod u-x {} \;');
                }
            }
        }
    }
}
