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
 * Error General Statistics Model
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 09/18/2012
 */
class AHM_Error_Model_Statistic_General {
    /**
     * Group Type - TYPE
     */

    const GROUP_TYPE = 'type';

    /**
     * Group Type - STATUS
     */
    const GROUP_STATUS = 'status';

    /**
     * Group Type - MODULE
     */
    const GROUP_MODULE = 'module';

    /**
     * Storage for list of errors
     *
     * @access protected
     * @var array
     */
    protected $_storage = NULL;

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
     * Handle the request
     *
     * @access public
     * @return array
     */
    public function run() {
        if (is_null($this->_storage)) { //scan only once per HTTP request
            $this->prepare();
        }

        return $this->_storage;
    }

    /**
     * Prepare Statistic
     *
     * @access protected
     */
    protected function prepare() {
        $this->reset();
        $_hash = array();
        foreach (Router::call('Error.errors') as $storage) {
            foreach ($storage as $hash => $data) {
                if (!in_array($hash, $_hash)) {
                    $this->incStat(self::GROUP_TYPE, $data['type']);
                    $this->incStat(self::GROUP_STATUS, ucfirst($data['status']));
                    $this->incStat(self::GROUP_MODULE, $data['module']);
                    $_hash[] = $hash;
                }
            }
        }
    }

    /**
     * Reset Statistic
     *
     * @access protected
     */
    protected function reset() {
        $this->_storage = array(
            self::GROUP_TYPE => array(),
            self::GROUP_STATUS => array(),
            self::GROUP_MODULE => array()
        );
    }

    /**
     * Increment Statistic value based on $group & $type
     *
     * @access protected
     * @param string $group
     * @param string $type
     * @param int $value
     */
    protected function incStat($group, $type, $value = 1) {
        if (!isset($this->_storage[$group][$type])) {
            $this->_storage[$group][$type] = 0;
        }
        $this->_storage[$group][$type] += $value;
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