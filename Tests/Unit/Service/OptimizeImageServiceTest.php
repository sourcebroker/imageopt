<?php

namespace SourceBroker\Imageopt\Tests\Unit\Service;

use Exception;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use SourceBroker\Imageopt\Service\OptimizeImageService;

/**
 * Test for OptimizeImageServiceTest
 *
 */
class OptimizeImageServiceTest extends UnitTestCase
{
    /**
     * imageIsOptimized
     *
     * @dataProvider imageIsOptimizedDataProvider
     * @test
     * @param $image
     * @throws Exception
     * @internal param $winner
     */
    public function imageIsOptimized($image)
    {
        /** @var \SourceBroker\Imageopt\Service\OptimizeImageService $optimizeImageService */
        $optimizeImageService = $this->getMockBuilder(OptimizeImageService::class)
            ->setConstructorArgs(array($this->staticTsConfig()))
            ->setMethods(null)
            ->getMock();

        $this->feedServiceGlobals();

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
    public function staticTsConfig()
    {
        return [
            'directories' => '',
            'default' =>
                [
                    'limits' =>
                        [
                            'notification' =>
                                [
                                    'disable' => '0',
                                    'sender' =>
                                        [
                                            'email' => '',
                                            'name' => '',
                                        ],
                                    'reciver' =>
                                        [
                                            'email' => '',
                                            'name' => '',
                                        ],
                                ],
                        ],
                    'options' =>
                        [
                            'quality' => '85',
                        ],
                    'providers' =>
                        [
                            'kraken' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                    'options' =>
                                        [
                                            'lossy' => 'true',
                                        ],
                                ],
                            'tinypng' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                ],
                            'imageoptim' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                    'options' =>
                                        [
                                            'lossy' => 'true',
                                        ],
                                ],
                        ],
                ],
            'providers' =>
                [
                    'jpeg' =>
                        [
                            'kraken' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                    'options' =>
                                        [
                                            'lossy' => 'true',
                                        ],
                                ],
                            'tinypng' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                ],
                            'imageoptim' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                    'options' =>
                                        [
                                            'lossy' => 'true',
                                        ],
                                ],
                            'jpegoptim' =>
                                [
                                    'command' => '{executable} {tempFile} -o --strip-all {quality}',
                                    'enabled' => '1',
                                    'options' =>
                                        [
                                            'quality' => '85',
                                            'qualityOptions' =>
                                                [
                                                    5 => '--max=5',
                                                    10 => '--max=10',
                                                    15 => '--max=15',
                                                    20 => '--max=20',
                                                    25 => '--max=25',
                                                    30 => '--max=30',
                                                    35 => '--max=35',
                                                    40 => '--max=40',
                                                    45 => '--max=45',
                                                    50 => '--max=50',
                                                    55 => '--max=55',
                                                    60 => '--max=60',
                                                    65 => '--max=65',
                                                    70 => '--max=70',
                                                    75 => '--max=75',
                                                    80 => '--max=80',
                                                    85 => '--max=85',
                                                    90 => '--max=90',
                                                    95 => '--max=95',
                                                    100 => '--max=100',
                                                ],
                                        ],
                                ],
                            'jpegrescan' =>
                                [
                                    'command' => '{executable} -s {tempFile} {tempFile}',
                                    'enabled' => '1',
                                ],
                            'jpegtran' =>
                                [
                                    'command' => '{executable} -copy none -optimize -progressive -outfile {tempFile} {tempFile}',
                                    'enabled' => '1',
                                ],
                            'mozjpg' =>
                                [
                                    'command' => '{executable} -copy none {tempFile} > {tempFile}',
                                    'enabled' => '1',
                                ],
                        ],
                    'gif' =>
                        [
                            'kraken' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                    'options' =>
                                        [
                                            'lossy' => 'true',
                                        ],
                                ],
                            'tinypng' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                ],
                            'imageoptim' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                    'options' =>
                                        [
                                            'lossy' => 'true',
                                        ],
                                ],
                            'gifsicle' =>
                                [
                                    'command' => '{executable} --batch {quality} {tempFile}',
                                    'enabled' => '1',
                                    'options' =>
                                        [
                                            'quality' => '85',
                                            'qualityOptions' =>
                                                [
                                                    5 => '--optimize=3',
                                                    10 => '--optimize=3',
                                                    15 => '--optimize=3',
                                                    20 => '--optimize=3',
                                                    25 => '--optimize=3',
                                                    30 => '--optimize=3',
                                                    35 => '--optimize=3',
                                                    40 => '--optimize=2',
                                                    45 => '--optimize=2',
                                                    50 => '--optimize=2',
                                                    55 => '--optimize=2',
                                                    60 => '--optimize=2',
                                                    65 => '--optimize=2',
                                                    70 => '--optimize=2',
                                                    75 => '--optimize=1',
                                                    80 => '--optimize=1',
                                                    85 => '--optimize=1',
                                                    90 => '--optimize=1',
                                                    95 => '--optimize=1',
                                                    100 => '--optimize=1',
                                                ],
                                        ],
                                ],
                        ],
                    'png' =>
                        [
                            'kraken' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                    'options' =>
                                        [
                                            'lossy' => 'true',
                                        ],
                                ],
                            'tinypng' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                ],
                            'imageoptim' =>
                                [
                                    'apikey' => '',
                                    'apipass' => '',
                                    'enabled' => '0',
                                    'limits' =>
                                        [
                                            'notification' =>
                                                [
                                                    'disable' => '0',
                                                    'sender' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                    'reciver' =>
                                                        [
                                                            'email' => '',
                                                            'name' => '',
                                                        ],
                                                ],
                                        ],
                                    'options' =>
                                        [
                                            'lossy' => 'true',
                                        ],
                                ],
                            'optipng' =>
                                [
                                    'command' => '{executable} {tempFile} -quiet -strip all {quality}',
                                    'enabled' => '1',
                                    'options' =>
                                        [
                                            'quality' => '85',
                                            'qualityOptions' =>
                                                [
                                                    5 => '-o7',
                                                    10 => '-o7',
                                                    15 => '-o7',
                                                    20 => '-o6',
                                                    25 => '-o6',
                                                    30 => '-o6',
                                                    35 => '-o5',
                                                    40 => '-o5',
                                                    45 => '-o5',
                                                    50 => '-o4',
                                                    55 => '-o4',
                                                    60 => '-o4',
                                                    65 => '-o3',
                                                    70 => '-o3',
                                                    75 => '-o3',
                                                    80 => '-o2',
                                                    85 => '-o2',
                                                    90 => '-o2',
                                                    95 => '-o1',
                                                    100 => '-o0',
                                                ],
                                        ],
                                ],
                            'pngcrush' =>
                                [
                                    'command' => '{executable} -q -rem alla -brute -reduce -ow {tempFile}',
                                    'enabled' => '1',
                                ],
                            'pngquant' =>
                                [
                                    'command' => '{executable} {tempFile} --skip-if-larger --force --strip --ext \'\' {quality}',
                                    'enabled' => '1',
                                    'options' =>
                                        [
                                            'quality' => '85',
                                            'qualityOptions' =>
                                                [
                                                    5 => '--speed 11',
                                                    10 => '--speed 10',
                                                    15 => '--speed 10',
                                                    20 => '--speed 9',
                                                    25 => '--speed 9',
                                                    30 => '--speed 8',
                                                    35 => '--speed 8',
                                                    40 => '--speed 7',
                                                    45 => '--speed 7',
                                                    50 => '--speed 6',
                                                    55 => '--speed 6',
                                                    60 => '--speed 5',
                                                    65 => '--speed 5',
                                                    70 => '--speed 4',
                                                    75 => '--speed 3',
                                                    80 => '--speed 3',
                                                    85 => '--speed=2',
                                                    90 => '--speed=2',
                                                    95 => '--speed 1',
                                                    100 => '--speed 1',
                                                ],
                                        ],
                                ],
                        ],
                ]
        ];

    }

    /*
     * Feed $GLOBALS['T3_SERVICES'] with info about available services.
     */
    public function feedServiceGlobals()
    {
        $GLOBALS['T3_SERVICES'] = [
            'ImageOptimizationGif' =>
                [
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifGifsicle' =>
                        [
                            'title' => 'Optimize gif image with command line executable "gifsicle"',
                            'description' => 'Optimize gif image with command line executable "gifsicle" so it will take less space.',
                            'available' => true,
                            'priority' => 90,
                            'quality' => 80,
                            'os' => '',
                            'exec' => 'gifsicle',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifGifsicle',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifGifsicle',
                            'serviceType' => 'ImageOptimizationGif',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifTinypng' =>
                        [
                            'title' => 'Optimize gif image with tinypng.com',
                            'description' => 'Optimize gif image with tinypng.com so it will take less space.',
                            'available' => true,
                            'priority' => 80,
                            'quality' => 80,
                            'os' => '',
                            'exec' => '',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifTinypng',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifTinypng',
                            'serviceType' => 'ImageOptimizationGif',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifKraken' =>
                        [
                            'title' => 'Optimize gif image with Kraken.io',
                            'description' => 'Optimize gif image with Kraken.io so it will take less space.',
                            'available' => true,
                            'priority' => 70,
                            'quality' => 80,
                            'os' => '',
                            'exec' => '',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifKraken',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifKraken',
                            'serviceType' => 'ImageOptimizationGif',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifImageoptim' =>
                        [
                            'title' => 'Optimize png image with Imageoptim.com',
                            'description' => 'Optimize png image with Imageoptim.com so it will take less space.',
                            'available' => true,
                            'priority' => 60,
                            'quality' => 80,
                            'os' => '',
                            'exec' => '',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifImageoptim',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderGifImageoptim',
                            'serviceType' => 'ImageOptimizationGif',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                ],
            'ImageOptimizationJpeg' =>
                [
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegJpegoptim' =>
                        [
                            'title' => 'Optimize jpeg image with command line executable "jpegoptim"',
                            'description' => 'Optimize jpeg image with command line executable "jpegoptim" so it will take less space.',
                            'available' => true,
                            'priority' => 90,
                            'quality' => 80,
                            'os' => '',
                            'exec' => 'jpegoptim',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegJpegoptim',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegJpegoptim',
                            'serviceType' => 'ImageOptimizationJpeg',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegJpegrescan' =>
                        [
                            'title' => 'Optimize jpeg image with command line executable "jpegrescan"',
                            'description' => 'Optimize jpeg image with command line executable "jpegrescan" so it will take less space.',
                            'available' => true,
                            'priority' => 90,
                            'quality' => 80,
                            'os' => '',
                            'exec' => 'jpegrescan',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegJpegrescan',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegJpegrescan',
                            'serviceType' => 'ImageOptimizationJpeg',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegJpegtran' =>
                        [
                            'title' => 'Optimize jpeg image with command line executable "jpegtran"',
                            'jpegtran i ondescription' => 'Optimize jpeg image with command line executable "jpegtran" so it will take less space.',
                            'available' => true,
                            'priority' => 90,
                            'quality' => 80,
                            'os' => '',
                            'exec' => 'jpegtran',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegJpegtran',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegJpegtran',
                            'serviceType' => 'ImageOptimizationJpeg',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegTinypng' =>
                        [
                            'title' => 'Optimize jpeg image with tinypng.com',
                            'description' => 'Optimize jpeg image with tinypng.com so it will take less space.',
                            'available' => true,
                            'priority' => 100,
                            'quality' => 80,
                            'os' => '',
                            'exec' => '',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegTinypng',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegTinypng',
                            'serviceType' => 'ImageOptimizationJpeg',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegKraken' =>
                        [
                            'title' => 'Optimize jpeg image with Kraken.io',
                            'description' => 'Optimize jpeg image with Kraken.io so it will take less space.',
                            'available' => true,
                            'priority' => 70,
                            'quality' => 80,
                            'os' => '',
                            'exec' => '',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegKraken',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegKraken',
                            'serviceType' => 'ImageOptimizationJpeg',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegImageoptim' =>
                        [
                            'title' => 'Optimize png image with Imageoptim.com',
                            'description' => 'Optimize png image with Imageoptim.com so it will take less space.',
                            'available' => true,
                            'priority' => 60,
                            'quality' => 80,
                            'os' => '',
                            'exec' => '',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegImageoptim',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderJpegImageoptim',
                            'serviceType' => 'ImageOptimizationJpeg',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                ],
            'ImageOptimizationPng' =>
                [
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngOptipng' =>
                        [
                            'title' => 'Optimize png image with command line executable "optipng"',
                            'description' => 'Optimize png image with command line executable "optipng" so it will take less space.',
                            'available' => true,
                            'priority' => 90,
                            'quality' => 80,
                            'os' => '',
                            'exec' => 'optipng',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngOptipng',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngOptipng',
                            'serviceType' => 'ImageOptimizationPng',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngPngcrush' =>
                        [
                            'title' => 'Optimize png image with command line executable "pngcrush"',
                            'description' => 'Optimize png image with command line executable "pngcrush" so it will take less space.',
                            'available' => true,
                            'priority' => 90,
                            'quality' => 80,
                            'os' => '',
                            'exec' => 'pngcrush',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngPngcrush',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngPngcrush',
                            'serviceType' => 'ImageOptimizationPng',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngPngquant' =>
                        [
                            'title' => 'Optimize png image with command line executable "pngquant"',
                            'description' => 'Optimize png image with command line executable "pngquant" so it will take less space.',
                            'available' => true,
                            'priority' => 90,
                            'quality' => 80,
                            'os' => '',
                            'exec' => 'pngquant',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngPngquant',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngPngquant',
                            'serviceType' => 'ImageOptimizationPng',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngTinypng' =>
                        [
                            'title' => 'Optimize png image with tinypng.com',
                            'description' => 'Optimize png image with tinypng.com so it will take less space.',
                            'available' => true,
                            'priority' => 80,
                            'quality' => 80,
                            'os' => '',
                            'exec' => '',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngTinypng',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngTinypng',
                            'serviceType' => 'ImageOptimizationPng',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngKraken' =>
                        [
                            'title' => 'Optimize png image with Kraken.io',
                            'description' => 'Optimize png image with Kraken.io so it will take less space.',
                            'available' => true,
                            'priority' => 70,
                            'quality' => 80,
                            'os' => '',
                            'exec' => '',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngKraken',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngKraken',
                            'serviceType' => 'ImageOptimizationPng',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                    'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngImageoptim' =>
                        [
                            'title' => 'Optimize png image with Imageoptim.com',
                            'description' => 'Optimize png image with Imageoptim.com so it will take less space.',
                            'available' => true,
                            'priority' => 60,
                            'quality' => 80,
                            'os' => '',
                            'exec' => '',
                            'className' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngImageoptim',
                            'extKey' => null,
                            'serviceKey' => 'SourceBroker\\Imageopt\\Providers\\ImageManipulationProviderPngImageoptim',
                            'serviceType' => 'ImageOptimizationPng',
                            'serviceSubTypes' =>
                                [
                                    '' => '',
                                ],
                        ],
                ],
        ];
    }

}
