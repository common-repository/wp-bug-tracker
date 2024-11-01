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

define('AHM_ERROR_STORAGE', 'file');
define('AHM_ERROR_LOGSIZE', 1048576); //1MB per Day
define('AHM_ERROR_SCANLIMIT', 5000);
define('AHM_ERROR_DAYCOUNT', 14); //number of days to take in consideration
define('AHM_ERROR_DATEFORMAT', 'Y-m-d');
define('AHM_ERROR_QUEUELIMIT', 10); //number of reports to send per request
define('AHM_ERROR_STATUS_NEW', 'new');
define('AHM_ERROR_STATUS_REPORTED', 'reported');
define('AHM_ERROR_STATUS_INACTIVE', 'inactive');
define('AHM_ERROR_STATUS_FAILED', 'failed');
define('AHM_ERROR_STATUS_RESOLVED', 'resolved');
define('AHM_ERROR_STATUS_REJECTED', 'rejected');
define('AHM_ERROR_STATUS_FIXED', 'fixed');

return AHM_Error_Controller::getInstance();