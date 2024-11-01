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
 * Class for Patch List Model
 *
 * @package WordPress\WP Bug Tracker\View
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 07/04/2013
 */
class AHM_View_Model_View_Patches {

    /**
     * Prepare Formated Error List
     *
     * @access public
     * @return array
     */
    public function handle() {
        $aaData = array();
        foreach (Router::call('Error.errors') as $storage) {
            foreach ($storage as $report) {
                if ($report['status'] == AHM_ERROR_STATUS_RESOLVED) {
                    $aaData[$report['patch']] = array(
                        $report['patch'],
                        sprintf('ID%04s', $report['patch']),
                        '',
                        'DT_RowId' => 'patch_' . $report['patch']
                    );
                }
            }
        }
        sort($aaData);

        return json_encode(array('aaData' => $aaData));
    }

}