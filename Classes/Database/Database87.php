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

namespace SourceBroker\Imageopt\Database;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Database87 implements Database
{
    /**
     * @inheritdoc
     */
    public function getRootPages()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        return $queryBuilder
            ->select('uid')
            ->from('pages')
            ->where(
                $queryBuilder->expr()->eq('pid', 0),
                $queryBuilder->expr()->eq('deleted', 0)
            )->execute()->fetch();
    }

    /**
     * @inheritdoc
     */
    public function resetOptimizationFlag()
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_file_processedfile')
            ->update(
                'sys_file_processedfile',
                ['tx_imageopt_executed_successfully' => 0],
                ['tx_imageopt_executed_successfully' => 1]
            );
    }

    /**
     * @inheritdoc
     */
    public function findNotOptimizedRaw(int $limit = 50, array $extensions)
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
