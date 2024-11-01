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

//define global constants
//get URL to public files
$rel_path = str_replace(
        Router::call('Factory.absPath'), 
        '', 
        str_replace('\\', '/', dirname(__FILE__))
);
$base_url = Router::call('Factory.baseURL') . $rel_path;

define('AHM_VIEW_JSURL', $base_url . '/View/js');
define('AHM_VIEW_CSSURL', $base_url . '/View/css');
define('AHM_VIEW_TMPLDIR', realpath(dirname(__FILE__) . '/View/tmpl'));

return new AHM_View_Controller;