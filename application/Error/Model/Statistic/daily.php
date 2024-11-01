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
 * Error Daily Statistics Model
 *
 * @package WordPress\WP Bug Tracker\Error
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 02/25/2013
 */
class AHM_Error_Model_Statistic_Daily {

    /**
     * Single instance of itself
     *
     * Is very importand to have only single instance to
     * avoid mismatch in error list
     *
     * @access private
     * @var AHM_Error_Model_Statistic_General
     */
    private static $_instance = NULL;

    /**
     * Grouped stats
     *
     * @var array
     *
     * @access private
     */
    private $_stats = array();

    /**
     * Handle the request
     *
     * @access public
     * @return array
     */
    public function run() {
        $this->prepare();

        return $this->_stats;
    }

    /**
     * Prepare Statistic
     *
     * @access protected
     */
    protected function prepare() {
        $this->reset();
        foreach (Router::call('Error.errors') as  $date => $storage) {
            $time = strtotime($date) * 1000; //this is used for jQuery Plot
            foreach ($storage as $data) {
                $this->incStats($time, $data);
            }
        }
    }

    /**
     * Increment stat
     *
     * @param string $date
     * @param array $record
     *
     * @return void
     *
     * @access protected
     */
    protected function incStats($date, $record){
        switch($record['type']){
            case 'Error':
                $index = 0;
                break;

            case 'Warning':
                $index = 1;
                break;

            default:
                $index = 2;
                break;
        }
        //increment
        foreach($this->_stats[$index]['data'] as &$row){
            if ($row[0] == $date){
                $row[1]++;
                break;
            }
        }
    }

    /**
     * Reset stats
     *
     * @return void
     *
     * @access protected
     */
    protected function reset()
    {
         $this->_stats = array(
            array(
                'label' => Router::call('Factory.lang', 'Errors'),
                'data' => array()
            ),
            array(
                'label' => Router::call('Factory.lang', 'Warnings'),
                'data' => array()
            ),
            array(
                'label' => Router::call('Factory.lang', 'Notices'),
                'data' => array()
            )
        );
        //reset counters
        $start = strtotime('today -' . AHM_ERROR_DAYCOUNT . ' days');
        $end = time();
        while($start <= $end){
            $date = $start * 1000;
            $this->_stats[0]['data'][] = array($date, 0);
            $this->_stats[1]['data'][] = array($date, 0);
            $this->_stats[2]['data'][] = array($date, 0);
            $start += 86400; //one day forward
        }
    }

    /**
     * Get Single instance of itself
     *
     * @access public
     * @return AHM_Error_Model_Statistic_General
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

}