<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum_post_rating.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter, PhAnToM, Fangree_Craig, SoBeNoFear
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";
require_once TEMPLATES."admin_header.php";
require_once LOCALE.LOCALESET."admin/forum_post_ratings.php";

if (!checkrights("FPR") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['forpr101'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['forpr102'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}

if (isset($_POST['savesettings'])) {
	$error = 0;
	if(!set_mainsetting('forum_post_ratings', stripinput($_POST['forum_post_ratings']))) { $error = 1; }
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

if (isset($_POST['goRating'])) {
$error = 0;
	
	$type_name = stripinput($_POST['type_name']);
	$type_icon = stripinput($_POST['type_icon']);
	
	if($type_name != "" && $type_icon != "") {
	
		if (isset($_GET['update']) && isnum($_GET['update'])) {
			
			$result = dbquery("SELECT type_name FROM ".DB_POST_RATING_TYPES." WHERE type_id='".(int)$_GET['update']."'");
			if (!dbrows($result)) redirect(FUSION_SELF.$aidlink."&error=1");
			
			$result = dbquery("UPDATE ".DB_POST_RATING_TYPES." SET type_name='".$type_name."', type_icon='".$type_icon."'
			WHERE type_id='".(int)$_GET['update']."'" );
			if (!$result) { $error = 1; }
			
			redirect(FUSION_SELF.$aidlink."&error=".$error);
		
		} else {
			
			$result = dbquery ("INSERT INTO ".DB_POST_RATING_TYPES." (type_name, type_icon) VALUES('".$type_name."', '".$type_icon."')" );
			if (!$result) { $error = 1; }
			
			redirect(FUSION_SELF.$aidlink."&error=".$error);
		
		}
	} else {
		// Error
	}
}
	
if (isset($_GET['del']) && isnum ($_GET['del'])) {
	$error = 0;
	
	$result = dbquery("DELETE FROM ".DB_POST_RATING_TYPES." WHERE type_id='".(int)$_GET['del']."'");
	if (!$result) { $error = 1; }
	$result = dbquery("DELETE FROM ".DB_POST_RATINGS." WHERE rate_type='".(int)$_GET['del']."'");
	if (!$result) { $error = 1; }
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

if (isset($_GET['edit']) && isnum($_GET['edit'])) {
	
	$result = dbquery("SELECT type_name,type_icon FROM ".DB_POST_RATING_TYPES." WHERE type_id='".(int)$_GET['edit']."'" );
	if (! dbrows($result))
		redirect(FUSION_SELF.$aidlink."&section=ratings");
	$data = dbarray($result);
	
	$type_name = stripslash($data['type_name']);
	$type_icon = $data['type_icon'];
	
	$action = FUSION_SELF.$aidlink."&section=ratings&update=".(int)$_GET['edit'];
	$panel = $locale['forpr115'];
	$button = $locale['forpr116'];

} else {
	
	$type_name = "";
	$type_icon = "";
	
	$action = FUSION_SELF.$aidlink."&section=ratings";
	$panel = $locale['forpr113'];
	$button = $locale['forpr114'];

}

opentable($locale['forpr100']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['forpr103']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_post_ratings' class='textbox'>\n";
echo "<option value='1'".($settings['forum_post_ratings'] == "1" ? " selected='selected'" : "").">".$locale['forpr104']."</option>\n";
echo "<option value='0'".($settings['forum_post_ratings'] == "0" ? " selected='selected'" : "").">".$locale['forpr105']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />";
echo "<input type='submit' name='savesettings' value='".$locale['forpr106']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

opentable($panel);
	
$icon_opts = makefileopts(makefilelist(IMAGES."forum_post_ratings/", ".|..|index.php|Thumbs.db" ), $type_icon );
	
echo "<form action='".$action."' name='ratingForm' method='post'>
	<table width='300' cellspacing='1' cellpadding='0' class='tbl-border center'>
	<tr>
		<td class='tbl1'>".$locale['forpr107']."</td>
		<td class='tbl2'><input type='text' name='type_name' class='textbox' value='".$type_name."'></td>
	</tr>
	<tr>
		<td class='tbl1'>".$locale['forpr108']."</td>
		<td class='tbl2'><select name='type_icon' class='textbox'>
		".$icon_opts."
		</select></td>
	</tr>
	<tr>
		<td class='tbl1' colspan='2' style='text-align:center;'>
		<input type='submit' name='goRating' value='".$button."' class='button'>
		</td>
	</tr>
	</table>
	</form>\n";
closetable();

opentable($locale['forpr117']);
	
$result = dbquery("SELECT type_name,type_icon,type_id FROM ".DB_POST_RATING_TYPES);
if (dbrows($result)) {
	
	echo "<table width='300' cellspacing='1' cellpadding='0' class='tbl-border center'>
		<tr>
			<td class='tbl2' style='font-weight:bold;'>".$locale['forpr107']."</td>
			<td class='tbl2' style='font-weight:bold;'>".$locale['forpr108']."</td>
			<td class='tbl2' style='font-weight:bold;'>".$locale['forpr109']."</td>
		</tr>\n";
	
	while ($data = dbarray($result)) {
		echo "<tr>
				<td class='tbl1'>".parseubb($data['type_name'])."</td>
				<td class='tbl1'><img src='".IMAGES."forum_post_ratings/".$data['type_icon']."' alt=''></td>
				<td class='tbl1'><a href='". FUSION_SELF.$aidlink."&amp;section=ratings&amp;edit=".$data['type_id']."'>".$locale['forpr110']."</a> :: 
				<a href='".FUSION_SELF.$aidlink."&amp;section=ratings&amp;del=".$data['type_id']."' onclick=\"return confirm('".$locale['forpr111']."');\">".$locale['forpr112']."</a></td>
			</tr>\n";
	}
	
	echo "</table>\n";

} else {
	echo "<div align='center'>".$locale['forpr118']."</div>\n";
}
closetable();

require_once TEMPLATES."footer.php";
?>