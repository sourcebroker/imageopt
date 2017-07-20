<?php

namespace SourceBroker\Imageopt\Tests\Unit\Configuration;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use SourceBroker\Imageopt\Configuration\Configurator;

/**
 * Tests for configurator
 *
 */
class ConfiguratorTest extends UnitTestCase
{
    /**
     * Test if configurator array options are correctly returned
     *
     * @test
     * @dataProvider configuratorOptionsAreCorrectlyReturnedDataProvider
     * @param $given
     * @param $expected
     */
    public function configuratorOptionsAreCorrectlyReturned($given, $expected)
    {
        /** @var \SourceBroker\Imageopt\Configuration\Configurator $configurator */
        $configurator = $this->getMockBuilder(Configurator::class)
            ->setMethods(null)
            ->getMock();
        $configurator->setConfig($this->staticTsConfig());
        $this->assertEquals($expected, $configurator->getOption($given));
    }

    /**
     * Data provider for configuratorOptionsAreCorrectlyReturned
     *
     * @return array
     */
    public function configuratorOptionsAreCorrectlyReturnedDataProvider()
    {
        return [
            'nonWorkingConfigurationNullConfig' => [
                null,
                null
            ],
            'nonWorkingConfigurationEmptyConfig' => [
                '',
                null
            ],
            'nonWorkingConfigurationArrayConfig' => [
                ['value'],
                null
            ],
            'nonWorkingConfigurationNotExistingConfig' => [
                'notExistingOption',
                null
            ],
            'workingConfigurationFirstLevelString' => [
                'option1',
                'value'
            ],
            'workingConfigurationFirstLevelArray' => [
                'option2',
                ['option2Sub' => 'value']
            ],
            'workingConfigurationSecondLevelString' => [
                'option2.option2Sub',
                'value'
            ],
            'workingConfigurationSecondLevelArray' => [
                'option3.option3Sub',
                [
                    'option3SubSub1' => 'value1',
                    'option3SubSub2' => 'value2'
                ]
            ],
        ];
    }

    public function staticTsConfig()
    {
        return [
            'option1' => 'value',
            'option2' => [
                'option2Sub' => 'value'
            ],
            'option3' => [
                'option3Sub' => [
                    'option3SubSub1' => 'value1',
                    'option3SubSub2' => 'value2'
                ]
            ]
        ];
    }
}
