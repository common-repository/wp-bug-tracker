<?php

/*
  Copyright (C) <2012-213>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Interface for Factory
 *
 * @package WordPress\WP Bug Tracker\Factory
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 07/19/2012
 */
interface AHM_Factory_Model_Interface{

    /**
     * Get Module Information based on file path
     *
     * This Function should return an associated array with next parameters:
     * array(
     *    'module' => (string) [Module Name],
     *    'version' => (string) [Module Version],
     *    'meta' => (mixed) [Any Related information about the Module],
     *    'sysPath' => (string) [Path to the file from the Application root]
     * )
     *
     * @access public
     * @param string $filepath
     */
    public function getModuleInfo($filepath);

    /**
     * Localize Label
     *
     * @access public
     * @param string $label
     * @param string $domain
     * @return string
     */
    public function lang($label, $domain = NULL);

    /**
     * Get System Absolute Path
     *
     * @access public
     * @return string
     */
    public function absPath();

    /**
     * Get base site URL
     *
     * @access public
     * @return string
     */
    public function baseURL();

    /**
     * Get Environment Name
     *
     * @access public
     * @return string
     */
    public function getEnvironment();
    
    /**
     * Send Remove request
     * 
     * @param string $url
     * @param array  $params
     * 
     * @return mixed
     * 
     * @access public
     */
    public function remoteRequest($url, $params = array());

}