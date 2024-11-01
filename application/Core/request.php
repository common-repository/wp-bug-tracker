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
 * Request model
 *
 * @package WordPress\WP Bug Tracker\Core
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @since 03/07/2013
 */
class AHM_Core_Request
{

    /**
     * Get parameter from global _GET array
     *
     * @param string $param   GET Parameter
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @access public
     */
    public static function get($param = null, $default = null)
    {
        return self::readArray($_GET, $param, $default);
    }

    /**
     * Get parameter from global _POST array
     *
     * @param string $param   POST Parameter
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @access public
     */
    public static function post($param = null, $default = null)
    {
        return self::readArray($_POST, $param, $default);
    }

    /**
     * Get parameter from global _REQUEST array
     *
     * @param string $param   REQUEST Parameter
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @access public
     * @static
     */
    public static function request($param = null, $default = null)
    {
        return self::readArray($_REQUEST, $param, $default);
    }

    /**
     * Get parameter from global _SERVER array
     *
     * @param string $param   SERVER Parameter
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @access public
     * @static
     */
    public static function server($param = null, $default = null)
    {
        return self::readArray($_SERVER, $param, $default);
    }

     /**
     * Get parameter from global _FILE array
     *
     * @param string $param   FILE Parameter
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @access public
     * @static
     */
    public static function file($param = null, $default = null)
    {
        return self::readArray($_FILES, $param, $default);
    }

    /**
     * Check array for specified parameter and return the it's value or default one
     *
     * @param array  &$array  Global array _GET, _POST etc
     * @param string $param   Array Parameter
     * @param mixed  $default Default value
     *
     * @return mixed
     *
     * @access protected
     * @static
     */
    protected static function readArray(&$array, $param, $default)
    {
        $result = $default;
        if (is_null($param)) {
            $result = $array;
        } elseif (isset($array[$param])) {
            $result = $array[$param];
        }

        return $result;
    }

}