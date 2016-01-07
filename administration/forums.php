<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forums.php
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
include LOCALE.LOCALESET."admin/forums.php";

if (!checkrights("F") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['action']) && $_GET['action'] == "prune") { require_once "forums_prune.php"; }

if (isset($_GET['action']) && $_GET['action'] == "refresh") {
	$i = 1;
	$result = dbquery("SELECT forum_id FROM ".DB_FORUMS." WHERE forum_cat='0' ORDER BY forum_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".DB_FORUMS." SET forum_order='".$i."' WHERE forum_id='".$data['forum_id']."'");
		$result2 = dbquery("SELECT forum_id FROM ".DB_FORUMS." WHERE forum_parent='0' AND forum_cat='".$data['forum_id']."' ORDER BY forum_order");
		$k = 1;
		while ($data2 = dbarray($result2)) {
			$result3 = dbquery("UPDATE ".DB_FORUMS." SET forum_order='".$k."' WHERE forum_id='".$data2['forum_id']."'");
			echo refresh($data2['forum_id']);
			$k++;
		}
		$i++;
	}
}

function refresh($parent){
	$result2 = dbquery("SELECT forum_id FROM ".DB_FORUMS." WHERE forum_parent='".$parent."' ORDER BY forum_order");
	$k = 1;
	$list = "";
	while ($data2 = dbarray($result2)) {
		$result3 = dbquery("UPDATE ".DB_FORUMS." SET forum_order='".$k."' WHERE forum_id='".$data2['forum_id']."'");
		$k++;
		$list .= refresh($data2['forum_id']);
	}
	return $list;
}


if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "savecn") {
		$message = $locale['410'];
	} elseif ($_GET['status'] == "savecu") {
		$message = $locale['411'];
	} elseif ($_GET['status'] == "savecf") {
		$message = "Error: Forum Category not updated/added";
	} elseif ($_GET['status'] == "savefn") {
		$message = $locale['510'];
	} elseif ($_GET['status'] == "savefu") {
		$message = $locale['511'];
	} elseif ($_GET['status'] == "savefm") {
		$message = $locale['515'];
	} elseif ($_GET['status'] == "delcn") {
		$message = $locale['412']."<br />\n<span class='small'>".$locale['413']."</span>";
	} elseif ($_GET['status'] == "delcy") {
		$message = $locale['414'];
	} elseif ($_GET['status'] == "delfn") {
		$message = $locale['512']."<br />\n<span class='small'>".$locale['513']."</span>";
	} elseif ($_GET['status'] == "delfy") {
		$message = $locale['514'];
	}
	if (isset($message)) { echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
}	

