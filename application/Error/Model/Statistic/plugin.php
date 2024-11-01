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
 * Error Statistics grouped by Plugin
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 02/25/2013
 */
class AHM_Error_Model_Statistic_Plugin {

    /**
     * Single instance of itself
     *
     * Is very importand to have only single instance to
     * avoid mismatch in error list
     *
     * @access protected
     * @var AHM_Error_Model_Statistic_General
     */
    protected static $_instance = NULL;

    /**
     * Grouped stats
     *
     * @var array
     *
     * @access private
     */
    private $_stats = array();

    /**
     * Handle the request
     *
     * @access public
     * @return array
     */
    public function run() {
        $this->prepare();

        return $this->_stats;
    }

    /**
     * Prepare Statistic
     *
     * @access protected
     */
    protected function prepare() {
        foreach (Router::call('Error.errors') as $storage) {
            foreach ($storage as $data) {
                if (!isset($this->_stats[$data['module']])) {
                    $this->_stats[$data['module']] = array(0, 0, 0);
                }
                if ($data['type'] == 'Error') {
                    $index = 0;
                } elseif ($data['type'] == 'Warning') {
                    $index = 1;
                } else {
                    $index = 2;
                }
                $this->_stats[$data['module']][$index]++;
            }
        }
    }

    /**
     * Get Single instance of itself
     *
     * @access public
     * @return AHM_Error_Model_Statistic_General
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

}