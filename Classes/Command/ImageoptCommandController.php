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

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * @package SourceBroker\OptimiseImages\Command
 */
class ImageoptCommandController extends CommandController
{
    /*
     * Number of images to process in one cron run
     */
    protected $numberOfImagesToProcess = 20;

    /**
     * Injection of Image Manipulation Service Object
     *
     * @var \SourceBroker\Imageopt\Service\ImageManipulationService
     * @inject
     */
    protected $imageManipulationService;

    /**
     * Optimise all TYPO3 processed images
     *
     */
    public function optimizeImagesCommand()
    {
        $this->imageManipulationService->optimizeImages($this->numberOfImagesToProcess);
    }

    /**
     * Clear optimized stat so all files can be optimized once more.
     * Can be useful for testing.
     *
     */
    public function resetOptimizationFlagCommand()
    {
        $this->imageManipulationService->resetOptimizationFlag();
    }
}
