<?php

namespace SourceBroker\Imageopt\Command;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Service\OptimizeImagesFalService;
use SourceBroker\Imageopt\Utility\CliDisplayUtility;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class OptimizeFalProcessedImages extends BaseCommand
{
    public function configure()
    {
        $this->setDescription('Optimize FAL processed images')
            ->addOption(
                'numberOfImagesToProcess',
                null,
                InputOption::VALUE_REQUIRED,
                'The number of images to process on single task call.'
            )
            ->addOption(
                'rootPageForTsConfig',
                null,
                InputOption::VALUE_REQUIRED,
                'The page uid for which the TSconfig is parsed. If not set then first found root page will be used.'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $numberOfImagesToProcess = $input->hasOption('numberOfImagesToProcess') && $input->getOption('numberOfImagesToProcess') !== null
            ? $input->getOption('numberOfImagesToProcess')
            : 50;
        $rootPageForTsConfig = $input->hasOption('rootPageForTsConfig') && $input->getOption('rootPageForTsConfig') !== null
            ? $input->getOption('rootPageForTsConfig')
            : null;

        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        $configurator = GeneralUtility::makeInstance(Configurator::class);
        $configurator->setConfigByPage($rootPageForTsConfig);
        $configurator->init();

        /** @var OptimizeImagesFalService $optimizeImagesFalService */
        $optimizeImagesFalService = $objectManager->get(OptimizeImagesFalService::class, $configurator->getConfig());
        $extensions = GeneralUtility::trimExplode(',', $configurator->getOption('extensions'), true);
        $filesToProcess = $optimizeImagesFalService->getFalProcessedFilesToOptimize($numberOfImagesToProcess, $extensions);
        if (!empty($filesToProcess)) {
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
