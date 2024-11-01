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
 * Class for View Index Model
 *
 * @package WordPress\WP Bug Tracker\View
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 06/29/2012
 */
class AHM_View_Model_View_Index {

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct() {
        //keep all output in buffer
        ob_start();
    }

    /**
     * Default Handler
     *
     * @access protected
     * @param string $template
     * @return string
     */
    public function handle() {
        require_once(realpath(AHM_VIEW_TMPLDIR . '/index.phtml'));
        $content = ob_get_contents();
        ob_clean();

        return $content;
    }

}