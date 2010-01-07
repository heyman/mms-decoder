<?php

/**
 * Copyright (C) 2004 Jonatan Heyman
 *
 * This file is part of MMS Decoder Example Application.
 * Recieve an MMS from the client.
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

if (isset($_POST['create'])) {
	// connect to the database
	if (!mysql_connect($_POST['host'], $_POST['user'], $_POST['pass']))
		die("Could not connct to database, check host/user/pass settings!");
	
	// check if we shall create a new database
	if ($_POST['create'] == "create") {
		if (!function_exists("mysql_create_db"))
			die("The mysql_create_db() function does not exist in your PHP installation! Please create the database by hand, and chose the 'Use existing DB' option in the installation script.");
		if (!mysql_create_db($_POST['name']))
			die("Database could not be created: " . $_POST['name']);
	}
	
	// select database
	if (!mysql_select_db($_POST['name']))
		die("Could not select db: " . $_POST['name']);
	
	/* mms table */
	$sql = "
		CREATE TABLE `mms` (
		  `id` int(10) NOT NULL auto_increment,
		  `from` char(255) NOT NULL default '',
		  `to` char(255) NOT NULL default '',
		  `subject` char(255) NOT NULL default '',
		  `content_type` char(255) NOT NULL default '',
		  PRIMARY KEY  (`id`)
		) TYPE=MyISAM AUTO_INCREMENT=28 ;
	";
	
	if (!mysql_query($sql))
		print("Could not create table: mms. MySQL: " . mysql_error() . "<br>\n");
	
	
	/* parts table */
	$sql = "
		CREATE TABLE `parts` (
		  `id` int(10) NOT NULL auto_increment,
		  `mmsid` int(10) NOT NULL default '0',
		  `datalen` int(12) NOT NULL default '0',
		  `content_type` varchar(255) NOT NULL default '',
		  `data` blob NOT NULL,
		  PRIMARY KEY  (`id`)
		) TYPE=MyISAM AUTO_INCREMENT=33 ;
	";
	
	if (!mysql_query($sql))
		print("Could not create table: parts. MySQL: " . mysql_error() . "<br>\n");
	
	echo "
		Installation complete!<br><br>
		Please set the right configuration in the config.php file to get the application to work. <br>
		I recommend that you remove the install.php from any direcory where it is accessible over 
		over HTTP without a password. 
	";
} else {
?>


<html>
<head>
	<title>MMS Decoder Example Application installation script</title>
</head>
<body>
	This is an installation script for the MMS Decoder Example application. The purpose of this script 
	is to save people the hassle to create the database/tables by hand. <br>
	See the readme file of the example application, for a more detailed description on how to install the application.
	<br><br>
	<form action="install.php" method="POST">
	<table border="0">
		<tr>
			<td>MySQL host</td>
			<td><input type="text" name="host"></td>
		</tr>
		<tr>
			<td>MySQL username</td>
			<td><input type="text" name="user"></td>
		</tr>
		<tr>
			<td>MySQL password</td>
			<td><input type="password" name="pass"></td>
		</tr>
		<tr>
			<td>Database name</td>
			<td><input type="text" name="name"></td>
		</tr>
		<tr>
			<td>Create DB or use existing?</td>
			<td>
				<select name="create">
					<option value="create" selected>Create DB</option>
					<option value="existing">Use existing DB</option>
				</select>
			</td>
		</tr>
	</table>
	<br><br>
	<input type="submit" value="Create database/tables">
	</form>
</body>
</html>


<?php
}

?>