<?php

namespace SourceBroker\Imageopt\Command;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ResetOptimizationFlagForFolders extends BaseCommand
{
    public function configure(): void
    {
        $this->setDescription('Reset optimized flag for folders images so all files can be optimized once more.')
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
        $rootPageForTsConfig = $input->getOption('rootPageForTsConfig');

        $configurator = $this->configurationFactory->createForPage($rootPageForTsConfig);
        $optimizeImagesFolderService = $this->optimizeImageServiceFactory->createFolderService($configurator);
        $optimizeImagesFolderService->resetOptimizationFlag();

        $io->writeln('Done successfully.');
        return 0;
    }
}
