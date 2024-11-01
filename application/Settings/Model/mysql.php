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
 * Settings MySQL Model
 *
 * @package WordPress\WP Bug Tracker\Settings
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 09/13/2012
 */
class AHM_Settings_Model_Mysql implements AHM_Settings_Model_Interface {

    /**
     * Cache
     *
     * @access protected
     * @var array
     */
    protected $_cache = array();

    /**
     * Instance of itself
     *
     * @access protected
     * @var AHM_Settings_Model_Mysql
     */
    protected static $_instance;

    /**
     * @inheritdoc
     */
    public function read($param) {
        if (!isset($this->_cache[$param])) {
            $this->_cache[$param] = Router::call('Factory.getOption', $param);
        }

        return $this->_cache[$param];
    }

    /**
     * @inheritdoc
     */
    public function update($param, $value) {
        if ($result = Router::call('Factory.updateOption', $param, $value)){
            $this->_cache[$param] = $value;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function delete($param) {
        if (isset($this->_cache[$param])) {
            unset($this->_cache[$param]);
        }

        return Router::call('Factory.deleteOption', $param);
    }

    /**
     * Get Single instance of itself
     *
     * @access public
     * @return AHM_Settings_Model_Mysql
     */
    public static function getInstance(){
        if (is_null(self::$_instance)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

}