<?php

namespace SourceBroker\Imageopt\Executor;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;

class OptimizationExecutorRemoteImageoptim extends OptimizationExecutorRemote
{
    protected function initConfiguration(Configurator $configurator): bool
    {
        if (!parent::initConfiguration($configurator)) {
            return false;
        }
        if (!isset($this->auth['key'], $this->url['upload'])) {
            return false;
        }
        if (!isset($this->apiOptions['quality']) && isset($this->executorOptions['quality'])) {
            $this->apiOptions['quality'] = $this->getExecutorQuality($configurator);
        }
        return true;
    }

    /**
     * Upload file to imageoptim.com and save it if optimization will be success
     */
    protected function process(string $inputImageAbsolutePath, ExecutorResult $executorResult): void
    {
        $optionsString = [];
        foreach ($this->apiOptions as $name => $value) {
            if (is_numeric($name)) {
                $optionsString[] = $value;
            } else {
                $optionsString[] = $name . '=' . $value;
            }
        }
        $url = implode('/', [
            $this->url['upload'],
            $this->auth['key'],
            implode(',', $optionsString),
        ]);
        $executorResult->setCommand('URL: ' . $url . " \n");
        $result = $this->request(['file' => curl_file_create($inputImageAbsolutePath)], $url);
        if ($result['success']) {
            if (isset($result['response'])) {
                if ((bool)file_put_contents($inputImageAbsolutePath, $result['response'])) {
                    $executorResult->setExecutedSuccessfully(true);
                    $executorResult->setCommandStatus('Done');
                } else {
                    $executorResult->setErrorMessage('Unable to save image');
                    $executorResult->setCommandStatus('Failed');
                }
            } else {
                $message = $result['error'] ?? 'Undefined error';
                $executorResult->setErrorMessage($message);
                $executorResult->setCommandStatus('Failed');
            }
        } else {
            $executorResult->setErrorMessage($result['error']);
            $executorResult->setCommandStatus('Failed');
        }
    }

    protected function request($data, string $url, array $options = []): array
    {
        $responseFromAPI = parent::request($data, $url, [
            'curl' => [],
        ]);
        $handledResponse = $this->handleResponseError($responseFromAPI);
        if ($handledResponse !== null) {
            $result = [
                'success' => false,
                'error' => $handledResponse,
            ];
        } else {
            $result = [
                'success' => true,
                'response' => $responseFromAPI['response'],
            ];
        }
        return $result;
    }
}
