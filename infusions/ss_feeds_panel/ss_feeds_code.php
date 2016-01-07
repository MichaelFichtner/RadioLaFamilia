<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: ss_feeds_admin.php
| Author: SiteMaster, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../../maincore.php";
require_once INCLUDES."output_handling_include.php";
require_once THEME."theme.php";
include INFUSIONS."ss_feeds_panel/infusion_db.php";
require_once INFUSIONS."ss_feeds_panel/functions.php";
require_once INCLUDES."bbcode_include.php";

if (!checkrights("SSFP") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (!$_GET['feed_name'] || empty($_GET['feed_name'])) {
	$feed_name = "";
} else {
	$feed_name = $_GET['feed_name'];
}
if (isset($_GET["updfrq"]) && isnum($_GET["updfrq"])){
	$updfrq = $_GET["updfrq"];
} else {
	$updfrq = "";
}
if (isset($_POST['feed_icon']) && isset($feed_name)){
	$result = dbquery("UPDATE ".DB_SS_FEEDS." SET feed_icon='".$_POST['feed_icon']."' WHERE feed_name='".$feed_name."'");
}
// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."ss_feeds_panel/locale/English/infusion.php";
}

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n";
echo "<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' />\n";
echo "</head>\n";
echo "<body>\n";
	
$result = dbquery("SELECT feed_name, feed_icon, feed_updfrq FROM ".DB_SS_FEEDS." WHERE feed_name='".$feed_name."'");

if (dbrows($result)) {
	opentable($locale['ssfp_005']);
	echo "<form name='updform' method='post' action='".FUSION_SELF.$aidlink."&amp;feed_name=".$feed_name."&amp;updfrq=".$updfrq."'>\n";
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
	echo "	<tr>\n";
	echo "		<td class='tbl1'>".$locale['ssfp_006']."<br /><br />".$updfrq.$locale['ssfp_007']."</td>\n";
	echo "	</tr>\n";
	
	while($data = dbarray($result)) {
		$rows = dbcount("(feed_id)", DB_SS_FEEDS, "");
		if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$data['feed_name'].".php")) {
			include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$data['feed_name'].".php";
		}else{
			include INFUSIONS."ss_feeds_panel/locale/English/feeds/".$data['feed_name'].".php";
		}
		include INFUSIONS."ss_feeds_panel/feeds/".$data['feed_name']."_var.php";
		
		$feed_icon = makefilelist(INFUSIONS."ss_feeds_panel/images/icon/", "index.php", true, "files");
		foreach($feed_icon as $icon){
			$aicon = "<a href='".INFUSIONS."ss_feeds_panel/rss/".$data['feed_name'].".rss' target='_blank'><img src='".INFUSIONS."ss_feeds_panel/images/icon/".$icon."' title='".$feed_title."' alt='".$feed_title."' style='border: 0pt none;' /></a>\n";
			$acheck = "&nbsp;<input type='radio' name='feed_icon' value='".$icon."' class='textbox' ".($icon == $data['feed_icon'] ? " checked='checked'" : "")." onclick='submit();' />".$locale['ssfp_010'];
			$code = "";
			$code .= "[code]";
			$code .= "rss_icon(\"".$data['feed_name']."\", ".$updfrq.", \"".$icon."\");\n";
			$code .= "[/code]";
			
			echo "	<tr>\n";
			echo "		<td class='tbl2' style='white-space:nowrap'>".$aicon.$acheck."</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class='tbl1' style='white-space:nowrap'>".nl2br(parseubb(stripinput($code)))."</td>\n";
			echo "	</tr>\n";
		}
	}
	echo "</table>\n";
	echo "</form>\n";
} else {
	opentable($locale['ssfp_005']);
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
	echo "	<tr>\n";
	echo "		<td align='center' class='tbl1'>".$locale['ssfp_102']."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

echo "<br /><br /><div align='center'><strong><a href='javascript:window.close();'>".$locale['ssfp_008']."</a></strong></div>\n";
closetable();

echo "</body>\n";
echo "</html>\n";
?>