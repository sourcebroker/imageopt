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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ProcessedFileRepository
 */
class ProcessedFileRepository extends \TYPO3\CMS\Core\Resource\ProcessedFileRepository
{
    /**
     * @var ConnectionPool
     */
    protected $connection;

    /**
     * Reset optimization flag for all images
     */
    public function resetOptimizationFlag()
    {
        $this->getDatabaseConnection()
            ->getConnectionForTable($this->table)
            ->update(
                $this->table,
                ['tx_imageopt_executed_successfully' => 0],
                ['tx_imageopt_executed_successfully' => 1]
            );
    }

    /**
     * Get all not optimized images with $limit
     *
     * @param int $limit Number of not optimized images to return
     * @return array
     */
    public function findNotOptimizedRaw($limit = 50)
    {
        $queryBuilder = $this->getDatabaseConnection()->getQueryBuilderForTable($this->table);

        return $queryBuilder
            ->select('*')
            ->from($this->table)
            ->where(
                $queryBuilder->expr()->isNotNull('name'),
                $queryBuilder->expr()->eq('tx_imageopt_executed_successfully',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                $queryBuilder->expr()->neq('task_type', $queryBuilder->createNamedParameter('Image.Preview')),
                $queryBuilder->expr()->neq('identifier', $queryBuilder->createNamedParameter(''))
            )->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('identifier', $queryBuilder->createNamedParameter('%.png')),
                    $queryBuilder->expr()->like('identifier', $queryBuilder->createNamedParameter('%.jpg')),
                    $queryBuilder->expr()->like('identifier', $queryBuilder->createNamedParameter('%.jpeg')),
                    $queryBuilder->expr()->like('identifier', $queryBuilder->createNamedParameter('%.gif'))
                )
            )->setMaxResults((int)$limit)->execute()->fetchAll();
    }

    /**
     * @return ConnectionPool
     */
    public function getDatabaseConnection()
    {
        if (!$this->connection) {
            $this->connection = GeneralUtility::makeInstance(ConnectionPool::class);
        }

        return $this->connection;
    }
}
