<?php

namespace SourceBroker\Imageopt\Executor;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class OptimizationExecutorRemoteKraken extends OptimizationExecutorRemote
{
    protected function initConfiguration(Configurator $configurator): bool
    {
        $result = parent::initConfiguration($configurator);
        if ($result) {
            if (!isset($this->auth['key'], $this->auth['pass'])) {
                $result = false;
            } elseif (!isset($this->url['upload'])) {
                $result = false;
            }
            if (!isset($this->apiOptions['quality']) && isset($this->executorOptions['quality'])) {
                $this->apiOptions['quality'] = (int)$this->executorOptions['quality']['value'];
            }
            if (isset($this->apiOptions['quality'])) {
                $this->apiOptions['quality'] = (int)$this->apiOptions['quality'];
            }
        }
        return $result;
    }

    protected function process(string $inputImageAbsolutePath, ExecutorResult $executorResult): void
    {
        $options = $this->apiOptions;
        $options['wait'] = true;
        // wait for processed file (forced option)
        $options['auth'] = [
            'api_key' => $this->auth['key'],
            'api_secret' => $this->auth['pass'],
        ];
        foreach ($options as $key => $value) {
            if ($value === 'true' || $value === 'false') {
                $options[$key] = $value === 'true';
            }
        }
        $post = [
            'file' => curl_file_create($inputImageAbsolutePath),
            'data' => json_encode($options, JSON_THROW_ON_ERROR),
        ];
        $result = $this->request($post, $this->url['upload'], ['type' => 'upload']);
        $executorResult->setCommand('URL: ' . $this->url['upload'] . " \n" . 'POST: ' . $post['data']);
        if ($result['success']) {
            if (isset($result['response']['kraked_url'])) {
                $download = $this->getFileFromRemoteServer($inputImageAbsolutePath, $result['response']['kraked_url']);
                if ($download) {
                    $executorResult->setExecutedSuccessfully(true);
                    $executorResult->setCommandStatus('Done');
                } else {
                    $executorResult->setErrorMessage('Unable to download image');
                    $executorResult->setCommandStatus('Failed');
                }
            } else {
                $executorResult->setErrorMessage('Download URL not defined');
                $executorResult->setCommandStatus('Failed');
            }
        } else {
            $executorResult->setErrorMessage($result['error']);
            $executorResult->setCommandStatus('Failed');
        }
    }

    protected function request($data, string $url, array $options = []): array
    {
        $optionsMod = [
            'curl' => [
                CURLOPT_CAINFO => ExtensionManagementUtility::extPath('imageopt') . 'Resources/Private/Cert/cacert.pem',
                CURLOPT_SSL_VERIFYPEER => 1,
            ],
        ];
        if (isset($options['type']) && $options['type'] === 'url') {
            $optionsMod['curl'][CURLOPT_HTTPHEADER] = [
                'Content-Type: application/json',
            ];
        }
        $responseFromAPI = parent::request($data, $url, $optionsMod);
        $handledResponse = $this->handleResponseError($responseFromAPI);
        if ($handledResponse !== null) {
            return [
                'success' => false,
                'error' => $handledResponse,
            ];
        }
        $response = json_decode($responseFromAPI['response'], true, 512, JSON_THROW_ON_ERROR);
        if ($response === null) {
            $result = [
                'success' => false,
                'error' => 'Unable to decode JSON',
            ];
        } elseif (!isset($response['success']) || $response['success'] === false) {
            $message = $response['message'] ?? 'Undefined error';

            $result = [
                'success' => false,
                'error' => 'API error: ' . $message,
            ];
        } else {
            $result = [
                'success' => true,
                'response' => $response,
            ];
        }
        return $result;
    }
}
