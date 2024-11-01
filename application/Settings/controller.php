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
 * Settings Controller
 *
 * @package WordPress\WP Bug Tracker\Settings
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 09/11/2012
 */
class AHM_Settings_Controller {

    /**
     * Settings Storage type
     *
     * @access protected
     * @var string
     */
    protected $_storage;

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct() {
        $storage  = 'AHM_Settings_Model_' . ucfirst(AHM_SETTINGS_STORAGE);
        $this->_storage = call_user_func(array($storage, 'getInstance'));
    }

    /**
     * Get UI settings
     *
     * @access public
     * @param array $data
     * @return mixed
     */
    public function ui($data = NULL){
        if (is_null($data)){
            $result = $this->read(AHM_SETTINGS_UIOPT);
        }else{
            $result = $this->update(AHM_SETTINGS_UIOPT, $data);
        }
        return $result;
    }

    /**
     * Get/Set account ID
     *
     * @param string $data
     *
     * @return boolean
     */
    public function account($data = NULL){
        if (is_null($data)){
            $result = $this->read(AHM_SETTINGS_ACCOUNTOPT);
        }else{
            $result = $this->update(AHM_SETTINGS_ACCOUNTOPT, $data);
        }
        return $result;
    }

    /**
     * Get Setting based on requested $param
     *
     * @access public
     * @param string $param
     * @return mixed
     */
    public function read($param){
        return $this->_storage->read($param);
    }

    /**
     * Insert or Update Setting based on requested $param
     *
     * @access public
     * @param string $param
     * @return mixed
     */
    public function update($param, $value){
        return $this->_storage->update($param, $value);
    }

    /**
     * Remove GUI settings but keep the account ID
     *
     * @return boolean
     */
    public function removeUI(){
        return $this->_storage->delete(AHM_SETTINGS_UIOPT);
    }

}