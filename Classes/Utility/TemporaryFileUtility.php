<?php

namespace SourceBroker\Imageopt\Utility;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use Exception;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Manage temporary files
 */
class TemporaryFileUtility implements SingletonInterface
{
    private string $tempFilePrefix = 'tx_imageopt';

    private bool $isUnlinkTempFilesRegisteredAsShutdownFunction = false;

    /**
     * Return a copy of file under a temporary filename.
     * File is deleted automatically after script end.
     */
    public function createTemporaryCopy(string $originalFileAbsolutePath): string
    {
        $tempFilename = GeneralUtility::tempnam(
            $this->tempFilePrefix,
            pathinfo($originalFileAbsolutePath, PATHINFO_EXTENSION)
        );
        if (!$this->isUnlinkTempFilesRegisteredAsShutdownFunction) {
            register_shutdown_function([$this, 'unlinkTempFiles']);
            $this->isUnlinkTempFilesRegisteredAsShutdownFunction = true;
        }
        if (file_exists($tempFilename)) {
            copy($originalFileAbsolutePath, $tempFilename);
        }
        return $tempFilename;
    }

    /**
     * Delete all temporary files of imageopt
     * @throws Exception
     */
    public function unlinkTempFiles(): void
    {
        $varPath = Environment::getVarPath();
        if (!empty($varPath)) {
            foreach (glob(Environment::getVarPath() . '/transient/' . $this->tempFilePrefix . '*') as $tempFile) {
                GeneralUtility::unlink_tempfile($tempFile);
            }
        }
    }
}
