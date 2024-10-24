<?php

namespace SourceBroker\Imageopt\Command;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Utility\CliDisplayUtility;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OptimizeFolderImages extends BaseCommand
{
    public function configure(): void
    {
        $this->setDescription('Optimize images in folders')
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
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $numberOfImagesToProcess = $input->getOption('numberOfImagesToProcess');
        $rootPageForTsConfig = $input->getOption('rootPageForTsConfig');

        $configurator = $this->configurationFactory->createForPage($rootPageForTsConfig);
        $optimizeImagesFolderService = $this->optimizeImageServiceFactory->createFolderService($configurator);

        $filesToProcess = $optimizeImagesFolderService->getFilesToOptimize($numberOfImagesToProcess);
        if (!empty($filesToProcess)) {
            foreach ($filesToProcess as $fileToProcess) {
                $optimizationResults = $optimizeImagesFolderService->optimizeFolderFile($fileToProcess);
                foreach ($optimizationResults as $optimizationResult) {
                    $io->write(CliDisplayUtility::displayOptionResult($optimizationResult, $configurator->getConfig()));
                }
            }
        } elseif (!$io->isQuiet()) {
            $output->writeln('No images found that can be optimized.');
        }
        return 0;
    }
}
