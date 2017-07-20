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


/**
 * Class OptimizedFileRepository
 */
class OptimizedFileRepository
{

    /**
     * @var string
     */
    protected $tableName = 'tx_imageopt_images';


    /**
     * Get all optimized images
     *
     * @param int $limit Number of optimized images to return
     * @param int $offset
     * @return array
     */
    public function getAll($limit = 10, $offset = 0)
    {
        return $this->getDatabaseConnection()->exec_SELECTgetRows('*', $this->tableName, '', '', '', $offset . ',' . $limit);
    }

    /**
     * Get all images executed less from timestamp
     * @param $timestamp
     * @return array|NULL
     */
    public function getAllExecutedFrom($timestamp)
    {
        return $this->getDatabaseConnection()->exec_SELECTgetRows('*', $this->tableName, 'tstamp >= ' . (int)$timestamp);
    }

    /**
     * Add optimization result to database
     *
     * @param $filePath string Absolute image path
     * @param $sizeBefore int Image file size before optimization
     * @param $sizeAfter int Image file size after optimization
     * @param $providerWinner string Optimization provider
     * @param string $providerResults
     * @return bool
     */
    public function add(
        $filePath,
        $sizeBefore,
        $sizeAfter = null,
        $providerWinner = '',
        $providerResults = ''
    ) {
        return $this->getDatabaseConnection()->exec_INSERTquery($this->tableName, [
            'pid' => 0,
            'optimization_bytes' => $sizeBefore - $sizeAfter,
            'file_size_before' => $sizeBefore,
            'file_size_after' => $sizeAfter,
            'optimization_percentage' => round(($sizeBefore - $sizeAfter) * 100 / $sizeBefore, 2),
            'provider_winner' => $providerWinner,
            'provider_results' => serialize($providerResults),
            'path' => $filePath,
            'tstamp' => $GLOBALS['EXEC_TIME'],
            'crdate' => $GLOBALS['EXEC_TIME']
        ]);
    }

    public function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
