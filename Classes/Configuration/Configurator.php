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

use Exception;
use SourceBroker\Imageopt\Utility\ArrayUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
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
     * @throws Exception
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
     * @param string $providerType
     * @param string $fileType
     * @return array
     */
    public function getProviders($providerType, $fileType)
    {
        $providers = !empty($this->providers[$providerType])
            ? $this->providers[$providerType]
            : [];

        return array_filter($providers, function ($provider) use ($fileType) {
            $providerFileTypes = explode(',', $provider['fileType']);
            return in_array($fileType, $providerFileTypes);
        });
    }

    /**
     * Initialize configurator
     *
     * @throws Exception
     */
    public function init()
    {
        if ($this->config === null) {
            throw new Exception('Configuration not set for ImageOpt ext');
        }

        if (!$this->isConfigBranchValid('providers')) {
            throw new Exception('Providers are not defined.');
        }

        if (!$this->isConfigBranchValid('mode')) {
            throw new Exception('Optimize modes are not defined.');
        }

        foreach ($this->config['mode'] as $name => &$optimizeMode) {
            if (empty($optimizeMode['name'])) {
                $optimizeMode['name'] = $name;
            }
        }
        foreach ($this->config['providers'] as $providerKey => $providerValues) {
            if ($this->isConfigBranchValid('providersDefault')) {
                $this->config['providers'][$providerKey] = ArrayUtility::arrayMergeAsFallback(
                    $providerValues,
                    $this->config['providersDefault']
                );
            }
            if (!is_array($providerValues['executors'])) {
                throw new Exception('No executors defined for provider: "' . $providerKey . '""');
            }
            foreach ($providerValues['executors'] as $executorKey => $executorValues) {
                if ($this->isConfigBranchValid('executorsDefault')) {
                    $this->config['providers'][$providerKey]['executors'][$executorKey] = ArrayUtility::arrayMergeAsFallback(
                        $executorValues,
                        $this->config['executorsDefault']
                    );
                }
            }
        }

        foreach ($this->config['providers'] as $providerKey => $providerValues) {
            foreach (GeneralUtility::trimExplode(',', $providerValues['type']) as $type) {
                $providerTyped = $providerValues;
                $providerTyped['type'] = $type;
                if (isset($providerTyped['typeOverride'][$type])) {
                    \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
                        $providerTyped,
                        $providerValues['typeOverride'][$type]
                    );
                }
                unset($providerTyped['typeOverride']);
                if (!isset($this->providers[$type])) {
                    $this->providers[$type] = [];
                }
                $this->providers[$type][$providerKey] = $providerTyped;
            }
        }

        foreach ($this->config['providers'] as $providerKey => $providerValues) {
            if (empty($providerValues['type'])) {
                throw new Exception('Provider types is not set for provider: "' . $providerKey . '"');
            }
            if (empty($providerValues['fileType'])) {
                throw new Exception('File types is not set for provider: "' . $providerKey . '"');
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
     * @throws Exception
     */
    public function getConfigForPage($rootPageForTsConfig = null)
    {
        if ($rootPageForTsConfig === null) {
            $rootPageForTsConfigRow = GeneralUtility::makeInstance(\SourceBroker\Imageopt\Resource\PageRepository::class)
                ->getRootPages();
            if ($rootPageForTsConfigRow !== null) {
                $rootPageForTsConfig = $rootPageForTsConfigRow['uid'];
            } else {
                throw new Exception('Can not detect the root page to generate page TSconfig.', 1501700792654);
            }
        }
        $serviceConfig = GeneralUtility::makeInstance(TypoScriptService::class)
            ->convertTypoScriptArrayToPlainArray(BackendUtility::getPagesTSconfig($rootPageForTsConfig));
        if (isset($serviceConfig['tx_imageopt'])) {
            return $serviceConfig['tx_imageopt'];
        } else {
            throw new Exception('There is no TSconfig for tx_imageopt in the root page id=' . $rootPageForTsConfig, 1501692752398);
        }
    }

    /**
     * @param string $branch
     * @return bool
     */
    protected function isConfigBranchValid($branch)
    {
        return !empty($this->config[$branch]) && is_array($this->config[$branch]);
    }
}
