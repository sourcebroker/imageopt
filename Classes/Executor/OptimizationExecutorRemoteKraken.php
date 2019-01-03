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
     * Initialize executor
     *
     * @param Configurator $configurator
     * @return bool
     */
    protected function initialize(Configurator $configurator) : bool
    {
        $result = parent::initialize($configurator);

        if ($result) {
            if (!isset($this->auth['key']) || !isset($this->auth['pass'])) {
                $result = false;
            } elseif (!isset($this->url['upload'])) {
                $result = false;
            }
        }


        return $result;
    }


    /**
     * Upload file to kraken.io and save it if optimization will be success
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @return array
     */
    protected function process(string $inputImageAbsolutePath) : array
    {
        $file = curl_file_create($inputImageAbsolutePath);

        $options = $this->options;
        $options['wait'] = true; // wait for processed file (forced option)
        $options['auth'] = $this->auth;

        if (isset($options['quality'])) {
            $options['quality'] = (int)$options['quality']['value'];
        }

        foreach ($options as $key => $value) {
            if ($value === 'true' || $value === 'false') {
                $options[$key] = (bool)$value;
            }
        }

        $post = [
            'file' => $file,
            'data' => json_encode($options),
        ];
        $result = self::request($post, $this->settings['url']['upload'], ['type' => 'upload']);

        if ($result['success']) {
            if (isset($result['response']['kraked_url'])) {
                $download = $this->getFileFromRemoteServer($inputImageAbsolutePath, $result['response']['kraked_url']);
                if (!$download) {
                    $result['success'] = false;
                    $result['providerError'] = 'Unable to download image';
                }
            } else {
                $result['success'] = false;
            }
        }

        return $result;
    }

    /**
     * @param array $data Array with data and file path
     * @param string $url API kraken.io url
     * @param array $params Additional parameters
     * @return array Result of optimization includes the response from the kraken.io
     */
    protected function request($data, string $url, array $params = []) : array
    {
        $options = [
            'curl' => [],
        ];

        if (isset($params['type']) && $params['type'] === 'url') {
            $options['curl'][CURLOPT_HTTPHEADER] = [
                'Content-Type: application/json',
            ];
        }

        $responseFromAPI = parent::request($data, $url, $options);

        if ($responseFromAPI['error']) {
            $result = [
                'success'       => false,
                'providerError' => 'cURL Error: ' . $responseFromAPI['error'],
            ];
        } elseif ($responseFromAPI['http_code'] === 429) {
            $result = [
                'success'       => false,
                'providerError' => 'Limit out',
            ];
        } elseif ($responseFromAPI['http_code'] !== 200) {
            $result = [
                'success'       => false,
                'providerError' => 'Url HTTP code: ' . $responseFromAPI['http_code'],
            ];
        } else {
            $response = json_decode($responseFromAPI['response'], true, 512);

            if ($response === null) {
                $result = [
                    'success'       => false,
                    'providerError' => 'Unable to decode JSON',
                ];
            } elseif (!isset($response['success']) || $response['success'] === false) {
                $message = isset($response['message'])
                    ? $response['message']
                    : 'Undefined';

                $result = [
                    'success'       => false,
                    'providerError' => 'API error: ' . $message,
                ];
            } else {
                $result = [
                    'success'  => true,
                    'response' => $response,
                ];
            }
        }

        return $result;
    }
}
