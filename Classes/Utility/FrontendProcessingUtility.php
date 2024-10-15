<?php

namespace SourceBroker\Imageopt\Utility;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;

/**
 * Manage temporary files
 */
class FrontendProcessingUtility
{
    public static function isAllowedToForceFrontendImageProcessing($file): bool
    {
        if ($file instanceof FileReference || $file instanceof File) {
            $file = $file->getPublicUrl();
        }
        $imageProcessing = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_imageopt.']['imageProcessing.'] ?? null;
        return !empty($imageProcessing['force'])
            && (
                empty($imageProcessing['exclusion.']['regexp'])
                || (
                    !empty($imageProcessing['exclusion.']['regexp'])
                    &&
                    !preg_match(
                        $imageProcessing['exclusion.']['regexp'],
                        $file
                    )
                )
            );
    }
}