if (isset($_POST['save_cat'])) {
	$cat_name = trim(stripinput($_POST['cat_name']));
	if(IF_MULTI_LANGUAGE_FORUM) { $cat_language = stripinput($_POST['cat_language']); } else { $cat_language = true; }
	if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['forum_id']) && isnum($_GET['forum_id'])) && (isset($_GET['t']) && $_GET['t'] == "cat")) {
		if(IF_MULTI_LANGUAGE_FORUM) { $insert_mysql = ", forum_language='$cat_language'"; } else { $insert_mysql = ''; }
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_name="._db($cat_name).$insert_mysql." WHERE forum_id='".$_GET['forum_id']."'");
		log_admin_action("admin-1", "admin_forumcat_edited", "", "", $cat_name." (ID: ".$_GET['forum_id'].")");
		redirect(FUSION_SELF.$aidlink."&status=".($result ? "savecu" : "savecf"));
	} else {
		if ($cat_name) {
			$cat_order = isnum($_POST['cat_order']) ? $_POST['cat_order'] : "";
			if(IF_MULTI_LANGUAGE_FORUM){ $insert_mysql1 = ", forum_language"; $insert_mysql2 = ", '$cat_language'"; } else { $insert_mysql1 = ''; $insert_mysql2 = ''; } // Pimped
			if(!$cat_order) { $cat_order = dbresult(dbquery("SELECT MAX(forum_order) FROM ".DB_FORUMS." WHERE forum_cat='0'"),0)+1; }
			$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order+1 WHERE forum_cat='0' AND forum_order>='$cat_order'");	
			$result = dbquery("INSERT INTO ".DB_FORUMS." (forum_cat, forum_name, forum_order, forum_description".$insert_mysql1.", forum_moderators, forum_access, forum_post, forum_reply, forum_poll, forum_vote, forum_attach, forum_image, forum_lastpost, forum_lastuser) VALUES ('0', '$cat_name', '$cat_order', ''".$insert_mysql2.", '', '', '0', '0', '0', '0', '0', '', '0', '0')");
			$cat_id = mysql_insert_id();
			log_admin_action("admin-1", "admin_forumcat_added", "", "", $cat_name." (ID: ".$cat_id.")");
			redirect(FUSION_SELF.$aidlink."&status=".($result ? "savecn" : "savecf"));
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
} elseif (isset($_POST['save_forum'])) {
	$forum_name = trim(stripinput($_POST['forum_name']));
	$forum_description = trim(stripinput($_POST['forum_description']));
	$forum_cat = isnum($_POST['forum_cat']) ? $_POST['forum_cat'] : 0;
	$forum_parent = isnum($_POST['forum_parent']) ? $_POST['forum_parent'] : 0;
	$forum_image = $_POST['forum_image'];
	if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['forum_id']) && isnum($_GET['forum_id'])) && (isset($_GET['t']) && $_GET['t'] == "forum")) {
		$forum_mods = $_POST['forum_mods'];
		$forum_access = $_POST['forum_access'];
		$forum_post = $_POST['forum_post'];
		$forum_reply = $_POST['forum_reply'];
		$forum_attach = $_POST['forum_attach'];
		$forum_poll = $_POST['forum_poll'];
		$forum_vote = $_POST['forum_vote'];
		$forum_markresolved = isnum($_POST['forum_markresolved']) ? $_POST['forum_markresolved'] : 0;
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_name="._db($forum_name).", forum_cat="._db($forum_cat).", forum_parent="._db($forum_parent).", forum_description="._db($forum_description).", forum_moderators="._db($forum_mods).", forum_access="._db($forum_access).", forum_post="._db($forum_post).", forum_reply="._db($forum_reply).", forum_attach="._db($forum_attach).", forum_image="._db($forum_image).", forum_markresolved="._db($forum_markresolved).", forum_poll="._db($forum_poll).", forum_vote="._db($forum_vote)." WHERE forum_id='".(int)$_GET['forum_id']."'");
		log_admin_action("admin-1", "admin_forum_edited", "", "", $forum_name." (ID: ".(int)$_GET['forum_id'].")");
		redirect(FUSION_SELF.$aidlink."&status=savefu");
	} else {
		if ($forum_name) {
			$forum_order = isnum($_POST['forum_order']) ? $_POST['forum_order'] : "";
			if(!$forum_order) $forum_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".DB_FORUMS." WHERE forum_parent='$forum_parent' AND forum_cat='$forum_cat'"),0)+1;
		 	$result = dbquery("INSERT INTO ".DB_FORUMS." (forum_cat, forum_parent, forum_name, forum_order, forum_description, forum_moderators, forum_access, forum_post, forum_reply, forum_attach, forum_image, forum_poll, forum_vote, forum_lastpost, forum_lastuser) VALUES ('".$forum_cat."', '".$forum_parent."', '".$forum_name."', '".$forum_order."', '".$forum_description."', '".nADMIN."', '".nMEMBER."', '".nMEMBER."', '".nMEMBER."', '0', "._db($forum_image).", '0', '0', '0', '0')");
			$f_id = mysql_insert_id();
			log_admin_action("admin-1", "admin_forum_added", "", "", $forum_name." (ID: ".$f_id.")");
			redirect(FUSION_SELF.$aidlink."&status=savefn");
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	}
} elseif ((isset($_GET['action']) && $_GET['action'] == "mu") && (isset($_GET['forum_id']) && isnum($_GET['forum_id'])) && (isset($_GET['order']) && isnum($_GET['order']))) {
	if (isset($_GET['t']) && $_GET['t'] == "cat") {
		$data = dbarray(dbquery("SELECT forum_id FROM ".DB_FORUMS." WHERE forum_cat='0' AND forum_order='".$_GET['order']."'"));
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order+1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order-1 WHERE forum_id='".$_GET['forum_id']."'");
	} elseif ((isset($_GET['t']) && $_GET['t'] == "forum") && (isset($_GET['cat']) && isnum($_GET['cat']))) {
		$parent = (isset($_GET['parent']) && isnum($_GET['parent']) ? " AND forum_parent='".$_GET['parent']."'" : "");
		$data = dbarray(dbquery("SELECT forum_id FROM ".DB_FORUMS." WHERE forum_cat='".$_GET['cat']."' AND forum_order='".$_GET['order']."'".$parent));
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order+1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order-1 WHERE forum_id='".$_GET['forum_id']."'");
	}
	redirect(FUSION_SELF.$aidlink);
} elseif ((isset($_GET['action']) && $_GET['action'] == "md") && (isset($_GET['forum_id']) && isnum($_GET['forum_id'])) && (isset($_GET['order']) && isnum($_GET['order']))) {
	if (isset($_GET['t']) && $_GET['t'] == "cat") {
		$data = dbarray(dbquery("SELECT forum_id FROM ".DB_FORUMS." WHERE forum_cat='0' AND forum_order='".$_GET['order']."'"));
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order-1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order+1 WHERE forum_id='".$_GET['forum_id']."'");
	} elseif ((isset($_GET['t']) && $_GET['t'] == "forum") && (isset($_GET['cat']) && isnum($_GET['cat']))) {
		$parent = (isset($_GET['parent']) && isnum($_GET['parent']) ? " AND forum_parent='".$_GET['parent']."'" : "");
		$data = dbarray(dbquery("SELECT forum_id FROM ".DB_FORUMS." WHERE forum_cat='".$_GET['cat']."' AND forum_order='".$_GET['order']."'".$parent));
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order-1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order+1 WHERE forum_id='".$_GET['forum_id']."'");
	}
	redirect(FUSION_SELF.$aidlink);
} elseif ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['forum_id']) && isnum($_GET['forum_id'])) && (isset($_GET['t']) && $_GET['t'] == "cat")) {
	if (!dbcount("(forum_id)", DB_FORUMS, "forum_cat='".$_GET['forum_id']."'")) {
		$data = dbarray(dbquery("SELECT forum_name, forum_order FROM ".DB_FORUMS." WHERE forum_id='".$_GET['forum_id']."'"));
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order-1 WHERE forum_cat='0' AND forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".DB_FORUMS." WHERE forum_id='".$_GET['forum_id']."'");
		log_admin_action("admin-1", "admin_forumcat_deleted", "", "", $data['forum_name']." (ID: ".$_GET['forum_id'].")");
		redirect(FUSION_SELF.$aidlink."&status=delcy");
	} else {
		redirect(FUSION_SELF.$aidlink."&status=delcn");
	}
} elseif ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['forum_id']) && isnum($_GET['forum_id'])) && (isset($_GET['t']) && $_GET['t'] == "forum")) {
	if (!dbcount("(thread_id)", DB_THREADS, "forum_id='".$_GET['forum_id']."'") && !dbcount("(forum_id)", DB_FORUMS."", "forum_parent='".$_GET['forum_id']."'")) {
		$parent = (isset($_GET['parent']) && isnum($_GET['parent']) ? " AND forum_parent='".$_GET['parent']."'" : "");
		$data = dbarray(dbquery("SELECT forum_name, forum_cat, forum_order FROM ".DB_FORUMS." WHERE forum_id='".$_GET['forum_id']."'"));
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_order=forum_order-1 WHERE forum_cat='".$data['forum_cat']."' AND forum_order>'".$data['forum_order']."'$parent");
		$result = dbquery("DELETE FROM ".DB_FORUMS." WHERE forum_id='".$_GET['forum_id']."'");
		log_admin_action("admin-1", "admin_forum_deleted", "", "", $data['forum_name']." (ID: ".$_GET['forum_id'].")");
		redirect(FUSION_SELF.$aidlink."&status=delfy");
	} else {
		redirect(FUSION_SELF.$aidlink."&status=delfn");
	}
} else {
	if((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['forum_id']) && isnum($_GET['forum_id']))){
		if(isset($_GET['t']) && $_GET['t'] == "cat"){
			$result = dbquery("SELECT forum_name, forum_language FROM ".DB_FORUMS." WHERE forum_id='".(int)$_GET['forum_id']."'");
			if(dbrows($result)) {
				$data = dbarray($result);
				$cat_name = $data['forum_name'];
				$cat_title = $locale['401'];
				$forum_cat_language = $data['forum_language']; // Pimped
				$cat_action = FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".(int)$_GET['forum_id']."&amp;t=cat";
				$forum_title = $locale['500'];
				$forum_action = FUSION_SELF.$aidlink;
			} else {
				redirect(FUSION_SELF.$aidlink);
			}
		}elseif (isset($_GET['t']) && $_GET['t'] == "forum"){
			$result = dbquery("SELECT forum_name, forum_description, forum_cat, forum_parent, forum_image,
			forum_moderators, forum_access, forum_post, forum_reply, forum_attach, forum_poll, forum_vote, forum_markresolved
			FROM ".DB_FORUMS." WHERE forum_id='".(int)$_GET['forum_id']."'");
			if (dbrows($result)) {
				$data = dbarray($result);
				$forum_name = $data['forum_name'];
				$forum_description = $data['forum_description'];
				$forum_cat = $data['forum_cat'];
				$forum_parent = $data['forum_parent'];
				$forum_cat_image = $data['forum_image'];
				#$forum_access = $data['forum_access'];
				$forum_post = $data['forum_post'];
				$forum_reply = $data['forum_reply'];
				$forum_attach = $data['forum_attach'];
				$forum_poll = $data['forum_poll'];
				$forum_vote = $data['forum_vote'];
				$forum_markresolved = $data['forum_markresolved'];
				$forum_title = $locale['501'];
				$forum_action = FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".(int)$_GET['forum_id']."&amp;t=forum";
				$cat_title = $locale['400'];
				$cat_action = FUSION_SELF.$aidlink;
			} else {
				redirect(FUSION_SELF.$aidlink);
			}
		}
	} else {
		$cat_name = "";
		$cat_order = "";
		$cat_title = $locale['400'];
		$cat_action = FUSION_SELF.$aidlink;
		$forum_name = "";
		$forum_description = "";
		$forum_cat = 0;
		$forum_parent = 0;
		$forum_cat_image = "default.png";
		$forum_cat_language = "";
		$forum_order = "";
		$forum_access = 0;
		$forum_post = 0;
		$forum_reply = 0;
		$forum_attach = 0;
		$forum_poll = 0;
		$forum_vote = 0;
		$forum_markresolved = 0;
		$forum_title = $locale['500'];
		$forum_action = FUSION_SELF.$aidlink;
	}
	if (!isset($_GET['t']) || $_GET['t'] != "forum") {
		opentable($cat_title);
		echo "<form name='addcat' method='post' action='".$cat_action."'>\n";
		echo "<table align='center' cellpadding='0' cellspacing='0' width='300'>\n<tr>\n";
		echo "<td class='tbl'>".$locale['420']."<br />\n";
		echo "<input type='text' name='cat_name' value='".$cat_name."' class='textbox' style='width:230px;' /></td>\n";
		echo "<td width='50' class='tbl'>";
		if (!isset($_GET['action']) || $_GET['action'] != "edit") {
			echo $locale['421']."<br />\n<input type='text' name='cat_order' value='".$cat_order."' class='textbox' style='width:45px;' />";
		}
		echo "</td>\n</tr>\n<tr>\n";
		if(IF_MULTI_LANGUAGE_FORUM) {
			echo "<td width='50' colspan='2' class='tbl'>".$locale['518']."<br />\n";
			$opts = make_admin_language_opts($forum_cat_language);
			echo "<select name='cat_language' class='textbox' style='width:200px;'>\n".$opts."</select></td>\n";
			echo "</tr>\n<tr>\n";
		}
		echo "<td align='center' colspan='2' class='tbl'>\n";
		echo "<input type='submit' name='save_cat' value='".$locale['422']."' class='button' /></td>\n";
		echo "</tr>\n</table>\n</form>\n";
		closetable();
	}
	if (!isset($_GET['t']) || $_GET['t'] != "cat") {
		$cat_opts = ""; $sel = "";
		$result2 = dbquery("SELECT forum_id, forum_name FROM ".DB_FORUMS." WHERE forum_cat='0' ORDER BY forum_order");
		if (dbrows($result2)) {
			while ($data2 = dbarray($result2)) {
				if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['t']) && $_GET['t'] == "forum")) { $sel = ($data2['forum_id'] == $forum_cat ? " selected='selected'" : ""); }
				$cat_opts .= "<option value='".$data2['forum_id']."'".$sel.">".$data2['forum_name']."</option>\n";
			}
		$parent_opts = ""; $parent_select = "";
		$parent_result = dbquery("SELECT forum_id, forum_name FROM ".DB_FORUMS." WHERE forum_cat!='0' AND forum_parent='0' ORDER BY forum_order");
		$parent_opts .= "<option value='0'".$parent_select."><span class='small'></option>\n";
			while ($parent_data = dbarray($parent_result)) {
				if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['t']) && $_GET['t'] == "forum")) { $parent_select = ($parent_data['forum_id'] == $forum_parent ? " selected='selected'" : ""); }
				$parent_opts .= "<option value='".$parent_data['forum_id']."'".$parent_select.">".$parent_data['forum_name']."</option>\n";
			}
	
			function create_options($selected, $hide=array(), $off=false) {
				global $locale; $option_list = ""; $options = getusergroups();
				if ($off) { $option_list = "<option value='0'>".$locale['531']."</option>\n"; }
				while(list($key, $option) = each($options)){
					if (!in_array($option['0'], $hide)) {
						$sel = ($selected == $option['0'] ? " selected='selected'" : "");
						$option_list .= "<option value='".$option['0']."'$sel>".$option['1']."</option>\n";
					}
				}
				return $option_list;
			}
			
			opentable($forum_title);
			echo "<form name='addforum' method='post' action='$forum_action'>\n";
			echo "<table align='center' cellpadding='0' cellspacing='0' width='300'>\n<tr>\n";
			echo "<td colspan='2' class='tbl'>".$locale['520']."<br />\n";
			echo "<input type='text' name='forum_name' value='".$forum_name."' class='textbox' style='width:285px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td colspan='2' class='tbl'>".$locale['521']."<br />\n";
			echo "<input type='text' name='forum_description' value='".$forum_description."' class='textbox' style='width:285px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td colspan='2' class='tbl'>".$locale['522']."<br />\n";
			echo "<select name='forum_cat' class='textbox' style='width:225px;'>\n".$cat_opts."</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>".$locale['519']."<br />\n";
			echo "<select name='forum_parent' class='textbox' style='width:225px;'>\n".$parent_opts."</select></td>\n";
			if (!isset($_GET['action']) || $_GET['action'] != "edit") { // Pimped: Forum Cat Images
				if($settings['forum_cat_icons'] == "1") {
					$image_files = makefilelist(IMAGES_FC, ".|..|index.php", true);
					$image_list = makefileopts($image_files,$forum_cat_image);
					echo "</tr>\n<tr>\n";
					echo "<td class='tbl'>".$locale['535']."<br />\n";
					echo "<select name='forum_image' class='textbox' style='width:200px;'>\n".$image_list."</select></td>\n";
				} else {
					echo "<input type='hidden' name='forum_image' value='".$forum_cat_image."'/>\n";
				}
			}
			echo "<td width='55' class='tbl'>";
			if (!isset($_GET['action']) || $_GET['action'] != "edit") {
				echo $locale['523']."<br />\n<input type='text' name='forum_order' value='".$forum_order."' class='textbox' style='width:45px;' />";
				echo "</td>\n</tr>\n<tr>\n";
				echo "<td align='center' colspan='2' class='tbl'>\n";
				echo "<input type='submit' name='save_forum' value='".$locale['532']."' class='button' />";
			}
			echo "</td>\n</tr>\n</table>\n";
			if (isset($_GET['action']) && $_GET['action'] == "edit") {
				echo "<br /><div class='tbl2'><strong>".$locale['524']."</strong></div><br />\n";
				#if (!isset($_GET['action']) || $_GET['action'] != "edit") {
				#	echo "<tr>\n<td align='center' colspan='2' class='tbl'>\n";
				#	echo "<input type='submit' name='save_forum' value='".$locale['532']."' class='button' /></td>\n";
				#	echo "</tr>\n</table>\n";
				#} ## not needed I think??
			}
			if (!isset($_GET['action'])) echo "\n</form>";
			if (isset($_GET['action']) && $_GET['action'] == "edit") {
			# Pimped ->
				$access_groups = getusergroups();
				while(list($key, $access_group) = each($access_groups)){
					if ($access_group['0'] != nSUPERADMIN) {
						if (!preg_match("(^{$access_group['0']}$|^{$access_group['0']}\.|\.{$access_group['0']}\.|\.{$access_group['0']}$)", $data['forum_access'])) {
							$access1_user_id[] = $access_group['0'];
							$access1_user_name[] = $access_group['1'];
						} else {
							$access2_user_id[] = $access_group['0'];
							$access2_user_name[] = $access_group['1'];
						}
					}
				}
				# Pimped: Layout testing
				add_to_head("
			<style type='text/css'>
			<!--
			.forum_admin {
			float: left;
			margin: 10px; padding: 10px;
			border: 1px dashed silver;
			}
			-->
			</style>
			");
				echo "<div class='forum_admin'>";
				echo "<strong>".$locale['525']."</strong><br />\n";
				echo "<select multiple='multiple' size='10' name='accesslist1' id='accesslist1' class='textbox' style='width:140px' onchange=\"addAccessUser('accesslist2','accesslist1');\">\n";
				if (isset($access1_user_id) && is_array($access1_user_id)) {
					for ($i=0;$i < count($access1_user_id);$i++) {
						echo "<option value='".$access1_user_id[$i]."'>".$access1_user_name[$i]."</option>\n";
					}
				}
				echo "</select>\n";
				echo "<select multiple='multiple' size='10' name='accesslist2' id='accesslist2' class='textbox' style='width:140px' onchange=\"addAccessUser('accesslist1','accesslist2');\">\n";
				if (isset($access2_user_id) && is_array($access2_user_id)) {
					for ($i=0;$i < count($access2_user_id);$i++) {
						echo "<option value='".$access2_user_id[$i]."'>".$access2_user_name[$i]."</option>\n";
					}
				}
				echo "</select>\n";
				echo "</div>\n";
				echo "<input type='hidden' name='forum_access' />\n";

				$post_groups = getusergroups();
				while(list($key, $post_group) = each($post_groups)){
					if ($post_group['0'] != "0" && $post_group['0'] != nSUPERADMIN) {
						if (!preg_match("(^{$post_group['0']}$|^{$post_group['0']}\.|\.{$post_group['0']}\.|\.{$post_group['0']}$)", $data['forum_post'])) {
							$post1_user_id[] = $post_group['0'];
							$post1_user_name[] = $post_group['1'];
						} else {
							$post2_user_id[] = $post_group['0'];
							$post2_user_name[] = $post_group['1'];
						}
					}
				}
				
				echo "<div class='forum_admin'>";
				echo "<strong>".$locale['526']."</strong><br />\n";
				echo "<select multiple='multiple' size='10' name='postlist1' id='postlist1' class='textbox' style='width:140px' onchange=\"addpostUser('postlist2','postlist1');\">\n";
				for ($i=0;$i < count($post1_user_id);$i++) {
					echo "<option value='".$post1_user_id[$i]."'>".$post1_user_name[$i]."</option>\n";
				}
				echo "</select>\n";
				echo "<select multiple='multiple' size='10' name='postlist2' id='postlist2' class='textbox' style='width:140px' onchange=\"addpostUser('postlist1','postlist2');\">\n";
				if (isset($post2_user_id) && is_array($post2_user_id)) {
					for ($i=0;$i < count($post2_user_id);$i++) {
						echo "<option value='".$post2_user_id[$i]."'>".$post2_user_name[$i]."</option>\n";
					}
				}
				echo "</select>\n";
				echo "<input type='hidden' name='forum_post' />\n";
				echo "</div>";

				$reply_groups = getusergroups();
				while(list($key, $reply_group) = each($reply_groups)){
					if ($reply_group['0'] != "0" && $reply_group['0'] != nSUPERADMIN) {
						if (!preg_match("(^{$reply_group['0']}$|^{$reply_group['0']}\.|\.{$reply_group['0']}\.|\.{$reply_group['0']}$)", $data['forum_reply'])) {
							$reply1_user_id[] = $reply_group['0'];
							$reply1_user_name[] = $reply_group['1'];
						} else {
							$reply2_user_id[] = $reply_group['0'];
							$reply2_user_name[] = $reply_group['1'];
						}
					}
				}
				
				echo "<div class='forum_admin'>";
				echo "<strong>".$locale['527']."</strong><br />\n";
				echo "<select multiple='multiple' size='10' name='replylist1' id='replylist1' class='textbox' style='width:140px' onchange=\"addreplyUser('replylist2','replylist1');\">\n";
				if (isset($reply1_user_id) && is_array($reply1_user_id)) {
					for ($i=0;$i < count($reply1_user_id);$i++) {
						echo "<option value='".$reply1_user_id[$i]."'>".$reply1_user_name[$i]."</option>\n";
					}
				}
				echo "</select>\n";
				echo "<select multiple='multiple' size='10' name='replylist2' id='replylist2' class='textbox' style='width:140px' onchange=\"addreplyUser('replylist1','replylist2');\">\n";
				if (isset($reply2_user_id) && is_array($reply2_user_id)) {
					for ($i=0;$i < count($reply2_user_id);$i++) {
						echo "<option value='".$reply2_user_id[$i]."'>".$reply2_user_name[$i]."</option>\n";
					}
				}
				echo "</select>\n";
				echo "<input type='hidden' name='forum_reply' />\n";
				echo "</div>";
			# <- Pimped
				$mod_groups = getusergroups(); $mods1_user_id = array(); $mods1_user_name = array();
				while(list($key, $mod_group) = each($mod_groups)){
					if ($mod_group['0'] != "0" && $mod_group['0'] != nMEMBER && $mod_group['0'] != nSUPERADMIN) {
						if (!preg_match("(^{$mod_group['0']}$|^{$mod_group['0']}\.|\.{$mod_group['0']}\.|\.{$mod_group['0']}$)", $data['forum_moderators'])) {
							$mods1_user_id[] = $mod_group['0'];
							$mods1_user_name[] = $mod_group['1'];
						} else {
							$mods2_user_id[] = $mod_group['0'];
							$mods2_user_name[] = $mod_group['1'];
						}
					}
				}
				
				echo "<div class='forum_admin'>";
				echo "<strong>".$locale['533']."</strong><br />\n";
				echo "<select multiple='multiple' size='10' name='modlist1' id='modlist1' class='textbox' style='width:140px' onchange=\"addUser('modlist2','modlist1');\">\n";
				for ($i=0;$i < count($mods1_user_id);$i++) {
					echo "<option value='".$mods1_user_id[$i]."'>".$mods1_user_name[$i]."</option>\n";
				}
				echo "</select>\n";
				echo "<select multiple='multiple' size='10' name='modlist2' id='modlist2' class='textbox' style='width:140px' onchange=\"addUser('modlist1','modlist2');\">\n";
				if (isset($mods2_user_id) && is_array($mods2_user_id)) {
					for ($i=0;$i < count($mods2_user_id);$i++) {
						echo "<option value='".$mods2_user_id[$i]."'>".$mods2_user_name[$i]."</option>\n";
					}
				}
				echo "</select>\n";
				echo "<input type='hidden' name='forum_mods' />\n";
				echo "</div>";
				
				# Pimped ->
				$attach_groups = getusergroups();
				while(list($key, $attach_group) = each($attach_groups)){
					if ($attach_group['0'] != "0" && $attach_group['0'] != nSUPERADMIN) {
						if (!preg_match("(^{$attach_group['0']}$|^{$attach_group['0']}\.|\.{$attach_group['0']}\.|\.{$attach_group['0']}$)", $data['forum_attach'])) {
							$attach1_user_id[] = $attach_group['0'];
							$attach1_user_name[] = $attach_group['1'];
						} else {
							$attach2_user_id[] = $attach_group['0'];
							$attach2_user_name[] = $attach_group['1'];
						}
					}
				}
				
				echo "<div class='forum_admin'>";
				echo "<strong>".$locale['528']."</strong><br />\n";
				echo "<select multiple='multiple' size='10' name='attachlist1' id='attachlist1' class='textbox' style='width:140px' onchange=\"addattachUser('attachlist2','attachlist1');\">\n";
				for ($i=0;$i < count($attach1_user_id);$i++) {
					echo "<option value='".$attach1_user_id[$i]."'>".$attach1_user_name[$i]."</option>\n";
				}
				echo "</select>\n";
				echo "<select multiple='multiple' size='10' name='attachlist2' id='attachlist2' class='textbox' style='width:140px' onchange=\"addattachUser('attachlist1','attachlist2');\">\n";
				if (isset($attach2_user_id) && is_array($attach2_user_id)) {
					for ($i=0;$i < count($attach2_user_id);$i++) {
						echo "<option value='".$attach2_user_id[$i]."'>".$attach2_user_name[$i]."</option>\n";
					}
				}
				echo "</select>\n";
				echo "<input type='hidden' name='forum_attach' />\n";
				echo "</div>";

				$poll_groups = getusergroups();
				while(list($key, $poll_group) = each($poll_groups)){
					if ($poll_group['0'] != "0" && $poll_group['0'] != nSUPERADMIN) {
						if (!preg_match("(^{$poll_group['0']}$|^{$poll_group['0']}\.|\.{$poll_group['0']}\.|\.{$poll_group['0']}$)", $data['forum_poll'])) {
							$poll1_user_id[] = $poll_group['0'];
							$poll1_user_name[] = $poll_group['1'];
						} else {
							$poll2_user_id[] = $poll_group['0'];
							$poll2_user_name[] = $poll_group['1'];
						}
					}
				}
				echo "<div class='forum_admin'>";
				echo "<strong>".$locale['529']."</strong><br />\n";
				echo "<select multiple='multiple' size='10' name='polllist1' id='polllist1' class='textbox' style='width:140px' onchange=\"addpollUser('polllist2','polllist1');\">\n";
				for ($i=0;$i < count($poll1_user_id);$i++) {
					echo "<option value='".$poll1_user_id[$i]."'>".$poll1_user_name[$i]."</option>\n";
				}
				echo "</select>\n";
				echo "<select multiple='multiple' size='10' name='polllist2' id='polllist2' class='textbox' style='width:140px' onchange=\"addpollUser('polllist1','polllist2');\">\n";
				if (isset($poll2_user_id) && is_array($poll2_user_id)) {
					for ($i=0;$i < count($poll2_user_id);$i++) {
						echo "<option value='".$poll2_user_id[$i]."'>".$poll2_user_name[$i]."</option>\n";
					}
				}
				echo "</select>\n";
				echo "<input type='hidden' name='forum_poll' />\n";
				echo "</div>";

				$vote_groups = getusergroups();
				while(list($key, $vote_group) = each($vote_groups)){
					if ($vote_group['0'] != "0" && $vote_group['0'] != nSUPERADMIN) {
						if (!preg_match("(^{$vote_group['0']}$|^{$vote_group['0']}\.|\.{$vote_group['0']}\.|\.{$vote_group['0']}$)", $data['forum_vote'])) {
							$vote1_user_id[] = $vote_group['0'];
							$vote1_user_name[] = $vote_group['1'];
						} else {
							$vote2_user_id[] = $vote_group['0'];
							$vote2_user_name[] = $vote_group['1'];
						}
					}
				}
				echo "<div class='forum_admin'>";
				echo "<strong>".$locale['530']."</strong><br />\n";
				echo "<select multiple='multiple' size='10' name='votelist1' id='votelist1' class='textbox' style='width:140px' onchange=\"addvoteUser('votelist2','votelist1');\">\n";
				for ($i=0;$i < count($vote1_user_id);$i++) {
					echo "<option value='".$vote1_user_id[$i]."'>".$vote1_user_name[$i]."</option>\n";
				}
				echo "</select>\n";
				echo "<select multiple='multiple' size='10' name='votelist2' id='votelist2' class='textbox' style='width:140px' onchange=\"addvoteUser('votelist1','votelist2');\">\n";
				if (isset($vote2_user_id) && is_array($vote2_user_id)) {
					for ($i=0;$i < count($vote2_user_id);$i++) {
						echo "<option value='".$vote2_user_id[$i]."'>".$vote2_user_name[$i]."</option>\n";
					}
				}
				echo "<input type='hidden' name='forum_vote' />\n";
				echo "</div>";
				
				// Forum Cat Images
				if($settings['forum_cat_icons'] == "1") {
					$forum_cat_image = $forum_cat_image != "" ? $forum_cat_image : "default.png";
					$image_files = makefilelist(IMAGES_FC, ".|..|index.php", true);
					$image_list = makefileopts($image_files,$forum_cat_image);
					
					echo "<div class='forum_admin'>";
					echo "<strong>".$locale['535']."</strong><br />\n";
					echo "<select name='forum_image' class='textbox' style='width:200px;' onchange=\"PreviewForumCat();\" id='forumcat_image'>\n".$image_list."</select>";
					echo "<br /><br /><strong>".$locale['536']."</strong><br /><br />";
					echo "<img src='".($forum_cat_image ? IMAGES_FC.$forum_cat_image : IMAGES_FC."default.png")."' alt='ForumCat' style='border:none' id='forumcat_preview' />";
					echo "</div>";
					echo "<script type='text/javascript'>\n";
					echo "function PreviewForumCat() {\n";
					echo "\tvar selectForumCat = document.getElementById('forumcat_image');\n";
					echo "\tvar imageForumCat = document.getElementById('forumcat_preview');\n";
					echo "\tvar optionValue = selectForumCat.options[selectForumCat.selectedIndex].value;\n";
					echo "\tif (optionValue!='') {\n";
					echo "\t\timageForumCat.src = '".IMAGES_FC."' + optionValue;\n";
					echo "\t} else {\n";
					echo "\t\timageForumCat.src = '".IMAGES_FC."default.png';\n";
					echo "\t}\n";
					echo "}\n";
					echo "</script>\n";
				} else {
					echo "<input type='hidden' name='forum_image' value='".$forum_cat_image."'/>\n";
				}
				
				echo "<div class='forum_admin'>";
				echo "<strong>".$locale['537']."</strong><br />\n";
				echo "<input type='radio' name='forum_markresolved' value='1'".($forum_markresolved == "1" ? " checked='checked'" : "")." /> ".$locale['538']."<br />";
				echo "<input type='radio' name='forum_markresolved' value='0'".($forum_markresolved == "0" ? " checked='checked'" : "")." /> ".$locale['539']."<br />";
				echo "</div>";

				echo "<p style='clear:left'></p><br />";
				// <- Pimped Layout
				# Pimped <-
				echo "<input type='hidden' name='forum_id' value='".(int)$_GET['forum_id']."' />\n";
				echo "<input type='hidden' name='save_forum' />\n";
				echo "<input type='button' name='save' value='".$locale['532']."' class='button' onclick='saveMods();' />";
                echo "</form>\n";
				echo "<script type='text/javascript'>\n";
				echo "function addUser(toGroup,fromGroup) {\n";
				echo "var listLength = document.getElementById(toGroup).length;\n";
				echo "var selItem = document.getElementById(fromGroup).selectedIndex;\n";
				echo "var selText = document.getElementById(fromGroup).options[selItem].text;\n";
				echo "var selValue = document.getElementById(fromGroup).options[selItem].value;\n";
				echo "var i; var newItem = true;\n";
				echo "for (i = 0; i < listLength; i++) {\n";
				echo "if (document.getElementById(toGroup).options[i].text == selText) {\n";
				echo "newItem = false; break;\n}\n}\n"."if (newItem) {\n";
				echo "document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);\n";
				echo "document.getElementById(fromGroup).options[selItem] = null;\n}\n}\n";
				# Pimped: ->
				echo "function addAccessUser(toGroup,fromGroup) {\n";
				echo "var listLength = document.getElementById(toGroup).length;\n";
				echo "var selItem = document.getElementById(fromGroup).selectedIndex;\n";
				echo "var selText = document.getElementById(fromGroup).options[selItem].text;\n";
				echo "var selValue = document.getElementById(fromGroup).options[selItem].value;\n";
				echo "var i; var newItem = true;\n";
				echo "for (i = 0; i < listLength; i++) {\n";
				echo "if (document.getElementById(toGroup).options[i].text == selText) {\n";
				echo "newItem = false; break;\n}\n}\n"."if (newItem) {\n";
				echo "document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);\n";
				echo "document.getElementById(fromGroup).options[selItem] = null;\n}\n}\n";

				echo "function addpostUser(toGroup,fromGroup) {\n";
				echo "var listLength = document.getElementById(toGroup).length;\n";
				echo "var selItem = document.getElementById(fromGroup).selectedIndex;\n";
				echo "var selText = document.getElementById(fromGroup).options[selItem].text;\n";
				echo "var selValue = document.getElementById(fromGroup).options[selItem].value;\n";
				echo "var i; var newItem = true;\n";
				echo "for (i = 0; i < listLength; i++) {\n";
				echo "if (document.getElementById(toGroup).options[i].text == selText) {\n";
				echo "newItem = false; break;\n}\n}\n"."if (newItem) {\n";
				echo "document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);\n";
				echo "document.getElementById(fromGroup).options[selItem] = null;\n}\n}\n";

				echo "function addreplyUser(toGroup,fromGroup) {\n";
				echo "var listLength = document.getElementById(toGroup).length;\n";
				echo "var selItem = document.getElementById(fromGroup).selectedIndex;\n";
				echo "var selText = document.getElementById(fromGroup).options[selItem].text;\n";
				echo "var selValue = document.getElementById(fromGroup).options[selItem].value;\n";
				echo "var i; var newItem = true;\n";
				echo "for (i = 0; i < listLength; i++) {\n";
				echo "if (document.getElementById(toGroup).options[i].text == selText) {\n";
				echo "newItem = false; break;\n}\n}\n"."if (newItem) {\n";
				echo "document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);\n";
				echo "document.getElementById(fromGroup).options[selItem] = null;\n}\n}\n";

				echo "function addattachUser(toGroup,fromGroup) {\n";
				echo "var listLength = document.getElementById(toGroup).length;\n";
				echo "var selItem = document.getElementById(fromGroup).selectedIndex;\n";
				echo "var selText = document.getElementById(fromGroup).options[selItem].text;\n";
				echo "var selValue = document.getElementById(fromGroup).options[selItem].value;\n";
				echo "var i; var newItem = true;\n";
				echo "for (i = 0; i < listLength; i++) {\n";
				echo "if (document.getElementById(toGroup).options[i].text == selText) {\n";
				echo "newItem = false; break;\n}\n}\n"."if (newItem) {\n";
				echo "document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);\n";
				echo "document.getElementById(fromGroup).options[selItem] = null;\n}\n}\n";

				echo "function addpollUser(toGroup,fromGroup) {\n";
				echo "var listLength = document.getElementById(toGroup).length;\n";
				echo "var selItem = document.getElementById(fromGroup).selectedIndex;\n";
				echo "var selText = document.getElementById(fromGroup).options[selItem].text;\n";
				echo "var selValue = document.getElementById(fromGroup).options[selItem].value;\n";
				echo "var i; var newItem = true;\n";
				echo "for (i = 0; i < listLength; i++) {\n";
				echo "if (document.getElementById(toGroup).options[i].text == selText) {\n";
				echo "newItem = false; break;\n}\n}\n"."if (newItem) {\n";
				echo "document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);\n";
				echo "document.getElementById(fromGroup).options[selItem] = null;\n}\n}\n";

				echo "function addvoteUser(toGroup,fromGroup) {\n";
				echo "var listLength = document.getElementById(toGroup).length;\n";
				echo "var selItem = document.getElementById(fromGroup).selectedIndex;\n";
				echo "var selText = document.getElementById(fromGroup).options[selItem].text;\n";
				echo "var selValue = document.getElementById(fromGroup).options[selItem].value;\n";
				echo "var i; var newItem = true;\n";
				echo "for (i = 0; i < listLength; i++) {\n";
				echo "if (document.getElementById(toGroup).options[i].text == selText) {\n";
				echo "newItem = false; break;\n}\n}\n"."if (newItem) {\n";
				echo "document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);\n";
				echo "document.getElementById(fromGroup).options[selItem] = null;\n}\n}\n";

				# Pimped <-
				echo "function saveMods() {\n"."var strValues = \"\";\n";
				echo "var boxLength = document.getElementById('modlist2').length;\n";
				echo "var count = 0;\n"."	if (boxLength != 0) {\n"."for (i = 0; i < boxLength; i++) {\n";
				echo "if (count == 0) {\n"."strValues = document.getElementById('modlist2').options[i].value;\n";
				echo "} else {\n"."strValues = strValues + \".\" + document.getElementById('modlist2').options[i].value;\n";
				echo "}\n"."count++;\n}\n}\n";
				# Pimped: <>
				echo "if (strValues.length != 0) {\n"."document.forms['addforum'].forum_mods.value = strValues;}\n";
				echo "var strAccessValues = \"\";\n";
				echo "var boxAccessLength = document.getElementById('accesslist2').length;\n";
				echo "var countAccess = 0;\n"."	if (boxAccessLength != 0) {\n"."for (a = 0; a < boxAccessLength; a++) {\n";
				echo "if (countAccess == 0) {\n"."strAccessValues = document.getElementById('accesslist2').options[a].value;\n";
				echo "} else {\n"."strAccessValues = strAccessValues + \".\" + document.getElementById('accesslist2').options[a].value;\n";
				echo "}\n"."countAccess++;\n}\n}\n";
				echo "if (strAccessValues.length != 0) {\n"."document.forms['addforum'].forum_access.value = strAccessValues;}\n";
				echo "var strpostValues = \"\";\n";
				echo "var boxpostLength = document.getElementById('postlist2').length;\n";
				echo "var countpost = 0;\n"."	if (boxpostLength != 0) {\n"."for (a = 0; a < boxpostLength; a++) {\n";
				echo "if (countpost == 0) {\n"."strpostValues = document.getElementById('postlist2').options[a].value;\n";
				echo "} else {\n"."strpostValues = strpostValues + \".\" + document.getElementById('postlist2').options[a].value;\n";
				echo "}\n"."countpost++;\n}\n}\n";
				echo "if (strpostValues.length != 0) {\n"."document.forms['addforum'].forum_post.value = strpostValues;}\n";
				echo "var strreplyValues = \"\";\n";
				echo "var boxreplyLength = document.getElementById('replylist2').length;\n";
				echo "var countreply = 0;\n"."	if (boxreplyLength != 0) {\n"."for (a = 0; a < boxreplyLength; a++) {\n";
				echo "if (countreply == 0) {\n"."strreplyValues = document.getElementById('replylist2').options[a].value;\n";
				echo "} else {\n"."strreplyValues = strreplyValues + \".\" + document.getElementById('replylist2').options[a].value;\n";
				echo "}\n"."countreply++;\n}\n}\n";
				echo "if (strreplyValues.length != 0) {\n"."document.forms['addforum'].forum_reply.value = strreplyValues;}\n";
				echo "var strattachValues = \"\";\n";
				echo "var boxattachLength = document.getElementById('attachlist2').length;\n";
				echo "var countattach = 0;\n"."	if (boxattachLength != 0) {\n"."for (a = 0; a < boxattachLength; a++) {\n";
				echo "if (countattach == 0) {\n"."strattachValues = document.getElementById('attachlist2').options[a].value;\n";
				echo "} else {\n"."strattachValues = strattachValues + \".\" + document.getElementById('attachlist2').options[a].value;\n";
				echo "}\n"."countattach++;\n}\n}\n";
				echo "if (strattachValues.length != 0) {\n"."document.forms['addforum'].forum_attach.value = strattachValues;}\n";
				echo "var strpollValues = \"\";\n";
				echo "var boxpollLength = document.getElementById('polllist2').length;\n";
				echo "var countpoll = 0;\n"."	if (boxpollLength != 0) {\n"."for (a = 0; a < boxpollLength; a++) {\n";
				echo "if (countpoll == 0) {\n"."strpollValues = document.getElementById('polllist2').options[a].value;\n";
				echo "} else {\n"."strpollValues = strpollValues + \".\" + document.getElementById('polllist2').options[a].value;\n";
				echo "}\n"."countpoll++;\n}\n}\n";
				echo "if (strpollValues.length != 0) {\n"."document.forms['addforum'].forum_poll.value = strpollValues;}\n";
				echo "var strvoteValues = \"\";\n";
				echo "var boxvoteLength = document.getElementById('votelist2').length;\n";
				echo "var countvote = 0;\n"."	if (boxvoteLength != 0) {\n"."for (a = 0; a < boxvoteLength; a++) {\n";
				echo "if (countvote == 0) {\n"."strvoteValues = document.getElementById('votelist2').options[a].value;\n";
				echo "} else {\n"."strvoteValues = strvoteValues + \".\" + document.getElementById('votelist2').options[a].value;\n";
				echo "}\n"."countvote++;\n}\n}\n";
				echo "if (strvoteValues.length != 0) {\n"."document.forms['addforum'].forum_vote.value = strvoteValues;}\n";
				echo "document.forms['addforum'].submit();\n}</script>\n<br />";
				# Pimped <-
			}
			closetable();
		}
    }
