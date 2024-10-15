<?php

namespace SourceBroker\Imageopt\Executor;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizationExecutorRemote extends OptimizationExecutorBase
{
    protected int $timeout = 30;

    /**
     * @var mixed
     */
    protected $proxy = [];

    /**
     * @var string[]
     */
    protected array $url = [];

    /**
     * @var string[]
     */
    protected array $auth = [];

    protected array $apiOptions = [];

    protected array $executorOptions = [];

    public function optimize(string $imageAbsolutePath, Configurator $configurator): ExecutorResult
    {
        $executorResult = GeneralUtility::makeInstance(ExecutorResult::class);
        $executorResult->setExecutedSuccessfully(false);
        if ($this->initConfiguration($configurator)) {
            $executorResult->setSizeBefore(filesize($imageAbsolutePath));
            $this->process($imageAbsolutePath, $executorResult);
            $executorResult->setSizeAfter(filesize($imageAbsolutePath));
        } else {
            $executorResult->setErrorMessage('Unable to initialize executor - check configuration');
        }
        return $executorResult;
    }

    protected function initConfiguration(Configurator $configurator): bool
    {
        $timeout = $configurator->getOption('timeout');
        if ($timeout !== null) {
            $this->timeout = (int)$timeout;
        }

        $proxy = $configurator->getOption('proxy');
        if (is_array($proxy) && !empty($proxy)) {
            $this->proxy = $proxy;
        }

        $apiUrl = $configurator->getOption('api.url');
        if (is_array($apiUrl) && !empty($apiUrl)) {
            $this->url = $apiUrl;
        } else {
            return false;
        }

        $apiAuth = $configurator->getOption('api.auth');
        if (is_array($apiAuth) && !empty($apiAuth)) {
            $this->auth = $apiAuth;
        } else {
            return false;
        }

        $options = $configurator->getOption('api.options');
        if (is_array($options) && !empty($options)) {
            $this->apiOptions = $options;
        }

        $options = $configurator->getOption('options');
        if (is_array($options) && !empty($options)) {
            $this->executorOptions = $options;
        }

        return true;
    }

    protected function process(string $inputImageAbsolutePath, ExecutorResult $executorResult): void
    {
    }

    protected function request($data, string $url, array $options = []): array
    {
        $curl = curl_init();

        if (isset($options['curl'])) {
            curl_setopt_array($curl, $options['curl']);
        }

        if (is_array($this->proxy) && count($this->proxy) > 0) {
            curl_setopt($curl, CURLOPT_PROXY, $this->proxy['host']);
            curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

            if (isset($this->proxy['port'])) {
                curl_setopt($curl, CURLOPT_PROXYPORT, $this->proxy['port']);
            }

            $creds = '';

            if (isset($this->proxy['user'])) {
                $creds .= $this->proxy['user'];
            }
            if (isset($this->proxy['pass'])) {
                $creds .= ':' . $this->proxy['pass'];
            }

            if ($creds !== '') {
                curl_setopt($curl, CURLOPT_PROXYAUTH, CURLAUTH_ANY);
                curl_setopt($curl, CURLOPT_PROXYUSERPWD, $creds);
            }
        }

        $userAgent
            = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 '
            . '(KHTML, like Gecko) Chrome/40.0.2214.85 Safari/537.36';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt(
            $curl,
            CURLOPT_USERAGENT,
            $userAgent
        );
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        //kraken?
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        //tiny?
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FAILONERROR, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);//imageopt?
        $response = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $result = [
            'response' => $response,
            'http_code' => $httpCode,
            'header_size' => $headerSize,
            'error' => curl_error($curl),
        ];
        curl_close($curl);

        return $result;
    }

    protected function handleResponseError(array $response): ?string
    {
        $result = null;

        if ($response['error']) {
            $result = 'cURL Error: ' . $response['error'];
        } else {
            switch ($response['http_code']) {
                case 401:
                    $result = 'HTTP unauthorized';
                    break;
                case 403:
                    $result = 'HTTP forbidden';
                    break;
                case 429:
                    $result = 'Limit out';
                    break;
                default:
                    if (!in_array($response['http_code'], [200, 201], true)) {
                        $result = 'HTTP code: ' . $response['http_code'];
                    } elseif (empty($response['response'])) {
                        $result = 'Empty response';
                    }
            }
        }

        return $result;
    }

    /**
     * Gets the image and saves
     */
    protected function getFileFromRemoteServer(string $inputImageAbsolutePath, string $url): bool
    {
        $headers = get_headers($url);

        if (stripos($headers[0], '200 OK')) {
            file_put_contents($inputImageAbsolutePath, fopen($url, 'b'));
            return true;
        }

        return false;
    }
}
