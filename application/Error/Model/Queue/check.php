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
 * Check Queue Model
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 05/16/2013
 */
class AHM_Error_Model_Queue_Check extends AHM_Error_Model_Queue_Abstract {

    /**
     * Constructor
     *
     * @access public
     * @param AHM_Core_Controller $ctrl
     */
    public function __construct() {
        $this->_file = AHM_ERROR_CACHEDIR . DIRECTORY_SEPARATOR . '_check';
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
            foreach ($storage as $hash => $info) {
                if (!isset($this->_queue[$hash])
                        && ($info['status'] == AHM_ERROR_STATUS_REPORTED)) {
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
            $result = Router::call(
                            'Service.check', $account, $error['report']
            );

            if ($result->status == 'success') {
                if (isset($result->patch)) {
                    $this->update($hash, array(
                        'status' => AHM_ERROR_STATUS_RESOLVED,
                        'patch' => $result->patch,
                        'price' => $result->price
                    ));
                } elseif (isset($result->reject)) {
                    $this->update($hash, array(
                        'status' => AHM_ERROR_STATUS_REJECTED,
                        'reason' => $result->reason
                    ));
                }
            } //ignore failed check. Run it again next time
        }

        return $this->getResult();
    }

}