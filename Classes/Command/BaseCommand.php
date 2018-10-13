<?php

namespace SourceBroker\Imageopt\Command;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ExecutorResult;
use SourceBroker\Imageopt\Domain\Model\OptimizationResult;
use SourceBroker\Imageopt\Domain\Model\ProviderResult;
use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ImageoptCommandController
 */
class BaseCommand extends Command
{
    /**
     * @var object|Configurator
     */
    protected $configurator;

    /**
     * @return object|Configurator
     */
    public function getConfigurator()
    {
        return $this->configurator;
    }

    /**
     * @param object|Configurator $configurator
     */
    public function setConfigurator($configurator)
    {
        $this->configurator = $configurator;
    }

    /**
     * Init configurator wiht TSconfig settings from cli params or scheduler parameters.
     *
     * @param int $rootPageForTsConfig
     * @throws \Exception
     */
    public function initConfigurator($rootPageForTsConfig = null)
    {
        if ($rootPageForTsConfig === null) {
            $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
            $queryBuilder = $connectionPool->getQueryBuilderForTable('pages');
            $rootPageForTsConfigRow = $queryBuilder
                ->select('uid')
                ->from('pages')
                ->where(
                    $queryBuilder->expr()->eq('pid', 0),
                    $queryBuilder->expr()->eq('deleted', 0)
                )->execute()->fetch();
            if ($rootPageForTsConfigRow !== null) {
                $rootPageForTsConfig = $rootPageForTsConfigRow['uid'];
            }
        }
        if ($rootPageForTsConfig !== null) {
            $serviceConfig = GeneralUtility::makeInstance(TypoScriptService::class)
                ->convertTypoScriptArrayToPlainArray(BackendUtility::getPagesTSconfig($rootPageForTsConfig));
            if (isset($serviceConfig['tx_imageopt'])) {
                $this->configurator = GeneralUtility::makeInstance(Configurator::class);
                $this->configurator->setConfig($serviceConfig['tx_imageopt']);
            } else {
                throw new \Exception('There is no TSconfig for tx_imageopt in the root page id=' . $rootPageForTsConfig,
                    1501692752398);
            }
        } else {
            throw new \Exception('Can not detect the root page to generate page TSconfig.', 1501700792654);
        }
    }

    /**
     * @param $optimizationResult OptimizationResult object to render
     * @return string
     * @throws \Exception
     */
    public function showResult($optimizationResult)
    {
        if ($optimizationResult instanceof OptimizationResult) {
            /** @var OptimizationResult $optimizationResult */
            $providersScore = [];
            $success = $nr = 0;
            /** @var ProviderResult $providerResult */
            foreach ($optimizationResult->getProvidersResults()->toArray() as $providerResult) {
                $nr++;
                if ($providerResult->isExecutedSuccessfully()) {
                    $success++;
                    $percentage = round((
                            $providerResult->getSizeBefore() - $providerResult->getSizeAfter()) * 100
                        / $providerResult->getSizeBefore(), 2);

                    $providersScore[] = $nr . ') ' . $providerResult->getName() . ': ' . $percentage . '%';
                } else {
                    /** @var ExecutorResult $executorResult */
                    $error = [];
                    foreach ($providerResult->getExecutorsResults()->toArray() as $executorResult) {
                        if (!$executorResult->isExecutedSuccessfully()) {
                            $error[] = $executorResult->getCommandStatus();
                        }
                    }
                    $providersScore[] = $nr . ') ' . $providerResult->getName() . ' - failed - ' . implode(' ', $error);
                }
            }
            return
                '---------------------------------' . "\n" .
                "File\t\t| " . $optimizationResult->getFileRelativePath() . "\n" .
                "Info\t\t| " . implode("\n\t\t| ", explode("\n", wordwrap($optimizationResult->getInfo(), 70))) . "\n" .
                "Provider stats\t| " . $success . ' out of ' . $optimizationResult->getProvidersResults()->count() . ' providers finished successfully:' . "\n" .
                "\t\t| " . implode("\n\t\t| ", $providersScore) . "\n";
        } else {
            throw new \Exception('Result in not an object of: ' . OptimizationResult::class);
        }
    }
}
