<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: downloads_feed.php
| Author: SiteMaster, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }
require_once INFUSIONS."ss_feeds_panel/infusion_db.php";
require_once INFUSIONS."ss_feeds_panel/functions.php";

if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php")) {
	include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php";
} else {
	include INFUSIONS."ss_feeds_panel/locale/English/infusion.php";
}
if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/downloads_feed.php")) {
	include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/downloads_feed.php";
} else {
	include INFUSIONS."ss_feeds_panel/locale/English/feeds/downloads_feed.php";
}

$result = dbquery("SELECT td.download_id, td.download_title, td.download_description, td.download_datestamp, tdc.download_cat_id FROM ".DB_DOWNLOADS." td
	LEFT JOIN ".DB_DOWNLOAD_CATS." tdc ON td.download_cat=tdc.download_cat_id
	WHERE tdc.download_cat_access='".nGUEST."' OR tdc.download_cat_access='".nMEMBER."'
	ORDER BY td.download_datestamp DESC LIMIT 0,20"
);

if (!dbrows(dbquery("SELECT feed_name FROM ".DB_SS_FEEDS." WHERE feed_name = 'downloads_feed'")) || !dbrows($result)){
	$rss = "";
	$rss .= "<?xml version='1.0' encoding='".$locale['charset']."'?>\n";
	$rss .= "<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>\n";
	$rss .= "	<channel>\n";
	$rss .= "		<title>".html2ascii($settings['sitename']." - ".$locale['feeds_title'])."</title>\n";
	$rss .= "		<link>".$settings['siteurl']."</link>\n";
	$rss .= "		<description>".strip_tags($settings['description'])."</description>\n";
	$rss .= "		<lastBuildDate>".date("D, d M Y H:i:s", time())." GMT</lastBuildDate>\n";
	$rss .= "		<generator>".$locale['ssfp_title']."</generator>\n";
	$rss .= "		<atom:link href='".$settings['siteurl']."infusions/ss_feeds_panel/rss/".$feed_name.$feed_language.".rss"."' rel='self' type='application/rss+xml' />\n";
	$rss .= "		<image>\n";
	$rss .= "			<title>".html2ascii($settings['sitename']." - ".$locale['feeds_title'])."</title>\n";
	$rss .= "			<url>".$settings['siteurl']."images/rss.gif</url>\n";
	$rss .= "			<link>".$settings['siteurl']."</link>\n";
	$rss .= "			<width>36</width>\n";
	$rss .= "			<height>13</height>\n";
	$rss .= "			<description>".html2ascii($settings['sitename'])."</description>\n";
	$rss .= "		</image>\n";
	$rss .= "		<item>\n";
	$rss .= "			<title>".html2ascii($settings['sitename']." - ".$locale['feeds_title'])."</title>\n";
	$rss .= "			<link>".$settings['siteurl']."</link>\n";
	$rss .= "			<description>".$locale['ssfp_201']."</description>\n";
	$rss .= "			<guid>".$settings['siteurl']."</guid>\n";
	$rss .= "			<pubDate>".date("D, d M Y H:i:s", time())." GMT</pubDate>\n";
	$rss .= "		</item>\n";
	$rss .= "	</channel>\n";
	$rss .= "</rss>\n";
}else{
	$rss = "";
	$rss .= "<?xml version='1.0' encoding='".$locale['charset']."'?>\n";
	$rss .= "<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>\n";
	$rss .= "	<channel>\n";
	$rss .= "		<title>".html2ascii($settings['sitename']." - ".$locale['feeds_title'])."</title>\n";
	$rss .= "		<link>".$settings['siteurl']."</link>\n";
	$rss .= "		<description>".strip_tags($settings['description'])."</description>\n";
	$rss .= "		<lastBuildDate>".date("D, d M Y H:i:s", time())." GMT</lastBuildDate>\n";
	$rss .= "		<generator>".$locale['ssfp_title']."</generator>\n";
	$rss .= "		<atom:link href='".$settings['siteurl']."infusions/ss_feeds_panel/rss/".$feed_name.$feed_language.".rss"."' rel='self' type='application/rss+xml' />\n";
	$rss .= "		<image>\n";
	$rss .= "			<title>".html2ascii($settings['sitename']." - ".$locale['feeds_title'])."</title>\n";
	$rss .= "			<url>".$settings['siteurl']."images/rss.gif</url>\n";
	$rss .= "			<link>".$settings['siteurl']."</link>\n";
	$rss .= "			<width>36</width>\n";
	$rss .= "			<height>13</height>\n";
	$rss .= "			<description>".html2ascii($settings['sitename'])."</description>\n";
	$rss .= "		</image>\n";
	
	while ($data=dbarray($result)) {
		$rsid = intval($data['download_cat_id']);
		$rtitle = $data['download_title'];
		$description = stripslashes(nl2br($data['download_description']));
		
		$rss .= "		<item>\n";
		$rss .= "			<title>".html2ascii(htmlspecialchars($rtitle))."</title>\n";
		$rss .= "			<link>".$settings['siteurl']."downloads.php?cat_id=".$rsid."&amp;download_id=".$data['download_id']."</link>\n";
		$rss .= "			<description>".html2ascii(htmlspecialchars($description))."</description>\n";
		$rss .= "			<guid>".$settings['siteurl']."downloads.php?cat_id=".$rsid."</guid>\n";
		$rss .= "			<pubDate>".date("D, d M Y H:i:s", $data['download_datestamp'])." GMT</pubDate>\n";
		$rss .= "		</item>\n";
	}
	$rss .= "	</channel>\n";
	$rss .= "</rss>\n";
}
?>