<?php

namespace SourceBroker\Imageopt\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case.
 */
class OptimizationResultTest extends UnitTestCase
{
    /**
     * @var \SourceBroker\Imageopt\Domain\Model\OptimizationResult
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \SourceBroker\Imageopt\Domain\Model\OptimizationResult();
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
    public function getOptimizationBytesReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getOptimizationBytes()
        );
    }

    /**
     * @test
     */
    public function setOptimizationBytesForStringSetsOptimizationBytes()
    {
        $this->subject->setOptimizationBytes('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'optimizationBytes',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getOptimizationPercentageReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getOptimizationPercentage()
        );
    }

    /**
     * @test
     */
    public function setOptimizationPercentageForStringSetsOptimizationPercentage()
    {
        $this->subject->setOptimizationPercentage('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'optimizationPercentage',
            $this->subject
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
    public function getExecutedSuccessfullyReturnsInitialValueForBool()
    {
        self::assertSame(
            false,
            $this->subject->getExecutedSuccessfully()
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
    public function setProvidersResultsForObjectStorageContainingProviderResultSetsProvidersResults()
    {
        $providersResult = new \SourceBroker\Imageopt\Domain\Model\ProviderResult();
        $objectStorageHoldingExactlyOneProvidersResults = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneProvidersResults->attach($providersResult);
        $this->subject->setProvidersResults($objectStorageHoldingExactlyOneProvidersResults);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneProvidersResults,
            'providersResults',
            $this->subject
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

    /**
     * @test
     */
    public function removeProvidersResultFromObjectStorageHoldingProvidersResults()
    {
        $providersResult = new \SourceBroker\Imageopt\Domain\Model\ProviderResult();
        $providersResultsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $providersResultsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($providersResult));
        $this->inject($this->subject, 'providersResults', $providersResultsObjectStorageMock);

        $this->subject->removeProvidersResult($providersResult);
    }
}
