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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * PluginConfiguration
 */
class PluginConfiguration extends Configuration
{
    /**
     * Load customize configuration from Page TSconfig
     */
    public function __construct()
    {
        if ($this->configuration === null) {
            $configTS = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig(1);
            $typoScriptService = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService');
            $configuration = $typoScriptService->convertTypoScriptArrayToPlainArray($configTS);

            if (isset($configuration['tx_imageopt'])) {
                $this->configuration = $configuration['tx_imageopt'];
            } else {
                $this->configuration = [];
            }
        }
    }

    /**
     * Searches an array of search configuration
     *
     * @param array $context Array with configuration
     * @param string $name Name separated by dots
     * @return array|null
     */
    private function getNestedVar(&$context, $name) {
        $pieces = explode('.', $name);
        foreach ($pieces as $piece) {
            if (!is_array($context) || !array_key_exists($piece, $context)) {
                return null;
            }
            $context = &$context[$piece];
        }
        return $context;
    }

    /**
     * Return option from configuration of plugin
     *
     * @param $name
     * @return string|null
     */
    public function getOption($name) {
        return $this->getNestedVar($this->configuration, $name);
    }
}