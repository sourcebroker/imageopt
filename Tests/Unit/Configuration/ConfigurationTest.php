<?php

namespace SourceBroker\Imageopt\Tests\Unit\Configuration;

use \SourceBroker\Imageopt\Configuration\Configurator;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test for configuration
 *
 */
class ConfigurationTest extends UnitTestCase
{

    /**
     * Test nested variables return good values
     *
     * @test
     * @dataProvider nestedVariableIsCorrectlyReturnedDataProvider
     * @param $given
     * @param $expected
     */
    public function nestedVariableIsCorrectlyReturned($given, $expected)
    {
        /** @var \SourceBroker\Imageopt\Configuration\Configurator $configuration */
        $configuration = $this->getMockBuilder(Configurator::class)->setMethods(['dummy'])->getMock();
        $configuration->setConfig($this->staticTsConfig());
        $this->assertEquals($expected, $configuration->getOption($given));
    }

    /**
     * Data provider for nestedVariableIsCorrectlyReturned
     *
     * @return array
     */
    public function nestedVariableIsCorrectlyReturnedDataProvider()
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
