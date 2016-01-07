<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: ss_feeds_panel.php
| Author: SiteMaster, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php")) {
	include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php";
} else {
	include INFUSIONS."ss_feeds_panel/locale/English/infusion.php";
}

include_once INFUSIONS."ss_feeds_panel/infusion_db.php";
include_once INFUSIONS."ss_feeds_panel/functions.php";

$result = dbquery("SELECT feed_name, feed_icon, feed_updfrq FROM ".DB_SS_FEEDS." ORDER BY feed_order");
if (dbrows($result)) {
	
	if(IF_MULTI_LANGUAGE) {
		$language = LANGUAGE;
	} else {
		$language = false;
	}
	$feed_language = ($language ? "_".strtolower($language) : "");
	
	if (checkrights("SSFP") && isset($_GET['force_update']) && $_GET['force_update'] != "") {
		unlink(INFUSIONS."ss_feeds_panel/rss/".$_GET['force_update'].$feed_language.".rss");
		redirect(FUSION_SELF);
	}
	
	openside($locale['ssfp_200']);
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='spacer'>\n";
	
	while($data = dbarray($result)) {
		if(file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$data['feed_name'].".php")) {
			include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$data['feed_name'].".php";
		} else {
			include INFUSIONS."ss_feeds_panel/locale/English/feeds/".$data['feed_name'].".php";
		}
		
		add_to_head("<link rel='alternate' type='application/rss+xml' title='".$locale['feeds_title']." - ".$settings['sitename']."' href='".INFUSIONS."ss_feeds_panel/rss/".$data['feed_name'].$feed_language.".rss' />");
		
		echo "	<tr>\n";
		if (checkrights("SSFP")){
			echo "		<td class='side_body'>\n";
			echo "			<a href='".FUSION_SELF."?force_update=".$data['feed_name']."'>\n";
			echo "				<img src='".INFUSIONS."ss_feeds_panel/images/update.png' title='".$locale['ssfp_009']."' alt='".$locale['ssfp_009']."' style='vertical-align: top; width:16px; height:16px; border: 0pt none;' />\n";
			echo "			</a>\n";
			echo "		</td>\n";
		}
		echo "		<td class='side_body'>\n";
		rss_icon($data['feed_name'], $data['feed_updfrq'], $data['feed_icon'], $language, true);
		echo "			<a href='".INFUSIONS."ss_feeds_panel/rss/".$data['feed_name'].$feed_language.".rss' target='_blank'>\n";
		echo "				".$locale['feeds_title']."\n";
		echo "			</a>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
	
	echo "</table>\n";
	closeside();
}
?>