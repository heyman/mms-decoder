<?php
/**
 * If the URL to this script is used as the MMSC url, the MMS messages 
 * will be saved in the same directory with the unix timestamp as filenames. 
 *
 * Copyright (c) 2004 Jonatan Heyman
 */


require_once("../mmsdecoder.php");

// check that something has actually been sent to the script
if ($HTTP_RAW_POST_DATA != "") {
	// parse MMS
	$mms = new MMSDecoder($HTTP_RAW_POST_DATA);
	$mms->parse();
	
	// The MMS is parsed, so let's get the mms data from the class by the print_r() function,
	$mmsdata = print_r($mms, true);
	$filename = time();
	
	// make sure the file is writable
	if (is_writable($filename)) {
		// write mms data to file
		$file = fopen($filename, "w");
		fwrite($file, $mmsdata);
		fclose($file);
	}
	
	// send confirmation response
	header('Content-type: application/vnd.wap.mms-message');
	$mms->confirm();
} else
	echo "This script should be accessed by an MMS client, wich shoul send MMS data!";


?>