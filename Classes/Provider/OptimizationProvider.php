<?php

/***************************************************************
 *  Copyright notice
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace SourceBroker\Imageopt\Provider;

use SourceBroker\Imageopt\Configuration\Configurator;
use SourceBroker\Imageopt\Domain\Model\ProviderResult;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class OptimizationProvider
{

    protected $executors;

    /**
     * @return mixed
     */
    public function getExecutors()
    {
        return $this->executors;
    }

    /**
     * @param mixed $executors
     */
    public function setExecutors($executors)
    {
        $this->executors = $executors;
    }


    public function optimize($image, Configurator $providerConfigurator)
    {
        $executorsDone = $executorsSuccesfull = 0;
        $providerResult = GeneralUtility::makeInstance(ProviderResult::class);
        $providerResult->setSizeBefore(filesize($image));
        foreach ((array)$providerConfigurator->getOption('executors') as $executorKey => $executor) {
            if ($executor['enabled']) {
                $executorsDone++;
                if (isset($executor['class']) && class_exists($executor['class'])) {
                    /** @var  \SourceBroker\Imageopt\Executor\OptimizationExecutorShell $imageOptimizationProvider */
                    $imageOptimizationProvider = GeneralUtility::makeInstance($executor['class']);
                    $executorResult = $imageOptimizationProvider->optimize(
                        $image,
                        GeneralUtility::makeInstance(
                            Configurator::class,
                            $executor)
                    );
                    $providerResult->addExecutorsResult($executorResult);
                    if ($executorResult->isExecutedSuccessfully()) {
                        $executorsSuccesfull++;
                    } else {
                        $providerResult->setExecutedSuccessfully(false);
                        break;
                    }
                } else {
                    throw new \Exception('No class found: ' . $executor['class'], 1500994839981);
                }
            }
        }

        $providerResult->setName($providerConfigurator->getOption('providerKey'));
        if ($executorsSuccesfull == $executorsDone) {
            $providerResult->setExecutedSuccessfully(true);
        }
        clearstatcache(true, $image);
        $providerResult->setSizeAfter(filesize($image));

        return $providerResult;
    }

}
