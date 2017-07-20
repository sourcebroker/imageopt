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

/**
 * ImageManipulationProviderAPIInterface
 */
interface ImageManipulationProviderBaseRemoteInterface
{
    /**
     * Uploading file to remote provider
     *
     * @param string $file Absolute path/file with original image
     * @param array $settings Additional options to optimize
     * @return array Result of optimization
     */
    public function upload($file, $settings = []);

    /**
     * Executes request at remote server
     *
     * @param string|array $data Data of request
     * @param string $url Url to execute request
     * @param array $params Additional parameters
     * @return array
     */
    public function request($data, $url, $params = []);
}
