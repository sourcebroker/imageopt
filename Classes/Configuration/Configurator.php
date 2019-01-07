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
     * @var array[]
     */
    protected $providers = [];

    public function __construct(array $config = null)
    {
        $this->config = $config;
    }

    /**
     * Set configuration via direct array
     *
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Set configuration via page Id
     *
     * @param int|null $pageId
     */
    public function setConfigByPage($pageId)
    {
        $this->config = $this->getConfigForPage($pageId);
    }

    /**
     * @return array|null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns providers with given type
     *
     * @param string|null $providerType
     */
    public function getProviders(string $providerType = null)
    {
        return $this->providers[$providerType] ?? [];
    }

    /**
     * Initialize configurator
     *
     * @throws \Exception
     */
    public function init()
    {
        if ($this->config === null) {
            throw new \Exception('Configuration not set for ImageOpt ext');
        }

        $this->config = $this->mergeDefaultForProviderAndExecutor($this->config);

        if (empty($this->config['providers'])) {
            throw new \Exception('Providers not defined');
        }

        foreach ($this->config['providers'] as $name => $provider) {
            if (empty($provider['type'])) {
                throw new \Exception('Provider types is not set for provider: "' . $name . '"');
            }
            if (empty($provider['fileType'])) {
                throw new \Exception('File types is not set for provider: "' . $name . '"');
            }
        }

        $this->createVirtualProviders();
    }

    /**
     * For convinience values from tx_imageopt are merged to corensponding providers and executors defaults
     *
     * @param array $config
     * @return array
     */
    protected function mergeDefaultForProviderAndExecutor(array $config): array
    {
        $defaultProviderValues = null;

        if (isset($config['providers']['_all'])) {
            $defaultProviderValues = $config['providers']['_all'];
            unset($config['providers']['_all']);
            foreach ($config['providers'] as $providerKey => &$providerValues) {
                $allExceptExecutors = $defaultProviderValues;
                unset($allExceptExecutors['executors']);
                \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($providerValues, $allExceptExecutors);
                foreach ($providerValues['executors'] as $executorKey => &$executorValues) {
                    if (isset($defaultProviderValues['executors'])) {
                        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
                            $executorValues,
                            $defaultProviderValues['executors']);
                    }
                }
            }
        }

        return $config;
    }

    /**
     * Creates virtual providers for each optimization option
     */
    protected function createVirtualProviders()
    {
        foreach ($this->config['providers'] as $name => $provider) {
            foreach (GeneralUtility::trimExplode(',', $provider['type']) as $type) {
                $providerTyped = $provider;
                $providerTyped['type'] = $type;
                if (isset($providerTyped['typeOverride'][$type])) {
                    \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($providerTyped, $provider['typeOverride'][$type]);
                }
                unset($providerTyped['typeOverride']);
                if (!isset($this->providers[$type])) {
                    $this->providers[$type] = [];
                }
                $this->providers[$type][$name] = $providerTyped;
            }
        }
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
     * @param int|null $rootPageForTsConfig
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
                return $serviceConfig['tx_imageopt'];
            } else {
                throw new \Exception('There is no TSconfig for tx_imageopt in the root page id=' . $rootPageForTsConfig,
                    1501692752398);
            }
        } else {
            throw new \Exception('Can not detect the root page to generate page TSconfig.', 1501700792654);
        }
    }
}
