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

class Database76 extends Database
{
    /**
     * @inheritdoc
     */
    public function getRootPages(): array
    {
        return $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
            'uid',
            'pages',
            'pid=0 AND deleted=0');
    }

    /**
     * @inheritdoc
     */
    public function resetOptimizationFlag(): array
    {
        $this->getDatabaseConnection()->exec_UPDATEquery(
            'sys_file_processedfile',
            'tx_imageopt_executed_successfully=1',
            ['tx_imageopt_executed_successfully' => 0]
        );
    }

    /**
     * @inheritdoc
     */
    public function findNotOptimizedRaw(int $limit = 50): array
    {
        return $this->getDatabaseConnection()->exec_SELECTgetRows(
            '*',
            'sys_file_processedfile',
            // if task_type is euqal to 'Image.Preview' then thses are backend thumbnails. We do not want to optimise them.
            'name IS NOT NULL AND tx_imageopt_executed_successfully=0 AND task_type != \'Image.Preview\' AND identifier != \'\' AND (identifier LIKE \'%.png\' OR identifier LIKE \'%.gif\' OR identifier LIKE \'%.jpg\' OR identifier LIKE \'%.jpeg\')',
            '',
            '',
            intval($limit)
        );
    }

    /**
     * @return mixed
     */
    public function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
