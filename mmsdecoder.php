<?php
// Version 0.81 //

/**
 * Copyright (C) 2004-2009 Jonatan Heyman
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


if (!defined("DEBUG"))
   define( "DEBUG", 0 );	/* Print parseerrors? Print values while they are parsed? If you enable this, 
	   	    		   getting the binary encoded confirmation message whensending MMS from mobiles 
				   will not work. This is only for development purpose. */


/*---------------------------------------------------*
 * Constants                                         *
 *                                                   *
 * http://wapforum.org/                              *
 * WAP-209-MMSEncapsulation-20020105-a               *
 * Table 8                                           *
 *                                                   *
 * The values are enconded using WSP 7bit encoding.  *
 * Read more about how to decode this here:          *
 * http://www.nowsms.com/discus/messages/12/3287.html*
 *                                                   *
 * Example from the above adress:                    *
 * 7Bit 0D =  0001101                                *
 * 8Bit 0D = 10001101 = 8D                           *
 *---------------------------------------------------*/
define( "BCC",			0x81 );
define( "CC",			0x82 );
define( "CONTENT_LOCATION",	0x83 );
define( "CONTENT_TYPE",		0x84 );
define( "DATE",			0x85 );
define( "DELIVERY_REPORT",	0x86 );
define( "DELIVERY_TIME",	0x87 );
define( "EXPIRY",		0x88 );
define( "FROM",			0x89 );
define( "MESSAGE_CLASS",	0x8A );
define( "MESSAGE_ID",		0x8B );
define( "MESSAGE_TYPE",		0x8C );
define( "MMS_VERSION",		0x8D );
define( "MESSAGE_SIZE",		0x8E );
define( "PRIORITY",		0x8F );
define( "READ_REPLY",		0x90 );
define( "REPORT_ALLOWED",	0x91 );
define( "RESPONSE_STATUS",	0x92 );
define( "RESPONSE_TEXT",	0x93 );
define( "SENDER_VISIBILITY",	0x94 );
define( "STATUS",		0x95 );
define( "SUBJECT",		0x96 );
define( "TO",			0x97 );
define( "TRANSACTION_ID",	0x98 );

define( "FROM_ADDRESS_PRESENT_TOKEN", 0x80);
define( "FROM_INSERT_ADDRESS_TOKEN",  0x81);


/*--------------------------*
 * Array of header contents *
 *--------------------------*/
$mmsMessageTypes = array (
	0x80 => "m-send-req",
	0x81 => "m-send-conf",
	0x82 => "m-notification-ind",
	0x83 => "m-notifyresp-ind",
	0x84 => "m-retrieve-conf",
	0x85 => "m-acknowledge-ind",
	0x86 => "m-delivery-ind",
	0x00 => NULL
);

/*--------------------------*
 * Some other useful arrays *
 *--------------------------*/
$mmsYesNo = array(
	0x80 => 1,
	0x81 => 0,
	0x00 => NULL
);

$mmsPriority = array(
	0x80 => "Low",
	0x81 => "Normal",
	0x82 => "High",
	0x00 => NULL
);

$mmsMessageClass = array(
	0x80 => "Personal",
	0x81 => "Advertisement",
	0x82 => "Informational",
	0x83 => "Auto"
);

