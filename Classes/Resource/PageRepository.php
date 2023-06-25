<?php

namespace SourceBroker\Imageopt\Resource;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageRepository
{
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
}
