<?php

declare(strict_types=1);

namespace SourceBroker\Imageopt\Utility;

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Type\File\ImageInfo;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GraphicalFunctionsUtility extends GraphicalFunctions
{
    public function getConfigurationForImageCropScale(array $configuration)
    {
        $options = [];
        if ($configuration['maxWidth'] ?? false) {
            $options['maxW'] = $configuration['maxWidth'];
        }
        if ($configuration['maxHeight'] ?? false) {
            $options['maxH'] = $configuration['maxHeight'];
        }
        if ($configuration['minWidth'] ?? false) {
            $options['minW'] = $configuration['minWidth'];
        }
        if ($configuration['minHeight'] ?? false) {
            $options['minH'] = $configuration['minHeight'];
        }

        $options['noScale'] = $configuration['noScale'] ?? null;

        return $options;
    }

    public function getImageDimensionsWithoutExtension(string $imageFile): ?array
    {
        $returnArr = null;
        if (file_exists($imageFile)) {
            $returnArr = $this->getCachedImageDimensions($imageFile);
            if (!$returnArr) {
                $imageInfoObject = GeneralUtility::makeInstance(ImageInfo::class, $imageFile);
                if ($imageInfoObject->getWidth()) {
                    $returnArr = [
                        $imageInfoObject->getWidth(),
                        $imageInfoObject->getHeight(),
                        '',
                        $imageFile,
                    ];
                    $this->cacheImageDimensions($returnArr);
                }
            }
        }
        return $returnArr;
    }
}