$mmsContentTypes = array(
	0x00 => '*/*',
	0x01 => 'text/*',
	0x02 => 'text/html',
	0x03 => 'text/plain',
	0x04 => 'text/x-hdml',
	0x05 => 'text/x-ttml',
	0x06 => 'text/x-vCalendar',
	0x07 => 'text/x-vCard',
	0x08 => 'text/vnd.wap.wml',
	0x09 => 'text/vnd.wap.wmlscript',
	0x0A => 'text/vnd.wap.wta-event',
	0x0B => 'multipart/*',
	0x0C => 'multipart/mixed',
	0x0D => 'multipart/form-data',
	0x0E => 'multipart/byterantes',
	0x0F => 'multipart/alternative',
	0x10 => 'application/*',
	0x11 => 'application/java-vm',
	0x12 => 'application/x-www-form-urlencoded',
	0x13 => 'application/x-hdmlc',
	0x14 => 'application/vnd.wap.wmlc',
	0x15 => 'application/vnd.wap.wmlscriptc',
	0x16 => 'application/vnd.wap.wta-eventc',
	0x17 => 'application/vnd.wap.uaprof',
	0x18 => 'application/vnd.wap.wtls-ca-certificate',
	0x19 => 'application/vnd.wap.wtls-user-certificate',
	0x1A => 'application/x-x509-ca-cert',
	0x1B => 'application/x-x509-user-cert',
	0x1C => 'image/*',
	0x1D => 'image/gif',
	0x1E => 'image/jpeg',
	0x1F => 'image/tiff',
	0x20 => 'image/png',
	0x21 => 'image/vnd.wap.wbmp',
	0x22 => 'application/vnd.wap.multipart.*',
	0x23 => 'application/vnd.wap.multipart.mixed',
	0x24 => 'application/vnd.wap.multipart.form-data',
	0x25 => 'application/vnd.wap.multipart.byteranges',
	0x26 => 'application/vnd.wap.multipart.alternative',
	0x27 => 'application/xml',
	0x28 => 'text/xml',
	0x29 => 'application/vnd.wap.wbxml',
	0x2A => 'application/x-x968-cross-cert',
	0x2B => 'application/x-x968-ca-cert',
	0x2C => 'application/x-x968-user-cert',
	0x2D => 'text/vnd.wap.si',
	0x2E => 'application/vnd.wap.sic',
	0x2F => 'text/vnd.wap.sl',
	0x30 => 'application/vnd.wap.slc',
	0x31 => 'text/vnd.wap.co',
	0x32 => 'application/vnd.wap.coc',
	0x33 => 'application/vnd.wap.multipart.related',
	0x34 => 'application/vnd.wap.sia',
	0x35 => 'text/vnd.wap.connectivity-xml',
	0x36 => 'application/vnd.wap.connectivity-wbxml',
	0x37 => 'application/pkcs7-mime',
	0x38 => 'application/vnd.wap.hashed-certificate',
	0x39 => 'application/vnd.wap.signed-certificate',
	0x3A => 'application/vnd.wap.cert-response',
	0x3B => 'application/xhtml+xml',
	0x3C => 'application/wml+xml',
	0x3D => 'text/css',
	0x3E => 'application/vnd.wap.mms-message',
	0x3F => 'application/vnd.wap.rollover-certificate',
	0x40 => 'application/vnd.wap.locc+wbxml',
	0x41 => 'application/vnd.wap.loc+xml',
	0x42 => 'application/vnd.syncml.dm+wbxml',
	0x43 => 'application/vnd.syncml.dm+xml',
	0x44 => 'application/vnd.syncml.notification',
	0x45 => 'application/vnd.wap.xhtml+xml',
	0x46 => 'application/vnd.wv.csp.cir',
	0x47 => 'application/vnd.oma.dd+xml',
	0x48 => 'application/vnd.oma.drm.message',
	0x49 => 'application/vnd.oma.drm.content',
	0x4A => 'application/vnd.oma.drm.rights+xml',
	0x4B => 'application/vnd.oma.drm.rights+wbxml'
);

// character set (mibenum numbers by IANA, ored with 0x80)
$mmsCharSet = array(0xEA => 'utf-8',
		    0x83 => 'ASCII', // ascii
		    0x84 => 'iso-8859-1',
		    0x85 => 'iso-8859-2',
		    0x86 => 'iso-8859-3',
		    0x87 => 'iso-8859-4');


/*-------------------------------*
 * The MMS header decoding class *
 *-------------------------------*/
class MMSDecoder {
	var $data;	// The unparsed MMS data in an array of the ascii numbers
	var $pos = 0;	// The current parsing position of the data array
	var $PARTS = array();
	
