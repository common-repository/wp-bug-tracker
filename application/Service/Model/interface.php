<?php

/*
  Copyright (C) <2012-2013>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

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
 * Interface for Web Service
 *
 * @package WordPress\WP Bug Tracker\Service
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 09/13/2012
 */
interface AHM_Service_Model_Interface{

    /**
     * Register the System Instance
     *
     * This request should be sent first (before any other activity)
     *
     * @param string $host
     * @param string $environment
     *
     * @return boolean
     *
     * @access public
     */
    public function register($host, $environment);

    /**
     * Report error
     *
     * @param string $instance
     * @param string $module
     * @param string $file
     * @param string $version
     * @param string $checksm
     * @param int    $level
     * @param string $message
     * @param int    $line
     *
     * @return int Report Status
     *
     * @access public
     */
    public function report($instance, $module, $file, $version, $checksum,
                                                            $level, $message, $line);

    /**
     * Check for available solutions
     *
     * @param string $instance
     * @param int    $reportID
     *
     * @return object Server Response
     *
     * @access public
     */
    public function check($instance, $reportID);

    /**
     * Apply Patch to the file
     *
     * @param string $instance System Instance number
     * @param int    $patchID  Patch ID
     *
     * @return object Server Response
     *
     * @access public
     */
    public function apply($instance, $patchID);

}