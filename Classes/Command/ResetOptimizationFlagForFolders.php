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
use SourceBroker\Imageopt\Service\OptimizeImagesFolderService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class ImageoptCommandController
 */
class ResetOptimizationFlagForFolders extends BaseCommand
{
    /**
     * Configure the command by defining the name, options and arguments
     */
    public function configure()
    {
        $this->setDescription('Reset optimized flag for folders images so all files can be optimized once more.')
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
        $rootPageForTsConfig = $input->hasArgument('rootPageForTsConfig') && $input->getArgument('rootPageForTsConfig') !== null ? $input->getArgument('rootPageForTsConfig') : null;
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $optimizeImagesFolderService = $objectManager->get(
            OptimizeImagesFolderService::class,
            GeneralUtility::makeInstance(Configurator::class)->getConfigForPage($rootPageForTsConfig)
        );
        $optimizeImagesFolderService->resetOptimizationFlag();
        $io->writeln('Done succesfully.');
        return 0;
    }
}
