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
 * List of storages
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 03/03/2013
 */
class AHM_Error_Model_List {

    /**
     * List of storages
     *
     * @var array
     *
     * @access private
     */
    private $_list = array();

    /**
     * Single instance of itself
     *
     * @var AHM_Error_Model_List
     *
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * Constructor
     *
     * @return void
     *
     * @access public
     */
    public function __construct() {
        $start_date = date(
                AHM_ERROR_DATEFORMAT, 
                strtotime('today -' . AHM_ERROR_DAYCOUNT . ' days')
        );
        foreach (scandir(AHM_ERROR_CACHEDIR) as $file) {
            $filename = realpath(AHM_ERROR_CACHEDIR . '/' . $file);
            if (is_file($filename)
                    && preg_match('/^[\d\-]+$/', $file)
                    && ($file >= $start_date)
            ) { //valid file && within date range
                $storage = unserialize(file_get_contents($filename));
                if ($storage instanceof AHM_Error_Model_Storage_Abstract) {
                    //filter storage from resolved and inactual errors
                    while($storage->valid()){
                        if ($this->validChecksum($storage->current()) === false) {
                            $storage->remove($storage->key());
                        }else{
                            $storage->next();
                        }
                    }
                    $this->_list[$storage->getDate()] = $storage;
                }
            } elseif (is_file($filename)) { //remove storage. It is retired
                unlink($filename);
            }
        }
    }

    /**
     * Validate the report's and actual file checksum
     * 
     * @param array $report
     * 
     * @return boolean
     * 
     * @access protected
     */
    protected function validChecksum($report) {
        static $checksum = array();

        $response = false;
        //make sure that file still exists
        if (file_exists($report['syspath'])) {
            if (!isset($checksum[$report['syspath']])) {
                //get current checksum
                $checksum[$report['syspath']] = md5(
                        file_get_contents($report['syspath'])
                );
            }
            //is it the same as it was reported?
            if ($checksum[$report['syspath']] == $report['checksum']) {
                $response = true;
            }
        }

        return $response;
    }

    /**
     * Get list
     *
     * @return array
     *
     * @access public
     */
    public function getList() {
        return $this->_list;
    }

    /**
     * Get total number of errors
     * 
     * @return int
     * 
     * @access public
     */
    public function count() {
        $hashTable = array();
        foreach ($this->getList() as $storage) {
            foreach ($storage as $hash => $data) {
                $hashTable[] = $hash;
            }
        }

        return count(array_unique($hashTable));
    }

    /**
     * Get single instance of itself
     *
     * @return AHM_Error_Model_List
     *
     * @access public
     * @static
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

}