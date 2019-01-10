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

class OptimizationExecutorRemoteImageoptim extends OptimizationExecutorRemote
{

    /**
     * Initialize executor
     *
     * @param Configurator $configurator
     * @return bool
     */
    protected function initConfiguration(Configurator $configurator)
    {
        if (!parent::initConfiguration($configurator)) {
            return false;
        }
        if (!isset($this->auth['key']) || !isset($this->url['upload'])) {
            return false;
        }
        if (!isset($this->apiOptions['quality']) && isset($this->executorOptions['quality'])) {
            $this->apiOptions['quality'] = $this->getExecutorQuality($configurator);
        }
        return true;
    }

    /**
     * Upload file to imageoptim.com and save it if optimization will be success
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @param ExecutorResult $executorResult
     */
    protected function process($inputImageAbsolutePath, ExecutorResult $executorResult)
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
            implode(',', $optionsString)
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

    /**
     * Executes request to remote server
     *
     * @param array $data Array with data of file
     * @param string $url Url to execute request
     * @param array $params Additional parameters
     * @return array
     */
    protected function request($data, $url, array $params = [])
    {
        $responseFromAPI = parent::request($data, $url, [
            'curl' => [],
        ]);
        $handledResponse = $this->handleResponseError($responseFromAPI);
        $result = null;
        if ($handledResponse !== null) {
            $result = [
                'success' => false,
                'error' => $handledResponse
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
