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

class OptimizationExecutorRemoteImageoptim extends OptimizationExecutorRemote
{

    /**
     * Optimize image
     *
     * @param string $inputImageAbsolutePath Absolute path/file with image to be optimized
     * @param Configurator $configurator
     * @return ExecutorResult Optimization result
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
                    'upload' => $configurator->getOption('api.url.upload'),
                ],
            ]);
            $result = $this->upload($inputImageAbsolutePath, $configurator->getOption('options'));

            if ($result['success']) {
                $saved = $this->save($inputImageAbsolutePath, $result['response']);

                if ($saved) {
                    $executorResult->setSizeAfter(filesize($inputImageAbsolutePath));
                    $executorResult->setExecutedSuccessfully(true);
                } else {
                    $executorResult->setErrorMessage('Unable to save image');
                }
            } else {
                $message = isset($result['providerError'])
                    ? $result['providerError']
                    : 'Undefined error';
                $executorResult->setErrorMessage($message);
            }
        } else {
            $executorResult->setErrorMessage('Set API account data in config file');
        }

        return $executorResult;
    }

    /**
     * Upload file to imageoptim.com
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @param array $options Additional options to optimize
     * @return array Result of optimization
     */
    protected function upload(string $inputImageAbsolutePath, $options = [])
    {
        $file = curl_file_create($inputImageAbsolutePath);

        $optionsString = '';
        foreach ($options as $name => $value) {
            $optionsString .= ($optionsString ? ',' : '');
            if (is_numeric($name)) {
                $optionsString .= $value;
            } else {
                $optionsString .= $name . '=' . $value;
            }
        }

        $url[] = $this->settings['url']['upload'];
        $url[] = $this->settings['auth']['api_key'];
        $url[] = $optionsString;
        $fullUrl = implode('/', $url);

        $result = self::request(['file' => $file], $fullUrl);

        if ($result['success']) {
            if (!isset($result['response'])) {
                $result['success'] = false;
            }
        }

        return $result;
    }

    /**
     * Save image data into file
     *
     * @param string $outputImageAbsolutePath
     * @param string $imageData
     * @return bool
     */
    protected function save(string $outputImageAbsolutePath, string $imageData)
    {
        return (bool) file_put_contents($outputImageAbsolutePath, $imageData);
    }


    /**
     * Executes request to remote server
     *
     * @param array $data Array with data of file
     * @param string $url Url to execute request
     * @param array $params Additional parameters
     * @return array
     */
    protected function request($data, $url, $params = [])
    {
        $options = [
            'curl' => [],
        ];

        $responseFromAPI = parent::request($data, $url, $options);

        if ($responseFromAPI['error']) {
            $result = [
                'success'       => false,
                'providerError' => 'cURL Error: ' . $responseFromAPI['error'],
            ];
        } elseif ($responseFromAPI['http_code'] !== 200) {
            $result = [
                'success'       => false,
                'providerError' => 'Url HTTP code: ' . $responseFromAPI['http_code'],
            ];
        } else {
            $result = [
                'success'  => true,
                'response' => $responseFromAPI['response'],
            ];
        }

        return $result;
    }
}
