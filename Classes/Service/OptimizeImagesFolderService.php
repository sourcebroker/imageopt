<?php

namespace SourceBroker\Imageopt\Service;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Repository\ModeResultRepository;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Optimize images from defined folders (for FAL images use OptimizeImagesFalService)
 */
class OptimizeImagesFolderService
{
    public Configurator $configurator;

    private ModeResultRepository $modeResultRepository;

    private OptimizeImageService $optimizeImageService;

    private PersistenceManager $persistenceManager;

    public function __construct($config = null)
    {
        if ($config === null) {
            throw new Exception('Configuration not set for OptimizeImagesFolderService class');
        }
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->configurator = $objectManager->get(Configurator::class, $config);
        $this->optimizeImageService = $objectManager->get(OptimizeImageService::class, $config);
        $this->modeResultRepository = $objectManager->get(ModeResultRepository::class);
        $this->persistenceManager = $objectManager->get(PersistenceManager::class);
    }

    public function getFilesToOptimize(int $numberOfFiles = 20): array
    {
        $filesToOptimize = [];
        $directories = explode(',', preg_replace('/\s+/', '', $this->configurator->getOption('directories')));
        foreach ($directories as $directoryWithExtensions) {
            if ($directoryWithExtensions !== '') {
                if (strpos($directoryWithExtensions, '*') !== false) {
                    list($directory, $stringExtensions) = explode('*', $directoryWithExtensions);
                    if (is_dir(Environment::getPublicPath() . '/' . $directory)) {
                        $directoryIterator = new RecursiveDirectoryIterator(Environment::getPublicPath() . '/' . $directory);
                        $iterator = new RecursiveIteratorIterator($directoryIterator);
                        $regexIterator = new RegexIterator(
                            $iterator,
                            '/\.(' . strtolower($stringExtensions) . '|' . strtoupper($stringExtensions) . ')$/'
                        );
                        foreach ($regexIterator as $file) {
                            $perms = fileperms($file->getPathname());
                            // Get only 6xx because 7xx are already optimized.
                            if (!($perms & 0x0040 && (($perms & 0x0800) ? false : true))) {
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
     * @throws Exception
     */
    public function optimizeFolderFile(string $absoluteFilePath): array
    {
        $modeResults = $this->optimizeImageService->optimize($absoluteFilePath);

        foreach ($modeResults as $modeResult) {
            if ($modeResult->isExecutedSuccessfully()) {
                if ((int)$modeResult->getSizeBefore() > (int)$modeResult->getSizeAfter()) {
                    // Modes can create files with different names than original like example.jpg -> example.jpg.webp, example.jpg.avif, etc.
                    // We need to use updateWithLocalFile only for the name that match the original file name

                    // Temporary resized images are created by default with permission 644.
                    // We set the "execute" bit of permission for optimized images (to have 744).
                    // This way we know what files are still there to be optimized or already optimized.
                    // If you have better idea how to do it then create issue on github.
                    exec('chmod u+x ' . escapeshellarg($absoluteFilePath), $out, $status);
                    if ($status !== 0) {
                        $modeResult->setInfo('Error executing chmod u+x. Error code: '
                            . $status . ' Error message: ' . implode("\n", $out));
                    }
                }
            }
            if ($this->configurator->getOption('log.enable')) {
                $this->modeResultRepository->add($modeResult);
            }
        }
        $this->persistenceManager->persistAll();

        return $modeResults;
    }

    /**
     * Reset optimization "done" flag for files in folder
     */
    public function resetOptimizationFlag(): void
    {
        $directories = explode(',', preg_replace('/\s+/', '', $this->configurator->getOption('directories')));
        foreach ($directories as $directoryWithExtensions) {
            if (strpos($directoryWithExtensions, '*') !== false) {
                $directory = trim(explode('*', $directoryWithExtensions)[0], '/\\');
                if (is_dir(Environment::getPublicPath() . '/' . $directory)) {
                    exec('find ' . Environment::getPublicPath() . '/' . $directory . ' -type f -exec chmod u-x {} \;');
                }
            }
        }
    }
}
