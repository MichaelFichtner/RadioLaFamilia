<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: pif_seo_functions.php
| Version: Pimped Fusion v0.10.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

// SEO Url Character Replacements
$url_replace = array(
	// Characters Replacements
	// You can add Characters here
	'Ä' => 'ae',
	'ä' => 'ae',
	'Á' => 'a',
	'á' => 'a',
	'À' => 'a',
	'à' => 'a',
	'Â' => 'a',
	'â' => 'a',
	'Ą' => 'a',
	'ą' => 'a',
	'Ć' => 'c',
	'ć' => 'c',
	'Č' => 'c',
	'č' => 'c',
	'Ď' => 'd',
	'ď' => 'd',
	'É' => 'e',
	'é' => 'e',
	'È' => 'e',
	'è' => 'e',
	'Ě' => 'e',
	'ě' => 'e',
	'Ę' => 'e',
	'ę' => 'e',
	'Í' => 'i',
	'í' => 'i',
	'Ł' => 'l',
	'ł' => 'l',
	'Ń' => 'n',
	'ń' => 'n',
	'Ň' => 'n',
	'ň' => 'n',
	'Ó' => 'o',
	'ó' => 'o',
	'Ò' => 'o',
	'ò' => 'o',
	'Ö' => 'oe',
	'ö' => 'oe',
	'Ř' => 'r',
	'ř' => 'r',
	'Ś' => 's',
	'ś' => 's',
	'Š' => 's',
	'š' => 's',
	'ß' => 'ss',
	'Ť' => 't',
	'ť' => 't',
	'Ü' => 'ue',
	'ü' => 'ue',
	'Ú' => 'u',
	'ů' => 'u',
	'ú' => 'u',
	'Ý' => 'y',
	'ý' => 'y',
	'Ž' => 'z',
	'Ź' => 'z',
	'Ż' => 'z',
	'ź' => 'z',
	'ż' => 'z',
	'ž' => 'z',
	'&amp;' => 'and',
	'€' => 'euro',
	'RE:-' => '' #end
	);

$seo_url_search  = array_keys($url_replace);
$seo_url_replace = array_values($url_replace);

// Cleanes the Title in Urls for url-rewrite
function clean_subject_urlrewrite($subject) {
	global $seo_url_search, $seo_url_replace;
	
	$subject = str_replace($seo_url_search, $seo_url_replace, $subject);
	$subject = preg_replace("/[^\d\w]+/", "-", $subject);
	$subject = trim($subject, "-");
	$subject = strtolower($subject);
return $subject;
}

// Gives url
function make_url($url_normal, $url_rewritten_part1, $title="", $url_rewritten_part2="") {
	if(URL_REWRITE) {
		$url = $url_rewritten_part1.clean_subject_urlrewrite($title).$url_rewritten_part2;
	} else {
		$url = $url_normal;
	}
return $url;
}

?>