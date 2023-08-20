<?php

namespace SourceBroker\Imageopt\Xclass;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Utility\FrontendProcessingUtility;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;

class ContentObjectRenderer extends \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
{
    /**
     *  TypoScript processed images
     *
     *  5 = IMAGE
     *  5 {
     *      file = fileadmin/img.jpg
     *      file.width = 20
     *  }
     *
     * @param string|File|FileReference $file A "imgResource" TypoScript data type. Either a TypoScript file resource, a file or a file reference object or the string GIFBUILDER. See description above.
     * @param array $fileArray TypoScript properties for the imgResource type
     * @return array|null Returns info-array
     */
    public function getImgResource($file, $fileArray): ?array
    {
        if (FrontendProcessingUtility::isAllowedToForceFrontendImageProcessing($file)) {
            $paramsValue = isset($fileArray['params.']) ? $this->stdWrap(
                $fileArray['params'],
                $fileArray['params.']
            ) : $fileArray['params'];
            unset($fileArray['params.']);
            // Make $fileArray['params'] at least one space to count that towards hash in order to make TYPO3 to process image even if it would not be processed without this.
            $fileArray['params'] = $paramsValue . ' ';
        }
        return parent::getImgResource($file, $fileArray);
    }
}
