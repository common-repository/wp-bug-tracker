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
 * View Controller
 *
 * @package WordPress\WP Bug Tracker\View
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 06/15/2012
 */
class AHM_View_Controller {

    /**
     * Render Headers to HTML
     *
     * @access public
     * @param string $type
     * @return string
     */
    public function header($type) {
        $model = AHM_View_Model_Header::getInstance($type);

        return $model->handle();
    }

    /**
     * Retrieve View based on $view
     *
     * @access public
     * @param type $view
     * @return type
     */
    public function retrieveView($view) {
        $className = 'AHM_View_Model_View_' . ucfirst($view);
        $model = new $className();

        return $model->handle();
    }

}