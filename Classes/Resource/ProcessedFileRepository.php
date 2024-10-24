<?php

namespace SourceBroker\Imageopt\Resource;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ProcessedFileRepository extends \TYPO3\CMS\Core\Resource\ProcessedFileRepository
{
    public function resetOptimizationFlag(): void
    {
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_file_processedfile')
            ->update(
                'sys_file_processedfile',
                ['tx_imageopt_executed_successfully' => 0],
                ['tx_imageopt_executed_successfully' => 1]
            );
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_file_processedfile')
            ->update(
                'sys_file_processedfile',
                ['tx_imageopt_executed' => 0],
                ['tx_imageopt_executed' => 1]
            );
    }

    public function findNotOptimizedRaw(int $limit, array $extensions)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_processedfile');

        $extensionsQuery = array_map(static function ($extension) use ($queryBuilder): string {
            return $queryBuilder->expr()->like('identifier', $queryBuilder->createNamedParameter('%.' . $extension));
        }, $extensions);

        $storages = array_map(static fn($storage) => $storage['uid'], $this->getStorages());

        return $queryBuilder
            ->select('*')
            ->from('sys_file_processedfile')
            ->where(
                $queryBuilder->expr()->isNotNull('name'),
                $queryBuilder->expr()->eq(
                    'tx_imageopt_executed',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->neq('task_type', $queryBuilder->createNamedParameter('Image.Preview')),
                $queryBuilder->expr()->neq('identifier', $queryBuilder->createNamedParameter('')),
                $queryBuilder->expr()->in('storage', $storages)
            )->andWhere(
                $queryBuilder->expr()->or(
                    ...$extensionsQuery
                )
            )->setMaxResults($limit)->executeQuery()->fetchAllAssociative();
    }

    public function findNotOptimized(int $limit, array $extensions): array
    {
        $processedFiles = [];
        foreach ($this->findNotOptimizedRaw($limit, $extensions) as $processedFileRaw) {
            $processedFiles[] = $this->createDomainObject($processedFileRaw);
        }

        return $processedFiles;
    }

    protected function getStorages(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_storage');

        $queryBuilder
            ->select('*')
            ->from('sys_file_storage');

        return $queryBuilder->executeQuery()->fetchAllAssociative();
    }
}
