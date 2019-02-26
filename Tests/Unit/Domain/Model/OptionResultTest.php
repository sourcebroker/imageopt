<?php

namespace SourceBroker\Imageopt\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case.
 */
class OptionResultTest extends UnitTestCase
{
    /**
     * @var \SourceBroker\Imageopt\Domain\Model\OptionResult
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \SourceBroker\Imageopt\Domain\Model\OptionResult();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getFileRelativePathReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getFileRelativePath()
        );
    }

    /**
     * @test
     */
    public function setFileRelativePathForStringSetsFileRelativePath()
    {
        $this->subject->setFileRelativePath('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'fileRelativePath',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSizeBeforeReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSizeBefore()
        );
    }

    /**
     * @test
     */
    public function setSizeBeforeForStringSetsSizeBefore()
    {
        $this->subject->setSizeBefore('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'sizeBefore',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSizeAfterReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getSizeAfter()
        );
    }

    /**
     * @test
     */
    public function setSizeAfterForStringSetsSizeAfter()
    {
        $this->subject->setSizeAfter('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'sizeAfter',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getOptimizationBytesReturnsInitialValue()
    {
        self::assertSame(
            0,
            $this->subject->getOptimizationBytes()
        );
    }

    /**
     * @test
     */
    public function getOptimizationPercentageReturnsInitialValue()
    {
        self::assertSame(
            0,
            $this->subject->getOptimizationPercentage()
        );
    }

    /**
     * @test
     */
    public function isExecutedSuccessfullyReturnsInitialValueForBool()
    {
        self::assertSame(
            false,
            $this->subject->isExecutedSuccessfully()
        );
    }

    /**
     * @test
     */
    public function setExecutedSuccessfullyForBoolSetsExecutedSuccessfully()
    {
        $this->subject->setExecutedSuccessfully(true);

        self::assertAttributeEquals(
            true,
            'executedSuccessfully',
            $this->subject
        );
    }
}
