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
 * Report Queue Model
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 07/10/2012
 */
class AHM_Error_Model_Queue_Report extends AHM_Error_Model_Queue_Abstract {

    /**
     * Constructor
     *
     * @access public
     * @param AHM_Core_Controller $ctrl
     */
    public function __construct() {
        $this->_file = AHM_ERROR_CACHEDIR . DIRECTORY_SEPARATOR . '_report';
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
        foreach (Router::call('Error.errors') as $storage) {
            //list of allowed statuses to include in queue
            $allowed = array(
                AHM_ERROR_STATUS_NEW, //obviously new should be here
                AHM_ERROR_STATUS_FAILED //in case it failed last time
            );
            foreach ($storage as $hash => $info) {
                if (!isset($this->_queue[$hash])
                        && in_array($info['status'], $allowed)) {
                    $this->_queue[$hash] = $info;
                }
            }
        }
    }

    /**
     * Run Queue
     *
     * @var int $limit
     *
     * @return array
     *
     * @access public
     */
    public function run($limit = AHM_ERROR_QUEUELIMIT) {
        $account = Router::call('Settings.account');
        for ($i = 1; ($i <= $limit) && $this->valid(); $i++) {
            $hash = $this->key();
            $error = $this->next();
            switch ($error['status']) {
                case AHM_ERROR_STATUS_NEW:
                case AHM_ERROR_STATUS_FAILED:
                    $result = Router::call(
                                    'Service.report', 
                                    $account, 
                                    $error['module'], 
                                    $error['modpath'], 
                                    $error['version'], 
                                    $error['checksum'], 
                                    $error['level'], 
                                    $error['message'], 
                                    $error['line']
                    );
                    if ($result->status == 'success') {
                        $this->update($hash, array(
                            'status' => AHM_ERROR_STATUS_REPORTED,
                            'report' => $result->reportID
                        ));
                    } else {
                        $this->update($hash, array(
                            'status' => AHM_ERROR_STATUS_FAILED
                        ));
                    }
                    break;

                default:
                    break;
            }
        }

        return $this->getResult();
    }

}