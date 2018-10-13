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

use SourceBroker\Imageopt\Service\OptimizeImagesFalService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class ImageoptCommandController
 */
class OptimizeFalProcessedImages extends BaseCommand
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    public function configure()
    {
        $this->setDescription('Optimize FAL processed images')
            ->addArgument(
                'numberOfImagesToProcess',
                InputArgument::OPTIONAL,
                'The number of images to process on single task call.'
            )
            ->addArgument(
                'rootPageForTsConfig',
                InputArgument::OPTIONAL,
                'The page uid for which the TSconfig is parsed. If not set then first found root page will be used.'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \InvalidArgumentException
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $numberOfImagesToProcess = $input->hasArgument('numberOfImagesToProcess') && $input->getArgument('numberOfImagesToProcess') !== null ? $input->getArgument('numberOfImagesToProcess') : 20;
        $rootPageForTsConfig = $input->hasArgument('rootPageForTsConfig') && $input->getArgument('rootPageForTsConfig') !== null ? $input->getArgument('rootPageForTsConfig') : null;

        $this->initConfigurator($rootPageForTsConfig);
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $optimizeImagesFalService = $objectManager->get(
            OptimizeImagesFalService::class,
            $this->getConfigurator()->getConfig()
        );
        $filesToProcess = $optimizeImagesFalService->getFalProcessedFilesToOptimize($numberOfImagesToProcess);
        if (!empty($filesToProcess)) {
            foreach ($filesToProcess as $fileToProcess) {
                $optimizationResult = $optimizeImagesFalService->optimizeFalProcessedFile($fileToProcess);
                $io->write($this->showResult($optimizationResult));
            }
        } else {
            if (!$io->isQuiet()) {
                $io->writeln('No images found that can be optimized.');
            }
        }
        return 0;
    }
}
