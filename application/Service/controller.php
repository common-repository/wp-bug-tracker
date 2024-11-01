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
 * Service Controller
 *
 * @package WordPress\WP Bug Tracker\Service
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 06/15/2012
 */
class AHM_Service_Controller {

    /**
     * Current Service to work with
     *
     * @access protected
     * @var AHM_Service_Model_Interface
     */
    protected $_service = NULL;

    /**
     * @inheritdoc
     */
    public function __construct() {
        $this->_service = new AHM_Service_Model_Rest($this);
    }

    /**
     * Handle service call
     *
     * @access public
     * @param string $name
     * @param array $arguments
     * @return array
     */
    public function __call($name, $arguments) {
        return call_user_func_array(array($this->_service, $name), $arguments);
    }

}