	// The parsed data will be saved in these variables
	var 
		$BCC, 
		$CC, 
		$CONTENTLOCATION, 
		$CONTENTTYPE, 
		$DATE, 
		$DELIVERYREPORT, 
		$DELIVERYTIME, 
		$EXPIRY, 
		$FROM, 
		$MESSAGECLASS, 
		$MESSAGEID, 
		$MESSAGETYPE, 
		$MMSVERSIONMAJOR, 
		$MMSVERSIONMINOR,
		$MESSAGESIZE, 
		$PRIORITY, 
		$READREPLY, 
		$REPORTALLOWED, 
		$RESPONSESTATUS, 
		$RESPONSETEXT, 
		$SENDERVISIBILITY, 
		$STATUS, 
		$SUBJECT, 
		$TO, 
		$TRANSACTIONID, 
		$MMSVERSIONRAW, 	// used for the m-send-conf (confirmation answer)
		$CONTENTTYPE_PARAMS;	// parameter-values for the MMS content-type
	
	
	
	// Constructor
	function MMSDecoder($data) {
		$this->data = array();
		
		// Save the data in an array containing the ascii numbers
		for ($i = 0; $i < strlen($data); $i++)
			$this->data[$i] = ord($data[$i]);
		
		// Reset position
		$this->pos = 0;
		
		// Reset variables
		$this->PARTS = array();
	}
	
	
	// This function is called when the data is to be parsed
	function parse() {
		// Reset position
		$this->pos = 0;
		
		// parse the header
		while ($this->parseHeader());
		
		// Header done, fetch parts, but make sure the header was parsed correctly
		if ($this->CONTENTTYPE == 'application/vnd.wap.multipart.related' || $this->CONTENTTYPE == 'application/vnd.wap.multipart.mixed')
			while ($this->parseParts());
		else
			return 0;
		
		return 1;
	}
	
	
	/*---------------------------------------------------*
	 * This function checks what kind of field is to be  *
	 * parsed at the moment                              *
	 *                                                   *
	 * If true is returned, the class will go on and     *
	 * and continue decode the header. If false, the     *
	 * class will end the header decoding.               *
	 *---------------------------------------------------*/
	function parseHeader() {
		// Some global variables used
		global $mmsMessageTypes, $mmsYesNo, $mmsPriority, $mmsMessageClass, $mmsContentTypes;
		
		if (!array_key_exists($this->pos, $this->data))
			return 0;
		
		switch ($this->data[$this->pos++]) {
			case BCC:
				$this->BCC = $this->parseEncodedStringValue();
				if (DEBUG) $this->debug("BCC", $this->BCC);
				break;
			case CC:
				$this->CC = $this->parseEncodedStringValue();
				if (DEBUG) $this->debug("CC", $this->CC);
				break;
			case CONTENT_LOCATION:
				$this->CONTENTLOCATION = $this->parseTextString();
				if (DEBUG) $this->debug("Content-location", $this->CONTENTLOCATION);
				break;
			case CONTENT_TYPE:
				if ($this->data[$this->pos] <= 31) { /* Content-general-form */
					$len = $this->parseValueLength();
					
					// check if next byte is in range of 32-127. Then we have a Extension-media which is a textstring
					if ($this->data[$this->pos] > 31 && $this->data[$this->pos] < 128)
						$this->CONTENTTYPE = $this->parseTextString();
					else {
						// we have Well-known-media; which is an integer
						$this->CONTENTTYPE = $mmsContentTypes[$this->parseIntegerValue()];
					}
				} elseif ($this->data[$this->pos] < 128) { /* Constrained-media - Extension-media*/
					$this->CONTENTTYPE = $this->parseTextString();
				} else /* Constrained-media - Short Integer*/
					$this->CONTENTTYPE = $mmsContentTypes[$this->parseShortInteger()];
				
				// Ok, now we have parsed the content-type of the message, let's see if there are any parameters
				$noparams = false;
				while (!$noparams) {
					switch ($this->data[$this->pos]) {
						case 0x89: // Start, textstring
							$this->pos++;
							$this->parseTextString();
							break;
						case 0x8A: // type, constrained media
							$this->pos++;
							if ($this->data[$this->pos] < 128) { /* Constrained-media - Extension-media*/
								$this->pos++;
								$this->parseTextString();
							} else // Constraind-media Short Integer
								$this->CONTENTTYPE_PARAMS[type] = $this->parseShortInteger();   
							break;
						default:
							$noparams = 1;
							break;
					}
				}
				
				if (DEBUG) $this->debug("Content-type", $this->CONTENTTYPE);
				
				// content-type parsed, that means we have reached the end of the header
				return 0;
				
			case DATE: /* In seconds from 1970-01-01 00:00 GMT */
				$this->DATE = $this->parseLongInteger();
				if (DEBUG) $this->debug("Date", date("Y-m-d H:i:s", $this->DATE));
				break;
			case DELIVERY_REPORT:		/* Yes | No */
				$this->DELIVERYREPORT = $mmsYesNo[ $this->data[$this->pos++] ];
				if (DEBUG) $this->debug("Delivery-report", $this->DELIVERYREPORT);
				break;
			case DELIVERY_TIME:
				if (DEBUG) $this->debug("Delivery-time", $this->DELIVERYTIME);
				break;
			case EXPIRY:
				// not sure if this is right, but if I remeber right, it's the same format as date...
				$this->EXPIRY = $this->parseLongInteger();
				if (DEBUG) $this->debug("Expiry", $this->EXPIRY);
				break;
			case FROM:
				/**
				 * The encoding mechanism works like this:
				 *  From-value = [VALUE-length] [0x80 or 0x81] [Optional: Encoded-String-Value]
				 * If we have 0x80 we have an encoded-string-value (0x80 is not part of that string).
				 * If we have 0x81 this is a insert-adress-token which means that the MMSC is supposed
				 * to insert it, which means that we can't retrieve the sender.
				 */
				
				$this->FROM = $this->parseFromValue();
				if (DEBUG) $this->debug("From", $this->FROM);
				break;
			case MESSAGE_CLASS:
				$this->MESSAGECLASS = $mmsMessageClass[ $this->parseMessageClassValue() ];
				if (DEBUG) $this->debug("Message-class", $this->MESSAGECLASS);
				break;
			case MESSAGE_ID:		/* Text string */
				$this->MESSAGEID = $this->parseTextString();
				if (DEBUG) $this->debug("Message-id", $this->MESSAGEID);
				break;
			case MESSAGE_TYPE:
				//$this->MESSAGETYPE = $mmsMessageTypes[ $this->data[$this->pos++] ];
				$this->MESSAGETYPE = $this->data[$this->pos++];
				
				// check that the message type is m-send-req
				if ($this->MESSAGETYPE != 128)
					$this->debug("Wrong type", "The message-type field is not 'm-send-req' (Octet 128)", 1);
				
				if (DEBUG) $this->debug("Message-type", $mmsMessageTypes[$this->MESSAGETYPE]);
				break;
			case MMS_VERSION:
				/**
				 * The version number (1.0) is encoded as a WSP short integer, which
				 * is a 7 bit value. 
				 * 
				 * The three most significant bits (001) are used to encode a major
				 * version number in the range 1-7. The four least significant
				 * bits (0000) contain a minor version number in the range 1-14. 
				 */
				$this->MMSVERSIONRAW = $this->data[$this->pos];
				$this->MMSVERSIONMAJOR = ($this->data[$this->pos] & 0x70) >> 4;
				$this->MMSVERSIONMINOR = ($this->data[$this->pos++] & 0x0F);
				
				if (DEBUG) $this->debug("MMS-version", $this->MMSVERSIONMAJOR . "." . $this->MMSVERSIONMINOR);
				break;
			case MESSAGE_SIZE:		/* Long integer */
				$this->MESSAGESIZE = $this->parseLongInteger();
				if (DEBUG) $this->debug("Message-size", $this->MESSAGESIZE);
				break;
			case PRIORITY:			/* Low | Normal | High */
				$this->PRIORITY = $mmsPriority[ $this->data[$this->pos++] ];
				if (DEBUG) $this->debug("Priority", $this->PRIORITY);
				break;
			case READ_REPLY:		/* Yes | No */
				$this->READREPLY = $mmsYesNo[ $this->data[$this->pos++] ];
				if (DEBUG) $this->debug("Read-reply", $this->READREPLY);
				break;
			case REPORT_ALLOWED:		/* Yes | No */
				$this->REPORTALLOWED = $mmsYesNo[ $this->data[$this->pos++] ];
				if (DEBUG) $this->debug("Report-allowed", $this->REPORTALLOWED);
				break;
			case RESPONSE_STATUS:
				$this->RESPONSESTATUS = $this->data[$this->pos++];
				if (DEBUG) $this->debug("Response-status", $this->RESPONSESTATUS);
				break;
			case RESPONSE_TEXT:		/* Encoded string value */
				$this->RESPONSETEXT = $this->parseEncodedStringValue();
				if (DEBUG) $this->debug("Response-text", $this->RESPONSETEXT);
				break;
			case SENDER_VISIBILITY:		/* Hide | show */
				$this->SENDERVISIBILITY = $mmsYesNo[ $this->data[$this->pos++] ];
				if (DEBUG) $this->debug("Sender-visibility", $this->SENDERVISIBILITY);
				break;
			case STATUS:
				$this->STATUS = $this->data[$this->pos++];
				if (DEBUG) $this->debug("Status", $this->STATUS);
				break;
			case SUBJECT:
				$this->SUBJECT = $this->parseEncodedStringValue();
				if (DEBUG) $this->debug("Subject", $this->SUBJECT);
				break;
			case TO:
				$this->TO = $this->parseEncodedStringValue();
				if (DEBUG) $this->debug("To", $this->TO);
				break;
			case TRANSACTION_ID:
				$this->TRANSACTIONID = $this->parseTextString();
				if (DEBUG) $this->debug("Transaction-id", $this->TRANSACTIONID);
				break;
			default:
				if ($this->data[$this->pos - 1] > 127) {
					$this->debug("Parse error", "Unknown field (" . $this->data[$pos-1] . ")!", $this->pos-1);
					$this->debughex($this->pos - 1, 10, $this->pos - 1, 1);
				} else {
					$this->debug("Parse error:", "Value encountered when expecting field!", $this->pos);
					$this->debughex($this->pos - 1, 10, $this->pos - 1, 1);
				}
				break;
		}
		
		return true;
	}
	
