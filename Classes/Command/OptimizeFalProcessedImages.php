<?php

namespace SourceBroker\Imageopt\Command;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use Exception;
use InvalidArgumentException;
use SourceBroker\Imageopt\Utility\CliDisplayUtility;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizeFalProcessedImages extends BaseCommand
{
    public function configure(): void
    {
        $this->setDescription('Optimize FAL processed images')
            ->addOption(
                'numberOfImagesToProcess',
                null,
                InputOption::VALUE_REQUIRED,
                'The number of images to process on single task call.',
                50
            )
            ->addOption(
                'rootPageForTsConfig',
                null,
                InputOption::VALUE_REQUIRED,
                'The page uid for which the TSconfig is parsed. If not set then first found root page will be used.'
            );
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $numberOfImagesToProcess = $input->getOption('numberOfImagesToProcess');
        $rootPageForTsConfig = $input->getOption('rootPageForTsConfig');

        $configurator = $this->configurationFactory->createForPage($rootPageForTsConfig);
        $optimizeImagesFalService = $this->optimizeImageServiceFactory->createFalService($configurator);

        $extensions = GeneralUtility::trimExplode(',', $configurator->getOption('extensions'), true);
        $filesToProcess = $optimizeImagesFalService->getFalProcessedFilesToOptimize(
            $numberOfImagesToProcess,
            $extensions
        );
        if (count($filesToProcess)) {
            foreach ($filesToProcess as $fileToProcess) {
                $optimizationResults = $optimizeImagesFalService->optimizeFalProcessedFile($fileToProcess);
                foreach ($optimizationResults as $optimizationResult) {
                    $io->write(CliDisplayUtility::displayOptionResult($optimizationResult, $configurator->getConfig()));
                }
            }
        } else {
            if (!$io->isQuiet()) {
                $io->writeln('No images found that can be optimized.');
            }
        }
        return 0;
    }
}
