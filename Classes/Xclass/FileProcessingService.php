<?php

namespace SourceBroker\Imageopt\Xclass;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use InvalidArgumentException;
use SourceBroker\Imageopt\Utility\FrontendProcessingUtility;
use TYPO3\CMS\Core\Resource;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ResourceStorage;

class FileProcessingService extends Resource\Service\FileProcessingService
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
     * @throws InvalidArgumentException
     */
    public function processFile(
        FileInterface $fileObject,
        ResourceStorage $targetStorage,
        $taskType,
        $configuration
    ): Resource\ProcessedFile {
        if (FrontendProcessingUtility::isAllowedToForceFrontendImageProcessing($fileObject->getPublicUrl())) {
            // Make additionalParameters at least one space to count that towards hash in order to make TYPO3 to process image even if it would not be processed without this.
            $configuration['additionalParameters'] = ' ';
        }
        return parent::processFile($fileObject, $targetStorage, $taskType, $configuration);
    }
}
