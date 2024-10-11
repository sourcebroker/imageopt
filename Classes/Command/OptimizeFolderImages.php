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
use SourceBroker\Imageopt\Service\OptimizeImagesFalService;
use SourceBroker\Imageopt\Service\OptimizeImagesFolderService;
use SourceBroker\Imageopt\Utility\CliDisplayUtility;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class OptimizeFolderImages extends BaseCommand
{
    private Configurator $configurator;
    private OptimizeImagesFalService $optimizeImagesFalService;
    private CliDisplayUtility $cliDisplayUtility;
    private OptimizeImagesFolderService $optimizeImagesFolderService;

    public function __construct(Configurator $configurator, OptimizeImagesFolderService $optimizeImagesFolderService, CliDisplayUtility $cliDisplayUtility)
    {
        $this->configurator = $configurator;
        $this->cliDisplayUtility = $cliDisplayUtility;
        $this->optimizeImagesFolderService = $optimizeImagesFolderService;
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setDescription('Optimize images in folders')
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
     * @throws InvalidArgumentException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $numberOfImagesToProcess = $input->hasOption('numberOfImagesToProcess') && $input->getOption('numberOfImagesToProcess') !== null ? $input->getOption('numberOfImagesToProcess') : 50;
        $rootPageForTsConfig = $input->hasOption('rootPageForTsConfig') && $input->getOption('rootPageForTsConfig') !== null ? $input->getOption('rootPageForTsConfig') : null;
        $this->configurator->setConfigByPage($rootPageForTsConfig);

        $filesToProcess = $this->optimizeImagesFolderService->getFilesToOptimize($numberOfImagesToProcess);
        if (!empty($filesToProcess)) {
            foreach ($filesToProcess as $fileToProcess) {
                $optimizationResults = $this->optimizeImagesFolderService->optimizeFolderFile($fileToProcess);
                foreach ($optimizationResults as $optimizationResult) {
                    $io->write($this->cliDisplayUtility->displayOptionResult($optimizationResult));
                }
            }
        } elseif (!$io->isQuiet()) {
            $output->writeln('No images found that can be optimized.');
        }
        return 0;
    }
}
