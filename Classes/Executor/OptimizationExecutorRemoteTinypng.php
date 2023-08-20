<?php

namespace SourceBroker\Imageopt\Executor;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;

class OptimizationExecutorRemoteTinypng extends OptimizationExecutorRemote
{
    protected function initConfiguration(Configurator $configurator): bool
    {
        $result = parent::initConfiguration($configurator);
        if ($result) {
            if (!isset($this->auth['key'])) {
                $result = false;
            } elseif (!isset($this->url['upload'])) {
                $result = false;
            }
        }
        return $result;
    }

    /**
     * Upload file to tinypng.com and save it if optimization will be success
     */
    protected function process(string $inputImageAbsolutePath, ExecutorResult $executorResult): void
    {
        $executorResult->setCommand('URL: ' . $this->url['upload']);
        $result = $this->request(file_get_contents($inputImageAbsolutePath), $this->url['upload']);
        if ($result['success']) {
            if (isset($result['response']['output']['url'])) {
                $download = $this->getFileFromRemoteServer(
                    $inputImageAbsolutePath,
                    $result['response']['output']['url']
                );

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
        $optionsMod = array_merge([
            'curl' => [
                CURLOPT_HEADER => true,
                CURLOPT_USERPWD => 'api:' . $this->auth['key'],
            ],
        ], $options);

        $responseFromAPI = parent::request($data, $url, $optionsMod);

        $handledResponse = $this->handleResponseError($responseFromAPI);
        if ($handledResponse !== null) {
            return [
                'success' => false,
                'error' => $handledResponse,
            ];
        }

        $body = substr($responseFromAPI['response'], $responseFromAPI['header_size']);
        return [
            'success' => true,
            'response' => json_decode($body, true),
        ];
    }
}
