<?php
// Version 0.80 development //

/**
 * Copyright (C) 2004 Jonatan Heyman
 *
 * This file is part of the PHP application MMS Decoder.
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

// load mms decoder class
require_once("../mmsdecoder.php");

// turn on debugging
define("DEBUG", 1);

?>

<html>
<head>
	<title>MMS Decoder - Debug tool, file upload</title>
</head>
<body>

This is a tool created to help in development of MMS decoding. Just upload a file
containing the raw MMS data and the results will be printed.

<br><br>

<form action="mmsfile.php" method="POST" enctype="multipart/form-data">
	File <input type='file' name='mmsfile'>
	<input type='submit' value='Upload and decode'>
</form>
<br><br>


<?php

if (isset($_FILES["mmsfile"])) {
	$mms = new MMSDecoder(file_get_contents($_FILES["mmsfile"]["tmp_name"]));
	$mms->parse();
}

?>

</body>
</html>
	