<?php

/*
  Copyright (C) <2012>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

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

//require the Router
require_once(realpath(dirname(__FILE__) . '/router.php'));

/**
 * Autoloader
 *
 * @param string $class_name
 */
function ahm_autoload($class_name){
    $chunk = explode('_', $class_name);
    if (array_shift($chunk) == 'AHM'){
        $chunk[count($chunk) - 1] = strtolower(end($chunk));
        $filename = dirname(__FILE__) . '/../' . implode('/', $chunk) . '.php';
        require_once(realpath($filename));
    }
}
//register autoload function
spl_autoload_register('ahm_autoload');

//require handler class. It is not included automatically
require_once(realpath(dirname(__FILE__) . '/../Error/Model/handler.php'));