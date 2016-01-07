<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: functions.php
| Author: SiteMaster, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

// Convert html to ascii
function html2ascii($text) {
	$search = array(
		" & ", "&lt;", "&gt;", "&nbsp;", "&iexcl;", "&cent;", "&pound;", "&curren;", "&yen;", "&brvbar;", "&sect;", "&uml;", "&copy;", "&ordf;", "&laquo;", "&not;", "&shy;", "&reg;", "&macr;", "&deg;", "&plusmn;", "&sup2;", "&sup3;", "&acute;", "&micro;", "&para;", "&middot;", "&cedil;", "&sup1;", "&ordm;", "&raquo;", "&frac14;", "&frac12;", "&frac34;", "&iquest;", "&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;", "&Auml;", "&Aring;", "&AElig;", "&Ccedil;", "&Egrave;", "&Eacute;", "&Ecirc;", "&Euml;", "&Igrave;", "&Iacute;", "&Icirc;", "&Iuml;", "&ETH;", "&Ntilde;", "&Ograve;", "&Oacute;", "&Ocirc;", "&Otilde;", "&Ouml;", "&times;", "&Oslash;", "&Ugrave;", "&Uacute;", "&Ucirc;", "&Uuml;", "&Yacute;", "&THORN;", "&szlig;", "&agrave;", "&aacute;", "&acirc;", "&atilde;", "&auml;", "&aring;", "&aelig;", "&ccedil;", "&egrave;", "&eacute;", "&ecirc;", "&euml;", "&igrave;", "&iacute;", "&icirc;", "&iuml;", "&eth;", "&ntilde;", "&ograve;", "&oacute;", "&ocirc;", "&otilde;", "&ouml;", "&divide;", "&oslash;", "&ugrave;", "&uacute;", "&ucirc;", "&uuml;", "&yacute;", "&thorn;", "&yuml;",
 		" &amp;amp; ", "&lt;", "&gt;", "&amp;nbsp;", "&amp;iexcl;", "&amp;cent;", "&amp;pound;", "&amp;curren;", "&amp;yen;", "&amp;brvbar;", "&amp;sect;", "&amp;uml;", "&amp;copy;", "&amp;ordf;", "&amp;laquo;", "&amp;not;", "&amp;shy;", "&amp;reg;", "&amp;macr;", "&amp;deg;", "&amp;plusmn;", "&amp;sup2;", "&amp;sup3;", "&amp;acute;", "&amp;micro;", "&amp;para;", "&amp;middot;", "&amp;cedil;", "&amp;sup1;", "&amp;ordm;", "&amp;raquo;", "&amp;frac14;", "&amp;frac12;", "&amp;frac34;", "&amp;iquest;", "&amp;Agrave;", "&amp;Aacute;", "&amp;Acirc;", "&amp;Atilde;", "&amp;Auml;", "&amp;Aring;", "&amp;AElig;", "&amp;Ccedil;", "&amp;Egrave;", "&amp;Eacute;", "&amp;Ecirc;", "&amp;Euml;", "&amp;Igrave;", "&amp;Iacute;", "&amp;Icirc;", "&amp;Iuml;", "&amp;ETH;", "&amp;Ntilde;", "&amp;Ograve;", "&amp;Oacute;", "&amp;Ocirc;", "&amp;Otilde;", "&amp;Ouml;", "&amp;times;", "&amp;Oslash;", "&amp;Ugrave;", "&amp;Uacute;", "&amp;Ucirc;", "&amp;Uuml;", "&amp;Yacute;", "&amp;THORN;", "&amp;szlig;", "&amp;agrave;", "&amp;aacute;", "&amp;acirc;", "&amp;atilde;", "&amp;auml;", "&amp;aring;", "&amp;aelig;", "&amp;ccedil;", "&amp;egrave;", "&amp;eacute;", "&amp;ecirc;", "&amp;euml;", "&amp;igrave;", "&amp;iacute;", "&amp;icirc;", "&amp;iuml;", "&amp;eth;", "&amp;ntilde;", "&amp;ograve;", "&amp;oacute;", "&amp;ocirc;", "&amp;otilde;", "&amp;ouml;", "&amp;divide;", "&amp;oslash;", "&amp;ugrave;", "&amp;uacute;", "&amp;ucirc;", "&amp;uuml;", "&amp;yacute;", "&amp;thorn;", "&amp;yuml;"
	 );
	
	$replace = array(
		" &#38; ", "&#60;", "&#62;", "&#160;", "&#161;", "&#162;", "&#163;", "&#164;", "&#165;", "&#166;", "&#167;", "&#168;", "&#169;", "&#170;", "&#171;", "&#172;", "&#173;", "&#174;", "&#175;", "&#176;", "&#177;", "&#178;", "&#179;", "&#180;", "&#181;", "&#182;", "&#183;", "&#184;", "&#185;", "&#186;", "&#187;", "&#188;", "&#189;", "&#190;", "&#191;", "&#192;", "&#193;", "&#194;", "&#195;", "&#196;", "&#197;", "&#198;", "&#199;", "&#200;", "&#201;", "&#202;", "&#203;", "&#204;", "&#205;", "&#206;", "&#207;", "&#208;", "&#209;", "&#210;", "&#211;", "&#212;", "&#213;", "&#214;", "&#215;", "&#216;", "&#217;", "&#218;", "&#219;", "&#220;", "&#221;", "&#222;", "&#223;", "&#224;", "&#225;", "&#226;", "&#227;", "&#228;", "&#229;", "&#230;", "&#231;", "&#232;", "&#233;", "&#234;", "&#235;", "&#236;", "&#237;", "&#238;", "&#239;", "&#240;", "&#241;", "&#242;", "&#243;", "&#244;", "&#245;", "&#246;", "&#247;", "&#248;", "&#249;", "&#250;", "&#251;", "&#252;", "&#253;", "&#254;", "&#255;",
		" &#38; ", "&#60;", "&#62;", "&#160;", "&#161;", "&#162;", "&#163;", "&#164;", "&#165;", "&#166;", "&#167;", "&#168;", "&#169;", "&#170;", "&#171;", "&#172;", "&#173;", "&#174;", "&#175;", "&#176;", "&#177;", "&#178;", "&#179;", "&#180;", "&#181;", "&#182;", "&#183;", "&#184;", "&#185;", "&#186;", "&#187;", "&#188;", "&#189;", "&#190;", "&#191;", "&#192;", "&#193;", "&#194;", "&#195;", "&#196;", "&#197;", "&#198;", "&#199;", "&#200;", "&#201;", "&#202;", "&#203;", "&#204;", "&#205;", "&#206;", "&#207;", "&#208;", "&#209;", "&#210;", "&#211;", "&#212;", "&#213;", "&#214;", "&#215;", "&#216;", "&#217;", "&#218;", "&#219;", "&#220;", "&#221;", "&#222;", "&#223;", "&#224;", "&#225;", "&#226;", "&#227;", "&#228;", "&#229;", "&#230;", "&#231;", "&#232;", "&#233;", "&#234;", "&#235;", "&#236;", "&#237;", "&#238;", "&#239;", "&#240;", "&#241;", "&#242;", "&#243;", "&#244;", "&#245;", "&#246;", "&#247;", "&#248;", "&#249;", "&#250;", "&#251;", "&#252;", "&#253;", "&#254;", "&#255;"
	);
	
	$text = str_replace($search, $replace, $text);
	return $text;
}

