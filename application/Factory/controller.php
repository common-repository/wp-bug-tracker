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
 * Factory Controller
 *
 * @package WordPress\WP Bug Tracker\Factory
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 06/15/2012
 */
class AHM_Factory_Controller {

    /**
     * Current Factory to work with
     *
     * @access private
     * @var AHM_Factory_Model
     */
    private $_factory = NULL;

    /**
     * Call Factory function
     *
     * @access public
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments) {
        if (is_null($this->_factory)){
            $className = 'AHM_Factory_Model_' . AHM_FACTORY_FACTORY;
            $this->_factory = new $className;
        }

        return call_user_func_array(array($this->_factory, $name), $arguments);
    }

}