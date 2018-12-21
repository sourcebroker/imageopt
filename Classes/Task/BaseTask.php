<?php

namespace SourceBroker\Imageopt\Task;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ImageoptCommandController
 */
class BaseTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask
{
    /**
     * @param null $page
     * @return object|Configurator
     * @throws \Exception
     */
    public function getConfiguratorForPage($page = null)
    {
        $configurator = GeneralUtility::makeInstance(Configurator::class);
        $configurator->setConfig($configurator->getConfigForPage($page));
        return $configurator;
    }

    public function execute()
    {
    }
}
