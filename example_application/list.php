<?php
/**
 * Copyright (C) 2004 Jonatan Heyman
 *
 * This file is part of MMS Decoder Example Application.
 * List the MMS and their parts.
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

require_once('config.php');
require_once('functions.php');


echo '
	<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>MMS list</title>
	</head>
	<body>
	This is MMS Decoder Example Application running. <br>
	To send an MMS to this site, setup your phone MMSC aka. MMS Center/MMS Server/MMS Service Center to <b>http://' . $_SERVER["HTTP_HOST"] . substr($_SERVER["PHP_SELF"], 0, strlen($_SERVER["PHP_SELF"]) - 8) . 'get.php</b> and send an MMS (the number doesn\'t matter). <br><br>
	
	To visit the homepage or download the sourcecode:<br>
		<li><a href="http://heyman.info/">MMS Decoder Homepage</a>
	
	<br><br>
	
	Below is a list of the MMS-messages in the database.
	<hr>
	<br>
';

// generation time
$start = microtime(true);

db_connect();
mms_list();
db_close();

$time = microtime(true) - $start;

echo '
	<hr>
	<i>MMS Decoder Example Aplication 0.78. MMS list generated in ' . $time . ' seconds.</i>
	</body>
	</html>
';

?>