function rss_icon($feed_name, $feed_updfrq, $feed_icon = "", $language = "all", $exists = false){
	
	if($exists) {
		$rows = true;
	} else {
		$result = dbquery("SELECT feed_name FROM ".DB_SS_FEEDS." WHERE feed_name='".$feed_name."'");
		$rows = dbrows($result);
	}
	
	if(IF_MULTI_LANGUAGE && $language) {
		$language = $language;
	} else {
		$language = false;
	}
	$feed_language = ($language ? "_".strtolower($language) : "");
	
	if ($rows != 0) {
		if (!file_exists(INFUSIONS."ss_feeds_panel/rss/".$feed_name."".$feed_language.".rss") OR 
			(file_exists(INFUSIONS."ss_feeds_panel/rss/".$feed_name."".$feed_language.".rss") && (time()-filemtime(INFUSIONS."ss_feeds_panel/rss/".$feed_name."".$feed_language.".rss"))>(round($feed_updfrq)*(60 * 60)))) {
			make_rss($feed_name, $feed_updfrq, $language, true);
		}
		if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$feed_name.".php")) {
			include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$feed_name.".php";
		}else{
			include INFUSIONS."ss_feeds_panel/locale/English/feeds/".$feed_name.".php";
		}
		$rssfiledate = showdate("longdate", filemtime(INFUSIONS."ss_feeds_panel/rss/".$feed_name."".$feed_language.".rss"));
		if ($feed_icon != ""){
			$icon_feed = INFUSIONS."ss_feeds_panel/images/icon/".$feed_icon;
		} else {
			$icon_feed = INFUSIONS."ss_feeds_panel/images/icon/feed_icon01.png";
		}
		$icon_size = @getimagesize($icon_feed);
		echo "<a href='".INFUSIONS."ss_feeds_panel/rss/".$feed_name."".$feed_language.".rss' target='_blank'>\n";
		echo "	<img src='".$icon_feed."' title='".$locale['feeds_title']." - ".$rssfiledate."' alt='".$locale['feeds_title']." - ".$rssfiledate."' style='vertical-align: top; width:".$icon_size[0]."px; height:".$icon_size[1]."px; border: 0pt none;' />\n";
		echo "</a>\n";
	}
}

