<?php

namespace SourceBroker\Imageopt\Configuration;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use Exception;
use SourceBroker\Imageopt\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Configurator
{
    protected array $config;

    protected array $providers = [];

    public function __construct(array $config, bool $init = false)
    {
        $this->config = $config;

        if ($init) {
            $this->init();
        }
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getProviders(string $providerType, string $fileType): array
    {
        $providers = !empty($this->providers[$providerType])
            ? $this->providers[$providerType]
            : [];

        return array_filter($providers, function ($provider) use ($fileType): bool {
            $providerFileTypes = explode(',', $provider['fileType']);
            return in_array($fileType, $providerFileTypes, true);
        });
    }

    /**
     * @return array|string|null
     */
    public function getOption(string $name = null, array $overwriteConfig = null)
    {
        $config = null;
        if (is_string($name)) {
            $pieces = explode('.', $name);
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

        return $config;
    }

    protected function init(): void
    {
        $this->validateConfig();
        $this->setupOptimizeModes();
        $this->setupProviders();
        $this->initializeTypedProviders();
        $this->validateProviderTypes();
    }

    protected function isConfigBranchValid(string $branch): bool
    {
        return !empty($this->config[$branch]) && is_array($this->config[$branch]);
    }

    protected function validateConfig(): void
    {
        if (count($this->config) === 0) {
            throw new Exception('Configuration not set for ImageOpt ext');
        }

        if (!$this->isConfigBranchValid('providers')) {
            throw new Exception('Providers are not defined.');
        }

        if (!$this->isConfigBranchValid('mode')) {
            throw new Exception('Optimize modes are not defined.');
        }
    }

    protected function setupOptimizeModes(): void
    {
        foreach ($this->config['mode'] as $name => &$optimizeMode) {
            if (empty($optimizeMode['name'])) {
                $optimizeMode['name'] = $name;
            }
        }
    }

    protected function setupProviders(): void
    {
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
                    $this->config['providers'][$providerKey]['executors'][$executorKey]
                        = ArrayUtility::arrayMergeAsFallback($executorValues, $this->config['executorsDefault']);
                }
            }
        }
    }

    protected function initializeTypedProviders(): void
    {
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
    }

    protected function validateProviderTypes(): void
    {
        foreach ($this->config['providers'] as $providerKey => $providerValues) {
            if (empty($providerValues['type'])) {
                throw new Exception('Provider types is not set for provider: "' . $providerKey . '"');
            }
            if (empty($providerValues['fileType'])) {
                throw new Exception('File types is not set for provider: "' . $providerKey . '"');
            }
        }
    }
}
