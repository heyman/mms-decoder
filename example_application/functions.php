<?php
/**
 * Copyright (C) 2004 Jonatan Heyman
 *
 * This file is part of MMS Decoder Example Application.
 * A collection of functions used by  the apllication.
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
 * It is safe to assume that the config.php file has already been included here
 */


// Save an MMS and its parts to the database
// Takes only a reference variable to a MMSDecode object as argument
function mms_save(&$mms) {
	// save MMS in database
	$sql = "
		INSERT INTO " . TABLE_MMS . " 
		(
			`from`, 
			`to`, 
			`subject`, 
			`content_type`
		) VALUES (
			'" . mysql_escape_string($mms->FROM) . "',
			'" . mysql_escape_string($mms->TO) . "',
			'" . mysql_escape_string($mms->SUBJECT) . "',
			'" . mysql_escape_string($mms->CONTENTTYPE) . "'
		)
	";
	//echo $sql;
	
	if (!mysql_query($sql))
		log_error('Database error: Could not insert MMS data into database. MySQL: ' . mysql_error());
	
	$mmsid = mysql_insert_id();
	
	// loop thru parts and save them in database
	foreach ($mms->PARTS as $part) {
		$result = mysql_query("INSERT INTO " . TABLE_PARTS . " (mmsid, datalen, content_type, data) VALUES ('$mmsid', '" . $part->DATALEN . "', '" . $part->CONTENTTYPE . "', '" . mysql_escape_string($part->DATA) . "')");
		if (!$result)
			log_error('Database error: Could not insert part data into database. MySQL: ' . mysql_error());
	}
}


function mms_list($ret = 0) {
	$sql = "
		SELECT
			" . TABLE_MMS . ".from,
			" . TABLE_MMS . ".to,
			" . TABLE_MMS . ".subject,
			" . TABLE_MMS . ".content_type as mms_content_type,
			" . TABLE_PARTS . ".id,
			" . TABLE_PARTS . ".mmsid,
			" . TABLE_PARTS . ".datalen,
			" . TABLE_PARTS . ".content_type
		FROM
			" . TABLE_MMS . ", " . TABLE_PARTS . "
		WHERE
			" . TABLE_PARTS . ".mmsid = " . TABLE_MMS . ".id
		ORDER BY
			" . TABLE_PARTS . ".mmsid DESC
	";
	
	$result = mysql_query($sql);
	
	$lastid = -1;
	$html = '<table border="1">';
	
	while ($rsdata = mysql_fetch_assoc($result)) {
		if ($lastid != $rsdata['mmsid']) {
			$html .= '
				<tr><td>
				Subject: ' . $rsdata['subject'] . '<br>
				From: ' . $rsdata['from'] . '<br>
				To: ' . $rsdata['to'] . '<br>
				Content-type: ' . $rsdata['mms_content_type'] . '
				<br><br>
			';
		}
		
		$html .= '<a href="dload.php?id=' . $rsdata['id'] . '">' . $rsdata['content_type'] . '</a> ' . $rsdata['datalen'] . ' bytes<br>';
		
		switch ($rsdata['content_type']) {
			case "image/gif":
			case "image/jpeg":
			case "image/png":
			case "image/tiff":
				$html .= '<img src="dload.php?id=' . $rsdata['id'] . '"><br><br>';
				break;
		}
		
		$lastid = $rsdata['mmsid'];
		
		if ($lastid != $rsdata['mmsid'])
			$html .= '</td></tr>';
	}
	
	$html .= '</td></tr></table>';
	
	if (!$ret)
		echo $html;
	else
		return $html;
}



/*
// Get the MMS and their parts from the database and genereate some HTML
function mms_list($ret = 0) {
	$sql = "
		SELECT
			" . TABLE_MMS . ".from,
			" . TABLE_MMS . ".to,
			" . TABLE_MMS . ".subject,
			" . TABLE_MMS . ".content_type as mms_content_type,
			" . TABLE_PARTS . ".id,
			" . TABLE_PARTS . ".mmsid,
			" . TABLE_PARTS . ".datalen,
			" . TABLE_PARTS . ".content_type
		FROM
			" . TABLE_MMS . ", " . TABLE_PARTS . "
		WHERE
			" . TABLE_PARTS . ".mmsid = " . TABLE_MMS . ".id
		ORDER BY
			" . TABLE_PARTS . ".mmsid DESC
	";
	
	$result = mysql_query($sql);
	
	$lastid = -1;
	$html = '<table border="1">';
	
	while ($rsdata = mysql_fetch_assoc($result)) {
		if ($lastid != $rsdata[mmsid]) {
			$html .= '
				<tr><td>
				Subject: ' . $rsdata[subject] . '<br>
				From: ' . $rsdata[from] . '<br>
				To: ' . $rsdata[to] . '<br>
				Content-type: ' . $rsdata[mms_content_type] . '
				<br><br>
			';
		}
		
		$html .= '<a href="dload.php?id=' . $rsdata[id] . '">' . $rsdata[content_type] . '</a> ' . $rsdata[datalen] . ' bytes<br>';
		
		// check if part-content-type is png, jpeg, tiff or gif.. then output picture
		if ($rsdata[content_type] == 'image/gif' || $rsdata[content_type] == 'image/jpeg' || $rsdata[content_type] == 'image/png' || $rsdata[content_type] == 'image/tiff')
			$html .= '<img src="dload.php?id=' . $rsdata[id] . '"><br><br>';
		
		$lastid = $rsdata[mmsid];
		
		if ($lastid != $rsdata[mmsid])
			$html .= '</td></tr>';
	}
	
	$html .= '</td></tr></table>';
	
	if (!$ret)
		echo $html;
	else
		return $html;
}
*/

function mms_part_dload($id) {
	$result = mysql_query("SELECT content_type, data FROM " . TABLE_PARTS . " WHERE id='$id'");
	
	if (!$result)
		echo 'Part not found!';
	else {
		$rsdata = mysql_fetch_assoc($result);
		header('Content-type: ' . $rsdata['content_type']);
		header('Content-Disposition: inline; filename="part"');
		echo $rsdata['data'];
	}
}


// log stuff so it doesn't need to be written out
function log_error($str) {
	echo $str . '<br>';
	$f = fopen("error.log", "w");
	fwrite($f, $str . "\n");
	fclose($f);
}


// connect to database
function db_connect() {
	// connect
	if (!mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)) {
		log('Could not connect to database!');
		return 0;
	}
	
	// select database
	if (!mysql_select_db(MYSQL_DB)) {
		log('Database ' . MYSQL_DB . ' not found!');
		return 0;
	}
	
	return 1;
}

// close database link
function db_close() {
	mysql_close();
}

?>