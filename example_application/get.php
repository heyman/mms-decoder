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

// includes
require_once('../mmsdecoder.php');
require_once('config.php');
require_once('functions.php');


if ($HTTP_RAW_POST_DATA != "") {
	// check if the raw post data shall be saved
	if (SAVE_RAWDATA) {
		// save RAW data
		$data = $HTTP_RAW_POST_DATA;
		$filename = md5($data . time() . rand(1, 1000));
		
		$file = fopen($filename, 'wb');
		fwrite($file, $data);
		fclose($file);
		
		$info = print_r($_SERVER, true);
		$file = fopen($filename . "_info", 'wb');
		fwrite($file, $info);
		fclose($file);
	}
	
	// parse MMS
	$mms = new MMSDecoder($HTTP_RAW_POST_DATA);
	$mms->parse();
	
	// connect to database
	db_connect();
	
	// save mms and it's parts
	mms_save($mms);
	
	// close db connection
	db_close();
	
	// set header
	header('Content-type: application/vnd.wap.mms-message');
	
	// send confirmation response
	$mms->confirm();
}

?>