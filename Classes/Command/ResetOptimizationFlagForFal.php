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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class ResetOptimizationFlagForFal extends BaseCommand
{
    public function configure()
    {
        $this->setDescription('Reset optimized flag for FAL processed images so all files can be optimized once more')
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
     * @throws \InvalidArgumentException
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());
        $rootPageForTsConfig = $input->hasOption('rootPageForTsConfig') && $input->getOption('rootPageForTsConfig') !== null ? $input->getOption('rootPageForTsConfig') : null;
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $optimizeImagesFalService = $objectManager->get(
            OptimizeImagesFalService::class,
            GeneralUtility::makeInstance(Configurator::class)->getConfigForPage($rootPageForTsConfig)
        );
        $optimizeImagesFalService->resetOptimizationFlag();
        $io->writeln('Done succesfully.');
        return 0;
    }
}
