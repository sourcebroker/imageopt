<?php

namespace SourceBroker\Imageopt\Tests\Unit\Service;

use Exception;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\OptimizationResult;
use SourceBroker\Imageopt\Service\OptimizeImageService;
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
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
        /** @var \SourceBroker\Imageopt\Service\OptimizeImageService $optimizeImageService */
        $optimizeImageService = $this->getMockBuilder(OptimizeImageService::class)
            ->setConstructorArgs([$this->pluginConfig()])
            ->setMethods(null)
            ->getMock();

        $temporaryFileUtility = GeneralUtility::makeInstance(TemporaryFileUtility::class);
        $imageForTesting = $temporaryFileUtility->createTemporaryCopy(
            $this->typo3WebRoot . '/typo3conf/ext/imageopt/Tests/Fixture/Unit/OptimizeImageService/' . $image
        );
        if (is_readable($imageForTesting)) {
            /** @var OptimizationResult $optimizationResult */
            $optimizationResult = $optimizeImageService->optimize($imageForTesting);
            $this->assertEquals(true, $optimizationResult->getExecutedSuccessfully());
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
        /** @var \SourceBroker\Imageopt\Service\OptimizeImageService $optimizeImageService */
        $optimizeImageService = $this->getMockBuilder(OptimizeImageService::class)
            ->setConstructorArgs([$this->pluginConfig()])
            ->setMethods(null)
            ->getMock();

        $temporaryFileUtility = GeneralUtility::makeInstance(TemporaryFileUtility::class);
        $imageForTesting = $temporaryFileUtility->createTemporaryCopy(
            $this->typo3WebRoot . '/typo3conf/ext/imageopt/Tests/Fixture/Unit/OptimizeImageService/' . $image
        );
        if (is_readable($imageForTesting)) {
            $originalFileSize = filesize($imageForTesting);
            /** @var OptimizationResult $optimizationResult */
            $optimizationResult = $optimizeImageService->optimize($imageForTesting, $image);
            $this->assertGreaterThan($optimizationResult->getSizeAfter(), $originalFileSize);
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
     */
    public function pluginConfig()
    {
        $configurator = GeneralUtility::makeInstance(Configurator::class);
        $typoscriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $typoscriptParser->parse(file_get_contents(realpath(__DIR__ . '/../../../Configuration/TsConfig/Page/tx_imageopt.tsconfig')));
        return $configurator->mergeDefaultForProviderAndExecutor(
            GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Service\TypoScriptService::class)
                ->convertTypoScriptArrayToPlainArray($typoscriptParser->setup)['tx_imageopt']
        );
    }
}
