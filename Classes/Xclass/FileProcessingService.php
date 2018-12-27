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
use TYPO3\CMS\Core\Resource;

class FileProcessingService extends \TYPO3\CMS\Core\Resource\Service\FileProcessingService
{
    /**
     * Fluid processed images
     *
     * @param Resource\FileInterface $fileObject The file object
     * @param Resource\ResourceStorage $targetStorage The storage to store the processed file in
     * @param string $taskType
     * @param array $configuration
     *
     * @return Resource\ProcessedFile
     * @throws \InvalidArgumentException
     *
     */
    public function processFile(
        Resource\FileInterface $fileObject,
        Resource\ResourceStorage $targetStorage,
        $taskType,
        $configuration
    ) {
        if (FrontendProcessingUtility::isAllowedToForceFrontendImageProcessing($fileObject->getPublicUrl())) {
            // Make additionalParameters at least one space to count that towards hash in order to make TYPO3 to process image even if it would not be processed without this.
            $configuration['additionalParameters'] = ' ';
        }
        return parent::processFile($fileObject, $targetStorage, $taskType, $configuration);
    }
}
