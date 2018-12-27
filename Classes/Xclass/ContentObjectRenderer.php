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

namespace SourceBroker\Imageopt\Xclass;

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
     * @param string |File|FileReference $file A "imgResource" TypoScript data type. Either a TypoScript file resource, a file or a file reference object or the string GIFBUILDER. See description above.
     * @param array $fileArray TypoScript properties for the imgResource type
     * @return array|NULL Returns info-array
     */
    public function getImgResource($file, $fileArray)
    {
        if (FrontendProcessingUtility::isAllowedToForceFrontendImageProcessing($file)) {
            $paramsValue = isset($fileArray['params.']) ? $this->stdWrap($fileArray['params'],
                $fileArray['params.']) : $fileArray['params'];
            unset($fileArray['params.']);
            // Make $fileArray['params'] at least one space to count that towards hash in order to make TYPO3 to process image even if it would not be processed without this.
            $fileArray['params'] = $paramsValue . ' ';
        }
        return parent::getImgResource($file, $fileArray);
    }
}
