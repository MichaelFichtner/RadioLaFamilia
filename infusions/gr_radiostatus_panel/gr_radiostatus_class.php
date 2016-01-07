<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Radiostatus v1.0 for PHP-Fusion 7
| Filename: gr_radiostatus_admin.php
| Author: Ralf Thieme  
| Webseite: www.granade.eu
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

class SHOUTcast {
	var $SHOUTcastData;
	var $error;
	
	function GetStatus($ip, $port, $pw) {
		error_reporting(0);
		$fp = fsockopen($ip, $port, $errno, $errstr, 1);
		if (!$fp) {
			error_reporting(E_ALL);
			$this->error = "$errstr ($errno)";
			return(0);
		} else {
			error_reporting(E_ALL);
			socket_set_timeout($fp, 2);
			fputs($fp, "GET /stats?sid=1?pass=".$pw."&mode=viewxml HTTP/1.1\r\n"); //  original --> admin.cgi?pass
			fputs($fp, "User-Agent: Mozilla\r\n\r\n");
			while (!feof($fp)) {
				$this->SHOUTcastData .= fgets($fp, 512);
			}
			fclose($fp);
			if (stristr($this->SHOUTcastData, "HTTP/1.1 200 OK") == true) {
			
				$this->SHOUTcastData = trim(substr($this->SHOUTcastData, 58));
			} else {
				$this->error = "Bad login";
				return(0);
			}
			$xmlparser = xml_parser_create('UTF-8');
			//xml_parse_into_struct($xmlparser, $this->SHOUTcastData, $this->values, $this->indexes);
			
			
			
			if (!xml_parse_into_struct($xmlparser, $this->SHOUTcastData, $this->values, $this->indexes)) {
				$this->error = "Unparsable XML";
				return(0);
			}
			
			xml_parser_free($this->values);
			return(1);
		}
	}
	
	function GetCurrentListeners() {
		return($this->values[$this->indexes["CURRENTLISTENERS"][0]]["value"]);
	}

	function GetPeakListeners() {
		return($this->values[$this->indexes["PEAKLISTENERS"][0]]["value"]);
	}

	function GetMaxListeners() {
		return($this->values[$this->indexes["MAXLISTENERS"][0]]["value"]);
	}

	function GetServerGenre() {
		return($this->values[$this->indexes["SERVERGENRE"][0]]["value"]);
	}
	
	function GetServerURL() {
		return($this->values[$this->indexes["SERVERURL"][0]]["value"]);
	}
	
	function GetServerTitle() {
		return($this->values[$this->indexes["SERVERTITLE"][0]]["value"]);
	}
	
	function GetCurrentSongTitle() {
	
		return($this->values[$this->indexes["SONGTITLE"][0]]["value"]);
	}
	
	function GetIRC() {
		return($this->values[$this->indexes["IRC"][0]]["value"]);
	}
	
	function GetAIM() {
		return($this->values[$this->indexes["AIM"][0]]["value"]);
	}
	
	function GetICQ() {
		return($this->values[$this->indexes["ICQ"][0]]["value"]);
	}

	function GetStreamStatus() {
		return($this->values[$this->indexes["STREAMSTATUS"][0]]["value"]);
	}
	
	function GetBitRate() {
		return($this->values[$this->indexes["BITRATE"][0]]["value"]);
	}
	
	function GetSongHistory() {
		for($i=1;$i<sizeof($this->indexes['TITLE']);$i++) {
			$arrhistory[$i-1] = array(
				"playedat"=>$this->values[$this->indexes['PLAYEDAT'][$i]]['value'],
				"title"=>$this->values[$this->indexes['TITLE'][$i]]['value']
			);
		}
		return($arrhistory);
	}

	function GetError() { return($this->error); }
}
?>