	/*---------------------------------------------------------------------*
	 * Function called after header has been parsed. This function fetches *
	 * the different parts in the MMS. Returns true until it encounter end *
	 * of data.                                                            *
	 *---------------------------------------------------------------------*/
	function parseParts() {
		global $mmsContentTypes; // for parsing the contenttypes
		
		if (!array_key_exists($this->pos, $this->data))
			return 0;
		
		// get number of parts
		$count = $this->parseUint();
		
		if (DEBUG) $this->debug("MMS parts", $count);
		
		for ($i = 0; $i < $count; $i++) {
			// new part, so clear the old data and header
			$data = "";
			$header = "";
			unset($ctype);
			
			// get header and data length
			$headerlen = $this->parseUint();
			$datalen = $this->parseUint();
			
			
			/* PARSE CONTENT-TYPE */
			// this is actually the same structure as in the MMS content-type
			// so maybe we should make this in a better way, but for now, I'll
			// just cut n paste
			
			// right now I just save the position in the MMS data array before I parse
			// the content-type, to be able to roll back after it has been parsed beacause
			// the headerlen includes both the content-type and the header
			// TODO: this is just a fast hack and shoul be done in a more proper way
			$ctypepos = $this->pos;
			
			if ($this->data[$this->pos] <= 31) { /* Content-general-form */
				// the value follows after the current byte and is "current byte" long
				$len = $this->parseValueLength();
				
				// check if next byte is in range of 32-127. Then we have a Extension-media which is a textstring
				if ($this->data[$this->pos] > 31 && $this->data[$this->pos] < 128)
					$ctype = $this->parseTextString();
				else {
					// we have Well-known-media; which is an integer
					$ctype = $mmsContentTypes[$this->parseIntegerValue()];
				}
			} elseif ($this->data[$this->pos] < 128) { /* Constrained-media - Extension-media*/
				//$this->pos++;
				$ctype = $this->parseTextString();
			} else /* Constrained-media - Short Integer */
				$ctype = $mmsContentTypes[$this->parseShortInteger()];
			
			// roll back position so it's just before the content-type again
			$this->pos = $ctypepos;
			/* END OF CONTENT TYPE */
			
			
			// Read header. Actually, we don't do anything with this yet.. just skipping it (note that the content-type is included in the header)
			for ($j = 0; $j < $headerlen; $j++)
				$header .= chr($this->data[$this->pos++]);
			
			// read data
			for ($j = 0; $j < $datalen; $j++)
				$data .= chr($this->data[$this->pos++]);
			
			if (DEBUG) $this->debug("Part ($i):headerlen", $headerlen);
			if (DEBUG) $this->debug("Part ($i):datalen", $datalen);
			if (DEBUG) $this->debug("Part ($i):content-type", $ctype);
			//if (DEBUG) $this->debug("Part ($i):data", $data); // I've commented this one, to get a cleaner debug
			
			$this->PARTS[] = new MMSPart($headerlen, $datalen, $ctype, $header, $data);
		}
		
		return false;
	}
	
