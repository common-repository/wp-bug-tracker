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
 * Settings Storage Interface
 *
 * @package WordPress\WP Bug Tracker\Settings
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 09/13/2012
 */
interface AHM_Settings_Model_Interface {

    /**
     * Read Setting
     *
     * @access public
     * @param string $param
     * @return mixed
     */
    public function read($param);

    /**
     * Insert or Update Setting
     *
     * @access public
     * @param string $param
     * @param mixed $value
     * @return boolean
     */
    public function update($param, $value);

    /**
     * Delete Setting
     *
     * @access public
     * @param string @param
     * @return boolean
     */
    public function delete($param);
}