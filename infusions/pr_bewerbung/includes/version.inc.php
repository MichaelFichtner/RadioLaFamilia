<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: includes/version.inc.php
| pr_Bewerbungsscript v2.00
| Author: PrugnatoR
| URL: http://www.prugnator.de
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

// Inf Version
$version = "2.00";
$url_h = "http://www.prugnator.de";
$url = "http://www.prugnator.de/vchecker/bewerbung.txt";

$version_rc = "2.00 RC6";

// Version Check
if(isset($pr_bew_admin)){
	function version() {
		global $url;
	
		$url_p = @parse_url($url);
		$host = $url_p['host'];
		$port = isset($url_p['port']) ? $url_p['port'] : 80;
		$fp = @fsockopen($url_p['host'], $port, $errno, $errstr, 5);
		if (!$fp) return false;
		@fputs($fp, 'GET '.$url_p['path'].' HTTP/1.1'.chr(10));
		@fputs($fp, 'HOST: '.$url_p['host'].chr(10));
		@fputs($fp, 'Connection: close'.chr(10).chr(10));
		$response = @fgets($fp, 1024);
		$content = @fread($fp,1024);
		$content = preg_replace("#(.*?)text/plain(.*?)$#is","$2",$content);
		@fclose ($fp);
		if (preg_match("#404#",$response)) return false;
		else return trim($content);
	}
	
	$ausgabe="";
	if (function_exists('fsockopen')) {
		global $version;
		$version_new = version();
		if ($version_new > $version_rc || !preg_match('#RC#', $version_new)) {
			$ausgabe .= "<table>\n<tr>\n<td><img src=\"".INFUSIONS."pr_bewerbung/admin/images/version_old.gif\" /></td>\n<td>";
		  $ausgabe .= "<span style=\"font-weight: bold; color: red;\">".$locale['pr_ver02'].$version_rc."</span><br />";
		  $ausgabe .= "<span style=\"font-weight: bold; color: #1bdc16;\">".$locale['pr_ver03'].$version_new."</span><br />";
		  $ausgabe .= "<span style=\"font-weight: bold; \">".$locale['pr_ver04']."</span><a href=\"".$url_h."\" target=\"_blank\" title=\"".$url_h."\"><span style=\"font-weight: bold; \">".$url_h."</span></a>";
		  $ausgabe .= "</td>\n</tr>\n</table>\n";
		} else {
			$ausgabe .= "<table>\n<tr>\n<td><img src=\"".INFUSIONS."pr_bewerbung/admin/images/version.gif\" /></td>\n";
			$ausgabe .= "<td><span style=\"font-weight: bold; color: #1bdc16;\">".$locale['pr_ver01'];
			$ausgabe .= $version_rc."</span></td>\n</tr>\n</table>\n";
		}
	} else {
		$ausgabe .= "<br />".$locale['pr_ver05']."<br />";
		$ausgabe .= $locale['pr_ver06']."<a href=\"".$url_h."\" target=\"_blank\" title=\"".$url_h."\"><span style=\"font-weight: bold; \">".$url_h."</span></a><br /><br />";
	}
	
	opentable("Version Check");
	echo $ausgabe;
	closetable();
}


// Copyright Function
// It isn't allowed to remove or edit this Copyright
// Es ist nicht erlaubt dieses Copyright zu entfernen oder zu aendern
	function render_copy($show_version=false){
	global $version;
		if($show_version == false){
			echo "<br /><div align='right'>Code &copy; by <a href='http://www.prugnator.de' target='_blank'>PrugnatoR</a></div>"; 	
		}else{
			echo "<br /><div align='right'><small>Bewerbungsscript v".$version."</small><br />Code &copy; by <a href='http://www.prugnator.de' target='_blank'>PrugnatoR</a></div>"; 	
		} 
	}

?>