	/*-------------------------------------------------------------------------------------------------*
	 * Parse From-value                                                                                *
	 * From-value = Value-length (Address-present-token Encoded-string-value | Insert-address-token )  *
	 *                                                                                                 *
	 * Address-present-token = <Octet 128>                                                             *
	 * Insert-address-token = <Octet 129>                                                              *
	 *-------------------------------------------------------------------------------------------------*/
	function parseFromValue() {
		$len = $this->parseValueLength();
		
		if ($this->data[$this->pos] == FROM_ADDRESS_PRESENT_TOKEN) {
		   	if (DEBUG) $this->debug("parseFromValue", "Address-present-token found", $this->pos);
		   	$this->pos++;
			return $this->parseEncodedStringValue();
		} else if ($this->data[$this->pos] == FROM_INSERT_ADDRESS_TOKEN) {
		        if (DEBUG) $this->debug("parseFromValue", "Insert-address-token found", $this->pos);
		       	$this->pos++;
		        return "";
		} else {
		        // something is wrong since none of the tokens are present, try to skip this field
			if (DEBUG) $this->debug("parseFromValue", "No from token found, trying to skip the value field by jumping " . $len . " bytes", $this->pos);
			$this->pos += $len;
		}
	}
	
	/*-------------------------------------------------------------------*
	 * Parse message-class                                               *
	 * message-class-value = Class-identifier | Token-text               *
	 * Class-idetifier = Personal | Advertisement | Informational | Auto *
	 *-------------------------------------------------------------------*/
	function parseMessageClassValue() {
		if ($this->data[$this->pos] > 127) {
			// the byte is one of these 128=personal, 129=advertisement, 130=informational, 131=auto
			return $this->data[$this->pos++];
		} else
			return $this->parseTextString();
	}
	
