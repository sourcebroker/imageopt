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
        return !empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_imageopt.']['imageProcessing.']['force'])
            && (
                empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_imageopt.']['imageProcessing.']['exclusion.']['regexp'])
                || (
                    !empty($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_imageopt.']['imageProcessing.']['exclusion.']['regexp'])
                    &&
                    !preg_match(
                        $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_imageopt.']['imageProcessing.']['exclusion.']['regexp'],
                        $file
                    )
                )
            );
    }
}
