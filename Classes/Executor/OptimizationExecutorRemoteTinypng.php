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

class OptimizationExecutorRemoteTinypng extends OptimizationExecutorRemote
{

    /**
     * Optimize image using remote Tinypng
     * Return the temporary file path
     *
     * @param string $inputImageAbsolutePath Absolute path/file with image to be optimized. It will be replaced with optimized version.
     * @param Configurator $configurator Executor configurator
     * @return ExecutorResult Executor Result
     */
    public function optimize(string $inputImageAbsolutePath, Configurator $configurator) : ExecutorResult
    {
        $executorResult = GeneralUtility::makeInstance(ExecutorResult::class);
        $executorResult->setExecutedSuccessfully(false);

        if (!empty($configurator->getOption('api.key'))) {
            $executorResult->setSizeBefore(filesize($inputImageAbsolutePath));
            $this->initialize([
                'auth' => [
                    'api_key' => $configurator->getOption('api.key'),
                ],
                'url'  => [
                    'upload' => 'https://api.tinify.com/shrink',
                ],
            ]);

            $this->upload($inputImageAbsolutePath, $configurator->getOption('options'));
            $executorResult->setSizeAfter(filesize($inputImageAbsolutePath));
            $executorResult->setExecutedSuccessfully(true);
        }

        return $executorResult;
    }

    /**
     * Upload file to tinypng.com and save it if optimization will be success
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @param array $options Additional options to optimize
     * @return array Result of optimization
     */
    public function upload($inputImageAbsolutePath, $options = [])
    {
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
    protected static function parseHeaders($headers)
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
                CURLOPT_HEADER  => true,
                CURLOPT_USERPWD => 'api:' . $this->settings['auth']['api_key'],
            ],
        ], $params);

        $responseFromAPI = parent::request($data, $url, $options);

        if (is_string($responseFromAPI['response'])) {
            $headers = self::parseHeaders(substr($responseFromAPI['response'], 0, $responseFromAPI['header_size']));
            $body = substr($responseFromAPI['response'], $responseFromAPI['header_size']);

            if ($responseFromAPI['http_code'] == 429) {
                $this->deactivateService();

                $email = $this->getConfiguration()->getOption('limits.notification.reciver.email');
                $this->sendNotificationEmail($email, 'Your limit has been exceeded',
                    'Your limit for Tinypng.com has been exceeded');

                return [
                    'success'       => false,
                    'providerError' => 'Limit out',
                ];
            } elseif ($responseFromAPI['http_code'] != 201) {
                return [
                    'success'       => false,
                    'providerError' => 'Url HTTP code: ' . $responseFromAPI['http_code'],
                ];
            }

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
}
