<?php

namespace SourceBroker\Imageopt\Tests\Unit\Service;

use Exception;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use SourceBroker\Imageopt\Service\OptimizeImageService;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test for OptimizeImageServiceTest
 *
 */
class OptimizeImageServiceTest extends UnitTestCase
{
    protected function setUp()
    {
        $this->feedServiceGlobals();
        parent::setUp();
    }

    protected function tearDown()
    {
        foreach (glob(__DIR__ . '/../../../.Build/Web/typo3temp/tx_imageopt*') as $tempFile) {
            @unlink($tempFile);
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

        $imageForTesting = realpath(__DIR__ . '/../../Fixture/Unit/OptimizeImageService/' . $image);
        if (file_exists($imageForTesting)) {
            $successfulOptimization = 0;
            $results = $optimizeImageService->optimize($imageForTesting);
            foreach ($results['providerOptimizationResults'] as $result) {
                if (isset($result['success']) && $result['success']) {
                    $successfulOptimization++;
                }
            }
            $this->assertEquals($successfulOptimization, count($results['providerOptimizationResults']));
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

        $imageForTesting = realpath(__DIR__ . '/../../Fixture/Unit/OptimizeImageService/' . $image);
        $originalFileSize = filesize($imageForTesting);
        if (file_exists($imageForTesting)) {
            $results = $optimizeImageService->optimize($imageForTesting);
            $optimizedFileSize = filesize($results['providerOptimizationResults'][$results['providerOptimizationWinnerKey']]['optimizedFileAbsPath']);
            $this->assertGreaterThan($optimizedFileSize, $originalFileSize);
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
            'Test png file resize' => [
                'mountains.png',
            ],
            'Test jpeg file resize' => [
                'mountains.jpg',
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
        $typoscriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $typoscriptParser->parse(file_get_contents(realpath(__DIR__ . '/../../../Configuration/TsConfig/Page/imageopt.tsconfig')));
        return GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService')
            ->convertTypoScriptArrayToPlainArray($typoscriptParser->setup)['tx_imageopt'];
    }

    /*
     * Feed $GLOBALS['T3_SERVICES'] with info about available services.
     */
    public function feedServiceGlobals()
    {
        define(TYPO3_MODE, 'BE');
        include(__DIR__ . '/../../../ext_localconf.php');
    }
}
