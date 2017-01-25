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
     * @var \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * Creates this object.
     */
    public function __construct()
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
    }

    /**
     * Get all optimized images
     * 
     * @param int $limit Number of optimized images to return
     * @param int $offset
     * @return array
     */
    public function getAll($limit = 10, $offset = 0)
    {
        return $this->databaseConnection->exec_SELECTgetRows('*', 'tx_imageopt_images', '', '', '', $offset . ',' . $limit);
    }

    /**
     * Add optimization result to database
     *
     * @param $filePath string Absolute image path
     * @param $sizeBefore int Image file size before optimization
     * @param $sizeAfter int Image file size after optimization
     * @param $providerWinner string Optimization provider
     * @param bool|string $status string Optimization provider
     * @param string $providerResults
     * @return bool
     */
    public function add($filePath, $sizeBefore, $sizeAfter = null, $providerWinner = '', $status = false, $providerResults = '')
    {
        return $this->databaseConnection->exec_INSERTquery('tx_imageopt_images', array(
            'pid' => 0,
            'optimized' => $status,
            'optimization_bytes' => $sizeBefore - $sizeAfter,
            'file_size_before' => $sizeBefore,
            'file_size_after' => $sizeAfter,
            'optimization_percentage' => round(($sizeBefore - $sizeAfter) * 100 / $sizeBefore, 2),
            'provider_winner' => $providerWinner,
            'provider_results' => serialize($providerResults),
            'path' => $filePath,
            'tstamp' => $GLOBALS['EXEC_TIME'],
            'crdate' => $GLOBALS['EXEC_TIME']
        ));
    }

    /**
     * Remove entry about optimization image
     *
     * @param $filePath string Absolute image path
     * @return bool
     */
    public function remove($filePath)
    {
        return $this->databaseConnection->exec_DELETEquery('tx_imageopt_images', "path = '" . $filePath . "'");
    }
}