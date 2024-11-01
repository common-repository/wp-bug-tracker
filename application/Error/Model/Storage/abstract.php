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
 * Error Storage Model
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 07/12/2012
 */
abstract class AHM_Error_Model_Storage_Abstract implements Iterator {

    /**
     * Date to Word with
     *
     * @access protected
     * @var string
     */
    protected $_date;

    /**
     * Actual Storage
     *
     * @access protected
     * @var array
     */
    protected $_storage = array();

    /**
     * Error Type List
     *
     * @access protected
     * @var array
     */
    protected $_errorType = array(
        E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR
    );

    /**
     * Warning Type List
     *
     * @access protected
     * @var array;
     */
    protected $_warningType = array(
        E_WARNING, E_CORE_WARNING, E_COMPILE_WARNING, E_USER_WARNING
    );

    /**
     * Desctructor
     *
     * @access public
     */
    abstract public function __destruct();

    /**
     * Set Date to work with
     *
     * @access public
     * @param string $date
     */
    public function setDate($date) {
        $this->_date = $date;
    }

    /**
     * Get Storage Date
     *
     * @access public
     * @return string
     */
    public function getDate() {
        return $this->_date;
    }

    /**
     * Get entire storage
     *
     * @access public
     * @return array
     */
    public function getStorage() {
        return $this->_storage;
    }

    /**
     * Return Current element in Storage
     *
     * @access public
     * @return array
     */
    public function current() {
        return current($this->_storage);
    }

    /**
     * Fetch a current key from the Storage
     *
     * @access public
     * @return string
     */
    public function key() {
        return key($this->_storage);
    }

    /**
     * Advance the internal array pointer of the Storage
     *
     * @access public
     */
    public function next() {
        next($this->_storage);
    }

    /**
     * Set the internal pointer of the Storage to its first element
     *
     * @access public
     */
    public function rewind() {
        reset($this->_storage);
    }

    /**
     * Check if the Storage does not reach the end
     *
     * @access public
     * @return boolean
     */
    public function valid() {
        return (is_array(current($this->_storage)) ? TRUE : FALSE);
    }

    /**
     * Add Record to the Storage
     *
     * @access public
     * @param string $time
     * @param int $level
     * @param string $msg
     * @param string $file
     * @param int $line
     * @param string $checksum
     * @param int $status
     * @return boolean
     */
    public function add($time, $level, $msg, $file, $line, $checksum, $n = 1) {
        //convert filepath to unix style format
        $file = str_replace('\\', '/', $file);
        
        //check if file is still exist
        if (!$this->fileWithinSystem($file)) {
            return FALSE;
        }
        //get module info and return if not found
        $module = Router::call('Factory.getModuleInfo', $file);
        if (!$module){
            return FALSE;
        }
        $message = $this->formatMessage($msg);
        $relpath = str_replace(Router::call('Factory.absPath'), '', $file);
        $modpath = str_replace($module['path'], '', $file);
        $hash = $this->getErrorHash(
                $relpath, $module['version'], $level, $message, $line
        );

        if (isset($this->_storage[$hash])) {
            $this->_storage[$hash]['occured']++;
            //Important!! In case the last occured error has different checksum.
            //The last file checksum will be taken in consideration during the
            //check
            $this->_storage[$hash]['checksum'] = $checksum;
        } else {
            $this->_storage[$hash] = array(
                'level' => $level,
                'type' => $this->getErrorType($level),
                'message' => $message,
                'relpath' => $relpath, //Path from System Root
                'syspath' => $file, //Real File Path
                'modpath' => $modpath, //Path from Module Root
                'checksum' => $checksum,
                'line' => $line,
                'last' => $time,
                'module' => $module['name'],
                'version' => $module['version'],
                'occured' => $n,
                'status' => AHM_ERROR_STATUS_NEW
            );
        }

        return TRUE;
    }

    /**
     * Make sure that file exists and is within the current system
     *
     * Sometimes the errors appeared in external libs, specified in include
     * path php config
     *
     * @param string $filepath
     * @return boolean
     */
    protected function fileWithinSystem($filepath) {
        if ((strpos($filepath, Router::call('Factory.absPath')) === 0)
                && file_exists($filepath)) {
            $result = TRUE;
        }else{
            $result = FALSE;
        }

        return $result;
    }

    /**
     * Compile Unique Error Hash ID
     *
     * @access protected
     * @param string $file
     * @param string $version
     * @param int $level
     * @param string $message
     * @param int $line
     * @return string
     */
    protected function getErrorHash($file, $version, $level, $message, $line) {
        return sha1($file . $version . $level . $message . $line);
    }

    /**
     * Format Error Message
     *
     * @access protected
     * @param string $msg
     * @return string
     */
    protected function formatMessage($msg) {
        //replace path with short one
        $message = str_replace(Router::call('Factory.absPath'), '', $msg);
        $message = str_replace(Router::call('Factory.baseURL'), '', $message);

        //strip HTML tags if reference present
        $message = strip_tags($message);

        //make Win path compatible to Linux path
        $message = str_replace('\\', '/', $message);

        return trim($message);
    }

    /**
     * Remove Record from the Storage based on provided hash
     *
     * @access public
     * @param string $hash
     * @return boolean
     */
    public function remove($hash) {
        $result = FALSE;
        if (isset($this->_storage[$hash])) {
            unset($this->_storage[$hash]);
            $result = TRUE;
        }

        return $result;
    }

    /**
     * Update Record
     *
     * @access public
     * @param string $hash
     * @param string|array $key
     * @param string $value
     * @return boolean
     */
    public function update($hash, $key, $value = '') {
        $result = FALSE;
        if (isset($this->_storage[$hash])) {
            if (is_array($key)) {
                foreach ($key as $field => $data) {
                    $this->_storage[$hash][$field] = $data;
                }
            } else {
                $this->_storage[$hash][$key] = $value;
            }
            $result = TRUE;
        }

        return $result;
    }

    /**
     * Based on Level # get the error type (Error, Warning, Notice)
     *
     * @access protected
     * @param int $level
     * @return string
     */
    protected function getErrorType($level) {
        if (in_array($level, $this->_errorType)) {
            $type = 'Error';
        } elseif (in_array($level, $this->_warningType)) {
            $type = 'Warning';
        } else {
            $type = 'Notice';
        }

        return $type;
    }

}