	/*----------------------------------------------------------------*
	 * Parse Text-string                                              *
	 * text-string = [Quote <Octet 127>] text [End-string <Octet 00>] *
	 *----------------------------------------------------------------*/
	function parseTextString() {
		$str = "";
		
		// Remove quote
		if ($this->data[$this->pos] == 0x7F)
			$this->pos++;
		
		while ($this->data[$this->pos])
			$str .= chr($this->data[$this->pos++]);
		
		$this->pos++;
		
		return $str;
	}
	
	
	/*------------------------------------------------------------------------*
	 * Parse Encoded-string-value                                             *
	 *                                                                        *
	 * Encoded-string-value = Text-string | Value-length Char-set Text-string *
	 *                                                                        *
	 *------------------------------------------------------------------------*/
	function parseEncodedStringValue() {
		global $mmsCharSet;
		
		if ($this->data[$this->pos] <= 31) {
			$len = $this->parseValueLength();
			
			$mibenum = $this->data[$this->pos++];
			
			// handle unknown charsets
			if (isset($mmsCharSet[$mibenum]))
				$charset = $mmsCharSet[$mibenum];
			else
				$charset = '';
			
			$raw = $this->parseTextString();
			
			// the only case we can handle currently is utf8 since character encoding support
			// in native PHP is so lousy
			if ($charset == 'utf-8')
			    $raw = utf8_decode($raw);
			
			return $raw;
			
			//for ($i = 0; $i < $len-1; $i++)
			//	$str .= chr( $this->data[$this->pos++] );
			//return $str;
		} else
			return $this->parseTextString();
	}
	
	
	/*--------------------------------------------------------------------------------*
	 * Parse Value-length                                                             *
	 * Value-length = Short-length<Octet 0-30> | Length-quote<Octet 31> Length<Uint>  *
	 *                                                                                *
	 * A list of content-types of a MMS message can be found here:                    *
	 * http://www.wapforum.org/wina/wsp-content-type.htm                              *
	 *--------------------------------------------------------------------------------*/
	function parseValueLength() {
		if ($this->data[$this->pos] < 31) {
			// it's a short-length
			return $this->data[$this->pos++];
		} elseif ($this->data[$this->pos] == 31) {
			// got the quote, length is an Uint
			$this->pos++;
			return $this->parseUint();
		} else {
			// uh, oh... houston, we got a problem
			die("Parse error: Short-length-octet (" . $this->data[$this->pos] . ") > 31 in Value-length at offset " . $this->pos . "!\n");
		}
	}
	
	
	/*--------------------------------------------------------------------------*
	 * Parse Long-integer                                                       *
	 * Long-integer = Short-length<Octet 0-30> Multi-octet-integer<1*30 Octets> *
	 *--------------------------------------------------------------------------*/
	function parseLongInteger() {
		// Get the number of octets which the long-integer is stored in
		$octetcount = $this->data[$this->pos++];
		
		// Error checking
		if ($octetcount > 30)
			die("Parse error: Short-length-octet (" . $this->data[$this->pos-1] . ") > 30 in Long-integer at offset " . $this->pos-1 . "!\n");
		
		// Get the long-integer
		$longint = 0;
		for ($i = 0; $i < $octetcount; $i++) {
			$longint = $longint << 8;
			$longint += $this->data[$this->pos++];
		}
		
		return $longint;
	}
	
	
	/*------------------------------------------------------------------------*
	 * Parse Short-integer                                                    *
	 * Short-integer = OCTET                                                  *
	 * Integers in range 0-127 shall be encoded as a one octet value with the *
	 * most significant bit set to one, and the value in the remaining 7 bits *
	 *------------------------------------------------------------------------*/
	function parseShortInteger() {
		return $this->data[$this->pos++] & 0x7F;
	}
	
	
	/*-------------------------------------------------------------*
	 * Parse Integer-value                                         *
	 * Integer-value = short-integer | long-integer                *
	 *                                                             *
	 * This function checks the value of the current byte and then *
	 * calls either parseLongInt() or parseShortInt() depending on *
	 * what value the current byte has                             *
	 *-------------------------------------------------------------*/
	function parseIntegerValue() {
		if ($this->data[$this->pos] < 31)
			return $this->parseLongInteger();
		elseif ($this->data[$this->pos] > 127)
			return $this->parseShortInteger();
		else {
			$this->debug('ERROR', 'Not a IntegerValue field', $this->pos);
			$this->pos++;
			return 0;
		}
	}
	
	
	/*------------------------------------------------------------------*
	 * Parse Unsigned-integer                                           *
	 *                                                                  *
	 * The value is stored in the 7 last bits. If the first bit is set, *
	 * then the value continues into the next byte.                     *
	 *                                                                  *
	 * http://www.nowsms.com/discus/messages/12/522.html                *
	 *------------------------------------------------------------------*/
	function parseUint() {
		//if (!($this->data[$this->pos] & 0x80))
		//	return $this->data[$this->pos++] & 0x7F;
		$uint = 0;
		
		while ($this->data[$this->pos] & 0x80) {
			// Shift the current value 7 steps
			$uint = $uint << 7;
			// Remove the first bit of the byte and add it to the current value
			$uint |= $this->data[$this->pos++] & 0x7F;
		}
		
		// Shift the current value 7 steps
		$uint = $uint << 7;
		// Remove the first bit of the byte and add it to the current value
		$uint |= $this->data[$this->pos++] & 0x7F;
		
		return $uint;
	}
	
	
	/**
	 * Send an OK response to the sender after the MMS has been recieved
	 * See "6.1.2. Send confirmation" in the wap-209-mmsencapsulation specification, on how this is constructed
	 */
	function confirm() {
		$pos = 0;
		
		$confirm[$pos++] = 0x8C; // message-type
		$confirm[$pos++] = 129;  // m-send-conf
		$confirm[$pos++] = 0x98; // transaction-id
		
		for ($i = 0; $i < strlen($this->TRANSACTIONID); $i++)
			$confirm[$pos++] = ord(substr($this->TRANSACTIONID, $i, 1));
		
		$confirm[$pos++] = 0x00; // end of string
		$confirm[$pos++] = 0x8D; // version
		$confirm[$pos++] = 0x90; // 1.0
		$confirm[$pos++] = 0x92; // response-status
		$confirm[$pos++] = 128;	 // OK
		
		$confirm[$pos++] = 0x8b; // Message-id
		
		// generate a message id based on the time
		$messageId = dechex(time());
		for ($i = 0; $i < strlen($messageId); $i++)
			$confirm[$pos++] = ord(substr($messageId, $i, 1));
		
		$confirm[$pos] = 0x00; // end of string
		
		// respond with the m-send-conf
		foreach ($confirm as $byte)
			echo chr($byte);
	}
	
	
	/*---------------------------------------*
	 * Function which outputs debug messages *
	 *---------------------------------------*/
	function debug($name, $str, $pos = -1, $errorlevel = 0) {
		if ($pos != -1)
			echo "<b>$name ($pos):</b> " . $str;
		else
			echo "<b>$name:</b> " . $str;
		
		echo "<br>\n";
		
		if ($errorlevel > 0)
			exit;
	}
	
	
	/*------------------------------------------*
	 * Function to output a part of the mmsdata *
	 * in HEX form, and mark one byte with a ^  *
	 *------------------------------------------*/
	function debughex($start, $count, $markstart = -1, $markcount = -1) {
		$hexcount = 0;
		$markstop = $markstart + $markcount;
		
		// set font so that the hex will be more readable
		echo '<br><font face="fixedsys" size="-1">';
		
		// loop thru data and print hex
		for ($i = $start; $i <= ($start+$count); $i++) {
			// fix marking
			if ($i == $markstart)
				echo '<font color="#ff0000">';
			if ($i == $markstop)
				echo '</font>';
			
			$hex = dechex($this->data[$i]);
			
			// add 0 before hex if needed
			if (strlen($hex) < 2)
				$hex = '0' . $hex;
			// add space
			$hex = ' ' . $hex;
			
			// check hexcount, wrap lines if needed etc
			if ($hexcount == 8)
				echo ' | ';
			elseif ($hexcount == 16) {
				echo '<br>';
				$hexcount = 0;
			}
			$hexcount++;
			
			echo $hex;
		}
		
		// som more html
		echo '</font><br><br>';
	}
}



/*---------------------------------------------------------------------*
 * The MMS part class                                                  *
 * An instance of this class contains the one parts of an MMS message. *
 *                                                                     *
 * The multipart type is formed as:                                    *
 * number |part1|part2|....|partN                                      *
 * where part# is formed by headerlen|datalen|contenttype|headers|data *
 *---------------------------------------------------------------------*/
class MMSPart {
	var $headerlen;
	var $header;
	var $DATALEN;
	var $CONTENTTYPE;
	var $DATA;
	
	/*----------------------------------*
	 * Constructor, just store the data *
	 *----------------------------------*/
	function MMSPart($headerlen, $datalen, $ctype, $header, $data) {
		$this->hpos = 0;
		$this->headerlen = $headerlen;
		$this->DATALEN = $datalen;
		$this->CONTENTTYPE = $ctype;
		$this->DATA = $data;
	}
	
	/*-------------------------------------*
	 * Save the data to a location on disk *
	 *-------------------------------------*/
	function save($filename) {
		$fp = fopen($filename, 'wb');
		fwrite($fp, $this->DATA);
		fclose($fp);
	}
}
?>