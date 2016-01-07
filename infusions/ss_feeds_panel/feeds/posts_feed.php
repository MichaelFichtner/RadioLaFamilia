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
if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/posts_feed.php")) {
	include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/posts_feed.php";
} else {
	include INFUSIONS."ss_feeds_panel/locale/English/feeds/posts_feed.php";
}

	$access = ''; // Pimped: access
	$aresult = dbquery(
		"SELECT tp.post_id, tf.forum_access
		FROM ".DB_THREADS." tt
		INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
		INNER JOIN ".DB_POSTS." tp ON tt.thread_lastpostid=tp.post_id
		INNER JOIN ".DB_USERS." tu ON tt.thread_lastuser=tu.user_id
		LEFT JOIN ".DB_FORUMS." f2 ON tf.forum_cat = f2.forum_id
		".(!(bool)IF_MULTI_LANGUAGE_FORUM ? '':" WHERE (f2.forum_language='all' OR f2.forum_language='".$language."')")."
		ORDER BY tt.thread_lastpost DESC LIMIT 0,20"); 
	while ($adata = dbarray($aresult)) {
		if ($access != '') { $access .= "|"; }
		$access .= $adata['forum_access'];
	}
	$result = dbquery(
		"SELECT
		tt.thread_id, tt.thread_subject, tt.thread_lastpost, tt.thread_lastpostid,
		tf.forum_id, tf.forum_access,
		tp.post_message
		FROM ".DB_THREADS." tt
		INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
		INNER JOIN ".DB_POSTS." tp ON tt.thread_lastpostid=tp.post_id
		LEFT JOIN ".DB_FORUMS." f2 ON tf.forum_cat = f2.forum_id
		WHERE ".groupaccess('tf.forum_access', $access, "($access)")."
		".(!(bool)IF_MULTI_LANGUAGE_FORUM ? '':" AND (f2.forum_language='all' OR f2.forum_language='".$language."')")."
		ORDER BY tt.thread_lastpost DESC LIMIT 0,20"
	);

if (!dbrows(dbquery("SELECT feed_name FROM ".DB_SS_FEEDS." WHERE feed_name = 'posts_feed'")) || !dbrows($result)){
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
		$rsid = intval($data['thread_id']);
		$rspid = intval($data['thread_lastpostid']);
		$rtitle = $data['thread_subject'];
		$description = stripslashes(nl2br($data['post_message']));

		$rss .= "		<item>\n";
		$rss .= "			<title>".html2ascii(htmlspecialchars($rtitle))."</title>\n";
		$rss .= "			<link>".$settings['siteurl']."forum/viewthread.php?thread_id=".$rsid."&amp;pid=".$rspid."#post_".$rspid."</link>\n";
		$rss .= "			<description>".html2ascii(htmlspecialchars(preg_replace("/max-height: 300px; /", "", parseubb(parsersssmileys($description)))))."</description>\n";
		$rss .= "			<guid>".$settings['siteurl']."forum/viewthread.php?thread_id=".$rsid."&amp;pid=".$rspid."#post_".$rspid."</guid>\n";
		$rss .= "			<pubDate>".date("D, d M Y H:i:s", $data['thread_lastpost'])." GMT</pubDate>\n";
		$rss .= "		</item>\n";
	}
	$rss .= "	</channel>\n";
	$rss .= "</rss>\n";
}
?>