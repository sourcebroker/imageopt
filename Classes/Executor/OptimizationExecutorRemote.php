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

namespace SourceBroker\Imageopt\Executor;

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class OptimizationExecutorRemote
 */
class OptimizationExecutorRemote extends OptimizationExecutorBase
{

    /**
     * @var int
     */
    protected $timeout = 30;

    /**
     * @var mixed
     */
    protected $proxy = [];

    /**
     * @var string[]
     */
    protected $url = [];

    /**
     * @var string[]
     */
    protected $auth = [];

    /**
     * @var array
     */
    protected $apiOptions = [];

    /**
     * @var array
     */
    protected $executorOptions = [];

    /**
     * Optimize image
     *
     * @param $inputImageAbsolutePath string Absolute path/file with image to be optimized
     * @param Configurator $configurator
     * @return ExecutorResult Optimization result
     */
    public function optimize(string $inputImageAbsolutePath, Configurator $configurator): ExecutorResult
    {
        $executorResult = GeneralUtility::makeInstance(ExecutorResult::class);
        $executorResult->setExecutedSuccessfully(false);

        $inited = $this->initialize($configurator);
        if ($inited) {
            $executorResult->setSizeBefore(filesize($inputImageAbsolutePath));

            $this->process($inputImageAbsolutePath, $executorResult);

            $executorResult->setSizeAfter(filesize($inputImageAbsolutePath));
        } else {
            $executorResult->setErrorMessage('Unable to initialize executor - check configuration');
        }

        return $executorResult;
    }

    /**
     * Initialize executor
     *
     * @param Configurator $configurator
     * @return bool
     */
    protected function initialize(Configurator $configurator): bool
    {
        $timeout = $configurator->getOption('timeout');
        if ($timeout !== null) {
            $this->timeout = (int)$timeout;
        }

        $proxy = $configurator->getOption('proxy');
        if (is_array($proxy) && !empty($proxy)) {
            $this->proxy = $proxy;
        }

        $apiUrl = $configurator->getOption('api.url');
        if (is_array($apiUrl) && !empty($apiUrl)) {
            $this->url = $apiUrl;
        } else {
            return false;
        }

        $apiAuth = $configurator->getOption('api.auth');
        if (is_array($apiAuth) && !empty($apiAuth)) {
            $this->auth = $apiAuth;
        } else {
            return false;
        }

        $options = $configurator->getOption('api.options');
        if (is_array($options) && !empty($options)) {
            $this->apiOptions = $options;
        }

        $options = $configurator->getOption('options');
        if (is_array($options) && !empty($options)) {
            $this->executorOptions = $options;
        }

        return true;
    }

    /**
     * Process specific executor logic
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @param ExecutorResult Optimization result
     */
    protected function process(string $inputImageAbsolutePath, ExecutorResult $executorResult)
    {
    }

    /**
     * Executes request to remote server
     *
     * @param string|array $data Data of request
     * @param string $url Url to execute request
     * @param array $options Additional options
     * @return array
     */
    protected function request($data, string $url, array $options = []): array
    {
        $curl = curl_init();

        if (isset($options['curl'])) {
            curl_setopt_array($curl, $options['curl']);
        }

        if ($this->proxy) {
            curl_setopt($curl, CURLOPT_PROXY, $this->proxy['host']);
            curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

            if (isset($this->proxy['port'])) {
                curl_setopt($curl, CURLOPT_PROXYPORT, $this->proxy['port']);
            }

            $creds = '';

            if (isset($this->proxy['user'])) {
                $creds .= $this->proxy['user'];
            }
            if (isset($this->proxy['pass'])) {
                $creds .= ':' . $this->proxy['pass'];
            }

            if ($creds != '') {
                curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_ANY);
                curl_setopt($curl, CURLOPT_PROXYUSERPWD, $creds);
            }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.85 Safari/537.36');
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1); //kraken?
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);//tiny?
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);//imageopt?
        $response = curl_exec($curl);

        $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $result = [
            'response' => $response,
            'http_code' => $httpCode,
            'header_size' => $headerSize,
            'error' => curl_error($curl),
        ];
        curl_close($curl);

        return $result;
    }

    /**
     * Handles response errors
     *
     * @param array $response
     * @return string|null
     */
    protected function handleResponseError(array $response)
    {
        $result = null;

        if ($response['error']) {
            $result = 'cURL Error: ' . $response['error'];
        } elseif ($response['http_code'] === 429) {
            $result = 'Limit out';
        } elseif (!in_array($response['http_code'], [200,201])) {
            $result = 'Url HTTP code: ' . $response['http_code'];
        } elseif (empty($response['response'])) {
            $result = 'Empty response';
        }

        return $result;
    }

    /**
     * Gets the image and saves
     *
     * @param string $inputImageAbsolutePath Absolute path to target image
     * @param string $url Url of the image to download
     * @return bool Returns true if the image exists and will be saved
     */
    protected function getFileFromRemoteServer(string $inputImageAbsolutePath, string $url): bool
    {
        $headers = get_headers($url);

        if (stripos($headers[0], '200 OK')) {
            file_put_contents($inputImageAbsolutePath, fopen($url, 'r'));
            return true;
        }

        return false;
    }
}
