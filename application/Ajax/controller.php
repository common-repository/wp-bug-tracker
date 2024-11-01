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
 * Ajax Controller
 *
 * @package WordPress\WP Bug Tracker\Ajax
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 09/14/2012
 */
class AHM_Ajax_Controller {

    /**
     * Process Ajax Request
     *
     * @return string JSON encoded result
     *
     * @access public
     */
    public function process() {
        Router::call('Factory.checkAjaxReferer'); //block access if not authorized
        $model = new AHM_Ajax_Model_Action;

        return $model->handle();
    }

}