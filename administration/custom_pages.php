<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: custom_pages.php
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
require_once TEMPLATES."admin_header_editor.php";
include LOCALE.LOCALESET."admin/custom_pages.php";
if ($settings['enable_tags']) require_once INCLUDES."tag_include.php"; // Pimped: tag

if (!checkrights("CP") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if(isset($_COOKIE['custompages_wysiwyg_editor']) && $_COOKIE['custompages_wysiwyg_editor'] == 1 && $settings['wysiwyg_enabled'] == "tinymce") {
	echo "<script language='javascript' type='text/javascript'>advanced();</script>\n";
} elseif(isset($_COOKIE['custompages_wysiwyg_editor']) && $_COOKIE['custompages_wysiwyg_editor'] == 1 && $settings['wysiwyg_enabled'] == "ckeditor") {
	// nothing here
} else {
	require_once INCLUDES."html_buttons_include.php";
}

// Pimped ->
if(isset($_POST['edit']) || isset($_GET['edit'])) { $request_edit = true; } else { $request_edit = false; }
if(isset($_POST['page_id']) && isnum($_POST['page_id'])) {
	$request_page_id = $_POST['page_id'];
} elseif(isset($_GET['page_id']) && isnum($_GET['page_id'])) {
	$request_page_id = $_GET['page_id'];
} else {
	$request_page_id = false;
}
// Pimped <-

if (isset($_GET['status']) && !isset($message)) {
	if (isset($_GET['pid']) && isnum($_GET['pid']) && ($_GET['status'] == "sn" || $_GET['status'] == "su")) {
		if(URL_REWRITE) { // Pimped: Url-Rewrite ->
			$result = dbquery("SELECT page_title FROM ".DB_CUSTOM_PAGES." WHERE page_id='".(int)$_GET['pid']."'");
			if (dbrows($result)) {
				$data = dbarray($result);
				$page_title = $data['page_title'];
			}
		} else {
			$page_title = '';
		}
		$link = make_url("viewpage.php?page_id=".intval($_GET['pid']), SEO_PAGE_A.SEO_PAGE_B1.intval($_GET['pid']).SEO_PAGE_B2, $page_title, SEO_PAGE_C);
	}	 // Pimped <-

	if ($_GET['status'] == "sn") {
		$message = $locale['410']."<br />\n".$locale['412']."\n<a href='".BASEDIR.$link."'>".$link."</a>\n"; // Pimped: Link
	} elseif ($_GET['status'] == "su") {
		$message = $locale['411']."<br />\n".$locale['412']."\n<a href='".BASEDIR.$link."'>".$link."</a>\n"; // Pimped: Link
	} elseif ($_GET['status'] == "del") {
		$message = $locale['413'];
	} elseif ($_GET['status'] == "pw") {
		$message = $locale['global_182'];
	}
	if ($message) { echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
}

if (isset($_POST['save'])) {
	$page_title = stripinput($_POST['page_title']);
	$page_access = isnum($_POST['page_access']) ? $_POST['page_access'] : "0";
	$page_content = addslash($_POST['page_content']);
	$tag_name = isset($_POST['tag_name']) ? stripinput($_POST['tag_name']) : ""; // Pimped: tag
	$page_keywords = stripinput($_POST['page_keywords']); // meta
	$comments = isset($_POST['page_comments']) ? "1" : "0";
	$ratings = isset($_POST['page_ratings']) ? "1" : "0";
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		if (isset($_POST['page_id']) && isnum($_POST['page_id'])) {
			$result = dbquery("UPDATE ".DB_CUSTOM_PAGES." SET page_title='$page_title', page_access='$page_access', page_content='$page_content', page_keywords='$page_keywords', page_allow_comments='$comments', page_allow_ratings='$ratings' WHERE page_id='".$_POST['page_id']."'");
			if ($settings['enable_tags']) update_tags($_POST['page_id'], "C", $tag_name); // Pimped: tag
			log_admin_action("admin-1", "admin_custompage_edited", "", "", $page_title." (ID: ".$_POST['page_id'].")");
		} else {
			$result = dbquery("INSERT INTO ".DB_CUSTOM_PAGES." (page_title, page_access, page_content, page_keywords, page_allow_comments, page_allow_ratings) VALUES ('$page_title', '$page_access', '$page_content', '$page_keywords', '$comments', '$ratings')");
			$page_id = mysql_insert_id();
			log_admin_action("admin-1", "admin_custompage_added", "", "", $page_title." (ID: ".$page_id.")");
			if (isset($_POST['add_link'])) {
				$data = dbarray(dbquery("SELECT link_order FROM ".DB_SITE_LINKS." ORDER BY link_order DESC LIMIT 1"));
				$link_order = $data['link_order'] + 1;
				$result = dbquery("INSERT INTO ".DB_SITE_LINKS." (link_name, link_url, link_seo_url,link_visibility, link_position, link_window, link_order) VALUES ('$page_title', 'viewpage.php?page_id=$page_id', 'page-".$page_id."-".clean_subject_urlrewrite($page_title).".html', '$page_access', '1', '0', '$link_order')"); // Pimped SEO Url added
				if ($settings['enable_tags']) {
					$id = mysql_insert_id(); // Pimped: tag
					insert_tags($id, "C", $tag_name); // Pimped: tag
				}
			}
		}
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		if (isset($_POST['page_id']) && isnum($_POST['page_id'])) {
			redirect(FUSION_SELF.$aidlink."&status=su&pid=".$_POST['page_id'], true);
		} else {
			redirect(FUSION_SELF.$aidlink."&status=sn&pid=".$page_id, true);
		}
	} else {
		redirect(FUSION_SELF.$aidlink."&status=pw");
	}
} else if (isset($_POST['delete']) && (isset($_POST['page_id']) && isnum($_POST['page_id']))) {
	$result = dbquery("SELECT page_title FROM ".DB_CUSTOM_PAGES." WHERE page_id='".$_POST['page_id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
	}
	$result = dbquery("DELETE FROM ".DB_CUSTOM_PAGES." WHERE page_id='".$_POST['page_id']."'");
	$result = dbquery("DELETE FROM ".DB_SITE_LINKS." WHERE link_url='viewpage.php?page_id=".$_POST['page_id']."'");
	if ($settings['enable_tags']) delete_tags($_POST['page_id'], "C"); // Pimped: tag
	log_admin_action("admin-1", "admin_custompage_deleted", "", "", $data['page_title']." (ID: ".$_POST['page_id'].")");
	redirect(FUSION_SELF.$aidlink."&status=del");
} else {
	if (isset($_POST['preview'])) {
		$addlink = isset($_POST['add_link']) ? " checked='checked'" : "";
		$page_title = stripinput($_POST['page_title']);
		$page_access = $_POST['page_access'];
		$page_content = stripslash($_POST['page_content']);
		$page_keywords = $_POST['page_keywords']; // meta
		$comments = isset($_POST['page_comments']) ? " checked='checked'" : "";
		$ratings = isset($_POST['page_ratings']) ? " checked='checked'" : "";
		if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
			log_admin_action("admin-1", "admin_custompage_preview", "", "", $page_title);
			opentable($page_title);
			eval("?>".$page_content."<?php ");
			closetable();
			set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		} else {
			echo "<div id='close-message'><div class='admin-message'>".$locale['global_182']."</div></div>\n";
		}
		$page_content = phpentities($page_content);
	}
	$result = dbquery("SELECT page_id, page_title FROM ".DB_CUSTOM_PAGES." ORDER BY page_title");
	if (dbrows($result) != 0) {
		$editlist = ""; $sel = "";
		while ($data = dbarray($result)) {
			if (isset($request_page_id)) { $sel = ($request_page_id == $data['page_id'] ? " selected='selected'" : ""); }
			$editlist .= "<option value='".$data['page_id']."'$sel>[".$data['page_id']."] ".$data['page_title']."</option>\n";
		}
		opentable($locale['402']);
		echo "<div style='text-align:center'>\n<form name='selectform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
		echo "<select name='page_id' class='textbox' style='width:200px;'>\n".$editlist."</select>\n";
		echo "<input type='submit' name='edit' value='".$locale['420']."' class='button' />\n";
		echo "<input type='submit' name='delete' value='".$locale['421']."' onclick='return DeletePage();' class='button' />\n";
		echo "</form>\n</div>\n";
		closetable();
	}

	if ($request_edit && $request_page_id) { // Pimped
		$result = dbquery("SELECT page_title, page_access, page_content, page_keywords, page_allow_comments, page_allow_ratings
		FROM ".DB_CUSTOM_PAGES." WHERE page_id='".$request_page_id."'"); // Pimped
		if (dbrows($result)) {
			$data = dbarray($result);
			$page_title = $data['page_title'];
			$page_access = $data['page_access'];
			$page_content = phpentities(stripslashes($data['page_content']));
			$page_keywords = $data['page_keywords']; // meta
			$comments = ($data['page_allow_comments'] == "1" ? " checked='checked'" : "");
			$ratings = ($data['page_allow_ratings'] == "1" ? " checked='checked'" : "");
			$addlink = "";
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
	if ($request_page_id) { // Pimped
		opentable($locale['401'].": [".$request_page_id."] ".$page_title); // Pimped
	} else {
		if (!isset($_POST['preview'])) {
			$page_title = "";
			$page_access = "";
			$page_content = "";
			$page_keywords = $settings['keywords'];
			$comments = " checked='checked'";
			$ratings = " checked='checked'";
			$addlink = "";
		}
		opentable($locale['400']);
	}
	$user_groups = getusergroups(); $access_opts = ""; $sel = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($page_access == $user_group['0'] ? " selected='selected'" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='inputform' method='post' action='".FUSION_SELF.$aidlink."' onsubmit='return ValidateForm(this);'>\n";
	echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
	if ($settings['wysiwyg_enabled'] == "tinymce" || $settings['wysiwyg_enabled'] == "ckeditor") {
		echo "<td width='100' class='tbl'>".$locale['globad_wy100']."</td>\n";
		echo "<td width='80%' class='tbl'><input type='button' id='wysiwyg_switch' name='wysiwyg_switch' value='".(!isset($_COOKIE['custompages_wysiwyg_editor']) || $_COOKIE['custompages_wysiwyg_editor'] == 0 ? $locale['globad_wy101'] : $locale['globad_wy102'])."' class='button' style='width:75px;' onclick=\"SetWYSIWYG(".(!isset($_COOKIE['custompages_wysiwyg_editor']) || $_COOKIE['custompages_wysiwyg_editor'] == 0 ? 1 : 0).");\"/>\n</td>\n";
		echo "</tr>\n<tr>\n";	
	}	
	echo "<td width='100' class='tbl'>".$locale['422']."</td>\n";
	echo "<td width='80%' class='tbl'><input type='text' name='page_title' value='".$page_title."' class='textbox' style='width:250px;' />\n";
	echo "&nbsp;".$locale['423']."<select name='page_access' class='textbox' style='width:150px;'>\n".$access_opts."</select></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td valign='top' width='100' class='tbl'>".$locale['424']."</td>\n";
	echo "<td width='80%' class='tbl'><textarea name='page_content' cols='100' rows='25' class='textbox' style='width:98%'>".$page_content."</textarea></td>\n";
	echo "</tr>\n<tr>\n";
	if (!isset($_COOKIE['custompages_wysiwyg_editor']) || !$_COOKIE['custompages_wysiwyg_editor'] || !$settings['wysiwyg_enabled']) {
	echo "<td class='tbl'></td><td class='tbl'>\n";
	echo "<input type='button' value='&lt;?php?&gt;' class='button' style='width:60px;' onclick=\"addText('page_content', '&lt;?php\\n', '\\n?&gt;');\" />\n";
	echo "<input type='button' value='&lt;p&gt;' class='button' style='width:35px;' onclick=\"addText('page_content', '&lt;p&gt;', '&lt;/p&gt;');\" />\n";
	echo "<input type='button' value='&lt;br /&gt;' class='button' style='width:40px;' onclick=\"insertText('page_content', '&lt;br /&gt;');\" />\n";
	echo display_html("inputform", "page_content", true)."</td>\n";
	echo "</tr>\n";
	}
	if ($settings['enable_tags']) {
		if ($request_edit && $request_page_id) {
			echo edit_tags($request_page_id, "C"); // Pimped: tag
		} else {
			echo add_tags("C"); // Pimped: tag
		}
	}
	echo "<tr>\n";
	echo "<td width='100' class='tbl'>".$locale['431']."</td>\n"; // meta
	echo "<td class='tbl'><input type='text' name='page_keywords' value='".$page_keywords."' class='textbox' style='width:250px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		echo "<td class='tbl'>".$locale['425']."</td>\n";
		echo "<td class='tbl'><input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n";
		echo "</tr>\n<tr>\n";
	}
	echo "<td class='tbl'></td><td class='tbl'>\n";
	if (!$request_page_id) { // Pimped
		echo "<label><input type='checkbox' name='add_link' value='1'".$addlink." />  ".$locale['426']."</label><br />\n";
	}
	echo "<label><input type='checkbox' name='page_comments' value='1'".$comments." /> ".$locale['427']."</label><br />\n";
	echo "<label><input type='checkbox' name='page_ratings' value='1'".$ratings." /> ".$locale['428']."</label>\n";
	echo "</td>\n</tr>\n";
	// Pimped: ->
	if($request_page_id) {
		echo "<tr>\n<td class='tbl'>\n";
		$link = make_url("viewpage.php?page_id=".$request_page_id, SEO_PAGE_A.SEO_PAGE_B1.$request_page_id.SEO_PAGE_B2, $page_title, SEO_PAGE_C);
		echo "Site Link: </td><td class='tbl'><a href='".BASEDIR.$link."'>".$link."</a>";
		echo "</td>\n</tr>\n";
	}
	// Pimped <-
	echo "<tr>\n<td align='center' colspan='2' class='tbl'><br />\n";
	if ($request_page_id) { // Pimped
		echo "<input type='hidden' name='page_id' value='".$request_page_id."' />\n"; // Pimped
	}
	echo "<input type='submit' name='preview' value='".$locale['429']."' class='button' />\n";
	echo "<input type='submit' name='save' value='".$locale['430']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();
	echo "<script type='text/javascript'>\n"."function DeletePage() {\n";
	echo "return confirm('".$locale['450']."');\n}"."\n";
	echo "function ValidateForm(frm) {\n"."if(frm.page_title.value=='') {\n";
	echo "alert('".$locale['451']."');\n"."return false;\n}\n";
	echo "if(frm.admin_password.value=='') {\n"."alert('".$locale['452']."');\n";
	echo "return false;\n}\n}\n";
	if ($settings['wysiwyg_enabled'] == "tinymce" || $settings['wysiwyg_enabled'] == "ckeditor") {
		echo "function SetWYSIWYG(val) {\n";
		echo "now=new Date();\n"."now.setTime(now.getTime()+1000*60*60*24*365);\n";
		echo "expire=(now.toGMTString());\n"."document.cookie=\"custompages_wysiwyg_editor=\"+escape(val)+\";expires=\"+expire;\n";
		echo "location.href='".FUSION_SELF.$aidlink."';\n"."}\n";
	}
	echo "</script>\n";
	if(isset($_COOKIE['custompages_wysiwyg_editor']) && $_COOKIE['custompages_wysiwyg_editor'] == 1 && $settings['wysiwyg_enabled'] == "ckeditor") {
		echo "<script type='text/javascript'>";
		echo "CKEDITOR.replace( 'page_content' );";
		echo "</script>";
	}
	
}

require_once TEMPLATES."footer.php";
?>