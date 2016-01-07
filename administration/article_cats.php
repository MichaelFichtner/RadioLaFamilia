<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: article_cats.php
| Version: Pimped Fusion v0.09.00
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
include LOCALE.LOCALESET."admin/article-cats.php";

if (!checkrights("AC") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "sn") {
		$message = $locale['410'];
	} elseif ($_GET['status'] == "su") {
		$message = $locale['411'];
	} elseif ($_GET['status'] == "deln") {
		$message = $locale['412']."<br />\n<span class='small'>".$locale['413']."</span>";
	} elseif ($_GET['status'] == "dely") {
		$message = $locale['414'];
	} elseif ($_GET['status'] == "delnc") {
		$message = $locale['412']."<br />\n<span class='small'>".$locale['415']."</span>";
	}
	if (isset($message)) { echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
}

if ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
	$count = dbcount("(article_cat)", DB_ARTICLES, "article_cat='".(int)$_GET['cat_id']."'");
	if ($count != 0) {
		redirect(FUSION_SELF.$aidlink."&status=deln");
	} else {
		$result = dbquery("SELECT article_cat_name FROM ".DB_ARTICLE_CATS." WHERE article_cat_id='".$_GET['cat_id']."'");
		if(dbrows($result)) {
			$data = dbarray($result);
			$result = dbquery("DELETE FROM ".DB_ARTICLE_CATS." WHERE article_cat_id='".$_GET['cat_id']."'");
			log_admin_action("admin-1", "admin_article_cat_deleted", "", "", $data['article_cat_name']);
			redirect(FUSION_SELF.$aidlink."&status=dely");
		} else {
			redirect(FUSION_SELF.$aidlink."&status=delnc");
		}
	}
} else {
	if (isset($_POST['save_cat'])) {
		$cat_name = stripinput(trim($_POST['cat_name']));
		$cat_description = stripinput(trim($_POST['cat_description']));
		if(IF_MULTI_LANGUAGE) { $cat_language = stripinput($_POST['cat_language']); } else { $cat_language = true; } // pimped
		$cat_parent = isnum($_POST['cat_parent']) ? $_POST['cat_parent'] : "0";//pimped
		$cat_access = isnum($_POST['cat_access']) ? $_POST['cat_access'] : "0";		
		if (isnum($_POST['cat_sort_by']) && $_POST['cat_sort_by'] == "1") {
			$cat_sorting = "article_id ".($_POST['cat_sort_order'] == "ASC" ? "ASC" : "DESC");
		} else if (isnum($_POST['cat_sort_by']) && $_POST['cat_sort_by'] == "2") {
			$cat_sorting = "article_subject ".($_POST['cat_sort_order'] == "ASC" ? "ASC" : "DESC");
		} else if (isnum($_POST['cat_sort_by']) && $_POST['cat_sort_by'] == "3") {
			$cat_sorting = "article_datestamp ".($_POST['cat_sort_order'] == "ASC" ? "ASC" : "DESC");
		} else {
			$cat_sorting = "article_subject ASC";
		}
		if ($cat_name && $cat_language) { // Pimped
			if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
				if(IF_MULTI_LANGUAGE) { $insert_mysql = ", article_cat_language='$cat_language'"; } else { $insert_mysql = ''; } // Pimped
				$result = dbquery("UPDATE ".DB_ARTICLE_CATS." SET article_cat_name='$cat_name', article_cat_description='$cat_description',
				article_cat_sorting='$cat_sorting', article_cat_parent='$cat_parent', article_cat_access='$cat_access'".$insert_mysql."
				WHERE article_cat_id='".$_GET['cat_id']."'"); // Pimped
				log_admin_action("admin-1", "admin_article_cat_edited", "", "", $cat_name);
				redirect(FUSION_SELF.$aidlink."&status=su");
			} else {
				if(IF_MULTI_LANGUAGE){
					$insert_mysql1 = ", article_cat_language"; $insert_mysql2 = ", '$cat_language'";
				} else {
					$insert_mysql1 = ''; $insert_mysql2 = '';
				} // Pimped
				$result = dbquery("INSERT INTO ".DB_ARTICLE_CATS." (article_cat_name, article_cat_description, article_cat_sorting,
				article_cat_parent, article_cat_access".$insert_mysql1.") VALUES
				('$cat_name', '$cat_description', '$cat_sorting', '$cat_parent', '$cat_access'".$insert_mysql2.")");// Pimped
				log_admin_action("admin-1", "admin_article_cat_added", "", "", $cat_name);
				redirect(FUSION_SELF.$aidlink."&status=sn");
			}
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
	if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
		$result = dbquery("SELECT article_cat_name, article_cat_description, article_cat_language,
		article_cat_sorting, article_cat_parent, article_cat_access
		FROM ".DB_ARTICLE_CATS." WHERE article_cat_id='".(int)$_GET['cat_id']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$cat_name = $data['article_cat_name'];
			$cat_description = $data['article_cat_description'];
			if(IF_MULTI_LANGUAGE) $cat_language = $data['article_cat_language']; // Pimped
			$cat_sorting = explode(" ", $data['article_cat_sorting']);
			if ($cat_sorting[0] == "article_id") { $cat_sort_by = "1"; }
			if ($cat_sorting[0] == "article_subject") { $cat_sort_by = "2"; }
			if ($cat_sorting[0] == "article_datestamp") { $cat_sort_by = "3"; }
			$cat_sort_order = $cat_sorting[1];
			$cat_parent = $data['article_cat_parent']; // Pimped
			$cat_access = $data['article_cat_access'];
			$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".(int)$_GET['cat_id'];
			opentable($locale['401']);
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	} else {
		$cat_name = "";
		$cat_description = "";
		$cat_language = ""; // Pimped
		$cat_sort_by = "2";
		$cat_sort_order = "ASC";
		$cat_parent = ""; // Pimped
		$cat_access = "";
		$formaction = FUSION_SELF.$aidlink;
		opentable($locale['400']);
	}
	$user_groups = getusergroups(); $access_opts = ""; $sel = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($cat_access == $user_group['0'] ? " selected='selected'" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	// Subcategory begin
	$editlist = ""; $sel = "";
	$result2 = dbquery("SELECT article_cat_id, article_cat_name FROM ".DB_ARTICLE_CATS."
	WHERE article_cat_parent='0' ".((isset($_GET['cat_id']) && isnum($_GET['cat_id'])) ? "AND article_cat_id!='".(int)$_GET['cat_id']."' " : "")."
	ORDER BY article_cat_name");
	if (dbrows($result2) != 0) {
	        $editlist .= "<option value='0'".$sel."><span class='small'></span></option>\n";
		while ($data2 = dbarray($result2)) {
			if (isset($_GET['action']) && $_GET['action'] == "edit") { $sel = ($data['article_cat_parent'] == $data2['article_cat_id'] ? " selected='selected'" : ""); }
			$editlist .= "<option value='".$data2['article_cat_id']."'$sel>".$data2['article_cat_name']."</option>\n";
		}
	}
	// Subcategory end
	echo "<form name='addcat' method='post' action='$formaction'>\n";
	echo "<table cellpadding='0' cellspacing='0' width='400' class='center'>\n<tr>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['420']."</td>\n";
	echo "<td class='tbl'><input type='text' name='cat_name' value='".$cat_name."' class='textbox' style='width:250px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['421']."</td>\n";
	echo "<td class='tbl'><input type='text' name='cat_description' value='".$cat_description."' class='textbox' style='width:250px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	if(IF_MULTI_LANGUAGE) { // Pimped
	echo "<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['431']."</td>\n"; // Pimped
	$opts = make_admin_language_opts($cat_language); // Pimped
	echo "<td class='tbl'><select name='cat_language' class='textbox' style='width:200px;'>\n".$opts."</select></td>\n"; // Pimped
	echo "</tr>\n<tr>\n"; // Pimped
	}  // Pimped
	echo "<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['422']."</td>\n";
	echo "<td class='tbl'><select name='cat_sort_by' class='textbox'>\n";
	echo "<option value='1'".($cat_sort_by == "1" ? " selected='selected'" : "").">".$locale['423']."</option>\n";
	echo "<option value='2'".($cat_sort_by == "2" ? " selected='selected'" : "").">".$locale['424']."</option>\n";
	echo "<option value='3'".($cat_sort_by == "3" ? " selected='selected'" : "").">".$locale['425']."</option>\n";
	echo "</select>\n<select name='cat_sort_order' class='textbox'>\n";
	echo "<option value='ASC'".($cat_sort_order == "ASC" ? " selected='selected'" : "").">".$locale['426']."</option>\n";
	echo "<option value='DESC'".($cat_sort_order == "DESC" ? " selected='selected'" : "").">".$locale['427']."</option>\n";
	echo "</select></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['430']."</td>\n"; // Pimped
	echo "<td class='tbl'><select name='cat_parent' class='textbox' style='width:150px;'>\n".$editlist."</select></td>\n"; // Pimped
	echo "</tr>\n<tr>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['428']."</td>\n";
	echo "<td class='tbl'><select name='cat_access' class='textbox' style='width:150px;'>\n".$access_opts."</select></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' colspan='2' class='tbl'>\n";
	echo "<input type='submit' name='save_cat' value='".$locale['429']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();

	opentable($locale['402']);
	echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n";
	$result = dbquery("SELECT article_cat_id, article_cat_name, article_cat_description, article_cat_language, article_cat_access
	FROM ".DB_ARTICLE_CATS." WHERE article_cat_parent='0' ORDER BY article_cat_name");
	if (dbrows($result) != 0) {
		$i = 0;
		echo "<tr>\n";
		echo "<td class='tbl2'>".$locale['440']."</td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['441']."</td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['442']."</td>\n";
		echo "</tr>\n";
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "<tr>\n";
			echo "<td class='".$cell_color."'><strong>".$data['article_cat_name']."</strong>"; // Pimped
			echo ($data['article_cat_description'] ? "<br />\n<span class='small'>".trimlink($data['article_cat_description'], 45)."</span>" : ""); // Pimped
			if(IF_MULTI_LANGUAGE) echo "<br />".$locale['431']." ".$data['article_cat_language']."\n"; // Pimped
			echo "</td>\n"; // Pimped
			echo "<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getgroupname($data['article_cat_access'])."</td>\n";
			echo "<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data['article_cat_id']."'>".$locale['443']."</a> -\n";
			echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;cat_id=".$data['article_cat_id']."' onclick=\"return confirm('".$locale['450']."');\">".$locale['444']."</a></td>\n";
			echo "</tr>\n";
			echo subcats($data['article_cat_id']); // Pimped
			$i++;
		}
		echo "</table>\n";
	} else {
		echo "<tr><td align='center' class='tbl1'>".$locale['445']."</td></tr>\n</table>\n";
	}
	closetable();
}

// Subcategory begin
function subcats($id) {
	global $aidlink, $locale;
	$sublist = "";
	$result2 = dbquery("SELECT article_cat_id, article_cat_name, article_cat_description, article_cat_language, article_cat_access
	FROM ".DB_ARTICLE_CATS." WHERE article_cat_parent='".(int)$id."' ORDER BY article_cat_name");
	while ($data2 = dbarray($result2)) {
		$sublist .= "<tr>\n";
		$sublist .= "<td class='tbl1'><strong>--".$data2['article_cat_name']."</strong>";
		$sublist .= ($data2['article_cat_description'] ? "<br />\n<span class='small'>".trimlink($data2['article_cat_description'], 45)."</span>" : "");
		if(IF_MULTI_LANGUAGE) $sublist .= "<br />".$locale['431']." ".$data2['article_cat_language']."\n";
		$sublist .= "</td>\n";
		$sublist .= "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".getgroupname($data2['article_cat_access'])."</td>\n";
		$sublist .= "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data2['article_cat_id']."'>".$locale['443']."</a> -\n";
		$sublist .= "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;cat_id=".$data2['article_cat_id']."' onclick=\"return confirm('".$locale['450']."');\">".$locale['444']."</a></td>\n";
		$sublist .= "</tr>\n";		
	}
	return $sublist;
}
// Subcategory end

require_once TEMPLATES."footer.php";
?>