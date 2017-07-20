<?php

namespace SourceBroker\Imageopt\Tests\Unit\Service;

use Exception;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use SourceBroker\Imageopt\Service\OptimizeImageService;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test for OptimizeImageServiceTest
 *
 */
class OptimizeImageServiceTest extends UnitTestCase
{
    /** @var string Path to TYPO3 web root*/
    private $typo3WebRoot;

    protected function setUp()
    {
        $this->typo3WebRoot = realpath(__DIR__ . '/../../../.Build/Web/');
        $this->feedServiceGlobals();
        parent::setUp();
    }

    protected function tearDown()
    {
        foreach (glob($this->typo3WebRoot . '/typo3temp/tx_imageopt*') as $tempFile) {
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

        $imageForTesting = $this->typo3WebRoot . '/typo3conf/ext/imageopt/Tests/Fixture/Unit/OptimizeImageService/' . $image;
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

        $imageForTesting = $this->typo3WebRoot . '/typo3conf/ext/imageopt/Tests/Fixture/Unit/OptimizeImageService/' . $image;
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
        $typoscriptParser = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Service\\TypoScriptService');
        $typoscriptParser->parse(file_get_contents($this->typo3WebRoot . '/typo3conf/ext/imageopt/Configuration/TsConfig/Page/tx_imageopt.tsconfig'));
        return GeneralUtility::makeInstance(TypoScriptService::class)
            ->convertTypoScriptArrayToPlainArray($typoscriptParser->setup)['tx_imageopt'];
    }

    /*
     * Make some $GLOBALS of TYPO3 avaialble for test.
     */
    public function feedServiceGlobals()
    {
        $GLOBALS['TYPO3_CONF_VARS']['BE']['lockRootPath'] = PATH_site;
        define(TYPO3_MODE, 'BE');
        include($this->typo3WebRoot . '/typo3conf/ext/imageopt/ext_localconf.php');
    }
}