opentable($locale['550']);
	$i = 1; $k = 1;
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
	$result = dbquery("SELECT forum_id, forum_name, forum_order FROM ".DB_FORUMS." WHERE forum_cat='0' ORDER BY forum_order");
	if (dbrows($result) != 0) {
		echo "<tr>\n<td class='tbl2'><strong>".$locale['551']."</strong></td>\n";
		echo "<td align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['552']."</strong></td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['553']."</strong></td>\n";
		echo "</tr>\n";
		$i = 1;
		while ($data = dbarray($result)) {
			
			echo "<tr>\n<td class='tbl2'><strong>".$data['forum_name']."</strong></td>\n";
			echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$data['forum_order']."</td>\n";
			echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>\n";
			if (dbrows($result) != 1) {
				$up = $data['forum_order'] - 1;	$down = $data['forum_order'] + 1;
				if ($i == 1) {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;action=md&amp;order=$down&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".get_image("down")."' alt='".$locale['557']."' title='".$locale['557']."' style='border:0px;' /></a>\n";
				} elseif ($i < dbrows($result)) {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;action=mu&amp;order=$up&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".get_image("up")."' alt='".$locale['556']."' title='".$locale['558']."' style='border:0px;' /></a>\n";
					echo "<a href='".FUSION_SELF.$aidlink."&amp;action=md&amp;order=$down&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".get_image("down")."' alt='".$locale['557']."' title='".$locale['557']."' style='border:0px;' /></a>\n";
				} else {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;action=mu&amp;order=$up&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".get_image("up")."' alt='".$locale['556']."' title='".$locale['558']."' style='border:0px;' /></a>\n";
				}
			}
			$i++;
			echo "</td>\n";
			echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data['forum_id']."&amp;t=cat'>".$locale['554']."</a> ::\n";
			echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;forum_id=".$data['forum_id']."&amp;t=cat' onclick=\"return confirm('".$locale['440']."');\">".$locale['555']."</a></td>\n";
			echo "</tr>\n";
			$result2 = dbquery("SELECT forum_id, forum_cat, forum_name, forum_description, forum_parent, forum_order FROM ".DB_FORUMS." WHERE forum_cat='".$data['forum_id']."' AND forum_parent='0' ORDER BY forum_order");
			if (dbrows($result2)) {
				$k = 1;
				while ($data2 = dbarray($result2)) {
					echo forum_parent($data2, $result2, $k);
					echo forum($data2['forum_id']);
					$k++;
				}
			}
			echo "</table><table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
		}
		echo "<tr>\n<td align='center' colspan='5' class='tbl2'>[ <a href='".FUSION_SELF.$aidlink."&amp;action=refresh'>".$locale['562']."</a> ]</td>\n</tr>\n";
	} else {
		echo "<tr>\n<td align='center' class='tbl1'>".$locale['560']."</td>\n</tr>\n";
	}
	echo "</table>\n";
	closetable();
}
function forum_parent($data2, $result2, $k){
	
	global $aidlink, $locale;
	
	$forumR = "";
	$forumR .= "<tr>\n";
	$forumR .= "<td class='tbl1'><span class='alt'>";
	$forumR .= ($data2['forum_parent'] == 0 ) ? $data2['forum_name'] : "<img src='".IMAGES_F."subforum.gif' alt='' style='vertical-align: middle;' /> ".$data2['forum_name'];
	$forumR .= "</span>\n";
	$forumR .= "[<a href='".FUSION_SELF.$aidlink."&amp;action=prune&amp;forum_id=".$data2['forum_id']."'>".$locale['563']."</a>]<br />\n";
	$forumR .= ($data2['forum_description'] ? "<span class='small'>".$data2['forum_description']."</span>" : "")."</td>\n";
	$forumR .= "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$data2['forum_order']."</td>\n";
	$forumR .= "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>\n";
	$parent = ($data2['forum_parent'] == 0 ? "" : "&amp;parent=".$data2['forum_parent']);
	
	if (dbrows($result2) != 1) {
		$up = $data2['forum_order'] - 1; $down = $data2['forum_order'] + 1;
		if ($k == 1) {
			$forumR .= "<a href='".FUSION_SELF.$aidlink."&amp;action=md&amp;order=$down&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."$parent'><img src='".get_image("down")."' alt='".$locale['557']."' title='".$locale['557']."' style='border:0px;' /></a>\n";
		} elseif ($k < dbrows($result2)) {
			$forumR .= "<a href='".FUSION_SELF.$aidlink."&amp;action=mu&amp;order=$up&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."$parent'><img src='".get_image("up")."' alt='".$locale['556']."' title='".$locale['558']."' style='border:0px;' /></a>\n";
			$forumR .= "<a href='".FUSION_SELF.$aidlink."&amp;action=md&amp;order=$down&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."$parent'><img src='".get_image("down")."' alt='".$locale['557']."' title='".$locale['557']."' style='border:0px;' /></a>\n";
		} else {
			$forumR .= "<a href='".FUSION_SELF.$aidlink."&amp;action=mu&amp;order=$up&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."$parent'><img src='".get_image("up")."' alt='".$locale['556']."' title='".$locale['558']."' style='border:0px;' /></a>\n";
		}
	}
	$forumR .= "</td>\n";
	$forumR .= "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data2['forum_id']."&amp;t=forum'>".$locale['554']."</a> ::\n";
	$forumR .= "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;forum_id=".$data2['forum_id']."&amp;t=forum$parent' onclick=\"return confirm('".$locale['570']."');\">".$locale['555']."</a></td>\n";
	$forumR .= "</tr>\n";
	
	return $forumR;
}

function forum($parent) {
	$result = dbquery("SELECT forum_id, forum_cat, forum_name, forum_description, forum_parent, forum_order FROM ".DB_FORUMS." WHERE forum_parent='$parent' ORDER BY forum_order asc");
	$forumR = "";
	if (dbrows($result)) {
		$k = 1;
		while ($data = dbarray($result)) {
			$forumR .= forum_parent($data, $result, $k);
			$k++;
			$forumR .= forum($data['forum_id']);	
		}
	}
	return $forumR;
}
require_once TEMPLATES."footer.php";
?>