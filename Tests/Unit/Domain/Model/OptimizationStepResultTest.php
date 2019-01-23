<?php

namespace SourceBroker\Imageopt\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case.
 */
class OptimizationStepResultTest extends UnitTestCase
{
    /**
     * @var \SourceBroker\Imageopt\Domain\Model\OptimizationStepResult
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \SourceBroker\Imageopt\Domain\Model\OptimizationStepResult();
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
    public function getProviderWinnerNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getProviderWinnerName()
        );
    }

    /**
     * @test
     */
    public function setProviderWinnerNameForStringSetsProviderWinnerName()
    {
        $this->subject->setProviderWinnerName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'providerWinnerName',
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
    public function getProvidersResultsReturnsInitialValueForProviderResult()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getProvidersResults()
        );
    }

    /**
     * @test
     */
    public function addProvidersResultToObjectStorageHoldingProvidersResults()
    {
        $providersResult = new \SourceBroker\Imageopt\Domain\Model\ProviderResult();
        $providersResultsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $providersResultsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($providersResult));
        $this->inject($this->subject, 'providersResults', $providersResultsObjectStorageMock);

        $this->subject->addProvidersResult($providersResult);
    }
}
