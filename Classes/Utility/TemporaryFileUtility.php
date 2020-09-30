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

namespace SourceBroker\Imageopt\Utility;

use Exception;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Manage temporary files
 */
class TemporaryFileUtility implements SingletonInterface
{
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
    private $isUnlinkTempFilesRegisteredAsShutdownFunction = false;

    /**
     * Create temporary file and register shoutdown function
     *
     * @return string $tempFile Name of temporary file
     */
    public function createTempFile()
    {
        $tempFile = GeneralUtility::tempnam($this->tempFilePrefix);
        if (!$this->isUnlinkTempFilesRegisteredAsShutdownFunction) {
            register_shutdown_function([$this, 'unlinkTempFiles']);
            $this->isUnlinkTempFilesRegisteredAsShutdownFunction = true;
        }

        return $tempFile;
    }

    /**
     * Return a copy of file under a temporary filename.
     * File is deleted autmaticaly after script end.
     *
     * @param string $originalFileAbsolutePath Absolute path/file with original image
     * @return string temporary file path
     */
    public function createTemporaryCopy($originalFileAbsolutePath)
    {
        $tempFilename = $this->createTempFile();
        if (file_exists($tempFilename)) {
            copy($originalFileAbsolutePath, $tempFilename);
        }
        return $tempFilename;
    }

    /**
     * Delete all temporary files
     * @return void
     * @throws Exception
     */
    public function unlinkTempFiles()
    {
        $pathSite = Environment::getPublicPath();
        if (!empty($pathSite)) {
            $pathSite .= '/';
            foreach (glob($pathSite . 'typo3temp/' . $this->tempFilePrefix . '*') as $tempFile) {
                GeneralUtility::unlink_tempfile($tempFile);
            }
            foreach (glob($pathSite . 'typo3temp/var/transient/' . $this->tempFilePrefix . '*') as $tempFile) {
                GeneralUtility::unlink_tempfile($tempFile);
            }
        } else {
            throw new Exception('PathSite is missing');
        }
    }
}
