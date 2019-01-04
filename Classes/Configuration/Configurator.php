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

use SourceBroker\Imageopt\Utility\ArrayUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Configuration Class
 */
class Configurator
{

    /**
     * Configuration of module set as array
     *
     * @var null|array
     */
    protected $config = null;

    /**
     * Configurator constructor.
     * @param array $config
     */
    public function __construct($config = null)
    {
        if ($config !== null) {
            $this->setConfig($config);
        }
    }

    /**
     * @return array|null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array|null $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Return option from configuration array with support for nested comma separated notation as "option1.suboption"
     *
     * @param string $name Configuration
     * @param array $overwriteConfig
     * @return array|null|string
     */
    public function getOption($name = null, $overwriteConfig = null)
    {
        $config = null;
        if (is_string($name)) {
            $pieces = explode('.', $name);
            if ($pieces !== false) {
                if ($overwriteConfig === null) {
                    $config = $this->config;
                } else {
                    $config = $overwriteConfig;
                }
                foreach ($pieces as $piece) {
                    if (!is_array($config) || !array_key_exists($piece, $config)) {
                        return null;
                    }
                    $config = $config[$piece];
                }
            }
        }

        return $config;
    }

    /**
     * Return config for given page.
     *
     * @param int $rootPageForTsConfig
     * @return array
     * @throws \Exception
     */
    public function getConfigForPage($rootPageForTsConfig = null)
    {
        if ($rootPageForTsConfig === null) {
            $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
            $queryBuilder = $connectionPool->getQueryBuilderForTable('pages');
            $rootPageForTsConfigRow = $queryBuilder
                ->select('uid')
                ->from('pages')
                ->where(
                    $queryBuilder->expr()->eq('pid', 0),
                    $queryBuilder->expr()->eq('deleted', 0)
                )->execute()->fetch();
            if ($rootPageForTsConfigRow !== null) {
                $rootPageForTsConfig = $rootPageForTsConfigRow['uid'];
            }
        }
        if ($rootPageForTsConfig !== null) {
            $serviceConfig = GeneralUtility::makeInstance(TypoScriptService::class)
                ->convertTypoScriptArrayToPlainArray(BackendUtility::getPagesTSconfig($rootPageForTsConfig));
            if (isset($serviceConfig['tx_imageopt'])) {
                return $this->mergeDefaultForProviderAndExecutor($serviceConfig['tx_imageopt']);
            } else {
                throw new \Exception('There is no TSconfig for tx_imageopt in the root page id=' . $rootPageForTsConfig,
                    1501692752398);
            }
        } else {
            throw new \Exception('Can not detect the root page to generate page TSconfig.', 1501700792654);
        }
    }

    /**
     * For convinience values from tx_imageopt are merged to corensponding providers and executors defaults
     *
     * @param array $config
     * @return array
     */
    public function mergeDefaultForProviderAndExecutor(array $config) : array
    {
        foreach ($config['providers'] as $extension => $providersForExtension) {
            foreach ($providersForExtension as $providerKey => $providerValues) {
                if (is_array($config['default']['providers']['_all'])) {
                    $allExceptExecutors = $config['default']['providers']['_all'];
                    unset($allExceptExecutors['executors']);
                    $providerValues = ArrayUtility::mergeRecursiveDistinct($allExceptExecutors, $providerValues);
                }
                if (is_array($config['default']['providers'][$providerKey])) {
                    $allExceptExecutors = $config['default']['providers'][$providerKey];
                    unset($allExceptExecutors['executors']);
                    $providerValues = ArrayUtility::mergeRecursiveDistinct($allExceptExecutors, $providerValues);
                }
                foreach ($providerValues['executors'] as $executorKey => $executorValues) {
                    if (is_array($config['default']['providers'][$providerKey]['executors'])) {
                        $defaultValues = $config['default']['providers'][$providerKey]['executors'];
                        $executorValues = ArrayUtility::mergeRecursiveDistinct($defaultValues, $executorValues);
                    }
                    if (is_array($config['default']['providers']['_all']['executors'])) {
                        $defaultValues = $config['default']['providers']['_all']['executors'];
                        $executorValues = ArrayUtility::mergeRecursiveDistinct($defaultValues, $executorValues);
                    }
                    $providerValues['executors'][$executorKey] = $executorValues;
                }
                $config['providers'][$extension][$providerKey] = $providerValues;
            }
        }
        return $config;
    }
}
