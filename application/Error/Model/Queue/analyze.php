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
 * Analyze Log Queue Model
 *
 * This is very important part of error grouping feature. In case of error logs
 * which have megs of information, this will divide the analyzing process on
 * phases. The set_time_limit is pretty ugly solution and that is why it turned
 * to more complex way of analyzing.
 * The idea is simple - analyze 1000 (configurable value) lines per request till
 * all logs will have caches object inside the Error/_files folder
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 07/10/2012
 */
class AHM_Error_Model_Queue_Analyze extends AHM_Error_Model_Queue_Abstract {

    /**
     * Constructor
     *
     * @access public
     * @param AHM_Core_Controller $ctrl
     */
    public function __construct() {
        $this->_file = AHM_ERROR_CACHEDIR . DIRECTORY_SEPARATOR . '_analyze';
        parent::__construct();
    }

    /**
     * Get Complete Error List and create a well-formated queue
     *
     * @access protected
     */
    protected function createQueue() {
        //prepare required settings
        $this->clear();
        $start_date = date(
                AHM_ERROR_DATEFORMAT,
                strtotime('today -' . AHM_ERROR_DAYCOUNT . ' days')
        );

        foreach (scandir(AHM_ERROR_LOGDIR) as $file) {
            $filename = realpath(AHM_ERROR_LOGDIR . '/' . $file);
            if (is_file($filename)
                    && preg_match('/^[\d\-]+$/', $file)
                    && ($file >= $start_date)
            ) { //valid file && within date range
                $storage = $this->getStorage($file);
                if ($storage->getComplete() === false
                        || ($file == date(AHM_ERROR_DATEFORMAT))
                ) {
                    $this->_queue[] = $storage;
                }
            } elseif (is_file($filename)) { //remove file, no need to keep it
                unlink($filename);
            }
        }
    }

    /**
     * Get Storage
     *
     * @return AHM_Error_Model_Storage
     *
     * @access public
     */
    protected function getStorage($date = null) {
        $className = 'AHM_Error_Model_Storage_' . ucfirst(AHM_ERROR_STORAGE);
        if ($date) {
            $cache = AHM_ERROR_CACHEDIR . '/' . $date;
            if (file_exists($cache)) {
                $storage = unserialize(file_get_contents($cache));
                if (!($storage instanceof $className)) {
                    $storage = new $className;
                }
            } else {
                $storage = new $className;
            }
            $storage->setDate($date);
        } else {
            $storage = new $className;
        }

        return $storage;
    }

    /**
     * Run Queue
     *
     * @access public
     * @return array
     */
    public function run($limit = AHM_ERROR_QUEUELIMIT) {
        $parser = AHM_Error_Model_Parser::getInstance();
        //scan one storage per request
        if ($this->valid()) {
            $parser->run(current($this->_queue));
            if (current($this->_queue)->getComplete()) {
                array_shift($this->_queue);
            }
        }

        return $this->getResult();
    }

    /**
     * Check if the Queue does not reach the end
     *
     * @access public
     * @return boolean
     */
    public function valid() {
        return (current($this->_queue) ? TRUE : FALSE);
    }

}