<?php

namespace SourceBroker\Imageopt\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case.
 */
class ExecutorResultTest extends UnitTestCase
{
    /**
     * @var \SourceBroker\Imageopt\Domain\Model\ExecutorResult
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \SourceBroker\Imageopt\Domain\Model\ExecutorResult();
    }

    protected function tearDown()
    {
        parent::tearDown();
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
    public function getCommandReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCommand()
        );
    }

    /**
     * @test
     */
    public function setCommandForStringSetsCommand()
    {
        $this->subject->setCommand('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'command',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCommandOutputReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCommandOutput()
        );
    }

    /**
     * @test
     */
    public function setCommandOutputForStringSetsCommandOutput()
    {
        $this->subject->setCommandOutput('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'commandOutput',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getCommandStatusReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getCommandStatus()
        );
    }

    /**
     * @test
     */
    public function setCommandStatusForStringSetsCommandStatus()
    {
        $this->subject->setCommandStatus('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'commandStatus',
            $this->subject
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

    /**
     * @test
     */
    public function getErrorMessageReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getErrorMessage()
        );
    }

    /**
     * @test
     */
    public function setErrorMessageForStringSetsErrorMessage()
    {
        $this->subject->setErrorMessage('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'errorMessage',
            $this->subject
        );
    }
}
