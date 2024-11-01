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
 * Class for Pie Graph View
 *
 * @package WordPress\WP Bug Tracker\View
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 02/25/2013
 */
class AHM_View_Model_View_Pie {

    /**
     * The impact value on the system for Notice
     */
    const NOTICE_IMPACT = 1;

    /**
     * The impact value on the system for Warning
     */
    const WARNING_IMPACT = 6;

    /**
     * The impact value on the system for Error
     */
    const ERROR_IMPACT = 18;

    /**
     * Prepare Formated Error List
     *
     * @access public
     * @return array
     */
    public function handle() {
        //calculate the impact value
        $total = 0;
        $data = array();

        foreach (Router::call('Error.statistics', 'plugin') as $plugin => $info) {
            $value  = $info[2] * self::NOTICE_IMPACT;
            $value += $info[1] * self::WARNING_IMPACT;
            $value += $info[0] * self::ERROR_IMPACT;
            $total += $value;
            $data[] = array(
                'label' => $plugin,
                'data' => $value
            );
        }

        foreach($data as &$row){
            $row['data'] = round(($row['data'] / $total) * 100, 2);
        }


        @header('Content-Type: application/json');

        return json_encode(array(
                    'status' => 'success',
                    'total' => Router::call('Error.errorCount'),
                    'data' => $data)
        );
    }

}