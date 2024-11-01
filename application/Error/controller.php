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
 * Error Controller
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 06/15/2012
 */
class AHM_Error_Controller {

    /**
     * Single Instance of itself
     *
     * @var AHM_Error_Controller
     *
     * @access private
     */
    private static $_instance = NULL;
    
    /**
     * Initialize Error Handling
     *
     * @access public
     * 
     * @return void
     */
    public function init() {
        set_error_handler(array($this, 'handler'));
        set_exception_handler(array($this, 'exception'));
        register_shutdown_function(array($this, 'shutdownHandler'));
    }
    
    /**
     * Initialize the file structure for bug tracker
     * 
     * @return void
     * 
     * @access public
     */
    public function filebase() {
        if (!defined('AHM_ERROR_LOGDIR')) {
            //define module constants
            $sep = DIRECTORY_SEPARATOR;
            define(
                    'AHM_ERROR_LOGDIR', 
                    Router::call('Factory.contentDir') . $sep . 'ahmlog'
            );
            define('AHM_ERROR_CACHEDIR', AHM_ERROR_LOGDIR . $sep . '_cache');
            define('AHM_BACKUP_DIR', AHM_ERROR_LOGDIR . $sep . '_backup');
            //create log storage folder with necessary htaccess file
            if (!file_exists(AHM_ERROR_LOGDIR)) {
                if (mkdir(AHM_ERROR_LOGDIR)) {
                    //create htaccess file
                    file_put_contents(
                            AHM_ERROR_LOGDIR . $sep . '.htaccess', 
                            'IndexIgnore *'
                    );
                } else {
                    trigger_error('Unable to create Log directory');
                }
            }
            //create cache directory
            if (!file_exists(AHM_ERROR_CACHEDIR)) {
                //make cache directory
                if (!mkdir(AHM_ERROR_CACHEDIR)) {
                    trigger_error('Unable to create cache directory');
                }
            }
            //create backup directory
            if (!file_exists(AHM_BACKUP_DIR)) {
                //make cache directory
                if (!mkdir(AHM_BACKUP_DIR)) {
                    trigger_error('Unable to create backup directory');
                }
            }
        }
    }

    /**
     * Get Single Instance of itself
     *
     * @access public
     * @return AHM_Error_Controller
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Get Error List
     *
     * @access public
     * @return array
     */
    public function errors() {
        return AHM_Error_Model_List::getInstance()->getList();
    }
    
    /**
     * Return total number of errors
     * 
     * This function is used for Admin Menu notification
     * 
     * @return int
     * 
     * @access public
     */
    public function errorCount(){
        return AHM_Error_Model_List::getInstance()->count();
    }

    /**
     * Analyze the list of logs. Prepare cache
     *
     * @return string JSON encoded result
     *
     * @access public
     */
    public function analyze(){
        return new AHM_Error_Model_Queue_Analyze();
    }

    /**
     * Clean the log directory
     *
     * @return boolean
     */
    public function clean(){
        return AHM_Error_Model_Parser::getInstance()->clean();
    }

    /**
     * Get Statistic information
     *
     * @param string $type
     *
     * @return array
     *
     * @access puclic
     */
    public function statistics($type = 'general'){
        $className = 'AHM_Error_Model_Statistic_' . ucfirst($type);

        return call_user_func(array($className, 'getInstance'))->run();
    }

    /**
     * Handler Error and Log it to file
     *
     * @param string $no
     * @param string $msg
     * @param string $file
     * @param string $line
     *
     * @access public
     */
    public function handler($no, $msg, $file, $line) {
        static $model;
        
        if (error_reporting() & $no){
            if (is_null($model)) {
                $model = new AHM_Error_Model_Handler();
            }
            $model->handle($no, $msg, $file, $line);
        }
    }

    /**
     * Get Report queue
     *
     * @return \AHM_Error_Model_Queue_Report
     *
     * @access public
     */
    public function reportQueue(){
        return new AHM_Error_Model_Queue_Report();
    }

    /**
     * Get Check queue
     *
     * @return \AHM_Error_Model_Queue_Check
     *
     * @access public
     */
    public function checkQueue(){
        return new AHM_Error_Model_Queue_Check();
    }
    
    /**
     * Actuall Patcher
     * 
     * @return \AHM_Error_Model_Patcher
     * 
     * @access public
     */
    public function applyQueue(){
        return new AHM_Error_Model_Queue_Apply();
    }

    /**
     * Exception handler
     *
     * @param Exception $e
     *
     * @access public
     */
    public function exception($e) {
        $msg = 'Uncatched exception ' . get_class($e) . ': ' . $e->getMessage();
        $this->handler(E_ERROR, $msg, $e->getFile(), $e->getLine());
    }

    /**
     * Handle Shut Down Process
     *
     * @access public
     */
    public function shutdownHandler() {
        if ($e = error_get_last()) {
            if (in_array($e['type'], array(E_ERROR, E_USER_ERROR))) {
                $this->handler(
                        $e['type'], $e['message'], $e['file'], $e['line']
                );
            }
        }
    }

}