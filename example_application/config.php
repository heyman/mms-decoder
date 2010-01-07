<?php

/**
 * Copyright (C) 2004 Jonatan Heyman
 *
 * This file is part of MMS Decoder Example Application.
 * Configuration file
 *
 * MMS Decoder is free software; you can redistribute it and/or
 * modify it under the terms of the Affero General Public License as
 * published by Affero, Inc.; either version 1 of the License, or
 * (at your option) any later version.
 *
 * MMS Decoder is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * Affero General Public License for more details.
 *
 * You should have received a copy of the Affero General Public
 * License in the COPYING file that comes with The Affero Project; if
 * not, write to Affero, Inc., 510 Third Street, Suite 225, San
 * Francisco, CA 94107 USA. 
 */


// MySQL settings
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', '');		// please note that it is a seurity risk to run and PHP scripts as 'root'
define('MYSQL_PASS', '');
define('MYSQL_DB', 'mms');

// Database table settings
define('TABLE_MMS', 'mms');
define('TABLE_PARTS', 'parts');

define('SAVE_RAWDATA', 0);	// if the raw post data shall be saved in files




?>
