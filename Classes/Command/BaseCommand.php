<?php

namespace SourceBroker\Imageopt\Command;

/*
This file is part of the "imageopt" Extension for TYPO3 CMS.
For the full copyright and license information, please read the
LICENSE.txt file that was distributed with this source code.
*/

use SourceBroker\Imageopt\Configuration\ConfiguratorFactory;
use SourceBroker\Imageopt\Service\OptimizeImageServiceFactory;
use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
    protected ConfiguratorFactory $configurationFactory;
    protected OptimizeImageServiceFactory $optimizeImageServiceFactory;

    public function __construct(
        ConfiguratorFactory $configurationFactory,
        OptimizeImageServiceFactory $optimizeImageServiceFactory,
        $name = null
    ) {
        parent::__construct($name);
        $this->configurationFactory = $configurationFactory;
        $this->optimizeImageServiceFactory = $optimizeImageServiceFactory;
    }
}
