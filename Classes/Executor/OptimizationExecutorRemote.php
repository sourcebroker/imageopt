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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class OptimizationExecutorRemote
 */
class OptimizationExecutorRemote extends OptimizationExecutorBase
{
    /**
     * @var array
     */
    protected $settings = [
        'timeout' => 30,
        'auth' => [],
        'url' => [],
        'proxy' => null
    ];

    /**
     * @param array $settings Provider settings
     */
    public function initialize($settings)
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * Executes request to remote server
     *
     * @param string|array $data Data of request
     * @param string $url Url to execute request
     * @param array $options Additional options
     * @return array
     */
    protected function request($data, $url, $options = [])
    {
        $curl = curl_init();

        if (isset($options['curl'])) {
            curl_setopt_array($curl, $options['curl']);
        }

        if (is_array($this->settings['proxy'])) {
            curl_setopt($curl, CURLOPT_PROXY, $this->settings['proxy']['host']);
            curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

            if (isset($this->settings['proxy']['port'])) {
                curl_setopt($curl, CURLOPT_PROXYPORT, $this->settings['proxy']['port']);
            }

            $creds = '';

            if (isset($this->settings['proxy']['user'])) {
                $creds .= $this->settings['proxy']['user'];
            }
            if (isset($this->settings['proxy']['pass'])) {
                $creds .= ':' . $this->settings['proxy']['pass'];
            }

            if ($creds != '') {
                curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_ANY);
                curl_setopt($curl, CURLOPT_PROXYUSERPWD, $creds);
            }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.85 Safari/537.36');
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1); //kraken?
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);//tiny?
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
        curl_setopt($curl, CURLOPT_CAINFO,
            ExtensionManagementUtility::extPath('imageopt') . 'Resources/Private/Cert/cacert.pem');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);//kraken?
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->settings['timeout']);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);//imageopt?
        $response = curl_exec($curl);

        $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $result = [
            'response' => $response,
            'http_code' => $httpCode,
            'header_size' => $headerSize,
            'error' => curl_error($curl)
        ];
        curl_close($curl);

        return $result;
    }

    /**
     * Gets the image and saves
     *
     * @param string $inputImageAbsolutePath Absolute path to target image
     * @param string $url Url of the image to download
     * @return bool Returns true if the image exists and will be saved
     */
    protected function getFileFromRemoteServer($inputImageAbsolutePath, $url)
    {
        $headers = get_headers($url);

        if (stripos($headers[0], '200 OK')) {
            file_put_contents($inputImageAbsolutePath, fopen($url, 'r'));

            return true;
        }

        return false;
    }
}
