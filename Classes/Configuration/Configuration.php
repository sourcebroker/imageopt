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


namespace SourceBroker\Imageopt\Configuration;

use TYPO3\CMS\Core\SingletonInterface;

/**
 * Configuration Class
 */
class Configuration implements SingletonInterface
{
    /**
     * Configuration of plugin setting in Page TSconfig
     *
     * @var null|array
     */
    protected $configuration = null;

    /**
     * Default configuration
     *
     * @var array
     */
    protected static $defaultConfiguration = [
        'apikey' => '',
        'apipass' => '',
        'command' => '',
        'enabled' => 0,
        'options' => []
    ];
}