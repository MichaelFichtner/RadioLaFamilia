<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: profile.php
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
require_once "maincore.php";
require_once TEMPLATES."header.php";
include LOCALE.LOCALESET."view_profile.php";
include LOCALE.LOCALESET."user_fields.php";

if (isset($_GET['lookup']) && isnum($_GET['lookup'])) {

	if (!$settings['hide_userprofiles'] || iMEMBER) {

		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".(int)$_GET['lookup']."'"); // Pimped

		if (dbrows($result)) { $user_data = dbarray($result); } else { redirect("index.php"); }
		
		$status = array($locale['440'], $locale['441'], $locale['442'], $locale['443'], $locale['444'], $locale['445'], $locale['446'], $locale['447']);
		
		if (iADMIN || checkrights("M")) {
			$visible_arr = $pif_global['visible_members_admin_pro']; // Pimped
		} else {
			$visible_arr = $pif_global['visible_members_pro']; // Pimped
		}
		
		if (iADMIN && ($user_data['user_status'] == 1 || $user_data['user_status'] == 3)) {
			$suspend = dbarray(dbquery(
				"SELECT suspend_reason FROM ".DB_SUSPENDS." WHERE suspended_user='".$_GET['lookup']."' ORDER BY suspend_date DESC LIMIT 1"
			 ));
		}
		
		if ((!iADMIN || !checkrights("M")) && !in_array($user_data['user_status'], $visible_arr)) {
			redirect("index.php");
		}
		
		// Message for deleted comments of this user
		if (isset($_GET['msg'])) {
			if($_GET['msg'] == "cdel_0") {
				$message = $locale['408'];
			} elseif($_GET['msg'] == "cdel_1") {
				$message = $locale['409'];
			}
			if (isset($message)) {
				echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
			}
		}
		
		if (iADMIN && checkrights("UG") && $user_data['user_id'] != $userdata['user_id']) {
			if ((isset($_POST['add_to_group'])) && (isset($_POST['user_group']) && isnum($_POST['user_group']))) {
				if (!preg_match("(^\.{$_POST['user_group']}$|\.{$_POST['user_group']}\.|\.{$_POST['user_group']}$)", $user_data['user_groups'])) {
					$result = dbquery("UPDATE ".DB_USERS." SET user_groups='".$user_data['user_groups'].".".$_POST['user_group']."' WHERE user_id='".$user_data['user_id']."'");
				}
				redirect(FUSION_SELF."?lookup=".$user_data['user_id']);
			}
		}
		
		// Delete all Comments/Shouts of this user
		if(isset($_GET['delete']) && $_GET['delete'] == "true" && isset($_GET['step']) && iADMIN && $user_data['user_id'] != $userdata['user_id']) {
			if($_GET['step'] == "comments") {
				if(checkrights("C") && defined("iAUTH") && isset($_GET['aid']) && $_GET['aid'] == iAUTH){
					$result = dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_name='".(int)$user_data['user_id']."'");
					if($result) {
						redirect(FUSION_SELF."?lookup=".$user_data['user_id']."&amp;msg=cdel_1");
					} else {
						redirect(FUSION_SELF."?lookup=".$user_data['user_id']."&amp;msg=cdel_0");
					}
				}
			} elseif($_GET['step'] == "shouts") {
				if(checkrights("S") && defined("iAUTH") && isset($_GET['aid']) && $_GET['aid'] == iAUTH){
					$result = dbquery("DELETE FROM ".DB_SHOUTBOX." WHERE shout_name='".(int)$user_data['user_id']."'");
					if($result) {
						redirect(FUSION_SELF."?lookup=".$user_data['user_id']."&amp;msg=cdel_1");
					} else {
						redirect(FUSION_SELF."?lookup=".$user_data['user_id']."&amp;msg=cdel_0");
					}
				}
			}
		}
		
		add_to_title($locale['global_200'].$locale['400'].$locale['global_201'].$user_data['user_name']);
		opentable($locale['400']);
		echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
		
		// How much rowspan is needed? ->
		$rowspan = 6 ;
		if ($user_data['user_status'] == 1 || $user_data['user_status'] == 3 && $suspend['suspend_reason'] != "") {
			$rowspan++;
		}
		
		// Pimped: Avatars for banned or suspended Users
		$banned_avatar = array(1, 3);
		$colspan = 2;
		if (!in_array($user_data['user_status'], $banned_avatar) && $user_data['user_avatar'] && file_exists(IMAGES."avatars/".$user_data['user_avatar'])) {
			echo "<td rowspan='".$rowspan."' width='1%' class='tbl profile_user_avatar'><!--profile_user_avatar--><img src='".IMAGES."avatars/".$user_data['user_avatar']."' alt='' /></td>\n";
			$colspan = 3;
		} elseif(!in_array($user_data['user_status'], $banned_avatar) && file_exists(IMAGES."avatars/noavatar.jpg")) {
			echo "<td rowspan='".$rowspan."' width='1%' class='tbl profile_user_avatar'><!--profile_user_avatar--><img src='".IMAGES."avatars/noavatar.jpg' alt='' /></td>\n";
			$colspan = 3;
		} elseif(in_array($user_data['user_status'], $banned_avatar) && file_exists(IMAGES."avatars/banned.jpg")) {
			echo "<td rowspan='".$rowspan."' width='1%' class='tbl profile_user_avatar'><!--profile_user_avatar--><img src='".IMAGES."avatars/banned.jpg' alt='' /></td>\n";
			$colspan = 3;
		}
		// end
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u001']."</td>\n";
		echo "<td align='right' class='tbl1 profile_user_name'><!--profile_user_name-->".$user_data['user_name']."</td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'></td>\n";
		echo "<td align='right' class='tbl1 profile_user_level'><!--profile_user_level-->".getuserlevel($user_data['user_level'])."</td>\n";
		echo "</tr>\n";
		if (iADMIN && $user_data['user_status'] > 0) {
			echo "<tr>\n";
			echo "<td width='1%' class='tbl1' style='white-space:nowrap'></td>\n";
			echo "<td align='right' class='tbl1 profile_user_status'><!--profile_user_status-->".$status[$user_data['user_status']]."</td>\n";
			echo "</tr>\n";
			if ($user_data['user_status'] == 1 || $user_data['user_status'] == 3 && $suspend['suspend_reason'] != "") {
				echo "<tr>\n";
				echo "<td valign='top' width='1%' class='tbl1' style='white-space:nowrap'>".$locale['448']."</td>\n";
				echo "<td align='right' class='tbl1 profile_user_reason'><!--profile_user_reason-->".$suspend['suspend_reason']."</td>\n";
				echo "</tr>\n";
			}
		}	
		echo "</tr>\n";
		if ($user_data['user_hide_email'] != "1" || iADMIN) {
			echo "<tr>\n";
			echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u005']."</td>\n";
			echo "<td align='right' class='tbl1'>".hide_email($user_data['user_email'])."</td>\n";
			echo "</tr>\n";
		}
		echo "<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u040']."</td>\n";
		echo "<td align='right' class='tbl1'>".showdate("longdate", $user_data['user_joined'])."</td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u041']."</td>\n";
		echo "<td align='right' class='tbl1'>".($user_data['user_lastvisit'] ? showdate("longdate", $user_data['user_lastvisit']) : $locale['u042'])."</td>\n";
		echo "</tr>\n";
		if (iMEMBER && $userdata['user_id'] != $user_data['user_id'] && in_array($user_data['user_status'], $pif_global['can_recieve_pm'])) {
			echo "<tr><td colspan='".$colspan."' class='tbl2' style='text-align:center;white-space:nowrap'><a href='messages.php?msg_send=".$user_data['user_id']."' title='".$locale['u043']."'>".$locale['u043']."</a>\n";
			if (iADMIN && checkrights("M") && $user_data['user_level'] != nSUPERADMIN && $user_data['user_id'] != "1") {
				echo " - <a href='".ADMIN."members.php".$aidlink."&amp;step=log&amp;user_id=".$_GET['lookup']."'>".$locale['460']."</a>";
			}
			echo "</td>\n</tr>\n";
		}
		echo "</table>\n";

		echo "<div style='margin:5px'></div>\n";

		$profile_method = "display"; $icu = 0; $user_cats = array(); $user_fields = array(); $ob_active = false;
		$result2 = dbquery(
			"SELECT tuf.field_name, tuf.field_cat, tufc.field_cat_name FROM ".DB_USER_FIELDS." tuf
			INNER JOIN ".DB_USER_FIELD_CATS." tufc ON tuf.field_cat = tufc.field_cat_id
			ORDER BY field_cat_order, field_order"
		);
		if (dbrows($result2)) {
			while($data2 = dbarray($result2)) {
				if ($icu != $data2['field_cat']) {
					if ($ob_active) {
						$user_fields[$icu] = ob_get_contents();
						ob_end_clean();
						$ob_active = false;
					}
					$icu = $data2['field_cat'];
					$user_cats[] = array(
						"field_cat_name" => $data2['field_cat_name'],
						"field_cat" => $data2['field_cat']
					);
				}
				if (!$ob_active) {
					ob_start();
					$ob_active = true;
				}
				if (file_exists(LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php")) {
					include LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php";
				} elseif (file_exists(LOCALE."English/user_fields/".$data2['field_name'].".php")) {
					include LOCALE."English/user_fields/".$data2['field_name'].".php";
				}
				if (file_exists(INCLUDES."user_fields/".$data2['field_name']."_include.php")) {
					include INCLUDES."user_fields/".$data2['field_name']."_include.php";
				}
			}
		}

		if ($ob_active) {
			$user_fields[$icu] = ob_get_contents();
			ob_end_clean();
		}
		$o = 1;
		foreach ($user_cats as $category) {
			if (array_key_exists($category['field_cat'], $user_fields) && $user_fields[$category['field_cat']]) {
				echo "<!--userfield_precat_".$o."-->\n";
				echo "<div style='margin:5px'></div>\n";
				echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
				echo "<td colspan='2' class='tbl2'><strong>".$category['field_cat_name']."</strong></td>\n";
				echo "</tr>\n".$user_fields[$category['field_cat']];
				echo "</table>\n";
				$o++;
			}
		}
		
		if (count($user_fields > 0)) {
			echo "<!--userfield_end-->\n";
		}
		
		if (iADMIN && checkrights("M")) {
			echo "<div style='margin:5px'></div>\n";
			echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
			echo "<td colspan='2' class='tbl2'><strong>".$locale['u048']."</strong></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['u049']."</td>\n";
			echo "<td align='right' class='tbl1'>".$user_data['user_ip']."</td>\n";
			echo "</tr>\n</table>\n";
		}

		if ($user_data['user_groups']) {
			echo "<div style='margin:5px'></div>\n";
			echo "<table cellpadding='0' cellspacing='1' width='400' class='center tbl-border'>\n<tr>\n";
			echo "<td class='tbl2'><strong>".$locale['401']."</strong></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1'>\n";
			$user_groups = (strpos($user_data['user_groups'], ".") == 0 ? explode(".", substr($user_data['user_groups'], 1)) : explode(".", $user_data['user_groups']));
			for ($i = 0; $i < count($user_groups); $i++) {
				echo "<div style='float:left'>".group_link($user_groups[$i], getgroupname($user_groups[$i]))."</div><div style='float:right'>".getgroupname($user_groups[$i], true)."</div><div style='float:none;clear:both'></div>\n"; // Pimped: url-rewrite
			}
			echo "</td>\n</tr>\n</table>\n";
		}
		if (iADMIN && checkrights("M") && $user_data['user_id'] != $userdata['user_id']) {
			$user_groups_opts = "";
			if (iSUPERADMIN || $user_data['user_level'] < nADMIN) {
				echo "<div style='margin:5px'></div>\n";
				echo "<form name='admin_form' method='post' action='".FUSION_SELF."?lookup=".$user_data['user_id']."'>\n";
				echo "<table cellpadding='0' cellspacing='0' width='400' class='center tbl-border'>\n<tr>\n";
				echo "<td class='tbl2' colspan='2'><strong>".$locale['402']."</strong></td>\n";
				echo "</tr>\n<tr>\n";
				echo "<td class='tbl1'><!--profile_admin_options-->\n";
				echo "<a href='".ADMIN."members.php".$aidlink."&amp;step=edit&amp;user_id=".$user_data['user_id']."'>".$locale['410']."</a> ::\n";
				echo "<a href='".ADMIN."members.php".$aidlink."&amp;action=1&amp;user_id=".$user_data['user_id']."'>".$locale['411']."</a> ::\n";
				echo "<a href='".ADMIN."members.php".$aidlink."&amp;step=delete&amp;status=0&amp;user_id=".$user_data['user_id']."' onclick=\"return confirm('".$locale['414']."');\">".$locale['412']."</a>\n";
				$ad = "";
				if(checkrights("C")) {
					$ad .= "<a href='".BASEDIR."profile.php".$aidlink."&amp;lookup=".$user_data['user_id']."&amp;delete=true&amp;step=comments' onclick=\"return confirm('".$locale['419']."');\">".$locale['418']."</a>";
				}
				if(checkrights("S")) {
					$ad .= ($ad ? " :: " : "")."<a href='".BASEDIR."profile.php".$aidlink."&amp;lookup=".$user_data['user_id']."&amp;delete=true&amp;step=shouts' onclick=\"return confirm('".$locale['419a']."');\">".$locale['418a']."</a>";
				}
				echo ($ad ? "<br />".$ad : "");
				echo "</td>\n";
				$result = dbquery("SELECT group_id, group_name FROM ".DB_USER_GROUPS." ORDER BY group_id ASC");
				if (dbrows($result)) {
					while ($data2 = dbarray($result)) {
						if (!preg_match("(^\.{$data2['group_id']}|\.{$data2['group_id']}\.|\.{$data2['group_id']}$)", $user_data['user_groups'])) {
							$user_groups_opts .= "<option value='".$data2['group_id']."'>".$data2['group_name']."</option>\n";
						}
					}
					if (iADMIN && checkrights("UG") && $user_groups_opts) {
						echo "<td align='right' class='tbl1'>".$locale['415']."\n";
						echo "<select name='user_group' class='textbox' style='width:100px'>\n".$user_groups_opts."</select>\n";
						echo "<input type='submit' name='add_to_group' value='".$locale['416']."' class='button'  onclick=\"return confirm('".$locale['417']."');\" /></td>\n";
					}
				}
				echo "</tr>\n</table>\n</form>\n";
			}
		}
		## Forum Stats
		if($settings['forum_profile_statistics']){
			list($name, $posts, $age) = dbarraynum(dbquery("SELECT user_name, user_posts, user_joined FROM ".DB_USERS." WHERE user_id=".(int)$_GET['lookup']));
			$posts = empty($posts) ? 0 : $posts;
			list($threads) = dbarraynum(dbquery("SELECT COUNT(thread_id) FROM ".DB_THREADS." WHERE thread_author=".(int)$_GET['lookup']));
			$threads = empty($threads) ? 0 : $threads;
			
			$threadspday = round_num($threads/((time() - $age)/(3600*24)));
			$postspday = round_num($posts/((time() - $age)/(3600*24)));
			
			list($ranked_higher) = dbarraynum(dbquery("SELECT COUNT(user_id) FROM ".DB_USERS." WHERE user_posts>".$posts));
			$rank = $ranked_higher+1;
			list($allposts) = dbarraynum(dbquery("SELECT SUM(forum_postcount) FROM ".DB_FORUMS));
			$percentage = empty($posts) || empty($allposts) ? 0 : ($posts*100.0)/$allposts;
			$percentage = round_num($percentage);
			
			closetable();
			opentable(sprintf($locale['forum_ext_title_profile'], $name));
				echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n\t<tr>
					<th class='forum-caption' width='1%' style='white-space: nowrap;' rowspan='2'><img alt='".$locale['forum_ext_stats']."' src='".IMAGES."forum/forum_stats.png' /></th>
					<td class='tbl1'>
						".number_format($threads)." ".$locale['forum_ext_threads']." ::
						".number_format($posts)." ".$locale['forum_ext_posts']." ::
						".$threadspday." ".$locale['forum_ext_threadspday']." ::
						".$postspday." ".$locale['forum_ext_postspday']."
					</td>
				</tr>
				<tr>
					<td class='tbl1'>
						".sprintf($locale['forum_ext_ranking'], $name, number_format($rank), $percentage)."
					</td>
				</tr>
			</table>";
			foreach(array("threads", "posts") as $type){
				$other_type = $type=="threads"? "posts" : "threads";
				if($type == "threads"){
					if(!isset($_GET['show']) || (isset($_GET['show']) && $_GET['show'] != "posts")){
						$visibility = "";
					}else{
						$visibility = "style='display: none;'";
					}
				}else{
					if(isset($_GET['show']) && $_GET['show'] == "posts"){
						$visibility = "";
					}else{
						$visibility = "style='display: none;'";
					}
				}
				if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
				$where = $type == "threads" ? "tt.thread_author='".(int)$_GET['lookup']."' GROUP BY tt.thread_id" : "tp.post_author='".(int)$_GET['lookup']."'";
				
				$rows_res = dbquery("SELECT post_id FROM ".DB_POSTS." tp
				INNER JOIN ".DB_FORUMS." tf ON tp.forum_id=tf.forum_id
				INNER JOIN ".DB_THREADS." tt ON tp.thread_id=tt.thread_id
				WHERE ".groupaccess('tf.forum_access')." AND ".$where."
				ORDER BY tp.post_datestamp DESC");
			
				$result = dbquery("SELECT tp.forum_id, tp.thread_id, tp.post_id, tp.post_author, tp.post_datestamp,
				tf.forum_name, tf.forum_access, tt.thread_subject
				FROM ".DB_POSTS." tp
				INNER JOIN ".DB_FORUMS." tf ON tp.forum_id=tf.forum_id
				INNER JOIN ".DB_THREADS." tt ON tp.thread_id=tt.thread_id
				WHERE ".groupaccess('tf.forum_access')." AND ".$where."
				ORDER BY tp.post_datestamp DESC LIMIT ".(int)$_GET['rowstart'].",10");
				
				echo "<script type='text/javascript'>
				$(document).ready(function(){
					$('#forum_panel_".$other_type."_toggle').click(function() {
						$('#forum_panel_".$other_type."').show();
						$('#forum_panel_".$type."').hide();
						return false;
					});
				});</script>
				<div id='forum_panel_".$type."' ".$visibility.">";
				

				if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
				echo "
				<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n\t<tr>
					<th class='forum-caption'>".$locale['forum_ext_recent_'.$type]." <a href='".FUSION_SELF."?lookup=".(int)$_GET['lookup']."&amp;show=".$other_type."' id='forum_panel_".$other_type."_toggle'>".$locale['forum_ext_recent_show_'.$other_type.'']."</a></th>
					<th class='forum-caption'>".$locale['global_048']."</th>
					<th class='forum-caption'>".$locale['global_047']."</th>
				</tr>\n";
				$rows = dbrows($rows_res);
				if ($rows) {
					$i=0;
					while ($data = dbarray($result)) {
						$i++; $row = $i%2 ? "class='tbl1'" : "class='tbl2'"; // Pimped: make_url() ->
						echo "<tr>\n\t<td width='100%' $row><a href='".make_url(FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['post_id'], BASEDIR."forum-thread-".$data['thread_id']."-pid".$data['post_id']."-", $data['thread_subject'], ".html")."#post_".$data['post_id']."'>".trimlink($data['thread_subject'], 40)."</a></td>
						<td width='1%' style='white-space:nowrap' $row>".trimlink($data['forum_name'], 30)."</td>
						<td align='center' width='1%' style='white-space:nowrap' $row>".showdate("forumdate", $data['post_datestamp'])."</td>\n</tr>\n";
					}
					if ($rows > 10){
						echo "<tr><td class='tbl2' colspan='3'><div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 10, $rows, 3, FUSION_SELF."?lookup=".$_GET['lookup']."&amp;show=$type&amp;")."\n</div></td></tr>\n";
					}
				} else {
					echo "<tr><td colspan='3' style='text-align:center' class='tbl1'>\n".$locale['forum_ext_no_'.$type]."</td></tr>\n";
				}
				echo "</table>\n";
				echo "</div>\n";
			}
		}
		closetable();
		# Pimped <-
	} else {
		opentable($locale['400']);
		echo "<div style='text-align:center;'><br />".$locale['430']."<br /><br /></div>";
		closetable();
	}
} elseif(isset($_GET['group_id']) && isnum($_GET['group_id'])) {
	if (!$settings['hide_groupprofiles'] || iMEMBER) {
		$result = dbquery("SELECT group_id, group_name  FROM ".DB_USER_GROUPS." WHERE group_id='".(int)$_GET['group_id']."' LIMIT 1"); // Pimped
		if (dbrows($result)) {
			$data = dbarray($result);
			$result = dbquery("SELECT user_id, user_name, user_level, user_status FROM ".DB_USERS." WHERE user_groups REGEXP('^\\\.{$_GET['group_id']}$|\\\.{$_GET['group_id']}\\\.|\\\.{$_GET['group_id']}$') ORDER BY user_level DESC, user_name");
			opentable($locale['420']);
			echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
			echo "<td align='center' colspan='2' class='tbl1'><strong>".$data['group_name']."</strong> (".sprintf((dbrows($result) == 1 ? $locale['421'] : $locale['422']), dbrows($result)).")</td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl2'><strong>".$locale['423']."</strong></td>\n";
			echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['424']."</strong></td>\n";
			echo "</tr>\n";
			while ($data = dbarray($result)) {
				$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
				echo "<tr>\n<td class='".$cell_color."'>\n".profile_link($data['user_id'], $data['user_name'], $data['user_status'])."</td>\n";
				echo "<td align='center' width='1%' class='".$cell_color."' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>\n</tr>";
			}
			echo "</table>\n";
			closetable();
		} else {
			redirect("index.php");
		}
	} else {
		opentable($locale['420']);
		echo "<div style='text-align:center;'><br />".$locale['430a']."<br /><br /></div>";
		closetable();
	}
} else {
	redirect("index.php");
}

require_once TEMPLATES."footer.php";
?>