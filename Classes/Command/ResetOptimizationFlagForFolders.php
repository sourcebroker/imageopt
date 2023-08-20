<?php

namespace SourceBroker\Imageopt\Command;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use Exception;
use InvalidArgumentException;
use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Service\OptimizeImagesFolderService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
     * @throws InvalidArgumentException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());
        $rootPageForTsConfig = $input->hasOption('rootPageForTsConfig') && $input->getOption('rootPageForTsConfig') !== null ? $input->getOption('rootPageForTsConfig') : null;
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        $configurator = GeneralUtility::makeInstance(Configurator::class);
        $configurator->setConfigByPage($rootPageForTsConfig);
        $configurator->init();

        /** @var OptimizeImagesFolderService $optimizeImagesFolderService */
        $optimizeImagesFolderService = $objectManager->get(
            OptimizeImagesFolderService::class,
            $configurator->getConfig()
        );
        $optimizeImagesFolderService->resetOptimizationFlag();

        $io->writeln('Done succesfully.');
        return 0;
    }
}
