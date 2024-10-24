<?php

declare(strict_types=1);

namespace SourceBroker\Imageopt\Configuration;

use SourceBroker\Imageopt\Resource\PageRepository;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ConfiguratorFactory
{
    public function __construct(
        private readonly TypoScriptService $typoScriptService,
        private readonly PageRepository $pageRepository,
    ) {}

    public function create(array $config): Configurator
    {
        return GeneralUtility::makeInstance(Configurator::class, $config, true);
    }

    public function createForPage(?int $pageUid): Configurator
    {
        return $this->create($this->getConfigForPage($pageUid));
    }

    /**
     * @throws \Exception
     */
    protected function getConfigForPage(?int $rootPageForTsConfig)
    {
        if ($rootPageForTsConfig === null) {
            $rootPageForTsConfigRow = $this->pageRepository->getRootPages();
            if ($rootPageForTsConfigRow !== null) {
                $rootPageForTsConfig = $rootPageForTsConfigRow['uid'];
            } else {
                throw new \Exception('Can not detect the root page to generate page TSconfig.', 1501700792654);
            }
        }
        $serviceConfig = $this->typoScriptService->convertTypoScriptArrayToPlainArray(
            BackendUtility::getPagesTSconfig($rootPageForTsConfig)
        );

        if (isset($serviceConfig['tx_imageopt'])) {
            return $serviceConfig['tx_imageopt'];
        }

        throw new \Exception(
            'There is no TSconfig for tx_imageopt in the root page id=' . $rootPageForTsConfig,
            1501692752398
        );
    }
}
