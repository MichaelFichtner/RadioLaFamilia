<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: articles_feed.php
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
if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/weblinks_feed.php")) {
	include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/weblinks_feed.php";
} else {
	include INFUSIONS."ss_feeds_panel/locale/English/feeds/weblinks_feed.php";
}

$result = dbquery("SELECT tw.weblink_id, tw.weblink_name, tw.weblink_description, tw.weblink_datestamp, twc.weblink_cat_id
	FROM ".DB_WEBLINKS." tw
	LEFT JOIN ".DB_WEBLINK_CATS." twc ON tw.weblink_cat=twc.weblink_cat_id
	WHERE (twc.weblink_cat_access='".nGUEST."' OR twc.weblink_cat_access='".nMEMBER."')
	".(!(bool)IF_MULTI_LANGUAGE ? '':" AND (twc.weblink_cat_language='all' OR twc.weblink_cat_language='".$language."')")."
	ORDER BY tw.weblink_datestamp DESC LIMIT 0,20"
);

if (!dbrows(dbquery("SELECT feed_name FROM ".DB_SS_FEEDS." WHERE feed_name = 'weblinks_feed'")) || !dbrows($result)){
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
		$rsid = intval($data['weblink_id']);
		$rtitle = $data['weblink_name'];
		$description = stripslashes(nl2br($data['weblink_description']));
		
		$rss .= "		<item>\n";
		$rss .= "			<title>".html2ascii(htmlspecialchars($rtitle))."</title>\n";
		$rss .= "			<link>".$settings['siteurl']."weblinks.php?cat_id=".$rsid."</link>\n";
		$rss .= "			<description>".html2ascii(htmlspecialchars($description))."</description>\n";
		$rss .= "			<guid>".$settings['siteurl']."weblinks.php?cat_id=".$rsid."</guid>\n";
		$rss .= "			<pubDate>".date("D, d M Y H:i:s", $data['weblink_datestamp'])." GMT</pubDate>\n";
		$rss .= "		</item>\n";
	}
	$rss .= "	</channel>\n";
	$rss .= "</rss>\n";
}
?>