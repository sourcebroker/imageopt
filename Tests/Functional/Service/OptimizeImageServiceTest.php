<?php

declare(strict_types=1);

namespace SourceBroker\Imageopt\Tests\Functional\Service;

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ModeResult;
use SourceBroker\Imageopt\Service\OptimizeImageService;
use SourceBroker\Imageopt\Utility\CliDisplayUtility;
use SourceBroker\Imageopt\Utility\TemporaryFileUtility;
use Symfony\Component\Dotenv\Dotenv;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class OptimizeImageServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/imageopt'];
    protected const TEST_FILES_PATH = __DIR__ . '/../../../Tests/Fixture/Unit/OptimizeImageService/';

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->configurator = $this->pluginConfigurator();
        $this->temporaryFileUtility = GeneralUtility::makeInstance(TemporaryFileUtility::class);
        $this->optimizeImageService = GeneralUtility::makeInstance(
            OptimizeImageService::class, $this->configurator,
            $this->temporaryFileUtility
        );
    }

    /**
     * imageIsOptimized
     *
     * @dataProvider imageIsOptimizedDataProvider
     * @test
     */
    public function allProvidersSuccessful(string $image): void
    {
        fwrite(STDOUT, "\n" . 'TEST if all providers was executed successfully.' . "\n");

        $originalImagePath = self::TEST_FILES_PATH . $image;
        $imageForTesting = $this->temporaryFileUtility->createTemporaryCopy($originalImagePath);

        if (is_readable($imageForTesting)) {
            $optimizationResults = $this->optimizeImageService->optimize($imageForTesting);

            foreach ($optimizationResults as $optimizationResult) {
                fwrite(STDOUT,
                    CliDisplayUtility::displayOptionResult($optimizationResult, $this->configurator->getConfig()));
            }

            /** @var ModeResult $defaultResult */
            $defaultResult = $optimizationResults['default'] ?? reset($optimizationResults);

            self::assertEquals(
                $defaultResult->getStepResults()->count(),
                $defaultResult->getExecutedSuccessfullyNum()
            );
        } else {
            throw new \Exception('Image for testing does not exists:' . $imageForTesting);
        }
    }


    /**
     * imageIsOptimized
     * @test
     * @dataProvider imageIsOptimizedDataProvider
     */
    public function imageIsOptimized(string $image): void
    {
        fwrite(STDOUT, "\n" . 'TEST has been optimized.' . "\n");

        $originalImagePath = self::TEST_FILES_PATH . $image;
        $imageForTesting = $this->temporaryFileUtility->createTemporaryCopy($originalImagePath);
        if (is_readable($imageForTesting)) {
            $originalFileSize = filesize($imageForTesting);
            $optimizationResults = $this->optimizeImageService->optimize($imageForTesting);

            foreach ($optimizationResults as $optimizationResult) {
                fwrite(STDOUT,
                    CliDisplayUtility::displayOptionResult($optimizationResult, $this->configurator->getConfig()));
            }
            $defaultOptimizationResult = $optimizationResults['default'] ?? reset($optimizationResults);
            self::assertGreaterThan($defaultOptimizationResult->getSizeAfter(), $originalFileSize);
        } else {
            throw new \Exception('Image for testing is not existing:' . $imageForTesting);
        }
    }

    public static function imageIsOptimizedDataProvider(): array
    {
        return [
            'Test jpeg file resize' => [
                'mountains.jpg',
            ],
            'Test png file resize' => [
                'mountains.png',
            ],
            'Test gif file resize' => [
                'mountains.gif',
            ],
        ];
    }


    /**
     * @throws \Exception
     */
    private function pluginConfigurator(): Configurator
    {
        /** @var TypoScriptParser $typoScriptParser */
        $typoScriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

        $file1 = file_get_contents(realpath(__DIR__ . '/../../../Configuration/TsConfig/Page/tx_imageopt.tsconfig'));
        $file2 = file_get_contents(realpath(__DIR__ . '/../../../Configuration/TsConfig/Page/tx_imageopt__0100.tsconfig'));
        $typoScriptParser->parse($file1 . "\n" . $file2);
        $rawConfig = $typoScriptService->convertTypoScriptArrayToPlainArray($typoScriptParser->setup)['tx_imageopt'];

        $rawConfig['providersDefault']['enabled'] = 1;
        $envFile = __DIR__ . '/../../../.env';
        if (file_exists($envFile)) {
            $dotenv = new Dotenv();
            $dotenv->load($envFile);
        } else {
            throw new \Exception('No .env file found at: ' . $envFile);
        }

        $envConfig = [];
        foreach ($_ENV as $key => $value) {
            if (str_starts_with($key, 'tx_imageopt__')) {
                $envConfig[substr($key, 13)] = $value;
            }
        }
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'tx_imageopt__')) {
                $envConfig[substr($key, 13)] = $value;
            }
        }
        foreach ($envConfig as $name => $value) {
            $this->mergeConfig($rawConfig, $name, $value);
        }
        return GeneralUtility::makeInstance(Configurator::class, $rawConfig, true);
    }

    private function mergeConfig(array &$targetArray, string $input, $value): void
    {
        $nestedArray = $this->convertStringToNestedArray($input, $value);
        ArrayUtility::mergeRecursiveWithOverrule($targetArray, $nestedArray, false);
    }

    private function convertStringToNestedArray(string $input, $value): array
    {
        $keys = explode('__', $input);
        $nestedArray = [];
        $current = &$nestedArray;

        foreach ($keys as $key) {
            $current = &$current[$key];
        }

        $current = $value;
        return $nestedArray;
    }
}
