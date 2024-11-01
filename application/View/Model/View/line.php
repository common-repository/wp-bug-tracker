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
 * Class for Line Graph View
 *
 * @package WordPress\WP Bug Tracker\View
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 02/25/2013
 */
class AHM_View_Model_View_Line {

    /**
     * Prepare Formated Error List
     *
     * @access public
     * @return array
     */
    public function handle() {
        $data = Router::call('Error.statistics', 'daily');
        //set colors
        $data[0]['color'] = '#CB4B4B'; //error
        $data[1]['color'] = '#EDC240'; //warning
        $data[2]['color'] = '#AFD8F8'; //notice

        @header('Content-Type: application/json');

        return json_encode(array(
                    'status' => 'success',
                    'total' => Router::call('Error.errorCount'),
                    'data' => $data)
        );
    }

}