<?php

namespace SourceBroker\Imageopt\Task;

use TYPO3\CMS\Core\Utility\CommandUtility;

class OptimizeFalProcessedImagesTask extends BaseTask
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function execute()
    {
        // Call symfony command. This is fallback for TYPO3 8.7 as in TYPO3 9.5 you should use task "Execute console commands".
        CommandUtility::exec(($this->getConfiguratorForPage()->getOption('binary.php') ?? 'php') . ' ' . PATH_site . 'typo3/sysext/core/bin/typo3 imageopt:optimizefalprocessedimages',
            $output,$returnValue);
        return $returnValue === 0 ? true : false;
    }
}
