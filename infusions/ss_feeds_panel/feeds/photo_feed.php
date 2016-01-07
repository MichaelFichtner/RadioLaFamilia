<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: photo_feed.php
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
if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/photo_feed.php")) {
	include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/photo_feed.php";
} else {
	include INFUSIONS."ss_feeds_panel/locale/English/feeds/photo_feed.php";
}

$result = dbquery(
	"SELECT tp.*, ta.*, tu.user_id,user_name
	FROM ".DB_PHOTOS." tp
	LEFT JOIN ".DB_PHOTO_ALBUMS." ta USING (album_id)
	LEFT JOIN ".DB_USERS." tu ON tp.photo_user=tu.user_id
	WHERE ta.album_access='".nGUEST."' OR ta.album_access='".nMEMBER."'
	ORDER BY tp.photo_datestamp DESC LIMIT 0,20"
);
if (!dbrows(dbquery("SELECT feed_name FROM ".DB_SS_FEEDS." WHERE feed_name = 'photo_feed'")) || !dbrows($result)){
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
	include LOCALE.LOCALESET."photogallery.php";
	define("RSSSAFEMODE", @ini_get("safe_mode") ? true : false);

	while ($data=dbarray($result)) {
		$PHOTODIR = $settings['siteurl']."images/photoalbum/".(!RSSSAFEMODE ? "album_".$data['album_id']."/" : "");
		$img = $PHOTODIR.$data['photo_thumb1'];
		
		if ($settings['photo_watermark']) {
			if ($settings['photo_watermark_save']) {
				$parts = explode(".", $data['photo_filename']);
				$wm_file1 = $parts[0]."_w1.".$parts[1];
				$wm_file2 = $parts[0]."_w2.".$parts[1];
				if (!file_exists($PHOTODIR.$wm_file1)) {
					if ($data['photo_thumb2']) { $photo_thumb = $settings['siteurl']."photo.php?photo_id=".$data['photo_id']; }
					$photo_file = $settings['siteurl']."photo.php?photo_id=".$data['photo_id'];
				} else {
					if ($data['photo_thumb2']) { $photo_thumb = $PHOTODIR.$wm_file1; }
					$photo_file = $PHOTODIR.$wm_file2;
				}
			} else {
				if ($data['photo_thumb2']) { $photo_thumb = $settings['siteurl']."photo.php?photo_id=".$data['photo_id']; }
				$photo_file = $settings['siteurl']."photo.php?photo_id=".$data['photo_id'];
			}
		} else {
			$photo_thumb = $data['photo_thumb2'] ? $PHOTODIR.$data['photo_thumb2'] : "";
			$photo_file = $PHOTODIR.$data['photo_filename'];
		}

		$rsid = intval($data['photo_id']);
		$rtitle = $data['album_title']." / ".$data['photo_title'];		
		$description = "";
		$description .= "<div align='center' style='margin:5px;'>";
		$description .= "<a href='".$settings['siteurl']."photogallery.php?photo_id=".$rsid."'>";
		$description .= "<img src='".(isset($photo_thumb) && !empty($photo_thumb) ? $photo_thumb : $photo_file)."' alt='".$data['photo_filename']."' title='".$locale['453']."' style='border:0px' /></a>";
		$description .= "</a></div>";
		$description .= "<div align='center' style='margin:5px 0px 5px 0px'>";
		if ($data['photo_description']) {
			$description .= stripslashes(nl2br($data['photo_description']))."<br /><br />";
		}
		$description .= $locale['433'].showdate("shortdate", $data['photo_datestamp'])."<br />";
		$description .= $locale['434']."<a href='".$settings['siteurl']."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br />";
		$description .= "</div>";
		
		$rss .= "		<item>\n";
		$rss .= "			<title>".html2ascii(htmlspecialchars($rtitle))."</title>\n";
		$rss .= "			<link>".$settings['siteurl']."photogallery.php?photo_id=".$rsid."</link>\n";
		$rss .= "			<description>".html2ascii(htmlspecialchars(parseubb(parsersssmileys($description), "b|i|u|center|small|url|mail|img|quote")))."</description>\n";
		$rss .= "			<guid>".$settings['siteurl']."photogallery.php?photo_id=".$rsid."</guid>\n";
		$rss .= "			<pubDate>".date("D, d M Y H:i:s", $data['photo_datestamp'])." GMT</pubDate>\n";
		$rss .= "		</item>\n";
	}
	$rss .= "	</channel>\n";
	$rss .= "</rss>\n";
}
?>