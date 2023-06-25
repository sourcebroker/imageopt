<?php

namespace SourceBroker\Imageopt\Resource;

use TYPO3\CMS\Core\Database\ConnectionPool;
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
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_file_processedfile')
            ->update(
                'sys_file_processedfile',
                ['tx_imageopt_executed_successfully' => 0],
                ['tx_imageopt_executed_successfully' => 1]
            );
    }

    /**
     * Get all not optimized images with $limit
     */
    public function findNotOptimizedRaw(int $limit, array $extensions)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_processedfile');

        $extensionsQuery = array_map(function ($extension) use ($queryBuilder) {
            return $queryBuilder->expr()->like('identifier', $queryBuilder->createNamedParameter('%.' . $extension));
        }, $extensions);

        return $queryBuilder
            ->select('*')
            ->from('sys_file_processedfile')
            ->where(
                $queryBuilder->expr()->isNotNull('name'),
                $queryBuilder->expr()->eq(
                    'tx_imageopt_executed_successfully',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->neq('task_type', $queryBuilder->createNamedParameter('Image.Preview')),
                $queryBuilder->expr()->neq('identifier', $queryBuilder->createNamedParameter(''))
            )->andWhere(
                $queryBuilder->expr()->orX(
                    ...$extensionsQuery
                )
            )->setMaxResults((int)$limit)->execute()->fetchAll();
    }
}
