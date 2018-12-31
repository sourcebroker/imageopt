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

class OptimizationExecutorRemoteKraken extends OptimizationExecutorRemote
{


    /**
     * Upload file to kraken.io and save it if optimization will be success
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @param array $options Additional options to optimize
     * @return array Result of optimization
     */
    public function upload($inputImageAbsolutePath, $options = [])
    {
        if (class_exists('CURLFile')) {
            $file = new \CURLFile($inputImageAbsolutePath);
        } else {
            $file = '@' . $inputImageAbsolutePath;
        }
        
        if(isset($options['quality']))
        {
            $options['quality'] = (int) $options['quality']['value'];
        }

        foreach ($options as $key => $value) {
            if ($value === 'true' || $value === 'false') {
                $options[$key] = (bool)$value;
            }
        }

        $result = self::request([
                'file' => $file,
                'data' => json_encode(array_merge(['auth' => $this->settings['auth']], $options))
            ],
            $this->settings['url']['upload'],
            ['type' => 'upload']
        );
        if ($result['success']) {
            if (isset($result['response']['kraked_url'])) {
                if (!$this->getFileFromRemoteServer($inputImageAbsolutePath, $result['response']['kraked_url'])) {
                    $result['success'] = false;
                }
            } else {
                $result['success'] = false;
            }
            unset($result['response']);
        }

        return $result;
    }

    /**
     * Return the status of account in kraken.io
     *
     * @return array
     */
    public function status()
    {
        $response = self::request(json_encode($this->settings['auth']), $this->settings['url']['status'], 'url');

        return $response;
    }

    /**
     * @param array $data Array with data and file path
     * @param string $url API kraken.io url
     * @param array $params Additional parameters
     * @return array Result of optimization includes the response from the kraken.io
     */
    public function request($data, $url, $params = [])
    {
        $options = [
            'curl' => []
        ];

        if (isset($params['type']) && $params['type'] == 'url') {
            $options['curl'][CURLOPT_HTTPHEADER] = [
                'Content-Type: application/json'
            ];
        }
        $responseFromAPI = parent::request($data, $url, $options);

        $response = json_decode($responseFromAPI['response'], true);

        if ($response === null) {
            $result = [
                'success' => false,
                'providerError' => 'cURL Error: ' . $responseFromAPI['error']
            ];
        } else {
            if ($responseFromAPI['http_code'] == 429) {
                return [
                    'success' => false,
                    'providerError' => 'Limit out'
                ];
            } else {
                if ($responseFromAPI['http_code'] != 200) {
                    return [
                        'success' => false,
                        'providerError' => 'Url HTTP code: ' . $responseFromAPI['http_code']
                    ];
                }
            }

            $result = [
                'success' => (isset($response['success']) && $response['success'] === true) ? true : false,
                'response' => $response
            ];
        }

        return $result;
    }

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

        if (!empty($configurator->getOption('api.key')) && !empty($configurator->getOption('api.pass'))) {
            $executorResult->setSizeBefore(filesize($inputImageAbsolutePath));
            $this->initialize([
                'auth' => [
                    'api_key' => $configurator->getOption('api.key'),
                    'api_secret' => $configurator->getOption('api.pass')
                ],
                'url' => [
                    'upload' => 'https://api.kraken.io/v1/upload',
                    'status' => 'https://api.kraken.io/user_status'
                ]
            ]);
            $this->upload($inputImageAbsolutePath,
                array_merge(['wait' => true], $configurator->getOption('options')));
            $executorResult->setSizeAfter(filesize($inputImageAbsolutePath));
            $executorResult->setExecutedSuccessfully(true);
        }
        return $executorResult;
    }
}
