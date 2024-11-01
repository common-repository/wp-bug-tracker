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
 * Error Storage File Model
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 09/11/2012
 */
class AHM_Error_Model_Storage_File extends AHM_Error_Model_Storage_Abstract {

    /**
     * Indicator that the error log file has been scanned completely
     *
     * @var boolean
     *
     * @access private
     */
    private $_complete = false;

    /**
     * File pointer to seek to
     *
     * @var int
     *
     * @access private
     */
    private $_ftell = 0;

    /**
     * @inheritdoc
     */
    public function __destruct() {
        file_put_contents($this->getCacheFilename(), serialize($this));
    }


    /**
     * Get real path to cache file
     *
     * @access protected
     * @return string
     */
    protected function getCacheFilename() {
        return AHM_ERROR_CACHEDIR . DIRECTORY_SEPARATOR . $this->getDate();
    }

    /**
     * Return the list of valiables to serialize
     *
     * @access public
     * @return array
     */
    public function __sleep() {
        return array('_storage', '_complete', '_ftell', '_date');
    }

    /**
     * Set Complete flag
     *
     * @param boolean $complete
     *
     * @return void
     *
     * @access public
     */
    public function setComplete($complete){
        $this->_complete = $complete;
    }

    /**
     * Get Complete flag
     *
     * @return boolean
     *
     * @access public
     */
    public function getComplete(){
        return $this->_complete;
    }

    /**
     * Set File pointer
     *
     * @param int $ftell
     *
     * @return void
     *
     * @access public
     */
    public function setFtell($ftell){
        $this->_ftell = $ftell;
    }

    /**
     * Get File pointer
     *
     * @return int
     *
     * @access public
     */
    public function getFtell(){
        return $this->_ftell;
    }

}