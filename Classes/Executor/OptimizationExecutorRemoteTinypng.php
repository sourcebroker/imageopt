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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class OptimizationExecutorRemoteTinypng extends OptimizationExecutorRemote
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
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @return array
     */
    protected function process(string $inputImageAbsolutePath) : array
    {
        $result = self::request(file_get_contents($inputImageAbsolutePath), $this->url['upload']);

        if ($result['success']) {
            if (isset($result['response']['output']['url'])) {
                $download = $this->getFileFromRemoteServer($inputImageAbsolutePath,
                    $result['response']['output']['url']);
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
     * Request to tinypng.com using CURL
     *
     * @param string $data String from image file
     * @param string $url API tinypng.com url
     * @param array $params Additional parameters
     * @return array Result of optimization includes the response from the tinypng.com
     */
    protected function request($data, string $url, array $params = []) : array
    {
        $options = array_merge([
            'curl' => [
                CURLOPT_HEADER  => true,
                CURLOPT_USERPWD => 'api:' . $this->auth['key'],
            ],
        ], $params);

        $responseFromAPI = parent::request($data, $url, $options);

        if ($responseFromAPI['error']) {
            $result = [
                'success'       => false,
                'providerError' => 'cURL Error: ' . $responseFromAPI['error'],
            ];
        } elseif ($responseFromAPI['http_code'] === 429) {
            $this->deactivateService();

            $email = $this->getConfiguration()->getOption('limits.notification.reciver.email');
            $this->sendNotificationEmail($email, 'Your limit has been exceeded',
                'Your limit for Tinypng.com has been exceeded');

            $result = [
                'success'       => false,
                'providerError' => 'Limit out',
            ];
        } elseif ($responseFromAPI['http_code'] !== 201) {
            $result = [
                'success'       => false,
                'providerError' => 'Url HTTP code: ' . $responseFromAPI['http_code'],
            ];
        } elseif (is_string($responseFromAPI['response'])) {
            $headers = self::parseHeaders(substr($responseFromAPI['response'], 0, $responseFromAPI['header_size']));
            $body = substr($responseFromAPI['response'], $responseFromAPI['header_size']);

            $result = [
                'success'                   => true,
                'providerSubscriptionLimit' => $headers['compression-count'],
                'response'                  => json_decode($body, true),
            ];
        } else {
            $result = [
                'success'       => false,
                'providerError' => 'cURL Error: ' . $responseFromAPI['error'],
            ];
        }

        return $result;
    }

    /**
     * Function parsing headers from response
     *
     * @param string $headers Headers from response
     * @return array Array created from headers
     */
    protected static function parseHeaders(string $headers)
    {
        if (!is_array($headers)) {
            $headers = explode("\r\n", $headers);
        }
        $result = [];
        foreach ($headers as $header) {
            if (empty($header)) {
                continue;
            }
            $split = explode(':', $header, 2);
            if (count($split) === 2) {
                $result[strtolower($split[0])] = trim($split[1]);
            }
        }

        return $result;
    }
}
