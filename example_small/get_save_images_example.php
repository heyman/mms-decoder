<?php

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


/**
 * Programmer: Jonatan Heyman <http://heyman.info>
 * 
 * Description: This is a simple example how you can retrieve an MMS and just
 * 		save the image parts as files (in the same directory as the script).
 */

// includes
require_once('../mmsdecoder.php');


if ($HTTP_RAW_POST_DATA != "") {
	// parse MMS
	$mms = new MMSDecoder($HTTP_RAW_POST_DATA);
	$mms->parse();
	
	// loop thru parts and save images as files on the server
	foreach ($mms->PARTS as $part) {
		switch ($part->CONTENTTYPE) {
			case "image/jpeg":
				$fileext = ".jpg";
				break;
			case "image/png":
				$fileext = ".png";
				break;
			case "image/gif":
				$fileext = ".gif";
				break;
			default:
				$nopic = true;
		}
		
		if (!$nopic) {
			// save data to file with the date and md5 hash of the data as filename
			$file = fopen(time() . "_" . md5($part->DATA) . $fileext, 'wb');
			fwrite($file, $part->DATA);
			fclose($file);
		}	
	}
	
	// set header
	header('Content-type: application/vnd.wap.mms-message');
	
	// send confirmation response
	$mms->confirm();
}

?>