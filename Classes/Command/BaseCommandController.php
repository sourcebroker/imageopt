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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;

class BaseCommandController extends CommandController
{
    /**
     * Set Task Message in Scheduler
     * @param $message
     * @param $title
     * @param int $status
     */
    public function setSchedulerTaskMessage($message, $title, $status = FlashMessage::INFO)
    {
        if (count($message) > 0) {
            /** @var FlashMessage $flashMessage */
            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                implode("<br/>", $message),
                $title,
                $status
            );

            /** @var $flashMessageService FlashMessageService */
            $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
            /** @var $defaultFlashMessageQueue FlashMessageQueue */
            $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
            $defaultFlashMessageQueue->enqueue($flashMessage);
        }
    }

    /**
     * Set Task Message in Console Window
     * @param $message
     */
    public function setConsoleTaskMessage($message)
    {
        $this->outputLine(implode("\n", $message));
    }
}
