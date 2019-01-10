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

interface Database
{
    /**
     * Get root pages
     *
     * @return array
     */
    public function getRootPages();

    /**
     * Reset optimization flag for all images
     */
    public function resetOptimizationFlag();

    /**
     * Get all not optimized images with $limit
     *
     * @param int $limit Number of not optimized images to return
     * @return array
     */
    public function findNotOptimizedRaw($limit = 50);
}
