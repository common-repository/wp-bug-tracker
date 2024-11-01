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
 * Class for Error List Model
 *
 * @package WordPress\WP Bug Tracker\View
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 06/29/2012
 */
class AHM_View_Model_View_List {

    /**
     * Prepare Formated Error List
     *
     * @access public
     * @return array
     */
    public function handle() {
        $aaData =  array();
        foreach (Router::call('Error.errors') as $storage) {
            foreach ($storage as $hash => $info) {
                if (isset($aaData[$hash])) {
                    $aaData[$hash][1][0] = $info['last'];
                    $aaData[$hash][1][1] += $info['occured'];
                } else {
                    $aaData[$hash] = array(
                        $this->formatDetails($info),
                        array($info['last'], $info['occured']),
                        ucfirst($info['status']),
                        $info['type'],
                        $info['module'],
                        (isset($info['patch']) ? $info['patch'] : ''),
                        'DT_RowClass' => strtolower($info['type']),
                        'DT_RowId' => $hash
                    );
                }
            }
        }
        sort($aaData);

        return json_encode(
                        array(
                            'aaData' => $aaData,
                            'aaStat' => Router::call('Error.statistics')
                        )
        );
    }
    
    /**
     * Format Error Details
     *
     * @access protected
     * @param array $info
     * @return string
     */
    protected function formatDetails($info) {
        $html = '<span class="ahm-message">' . ($info['message']) . '</span>';
        $html .= '<br/><span class="ahm-file">' . $info['relpath'] . '</span>';
        $html .= '<span class="ahm-line">(' . $info['line'] . ')</span>';

        return $html;
    }

}