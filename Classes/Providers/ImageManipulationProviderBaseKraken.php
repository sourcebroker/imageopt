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

namespace SourceBroker\Imageopt\Providers;

/**
 * ImageManipulationProviderBaseKraken
 */
class ImageManipulationProviderBaseKraken extends ImageManipulationProviderBaseRemote implements ImageManipulationProviderBaseRemoteInterface
{
    /**
     * Provider name
     *
     * @var string
     */
    public $name = 'kraken';

    /**
     * Upload file to kraken.io and save it if optimization will be success
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @param array $options Additional options to optimize
     * @return array Result of optimization
     */
    public function upload($inputImageAbsolutePath, $options = [])
    {
        if (!file_exists($inputImageAbsolutePath)) {
            return [
                'success' => false,
                'error' => 'File `' . $inputImageAbsolutePath . '` does not exist'
            ];
        }
        if (class_exists('CURLFile')) {
            $file = new \CURLFile($inputImageAbsolutePath);
        } else {
            $file = '@' . $inputImageAbsolutePath;
        }

        foreach ($options as $key => $value) {
            if ($value == 'true' || $value == 'false') {
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
     * Request to kraken.io using CURL
     *
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
                $this->deactivateService();

                $email = $this->getConfigurator()->getOption('limits.notification.reciver.email');
                $this->sendNotificationEmail($email, 'Your limit has been exceeded',
                    'Your limit for Kraken.io has been exceeded');

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
     * @return array Optimization result
     */
    public function optimize($inputImageAbsolutePath)
    {
        $temporaryFileToBeOptimized = $this->createTemporaryCopy($inputImageAbsolutePath);

        $this->optimizationResult['success'] = false;
        $this->optimizationResult['providerName'] = $this->name;

        if ($temporaryFileToBeOptimized) {
            if ($this->getConfigurator()->getOption('apikey') != '' && $this->getConfigurator()->getOption('apipass') != '') {
                $this->initialize([
                    'auth' => [
                        'api_key' => $this->getConfigurator()->getOption('apikey'),
                        'api_secret' => $this->getConfigurator()->getOption('apipass')
                    ],
                    'url' => [
                        'upload' => 'https://api.kraken.io/v1/upload',
                        'status' => 'https://api.kraken.io/user_status'
                    ]
                ]);
                $this->optimizationResult = array_merge(
                    $this->optimizationResult,
                    $this->upload($temporaryFileToBeOptimized,
                        array_merge(['wait' => true], $this->getConfigurator()->getOption('options')))
                );
                $this->optimizationResult['optimizedFileAbsPath'] = $temporaryFileToBeOptimized;
            }
        }

        return $this->optimizationResult;
    }
}
