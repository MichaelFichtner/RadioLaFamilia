<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/postreply.php
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
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if ($settings['enable_tags']) require_once INCLUDES."tag_include.php"; // Pimped: tag
if (isset($_POST['previewreply'])) {
	$message = trim(stripinput(censorwords($_POST['message'])));
	$sig_checked = isset($_POST['show_sig']) ? " checked='checked'" : "";
	$disable_smileys_check = isset($_POST['disable_smileys']) || preg_match("#\[code\](.*?)\[/code\]#si", $message) ? " checked='checked'" : "";
	if ($settings['thread_notify']) $notify_checked = isset($_POST['notify_me']) ? " checked='checked'" : "";
	if ($message == "") {
		$previewmessage = $locale['421'];
	} else {
		$previewmessage = $message;
		if ($sig_checked) { $previewmessage = $previewmessage."\n\n".$userdata['user_sig']; }
		if (!$disable_smileys_check) {  $previewmessage = parsesmileys($previewmessage); }
		$previewmessage = parseubb($previewmessage);
		$previewmessage = nl2br($previewmessage);
	}
	$is_mod = iMOD && iUSER < nADMIN ? true : false;
	opentable($locale['402']);
	echo "<div class='tbl2 forum_breadcrumbs' style='margin-bottom:5px'><span class='small'><a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$settings['sitename']."</a> &raquo; ".$caption."</span></div>\n"; // Pimped: make_url

	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	echo "<td colspan='2' class='tbl2'><strong>".$tdata['thread_subject']."</strong></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl2' style='width:140px;'>".profile_link($userdata['user_id'], $userdata['user_name'], $userdata['user_status'])."</td>\n";
	echo "<td class='tbl2'>".$locale['426'].showdate("forumdate", time())."</td>\n";
	echo "</tr>\n<tr>\n<td valign='top' width='140' class='tbl2'>\n";
	if ($userdata['user_avatar'] && file_exists(IMAGES."avatars/".$userdata['user_avatar'])) {
		echo "<img src='".IMAGES."avatars/".$userdata['user_avatar']."' alt='' /><br /><br />\n";
	}
	echo "<span class='small'>".getuserlevel($userdata['user_level'])."</span><br /><br />\n";
	echo "<span class='small'><strong>".$locale['423']."</strong> ".$userdata['user_posts']."</span><br />\n";
	echo "<span class='small'><strong>".$locale['425']."</strong> ".showdate("%d.%m.%y", $userdata['user_joined'])."</span><br />\n";
	echo "<br /></td>\n<td valign='top' class='tbl1'>".$previewmessage."</td>\n";
	echo "</tr>\n</table>\n";
	closetable();
}
if (isset($_POST['postreply'])) {
	$message = trim(stripinput(censorwords($_POST['message'])));
	$flood = false; $error = 0;
	$sig = isset($_POST['show_sig']) ? "1" : "0";
	$smileys = isset($_POST['disable_smileys']) || preg_match("#\[code\](.*?)\[/code\]#si", $message) ? "0" : "1";
	$thread_time = time();
	$tag_name = isset($_POST['tag_name']) ? stripinput($_POST['tag_name']) : false; // Pimped: tag
	if (iMEMBER) {
		if ($message != "") {
			require_once INCLUDES."flood_include.php";
			if (!flood_control("post_datestamp", DB_POSTS, "post_author='".$userdata['user_id']."'")) {
			if($settings['forum_double_post_merger']) { // Pimped, Double post merger by Qwertz
			$results = dbquery("SELECT post_author, post_message, post_id FROM ".DB_POSTS." WHERE thread_id='".$_GET['thread_id']."' AND post_id = (select max(post_id) from ".DB_POSTS." where thread_id='".$_GET['thread_id']."' and post_id = (SELECT MAX(post_id) FROM ".DB_POSTS."))");
				if (dbrows($results)) {
					while ($datas = dbarray($results)) {
						if ($datas['post_author'] == $userdata['user_id']) {
							$message2 = $datas['post_message']."\n\n[b]Edit:[/b]\n".$message;
							$result = dbquery("UPDATE ".DB_POSTS." SET post_message='$message2', post_edituser='".$userdata['user_id']."', post_edittime='".$thread_time."' WHERE post_id='".$datas['post_id']."' ");
							$newpost_id = $datas['post_id'];
						} else {
							$result = dbquery("INSERT INTO ".DB_POSTS." (forum_id, thread_id, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_edituser, post_edittime) VALUES ('".$_GET['forum_id']."', '".$_GET['thread_id']."', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".$thread_time."', '".USER_IP."', '0', '0')");
						}
					}
				} else {
					$result = dbquery("INSERT INTO ".DB_POSTS." (forum_id, thread_id, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_edituser, post_edittime) VALUES ('".$_GET['forum_id']."', '".$_GET['thread_id']."', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".$thread_time."', '".USER_IP."', '0', '0')");
				}
			} else { // pimped end
				$result = dbquery("INSERT INTO ".DB_POSTS." (forum_id, thread_id, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_edituser, post_edittime) VALUES ('".$_GET['forum_id']."', '".$_GET['thread_id']."', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".$thread_time."', '".USER_IP."', '0', '0')");
			}
				$newpost_id = isset($newpost_id) && isnum($newpost_id) ? $newpost_id : mysql_insert_id();
				if (($settings['enable_tags']) && $tag_name !== false && (iMOD || (iMEMBER && $tdata['thread_author'] == $userdata['user_id']))) {			
			        echo update_tags((int)$_GET['thread_id'], "F" , $tag_name); // Pimped: tag
	            }
				$result = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='".$thread_time."', forum_postcount=forum_postcount+1, forum_lastuser='".$userdata['user_id']."' WHERE forum_id='".$_GET['forum_id']."'");
				// Pimped ->
				$result2 = dbquery("SELECT forum_parent FROM ".DB_FORUMS." WHERE forum_id='".$_GET['forum_id']."'");
				if(dbrows($result2)) {
					$data2 = dbarray($result2);
					$result = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='".$thread_time."', forum_postcount=forum_postcount+1, forum_lastuser='".$userdata['user_id']."' WHERE forum_id='".$data2['forum_parent']."'");
				}
				// <-
				$result = dbquery("UPDATE ".DB_THREADS." SET thread_lastpost='".$thread_time."', thread_lastpostid='$newpost_id', thread_postcount=thread_postcount+1, thread_lastuser='".$userdata['user_id']."' WHERE thread_id='".$_GET['thread_id']."'");
				$result = dbquery("UPDATE ".DB_USERS." SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
				if ($settings['thread_notify'] && isset($_POST['notify_me'])) {
					if (!dbcount("(thread_id)", DB_THREAD_NOTIFY, "thread_id='".$_GET['thread_id']."' AND notify_user='".$userdata['user_id']."'")) {
						$result = dbquery("INSERT INTO ".DB_THREAD_NOTIFY." (thread_id, notify_datestamp, notify_user, notify_status) VALUES('".$_GET['thread_id']."', '".$thread_time."', '".$userdata['user_id']."', '1')");
					}
				}
				// Pimped: Mark thread as read
					$thread_match = $_GET['thread_id']."\|".$thread_time."\|".$fdata['forum_id'];
					if (($thread_time > $lastvisited) && !preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
						$result = dbquery("UPDATE ".DB_USERS." SET user_threads='".$userdata['user_threads'].".".stripslashes($thread_match)."' WHERE user_id='".$userdata['user_id']."'");
					}
				//
				if (isset($_FILES['attach']) && $fdata['forum_attach'] && checkgroup($fdata['forum_attach'])) {
					$_FILES['attach'] = array_reverse($_FILES['attach']); // Pimped
						for ($a = 0 ; $a < count($_FILES['attach']['tmp_name']); $a++) { // Pimped

						$attach['name'] = $_FILES['attach']['name'][$a]; // Pimped
						$attach['error'] = $_FILES['attach']['error'][$a]; // Pimped
						$attach['tmp_name'] = $_FILES['attach']['tmp_name'][$a]; // Pimped
						$attach['size'] = $_FILES['attach']['size'][$a]; // Pimped
						
						$attach['name'] = attach_replace_space($attach['name']); // Pimped
						if ($attach['name'] != "" && !empty($attach['name']) && is_uploaded_file($attach['tmp_name'])) {
							$attachname = attach_name($attach['name'], true);
							$attachext = attach_name($attach['name'], false, true);
							if (preg_match("/^[-0-9A-Z_\[\]]+$/i", $attachname) && $attach['size'] <= $settings['attachmax']) {
								$attachtypes = explode(",", $settings['attachtypes']);
								if (in_array($attachext, $attachtypes)) {
									$fullattachname = attach_name($attach['name']);
									move_uploaded_file($attach['tmp_name'], FORUM_ATT.$fullattachname);
									chmod(FORUM_ATT.$fullattachname,0644);
									if (in_array($attachext, $imagetypes) && (!@getimagesize(FORUM_ATT.$fullattachname) || !@verify_image(FORUM_ATT.$fullattachname))) {
										unlink(FORUM_ATT.$fullattachname);
										$error = 1;
									}
									if (!$error) {
										$result = dbquery("INSERT INTO ".DB_FORUM_ATTACHMENTS." (thread_id, post_id, attach_name, attach_ext, attach_size) VALUES ('".$_GET['thread_id']."', '".$newpost_id."', '$fullattachname', '$attachext', '".$attach['size']."')");
										$result = dbquery("UPDATE ".DB_POSTS." SET post_attachments=post_attachments+1 WHERE post_id='".$newpost_id."'");
									}
								} else {
									@unlink($attach['tmp_name']);
									$error = 1;
								}
							} else {
								@unlink($attach['tmp_name']);
								$error = 2;
							}
						}
					} // Pimped
				}
			} else {
					redirect(make_url(FORUM."viewforum.php?forum_id=".$_GET['forum_id'], BASEDIR."forum-".$_GET['forum_id']."-", $fdata['forum_name'], ".html")); // Pimped: make_url
			}
		} else {
			$error = 3;
		}
	} else {
		$error = 4;
	}
	if ($error > 2) {
		redirect(make_url(FORUM."postify.php?post=reply&error=$error&forum_id=".$_GET['forum_id']."&thread_id=".$_GET['thread_id'], FORUM."postify.php?post=reply&error=$error&forum_id=".$_GET['forum_id']."&thread_id=".$_GET['thread_id'], "", "")); // Pimped: make_url, but no seo url-rewrite
	} else {
		redirect(make_url(FORUM."postify.php?post=reply&error=$error&forum_id=".$_GET['forum_id']."&thread_id=".$_GET['thread_id']."&post_id=$newpost_id", FORUM."postify.php?post=reply&error=$error&forum_id=".$_GET['forum_id']."&thread_id=".$_GET['thread_id']."&post_id=$newpost_id", "", "")); // Pimped: make_url, but no seo url-rewrite
	}
} else {
	if (!isset($_POST['previewreply'])) {
		$message = "";
		$disable_smileys_check = "";
		$sig_checked = " checked='checked'";
		if ($settings['thread_notify']) {
			if (dbcount("(thread_id)", DB_THREAD_NOTIFY, "thread_id='".$_GET['thread_id']."' AND notify_user='".$userdata['user_id']."'")) {
				$notify_checked = " checked='checked'";
			} else {
				$notify_checked = "";
			}
		}
	}
	if (isset($_GET['quote']) && isnum($_GET['quote'])) {
		$result = dbquery(
			"SELECT post_message, user_name FROM ".DB_POSTS."
			INNER JOIN ".DB_USERS." ON ".DB_POSTS.".post_author=".DB_USERS.".user_id
			WHERE thread_id='".$_GET['thread_id']."' and post_id='".$_GET['quote']."'"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			$message = "[quote][b]".$data['user_name'].$locale['429']."[/b]\n".strip_bbcodes($data['post_message'])."[/quote]";
		}
	}
	add_to_title($locale['global_201'].$locale['403']);
	echo "<!--pre_postreply-->";
	opentable($locale['403']);
	if (!isset($_POST['previewreply'])) echo "<div class='tbl2 forum_breadcrumbs' style='margin-bottom:5px'><a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$settings['sitename']."</a> &raquo; ".$caption."</div>\n"; // Pimped: make_url
	
	echo "<form name='inputform' method='post' action='".make_url(FORUM.FUSION_SELF."?action=reply&amp;forum_id=".$_GET['forum_id']."&amp;thread_id=".$_GET['thread_id'], FORUM.FUSION_SELF."?action=reply&amp;forum_id=".$_GET['forum_id']."&amp;thread_id=".$_GET['thread_id'], "", "")."' enctype='multipart/form-data'>\n"; // Pimped: make_url, but no seo url-rewrite
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	echo "<td valign='top' width='145' class='tbl2'>".$locale['461']."</td>\n";
	echo "<td class='tbl1'><textarea name='message' cols='60' rows='15' class='textbox' style='width:98%'>$message</textarea></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='145' class='tbl2'>&nbsp;</td>\n";
	echo "<td class='tbl1'>".display_bbcodes("99%", "message")."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td valign='top' width='145' class='tbl2'>".$locale['463']."</td>\n";
	echo "<td class='tbl1'>\n";
	echo "<label><input type='checkbox' name='disable_smileys' value='1'".$disable_smileys_check." /> ".$locale['482']."</label>";
	if (array_key_exists("user_sig", $userdata) && $userdata['user_sig']) {
		echo "<br />\n<label><input type='checkbox' name='show_sig' value='1'".$sig_checked." /> ".$locale['483']."</label>";
	}
	if ($settings['thread_notify']) {
		echo "<br />\n<label><input type='checkbox' name='notify_me' value='1'".$notify_checked." /> ".$locale['486']."</label>";
	}
	echo "</td>\n</tr>\n";
	if (($settings['enable_tags']) && (iMOD || (iMEMBER && $tdata['thread_author'] == $userdata['user_id']))) {
		echo edit_tags((int)$_GET['thread_id'], "F", "tbl2"); // Pimped: tag
	}
	if ($fdata['forum_attach'] && checkgroup($fdata['forum_attach'])) {
		add_to_head("<script src='".INCLUDES_JS."multiupload.js' type='text/javascript'></script>"); // Pimped: Multi-Upload
		echo "<tr>\n<td width='145' class='tbl2'>".$locale['464']."</td>\n";
		$attachtypes = explode(",", $settings['attachtypes']);
		$insert_type = ''; $x = true;
		foreach($attachtypes as $type) {
			if(substr($type, 0, 1) == '.') { $type = substr($type, 1); }
			$insert_type .= ($x == false ? '|' : '').$type;
			$x = false;
		}
		echo "<td class='tbl1'><input type='file' name='attach[]' class='multi' accept='".$insert_type."'  maxlength='".$settings['attachmentsmax_files']."' style='width:200px;' /><br />\n";
		echo "<span class='small2'>".sprintf($locale['466'], parsebytesize($settings['attachmax']), str_replace(',', ' ', $settings['attachtypes']))."</span></td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n<td align='center' colspan='2' class='tbl1'>\n";
	echo "<input type='submit' name='previewreply' value='".$locale['402']."' class='button' />\n";
	echo "<input type='submit' name='postreply' value='".$locale['404']."' class='button' />\n";
	echo "</td>\n</tr>\n</table>\n</form>\n";
	closetable();
	echo "<!--sub_postreply-->";
	// Pimped: Post Review
	if($_GET['thread_id']){
		$posts_res = dbquery(
			"SELECT p.post_message, p.post_datestamp, u.user_id, u.user_name, u.user_status, u2.user_name AS edit_name
			FROM ".DB_POSTS." p
			LEFT JOIN ".DB_USERS." u ON p.post_author = u.user_id
			LEFT JOIN ".DB_USERS." u2 ON p.post_edituser = u2.user_id AND post_edituser > '0'
			WHERE p.thread_id='".(int)$_GET['thread_id']."' ORDER BY post_datestamp DESC LIMIT 20"
		);
		
	opentable($locale['475']);
	echo "<div style='max-height: 600px; overflow: auto;'><table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n\t";
	$i = 0;
	while($post_data = dbarray($posts_res)){
		$i++;
		$class = $i%2 == 0 ? "tbl1" : "tbl2";
		echo "<tr><td rowspan='2' valign='top' class='$class' width='1%' style='white-space: nowrap;'>
				".profile_link($post_data['user_id'], $post_data['user_name'], $post_data['user_status'], '', '', 'font-weight:bold;')."<br />
			</td><td class='$class'>
				".showdate("forumdate", $post_data['post_datestamp'])."
			</td></tr><tr>
			<td class='$class'>
				".nl2br(parseubb($post_data['post_message']))."
			</td></tr>";
	}
	echo "</table></div>";
	closetable();
}
}
?>