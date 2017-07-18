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

        $imageForTesting = realpath(__DIR__ . '/../../Fixture/OptimizeImageService/' . $image);
        $originalFileSize = filesize($imageForTesting);
        if (file_exists($imageForTesting)) {
            $results = $optimizeImageService->optimize(realpath(__DIR__ . '/../../Fixture/OptimizeImageService/' . $image));
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
            'Test jpg file resize' => [
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
                    'jpg' =>
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
                                    'command' => '{executable} {tempFile} -strip "all" {quality}',
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
                                    'command' => '{executable} -q -rem alla -brute -reduce -ow {tempFile} >/dev/null',
                                    'enabled' => '1',
                                ],
                            'pngquant' =>
                                [
                                    'command' => '{executable} {tempFile} --force --ext \'\'',
                                    'enabled' => '1',
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
        define('TYPO3_MODE', 'BE');
        require(__DIR__ . '/../../../ext_localconf.php');
    }

}
