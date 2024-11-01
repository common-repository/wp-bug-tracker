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
 * Ajax action model
 *
 * @package WordPress\WP Bug Tracker\Ajax
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 09/14/2012
 */
class AHM_Ajax_Model_Action {
    /**
     * Ajax Response status SUCCESS
     */

    const STATUS_SUCCESS = 'success';

    /**
     * Ajax Response status FAILED
     */
    const STATUS_FAILED = 'failed';

    /**
     * Handle Ajax Request
     *
     * @return string JSON encoded response
     *
     * @access public
     */
    public function handle() {
        switch (AHM_Core_Request::request('sub_action')) {
            //Trigger different view. Initiated after user changed "Screen" drop-down
            case 'trigger_view':
                $response = Router::call(
                        'View.retrieveView',
                        AHM_Core_Request::request('view')
                );
                break;
            //clear filters for Error List
            case 'clear_filters':
                $dump = Router::call('Settings.ui');
                $dump['type'] = $dump['module'] = '';
                if (Router::call('Settings.ui', $dump)) {
                    $result = array('status' => self::STATUS_SUCCESS);
                } else {
                    $result = array('status' => self::STATUS_FAILED);
                }
                $response = json_encode($result);
                break;
            //Save UI Option. Initiated for control elements of GUI
            case 'save_option':
                $dump = Router::call('Settings.ui');
                $key = AHM_Core_Request::request('key');
                $dump[$key] = AHM_Core_Request::request('value');
                if (Router::call('Settings.ui', $dump)) {
                    $result = array('status' => self::STATUS_SUCCESS);
                } else {
                    $result = array('status' => self::STATUS_FAILED);
                }
                $response = json_encode($result);
                break;
            //get UI option
            case 'get_option':
                $dump = Router::call('Settings.ui');
                if (isset($dump[$_REQUEST['key']])) {
                    $result = array(
                        'status' => self::STATUS_SUCCESS,
                        'data' => $dump[$_REQUEST['key']]
                    );
                } else {
                    $result = array(
                        'status' => self::STATUS_SUCCESS,
                        'data' => ''
                    );
                }
                $response = json_encode($result);
                break;
            //Register new system. Send REST Request
            case 'register':
                $result = Router::call(
                              'Service.register',
                              Router::call('Factory.baseURL'),
                              Router::call('Factory.getEnvironment')
                );
                $ui = Router::call('Settings.ui');
                if ($result->status == 'success') {
                    Router::call('Settings.account', $result->instance);
                    $result = array('status' => self::STATUS_SUCCESS);
                    //update the setting that user is registered
                    $ui['registered'] = true;
                    Router::call('Settings.ui', $ui);
                } else {
                    $result = array('status' => self::STATUS_FAILED);
                    $ui['registered'] = false;
                    Router::call('Settings.ui', $ui);
                }
                $response = json_encode($result);
                break;
            //Analyze error logs before rendering
            case 'analyze':
                $queue = Router::call('Error.analyze');
                $result = $queue->run();
                $response = json_encode($result);
                break;
            //check for available solutions
            case 'check':
                $queue = Router::call('Error.checkQueue');
                $response = json_encode($queue->run());
                break;
            //apply patch
            case 'apply':
                $patcher = Router::call('Error.applyQueue');
                $response = json_encode($patcher->run());
                break;
            //Clear error logs and cache
            case 'clean':
                $res = Router::call('Error.clean');
                $response = json_encode(array(
                    'status' => ($res ? self::STATUS_SUCCESS : self::STATUS_FAILED)
                ));
                break;
            //send an email
            case 'send_email':
                $result = Router::call('Factory.sendEmail', array(
                    'fromName' => AHM_Core_Request::post('name'),
                    'fromEmail' => AHM_Core_Request::post('email'),
                    'message' => AHM_Core_Request::post('message')
                ));
                $response = json_encode(
                        array(
                            'status' => ($result ? self::STATUS_SUCCESS : self::STATUS_FAILED)
                        )
                );
                break;
            //check for number of available solutions
            case 'available_fixnum':
                $number = 0;
                foreach (Router::call('Error.errors') as $storage) {
                    foreach ($storage as $report) {
                        if ($report['status'] == AHM_ERROR_STATUS_RESOLVED) {
                            $number++;
                        }
                    }
                }
                $response = json_encode(array('number' => $number));
                break;

            //Oops! Sounds like somethings weird :)
            default:
                $response = json_encode(array('status' => self::STATUS_FAILED));
                break;
        }

        return $response;
    }

}