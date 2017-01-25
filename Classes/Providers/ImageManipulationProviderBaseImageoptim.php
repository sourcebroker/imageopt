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
 * ImageManipulationProviderBaseImageoptim
 */
class ImageManipulationProviderBaseImageoptim extends ImageManipulationProviderBaseRemote implements ImageManipulationProviderBaseRemoteInterface
{
    /**
     * Provider name
     *
     * @var string
     */
    public $name = 'imageoptim';

    /**
     * Upload file to imageoptim.com and save it if optimization will be success
     *
     * @param string $inputImageAbsolutePath Absolute path/file with original image
     * @param array $options Additional options to optimize
     * @return array Result of optimization
     */
    public function upload($inputImageAbsolutePath, $options = []) {
        if (!file_exists($inputImageAbsolutePath)) {
            return [
                'success' => false,
                'error' => 'File `' . $inputImageAbsolutePath . '` does not exist'
            ];
        }

        $url[] = $this->settings['url']['upload'];
        $url[] = $this->settings['auth']['apikey'];
        $url[] = 'full';

        $result = self::request([
            'file' => curl_file_create($inputImageAbsolutePath),
        ], implode('/', $url));

        if ($result['success']) {
            if (isset($result['response'])) {
                file_put_contents($inputImageAbsolutePath, $result['response']);
            } else {
                $result['success'] = false;
            }
            unset($result['response']);
        }

        return $result;
    }

    /**
     * Executes request to remote server
     *
     * @param array $data Array with data of file
     * @param string $url Url to execute request
     * @param array $params Additional parameters
     * @return array
     */
    public function request($data, $url, $params = []) {
        $options = [
            'curl' => []
        ];

        $responseFromAPI = parent::request($data, $url, $options);

        if ($responseFromAPI['http_code'] != 200) {
            return [
                'success' => false,
                'providerError' => 'Url HTTP code: ' . $responseFromAPI['http_code']
            ];
        }

        return [
            'success' => true,
            'response' => $responseFromAPI['response']
        ];
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
                        'upload' => 'https://im2.io'
                    ]
                ]);
                $this->optimizationResult = array_merge(
                    $this->optimizationResult,
                    $this->upload($temporaryFileToBeOptimized, $this->configuration->getOption('options'))
                );
                $this->optimizationResult['optimizedFileAbsPath'] = $temporaryFileToBeOptimized;
            }
        }

        return $this->optimizationResult;
    }
}