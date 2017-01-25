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
 * ImageManipulationProviderBaseTinypng
 */
class ImageManipulationProviderBaseTinypng extends ImageManipulationProviderBaseRemote implements ImageManipulationProviderBaseRemoteInterface
{
    /**
     * Provider name
     *
     * @var string
     */
    public $name = 'tinypng';

    /**
     * Upload file to tinypng.com and save it if optimization will be success
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @param array $options Additional options to optimize
     * @return array Result of optimization
     */
    public function upload($inputImageAbsolutePath, $options = []) {
        if (!file_exists($inputImageAbsolutePath)) {
            return array(
                'success' => false,
                'error' => 'File `' . $inputImageAbsolutePath . '` does not exist'
            );
        }

        $result = self::request(file_get_contents($inputImageAbsolutePath), $this->settings['url']['upload']);

        if ($result['success']) {
            if (isset($result['response']['output']['url'])) {
                if (!$this->getFileFromRemoteServer($inputImageAbsolutePath, $result['response']['output']['url'])) {
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
     * Function parsing headers from response
     *
     * @param string $headers Headers from response
     * @return array Array created from headers
     */
    protected static function parseHeaders($headers) {
        if (!is_array($headers)) {
            $headers = explode("\r\n", $headers);
        }
        $result = array();
        foreach ($headers as $header) {
            if (empty($header)) continue;
            $split = explode(":", $header, 2);
            if (count($split) === 2) {
                $result[strtolower($split[0])] = trim($split[1]);
            }
        }

        return $result;
    }

    /**
     * Request to tinypng.com using CURL
     *
     * @param string $data String from image file
     * @param string $url API tinypng.com url
     * @param array $params Additional parameters
     * @return array Result of optimization includes the response from the tinypng.com
     */
    public function request($data, $url, $params = [])
    {
        $options = array_merge([
            'curl' => [
                CURLOPT_HEADER => true,
                CURLOPT_USERPWD => 'api:' . $this->settings['auth']['apikey']
            ]
        ], $params);

        $responseFromAPI = parent::request($data, $url, $options);

        if (is_string($responseFromAPI['response'])) {
            $headers = self::parseHeaders(substr($responseFromAPI['response'], 0, $responseFromAPI['header_size']));
            $body = substr($responseFromAPI['response'], $responseFromAPI['header_size']);

            if ($responseFromAPI['http_code'] == 429) {
                $this->deactivateService();

                $email = $this->getConfiguration()->getOption('limits.notification.reciver.email');
                $this->sendNotificationEmail($email, 'Your limit has been exceeded', 'Your limit for Tinypng.com has been exceeded');

                return [
                    'success' => false,
                    'providerError' => 'Limit out'
                ];
            }
            else if ($responseFromAPI['http_code'] != 201) {
                return [
                    'success' => false,
                    'providerError' => 'Url HTTP code: ' . $responseFromAPI['http_code']
                ];
            }

            $result = [
                'success' => true,
                'providerSubscriptionLimit' => $headers['compression-count'],
                'response' => json_decode($body, true)
            ];
        } else {
            $result = [
                'success' => false,
                'providerError' => 'cURL Error: ' . $responseFromAPI['error']
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

        if ($temporaryFileToBeOptimized) {
            if ($this->configuration->getOption('apikey') != '') {
                $this->initialize([
                    'auth' => [
                        'apikey' => $this->configuration->getOption('apikey')
                    ],
                    'url' => [
                        'upload' => 'https://api.tinify.com/shrink'
                    ]
                ]);
                $this->optimizationResult = array_merge(
                    $this->optimizationResult,
                    $this->upload($temporaryFileToBeOptimized)
                );

                $this->optimizationResult['optimizedFileAbsPath'] = $temporaryFileToBeOptimized;
            }
        }

        return $this->optimizationResult;
    }
}