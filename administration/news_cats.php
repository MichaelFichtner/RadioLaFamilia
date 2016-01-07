<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: news_cats.php
| Version: Pimped Fusion v0.06.00
+----------------------------------------------------------------------------+
| based on PHP-Fusion CMS v7.01 by Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";
require_once TEMPLATES."admin_header.php";
include LOCALE.LOCALESET."admin/news-cats.php";

if (!checkrights("NC") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }
add_newscatimages();

if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "sn") {
		$message = $locale['420'];
	} elseif ($_GET['status'] == "su") {
		$message = $locale['421'];
	} elseif ($_GET['status'] == "dn") {
		$message = $locale['422']."<br />\n<span class='small'>".$locale['423']."</span>";
	} elseif ($_GET['status'] == "dy") {
		$message = $locale['424'];
	}
	if (isset($message)) { echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
}

if ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
	$count = dbcount("(news_id)", DB_NEWS, "news_cat='".(int)$_GET['cat_id']."'");
	if ($count != 0) {
		redirect(FUSION_SELF.$aidlink."&status=dn");
	} else {
		$result = dbquery("SELECT news_cat_name FROM ".DB_NEWS_CATS." WHERE news_cat_id='".(int)$_GET['cat_id']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
		}
		$result = dbquery("DELETE FROM ".DB_NEWS_CATS." WHERE news_cat_id='".(int)$_GET['cat_id']."'");
		log_admin_action("admin-1", "admin_newscat_deleted", "", "", $data['news_cat_name']." (ID: ".(int)$_GET['cat_id'].")");
		redirect(FUSION_SELF.$aidlink."&status=dy");
	}
} elseif (isset($_POST['save_cat'])) {
	$cat_name = stripinput($_POST['cat_name']);
	$cat_image = stripinput($_POST['cat_image']);
	if(IF_MULTI_LANGUAGE) { $cat_language = stripinput($_POST['cat_language']); } else { $cat_language = true; }
	if ($cat_name && $cat_image && $cat_language) {
		if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
			if(IF_MULTI_LANGUAGE) { $insert_mysql = ", news_cat_language='$cat_language'"; } else { $insert_mysql = ''; }
			$result = dbquery("UPDATE ".DB_NEWS_CATS." SET news_cat_name='$cat_name', news_cat_image='$cat_image'".$insert_mysql."
			WHERE news_cat_id='".$_GET['cat_id']."'");
			log_admin_action("admin-1", "admin_newscat_edited", "", "", $cat_name." (ID: ".(int)$_GET['cat_id'].")");
			redirect(FUSION_SELF.$aidlink."&status=su");
		} else {
			if(IF_MULTI_LANGUAGE) {
				$insert_mysql1 = ", news_cat_language"; $insert_mysql2 = ", '$cat_language'";
			} else {
				$insert_mysql1 = ''; $insert_mysql2 = '';
			}
			$result = dbquery("INSERT INTO ".DB_NEWS_CATS." (news_cat_name, news_cat_image".$insert_mysql1.") VALUES 
			('$cat_name', '$cat_image'".$insert_mysql2.")");
			$id = mysql_insert_id();
			log_admin_action("admin-1", "admin_newscat_added", "", "", $cat_name." (ID: ".$id.")");
			redirect(FUSION_SELF.$aidlink."&status=sn");
		}
	} else {
		redirect(FUSION_SELF.$aidlink);
	}
} elseif ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
	$result = dbquery("SELECT news_cat_id, news_cat_name, news_cat_image, news_cat_language FROM ".DB_NEWS_CATS." WHERE news_cat_id='".$_GET['cat_id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$cat_name = $data['news_cat_name'];
		$cat_image = $data['news_cat_image'];
		if(IF_MULTI_LANGUAGE) $cat_language = $data['news_cat_language'];
		$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data['news_cat_id'];
		opentable($locale['400']);
	} else {
		redirect(FUSION_SELF.$aidlink);
	}
} else {
	$cat_name = "";
	$cat_image = "";
	$cat_language = "";
	$formaction = FUSION_SELF.$aidlink;
	opentable($locale['401']);
}
$image_files = makefilelist(IMAGES_NC, ".|..|index.php", true);
$image_list = makefileopts($image_files,$cat_image);
echo "<form name='addcat' method='post' action='".$formaction."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='400' class='center'>\n<tr>\n";
echo "<td width='130' class='tbl'>".$locale['430']."</td>\n";
echo "<td class='tbl'><input type='text' name='cat_name' value='".$cat_name."' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
if(IF_MULTI_LANGUAGE) {
	echo "<td width='130' class='tbl'>".$locale['437']."</td>\n";
	$opts = make_admin_language_opts($cat_language);
	echo "<td class='tbl'><select name='cat_language' class='textbox' style='width:200px;'>\n".$opts;
	"</select></td>\n";
	echo "</tr>\n<tr>\n";
}
echo "<td width='130' class='tbl'>".$locale['431']."</td>\n";
echo "<td class='tbl'><select name='cat_image' class='textbox' style='width:200px;'>\n".$image_list."</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='save_cat' value='".$locale['432']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

opentable($locale['402']);
$result = dbquery("SELECT news_cat_id, news_cat_name, news_cat_language FROM ".DB_NEWS_CATS." ORDER BY news_cat_name");
$rows = dbrows($result);
if ($rows != 0) {
	$counter = 0; $columns = 4; 
	echo "<table cellpadding='0' cellspacing='1' width='500' class='center'>\n<tr>\n";
	while ($data = dbarray($result)) {
		if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
		echo "<td align='center' width='25%' class='tbl'><strong>".$data['news_cat_name']."</strong><br />";
		if(IF_MULTI_LANGUAGE) echo $locale['437'].": ".$data['news_cat_language']."<br />\n"; //pimped
		echo "<img src='".get_image("nc_".$data['news_cat_name'])."' alt='".$data['news_cat_name']."' /><br /><br />\n";
		echo "<span class='small'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data['news_cat_id']."'>".$locale['433']."</a> -\n";
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;cat_id=".$data['news_cat_id']."' onclick=\"return confirm('".$locale['450']."');\">".$locale['434']."</a></span></td>\n";
		$counter++;
	}
	echo "</tr>\n</table>\n";
} else {
	echo "<div style='text-align:center'><br />\n".$locale['435']."<br /><br />\n</div>\n";
}
echo "<div style='text-align:center'><br />\n<a href='".ADMIN."images.php".$aidlink."&amp;ifolder=imagesnc'>".$locale['436']."</a><br /><br />\n</div>\n";
closetable();

require_once TEMPLATES."footer.php";
?>