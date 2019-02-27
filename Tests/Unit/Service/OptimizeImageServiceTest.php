<?php

namespace SourceBroker\Imageopt\Tests\Unit\Service;

use Exception;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\OptionResult;
use SourceBroker\Imageopt\Service\OptimizeImageService;
use SourceBroker\Imageopt\Utility\ArrayUtility;
use SourceBroker\Imageopt\Utility\CliDisplayUtility;
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
use Symfony\Component\Dotenv\Dotenv;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test for OptimizeImageServiceTest
 *
 */
class OptimizeImageServiceTest extends UnitTestCase
{
    /** @var string Path to TYPO3 web root */
    private $typo3WebRoot;

    protected function setUp()
    {
        $this->typo3WebRoot = realpath(__DIR__ . '/../../../.Build/Web/');
        parent::setUp();
    }

    protected function tearDown()
    {
        foreach (glob($this->typo3WebRoot . '/typo3temp/tx_imageopt*') as $tempFile) {
            unlink($tempFile);
        }
        foreach (glob($this->typo3WebRoot . '/typo3temp/var/transient/tx_imageopt*') as $tempFile) {
            unlink($tempFile);
        }
    }

    /**
     * imageIsOptimized
     *
     * @dataProvider imageIsOptimizedDataProvider
     * @test
     * @param $image
     * @throws Exception
     */
    public function allProvidersSuccessful($image)
    {
        fwrite(STDOUT, "\n" . 'TEST if all providers was executed succesfully.' . "\n");

        /** @var \SourceBroker\Imageopt\Service\OptimizeImageService $optimizeImageService */
        $optimizeImageService = $this->getMockBuilder(OptimizeImageService::class)
            ->setConstructorArgs([$this->pluginConfig()])
            ->setMethods(null)
            ->getMock();

        $temporaryFileUtility = GeneralUtility::makeInstance(TemporaryFileUtility::class);
        $orignalImagePath = $this->typo3WebRoot . '/typo3conf/ext/imageopt/Tests/Fixture/Unit/OptimizeImageService/' . $image;
        $imageForTesting = $temporaryFileUtility->createTemporaryCopy($orignalImagePath);
        if (is_readable($imageForTesting)) {
            /** @var OptionResult[] $optimizationResults */
            $optimizationResults = $optimizeImageService->optimize($imageForTesting);

            foreach ($optimizationResults as $optimizationResult) {
                fwrite(STDOUT, CliDisplayUtility::displayOptionResult($optimizationResult, $this->pluginConfig()));
            }

            /** @var OptionResult $defaultResult */
            $defaultResult = isset($optimizationResults['default'])
                ? $optimizationResults['default']
                : reset($optimizationResults);

            $this->assertEquals(
                $defaultResult->getStepResults()->count(),
                $defaultResult->getExecutedSuccessfullyNum()
            );
        } else {
            throw new Exception('Image for testing is not existing:' . $imageForTesting);
        }
    }

    /**
     * imageIsOptimized
     * @test
     * @dataProvider imageIsOptimizedDataProvider
     * @param $image
     * @throws Exception
     * @internal param $winner
     */
    public function imageIsOptimized($image)
    {
        fwrite(STDOUT, "\n" . 'TEST has been optimized.' . "\n");

        /** @var \SourceBroker\Imageopt\Service\OptimizeImageService $optimizeImageService */
        $optimizeImageService = $this->getMockBuilder(OptimizeImageService::class)
            ->setConstructorArgs([$this->pluginConfig()])
            ->setMethods(null)
            ->getMock();

        $temporaryFileUtility = GeneralUtility::makeInstance(TemporaryFileUtility::class);
        $orignalImagePath = $this->typo3WebRoot . '/typo3conf/ext/imageopt/Tests/Fixture/Unit/OptimizeImageService/' . $image;
        $imageForTesting = $temporaryFileUtility->createTemporaryCopy($orignalImagePath);
        if (is_readable($imageForTesting)) {
            $originalFileSize = filesize($imageForTesting);
            /** @var OptionResult[] $optimizationResults */
            $optimizationResults = $optimizeImageService->optimize($imageForTesting);

            foreach ($optimizationResults as $optimizationResult) {
                fwrite(STDOUT, CliDisplayUtility::displayOptionResult($optimizationResult,$this->pluginConfig()));
            }

            $defaultOptimizationResult = isset($optimizationResults['default'])
                ? $optimizationResults['default']
                : reset($optimizationResults);

            $this->assertGreaterThan($defaultOptimizationResult->getSizeAfter(), $originalFileSize);
        } else {
            throw new Exception('Image for testing is not existing:' . $imageForTesting);
        }
    }

    /**
     * Data provider for imageIsOptimized
     *
     * @return array
     */
    public function imageIsOptimizedDataProvider()
    {
        return [
            'Test jpeg file resize' => [
                'mountains.jpg',
            ],
            'Test png file resize' => [
                'mountains.png',
            ],
            'Test gif file resize' => [
                'mountains.gif',
            ],
        ];
    }

    /**
     * Return static config for module.
     *
     * @return array
     * @throws Exception
     */
    public function pluginConfig()
    {
        $configurator = GeneralUtility::makeInstance(Configurator::class);
        $typoscriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $typoscriptParser->parse(file_get_contents(realpath(__DIR__ . '/../../../Configuration/TsConfig/Page/tx_imageopt.tsconfig')));

        $rawConfig = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Service\TypoScriptService::class)
            ->convertTypoScriptArrayToPlainArray($typoscriptParser->setup)['tx_imageopt'];
        $rawConfig['providersDefault']['enabled'] = 1;
        if (file_exists(__DIR__ . '/../../../.env')) {
            $dotenv = new Dotenv();
            $dotenv->load(__DIR__ . '/../../../.env');
        }

        $envConfig = [];
        foreach ($_ENV as $key => $value) {
            if (strpos($key, 'tx_imageopt__') === 0) {
                $key = substr($key, 13);
                $envConfig[$key] = $value;
            }
        }
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'tx_imageopt__') === 0) {
                $key = substr($key, 13);
                $envConfig[$key] = $value;
            }
        }
        foreach ($envConfig as $name => $value) {
            $plainConfig = explode('__', $name);
            $plainConfig[] = $value;
            $nestedConfig = ArrayUtility::plainToNested($plainConfig);
            \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($rawConfig, $nestedConfig, false);
        }
        $configurator->setConfig($rawConfig);
        $configurator->init();
        return $configurator->getConfig();
    }
}
