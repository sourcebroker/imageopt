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

namespace SourceBroker\Imageopt\Providers;

use SourceBroker\Imageopt\Configuration\Configurator;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Service\AbstractService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ImageManipulationProvider
 */
abstract class ImageManipulationProvider extends AbstractService
{
    /**
     * General configuration
     *
     * @var null|\SourceBroker\Imageopt\Configuration\Configurator
     */
    protected $configurator = null;

    /**
     * Result of optimization
     *
     * @var array
     */
    protected $optimizationResult = [
        'success' => false,
        'optimizedFileAbsPath' => null,
        'providerCommand' => null,
        'providerError' => null,
    ];

    /**
     * Image file extension operated by provider
     *
     * @var string
     */
    protected $fileType = '';

    /**
     * Provider name
     *
     * @var string
     */
    protected $name = '';

    /**
     * ImageManipulationProvider constructor
     */
    public function __construct()
    {
        $this->configurator = GeneralUtility::makeInstance(Configurator::class);
    }

    /**
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param string $fileType
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set configuration object for provider
     *
     * @param $configurator
     * @return object
     */
    public function setConfigurator($configurator)
    {
        return $this->configurator = $configurator;
    }

    /**
     * Get configuration object for provider
     *
     * @return object
     */
    public function getConfigurator()
    {
        return $this->configurator;
    }

    abstract public function optimize($image);

    /**
     * Create temporary file
     *
     * @return bool|string
     */
    protected function getTemporaryFilename()
    {
        return $this->tempFile('tx_imageopt_');
    }

    /**
     * Return a copy of file under a temporary filename.
     * File is deleted autmaticaly after script end.
     *
     * @param string $originalFileAbsolutePath Absolute path/file with original image
     * @return bool if not created file or string with temporary file path
     */
    protected function createTemporaryCopy($originalFileAbsolutePath)
    {
        $this->checkInputFile($originalFileAbsolutePath);
        $tempFilename = $this->getTemporaryFilename();
        if (file_exists($tempFilename)) {
            copy($originalFileAbsolutePath, $tempFilename);
        }
        return $tempFilename;
    }


    /**
     * Return true if provider is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->getConfigurator()->getOption('enabled');
    }

    /**
     * Send notification email
     *
     * @param string $email Notification email defined in TS
     * @param string $title Message title
     * @param string $message Message body
     */
    public function sendNotificationEmail($email, $title, $message)
    {
        if (!(bool)$this->getConfigurator()->getOption('limits.notification.disable')) {
            $senderEmail = $this->getConfigurator()->getOption('limits.notification.sender.email');
            $senderName = $this->getConfigurator()->getOption('limits.notification.sender.name');

            if ($email != '' && $senderEmail != '' && GeneralUtility::validEmail($email) && GeneralUtility::validEmail($senderEmail)) {
                $mail = GeneralUtility::makeInstance(MailMessage::class);
                $mail->setSubject($title)
                    ->setFrom([$senderEmail => ($senderName ? $senderName : 'Imageopt Notifications')])
                    ->setTo([$email])
                    ->setBody($message)
                    ->send();
            }
        }
    }
}
