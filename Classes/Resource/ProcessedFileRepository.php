<?php

namespace SourceBroker\Imageopt\Resource;

/*
 * This file is part of the TYPO3 CMS project
 *
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ProcessedFileRepository
 */
class ProcessedFileRepository extends \TYPO3\CMS\Core\Resource\ProcessedFileRepository
{
    /**
     * Reset optimization flag for all images
     */
    public function resetOptimizationFlag()
    {
        GeneralUtility::makeInstance($GLOBALS['TYPO3_CONF_VARS']['EXT']['EXTCONF']['imageopt']['database'])->resetOptimizationFlag();
    }

    /**
     * Get all not optimized images with $limit
     *
     * @param int $limit Number of not optimized images to return
     * @return array
     */
    public function findNotOptimizedRaw($limit = 50)
    {
        return GeneralUtility::makeInstance($GLOBALS['TYPO3_CONF_VARS']['EXT']['EXTCONF']['imageopt']['database'])->findNotOptimizedRaw($limit);
    }
}