function make_rss($feed_name, $feed_updfrq, $language = "all", $exists = false) {
	global $settings, $locale;
	
	if(IF_MULTI_LANGUAGE && $language) {
		$language = $language;
	} else {
		$language = false;
	}
	$feed_language = ($language ? "_".strtolower($language) : "");
	
	if($exists) {
		$rows = true;
	} else {
		$result = dbquery("SELECT feed_name FROM ".DB_SS_FEEDS." WHERE feed_name='".$feed_name."'");
		$rows = dbrows($result);
	}
	
	if ($rows != 0) {
		if (file_exists(INFUSIONS."ss_feeds_panel/feeds/".$feed_name.".php")){
			include INFUSIONS."ss_feeds_panel/feeds/".$feed_name.".php";
		}
		$rssfile = INFUSIONS."ss_feeds_panel/rss/".$feed_name."".$feed_language.".rss";
		
		if (file_exists($rssfile)){
			chmod($rssfile, 0777);
		}
		$write=false;
		$file=fopen($rssfile,"w");
		
		if (fwrite($file,$rss)) {
			$write=true;
		}
		fclose($file);
		chmod($rssfile, 0644);
		return $write;
	}
}

// Parse smiley bbcode
function parsersssmileys($message) {
	global $smiley_cache, $settings;
	if (!preg_match("#(\[code\](.*?)\[/code\]|\[geshi=(.*?)\](.*?)\[/geshi\]|\[php\](.*?)\[/php\])#si", $message)) {
		if (!$smiley_cache) { cache_smileys(); }
		if (is_array($smiley_cache) && count($smiley_cache)) {
			foreach ($smiley_cache as $smiley) {
				$smiley_code = preg_quote($smiley['smiley_code']);
				$smiley_image = "<img src='".$settings['siteurl'].get_image("smiley_".$smiley['smiley_text'])."' alt='".$smiley['smiley_text']."' style='vertical-align:middle;' />";
				$message = preg_replace("#{$smiley_code}#si", $smiley_image, $message);
			}
		}
	}
	return $message;
}
?>