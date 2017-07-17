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


/**
 * Image Provider Configuration
 */
class ImageProviderConfiguration extends PluginConfiguration
{
    /**
     * Prefix of configuration
     *
     * @var string
     */
    private $configPrefix = '';

    /**
     * Sets prefix of configuration
     *
     * @param $prefix string
     */
    public function setPrefix($prefix)
    {
        $this->configPrefix = $prefix;
    }

    /**
     * Return option from configuration of plugin
     *
     * @param string $name
     * @return string|null
     */
    public function getOption($name = '')
    {
        $name = ($name != '') ? ($this->configPrefix != '') ? $this->configPrefix . '.' . $name : $name : $this->configPrefix;

        return parent::getOption($name);
    }

    /**
     * Get prefix name
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->configPrefix;
    }
}