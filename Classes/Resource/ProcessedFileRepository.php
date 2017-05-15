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
 * Class ProcessedFileRepository
 */
class ProcessedFileRepository extends \TYPO3\CMS\Core\Resource\ProcessedFileRepository
{
    /**
     * Count not optimized images
     *
     * @return int
     */
    public function countNotOptimized()
    {
        $row = $this->databaseConnection->exec_SELECTgetSingleRow('COUNT(*) AS not_optimized_counter ', $this->table, 'tx_imageopt_optimized=0');
        return $row['not_optimized_counter'];
    }

    /**
     * Get optimalistation level
     *
     * @return int
     */
    public function getOptimalistationLevel()
    {
        $row = $this->databaseConnection->exec_SELECTgetSingleRow('SUM(tx_imageopt_optimized_level) AS optimized_level_all', $this->table, 'tx_imageopt_optimized=1');
        return $row['optimized_level_all'];
    }

    /**
     * Get all optimized images
     *
     * @return array
     */
    public function findOptimizedRaw()
    {
        return $this->databaseConnection->exec_SELECTgetRows('*', $this->table, 'tx_imageopt_optimized=1');
    }

    /**
     * Reset optimization flag for all images
     */
    public function resetOptimizationFlag()
    {
        $this->databaseConnection->exec_UPDATEquery($this->table, 'tx_imageopt_optimized=1', ['tx_imageopt_optimized' => 0]);
    }

    /**
     * Get all not optimized images with $limit
     *
     * @param int $limit Number of not optimized images to return
     * @return array
     */
    public function findNotOptimizedRaw($limit = 50)
    {
        return $this->databaseConnection->exec_SELECTgetRows(
            '*',
            $this->table,
            // task_type == 'Image.Preview' are backend thumbnails. We do not want to optimise them.
            'name IS NOT NULL AND tx_imageopt_optimized=0 AND task_type != \'Image.Preview\' AND identifier != \'\' AND (identifier LIKE \'%.png\' OR identifier LIKE \'%.gif\' OR identifier LIKE \'%.jpg\' OR identifier LIKE \'%.jpeg\')',
            '',
            '',
            intval($limit)
        );
    }

    /**
     * Count optimized images
     *
     * @return int
     */
    public function countOptimized()
    {
        $row = $this->databaseConnection->exec_SELECTgetSingleRow('COUNT(*) AS optimized_counter ', $this->table, 'tx_imageopt_optimized=1');
        return $row['optimized_counter'];
    }

    public function getDomainObject($raw)
    {
        return $this->createDomainObject($raw);
    }

}