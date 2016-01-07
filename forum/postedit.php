<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/postedit.php
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

if (isset($_POST['previewchanges']) || isset($_POST['delete_poll']) || isset($_POST['update_poll_title']) || isset($_POST['update_poll_option']) || isset($_POST['delete_poll_option']) || isset($_POST['add_poll_option'])) {
	$message = trim(stripinput(censorwords($_POST['message'])));
	$subject = isset($_POST['subject']) ? trim(stripinput(censorwords($_POST['subject']))) : $tdata['thread_subject'];
	$description = isset($_POST['description']) ? trim(stripinput(censorwords($_POST['description']))) : ""; // Pimped
	$disable_smileys_check = isset($_POST['disable_smileys']) || preg_match("#\[code\](.*?)\[/code\]#si", $message) ? " checked='checked'" : "";
	$del_check = isset($_POST['delete']) ? " checked='checked'" : "";
	$edit_hide_check = isset($_POST['edit_hide']) ? " checked='checked'" : ""; 
	$del_attach_check = isset($_POST['delete_attach']) ? " checked='checked'" : "";
	$poll_opts = array();
	
	if ($fdata['forum_poll'] && checkgroup($fdata['forum_poll'])) {
		if ($tdata['thread_poll'] == 1 && ($pdata['post_author'] == $tdata['thread_author']) && ($userdata['user_id'] == $tdata['thread_author'] || iSUPERADMIN || iMOD)) {
			$poll_title = trim(stripinput(censorwords($_POST['poll_title'])));
			if (isset($_POST['update_poll_title'])) {
					$result = dbquery("UPDATE ".DB_FORUM_POLLS." SET forum_poll_title='$poll_title' WHERE thread_id='".$_GET['thread_id']."'");
			} elseif (isset($_POST['delete_poll'])) {
					$result = dbquery("DELETE FROM ".DB_FORUM_POLLS." WHERE thread_id='".$_GET['thread_id']."'");
					$result = dbquery("DELETE FROM ".DB_FORUM_POLL_OPTIONS." WHERE thread_id='".$_GET['thread_id']."'");
					$result = dbquery("DELETE FROM ".DB_FORUM_POLL_VOTERS." WHERE thread_id='".$_GET['thread_id']."'");
					$result = dbquery("UPDATE ".DB_THREADS." SET thread_poll='0' WHERE thread_id='".$_GET['thread_id']."'");
					$fdata['forum_poll'] = 0;
			}
			if (isset($_POST['poll_options']) && is_array($_POST['poll_options'])) {
				$i = 1;
				foreach ($_POST['poll_options'] as $poll_option) {
					if (isset($_POST['delete_poll_option'][$i])) {
						$data = dbarray(dbquery("SELECT forum_poll_option_votes FROM ".DB_FORUM_POLL_OPTIONS." WHERE thread_id='".$_GET['thread_id']."' AND forum_poll_option_id='".$i."'"));
						$result = dbquery("DELETE FROM ".DB_FORUM_POLL_OPTIONS." WHERE thread_id='".$_GET['thread_id']."' AND forum_poll_option_id='".$i."'");
						$result = dbquery("UPDATE ".DB_FORUM_POLL_OPTIONS." SET forum_poll_option_id=forum_poll_option_id-1 WHERE thread_id='".$_GET['thread_id']."' AND forum_poll_option_id>'".$i."'");
						$result = dbquery("UPDATE ".DB_FORUM_POLLS." SET forum_poll_votes=forum_poll_votes-".$data['forum_poll_option_votes']." WHERE thread_id='".$_GET['thread_id']."'");
					} elseif (isset($_POST['add_poll_option'][$i])) {
						if (trim($poll_option)) {
							$poll_opts[] = trim(stripinput($poll_option));
							$result = dbquery("INSERT INTO ".DB_FORUM_POLL_OPTIONS." (thread_id, forum_poll_option_id, forum_poll_option_text, forum_poll_option_votes) VALUES('".$_GET['thread_id']."', '".$i."', '".trim(stripinput($poll_option))."', '0')");
						}
					} elseif (isset($_POST['update_poll_option'][$i])) {
						if (trim($poll_option)) {
							$poll_opts[] = trim(stripinput($poll_option));
							$result = dbquery("UPDATE ".DB_FORUM_POLL_OPTIONS." SET forum_poll_option_text='".trim(stripinput($poll_option))."' WHERE thread_id='".$_GET['thread_id']."' AND forum_poll_option_id='".$i."'");
						}
					} else {
						if (trim($poll_option)) { $poll_opts[] = trim(stripinput($poll_option)); }
					}
					$i++;
				}
			} else {
				$poll_opts = array();
			}
		}
	}
	
	if (isset($_POST['previewchanges'])) {
		if ($message == "") {
			$previewmessage = $locale['421'];
		} else {
			$previewmessage = $message;
			if (!$disable_smileys_check) { $previewmessage = parsesmileys($previewmessage); }
			$previewmessage = parseubb($previewmessage);
			$previewmessage = nl2br($previewmessage);
		}
		$udata = dbarray(dbquery("SELECT user_id, user_name, user_status, user_avatar, user_level, user_posts, user_joined FROM ".DB_USERS." WHERE user_id='".$pdata['post_author']."'"));
		add_to_title($locale['global_201'].$locale['405']);
		opentable($locale['405']);
		echo "<div class='tbl2 forum_breadcrumbs' style='margin-bottom:5px'><a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$settings['sitename']."</a> &raquo; ".$caption."</div>\n"; // Pimped: make_url
		
		if ($fdata['forum_poll'] && checkgroup($fdata['forum_poll'])) {
			if ($tdata['thread_poll'] == 1 && ($pdata['post_author'] == $tdata['thread_author']) && ($userdata['user_id'] == $tdata['thread_author'] || iSUPERADMIN || iMOD)) {
				if ((isset($poll_title) && $poll_title != "") && (isset($poll_opts) && is_array($poll_opts))) {
					echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border' style='margin-bottom:5px'>\n<tr>\n";
					echo "<td align='center' class='tbl2'><strong>".$poll_title."</strong></td>\n</tr>\n<tr>\n<td class='tbl1'>\n";
					echo "<table align='center' cellpadding='0' cellspacing='0'>\n";
					foreach ($poll_opts as $poll_option) {
						echo "<tr>\n<td class='tbl1'><input type='radio' name='poll_option' value='$i' style='vertical-align:middle;' /> ".$poll_option."</td>\n</tr>\n";
						$i++;
					}
					echo "</table>\n</td>\n</tr>\n</table>\n";
				}
			}
		}
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_thread_table'>\n<tr>\n";
		echo "<td colspan='2' class='tbl2'><strong>".$subject."</strong></td>\n</tr>\n";
		echo "<tr>\n<td class='tbl2 forum_thread_user_name' style='width:140px;'>".profile_link($udata['user_id'], $udata['user_name'], $udata['user_status'])."</td>\n";
		echo "<td class='tbl2 forum_thread_post_date'>".$locale['426'].showdate("forumdate", time())."</td>\n";
		echo "</tr>\n<tr>\n<td valign='top' width='140' class='tbl2 forum_thread_user_info'>\n";
		if ($userdata['user_avatar'] && file_exists(IMAGES."avatars/".$udata['user_avatar'])) {
			echo "<img src='".IMAGES."avatars/".$udata['user_avatar']."' alt='' /><br /><br />\n";
		}
		echo "<span class='small'>".getuserlevel($udata['user_level'])."</span><br /><br />\n";
		echo "<span class='small'><strong>".$locale['423']."</strong> ".$udata['user_posts']."</span><br />\n";
		echo "<span class='small'><strong>".$locale['425']."</strong> ".showdate("%d.%m.%y", $udata['user_joined'])."</span><br />\n";
		echo "<br /></td>\n<td valign='top' class='tbl1 forum_thread_user_post'>".$previewmessage;
		
		if(!$edit_hide_check) {
			echo "<hr />\n".$locale['427'].profile_link($userdata['user_id'], $userdata['user_name'], $userdata['user_status'])."".$locale['428'].showdate("forumdate", time())."</td>\n";
		}
		
		#echo "<hr />\n".$locale['427'].profile_link($pdata['post_edituser'], $pdata['user_name'], $pdata['user_status'])."".$locale['428'].showdate("forumdate", $pdata['post_edittime'])."</td>\n";
		
		echo "</tr>\n</table>\n";
		closetable();
	}
}
if (isset($_POST['savechanges'])) {
	if (isset($_POST['delete'])) {
		$result = dbquery("SELECT post_author FROM ".DB_POSTS." WHERE post_id='".$_GET['post_id']."' AND thread_id='".$_GET['thread_id']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			$result = dbquery("UPDATE ".DB_USERS." SET user_posts=user_posts-1 WHERE user_id='".$data['post_author']."'");
			$result = dbquery("DELETE FROM ".DB_POSTS." WHERE post_id='".$_GET['post_id']."' AND thread_id='".$_GET['thread_id']."'");
			$result = dbquery("UPDATE ".DB_FORUMS." SET forum_postcount=forum_postcount-1 WHERE forum_id = '".$_GET['forum_id']."'");
			$result = dbquery("SELECT attach_name FROM ".DB_FORUM_ATTACHMENTS." WHERE post_id='".$_GET['post_id']."'");
			if (dbrows($result)) { // Pimped: Multi-Upload
				while ($attach = dbarray($result)) {
					unlink(FORUM_ATT.$attach['attach_name']);
				}
				$result2 = dbquery("DELETE FROM ".DB_FORUM_ATTACHMENTS." WHERE post_id='".$_GET['post_id']."'");
			}
			$posts = dbcount("(post_id)", DB_POSTS, "thread_id='".$_GET['thread_id']."'");
			if (!$posts) {
				$result = dbquery("DELETE FROM ".DB_THREADS." WHERE thread_id='".$_GET['thread_id']."' AND forum_id='".$_GET['forum_id']."'");
				$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE thread_id='".$_GET['thread_id']."'");
				$result = dbquery("UPDATE ".DB_FORUMS." SET forum_threadcount=forum_threadcount-1 WHERE forum_id = '".$_GET['forum_id']."'");
				if ($settings['enable_tags']) { delete_tags((int)$_GET['thread_id'], "F"); } // Pimped: tag
			}
			$result = dbquery("SELECT * FROM ".DB_FORUMS." WHERE forum_id='".$_GET['forum_id']."' AND forum_lastuser='".$pdata['post_author']."' AND forum_lastpost='".$pdata['post_datestamp']."'");
			if (dbrows($result)) {
				$result = dbquery("SELECT forum_id,post_author,post_datestamp FROM ".DB_POSTS." WHERE forum_id='".$_GET['forum_id']."' ORDER BY post_datestamp DESC LIMIT 1");
				if (dbrows($result)) { 
					$pdata2 = dbarray($result);
					$result = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='".$pdata2['post_datestamp']."', forum_lastuser='".$pdata2['post_author']."' WHERE forum_id='".$_GET['forum_id']."'");
				} else {
					$result = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='0', forum_lastuser='0' WHERE forum_id='".$_GET['forum_id']."'");
				}
			}
			if ($posts) {
				$result = dbcount("(thread_id)", DB_THREADS, "thread_id='".$_GET['thread_id']."' AND thread_lastpost='".$pdata['post_datestamp']."' AND thread_lastuser='".$pdata['post_author']."'");
				if ($result != 0) {
					$result = dbquery("SELECT thread_id,post_id,post_author,post_datestamp FROM ".DB_POSTS." WHERE thread_id='".$_GET['thread_id']."' ORDER BY post_datestamp DESC LIMIT 1");
					$pdata2 = dbarray($result);
					$result = dbquery("UPDATE ".DB_THREADS." SET thread_lastpost='".$pdata2['post_datestamp']."', thread_lastpostid='".$pdata2['post_id']."', thread_postcount=thread_postcount-1, thread_lastuser='".$pdata2['post_author']."' WHERE thread_id='".$_GET['thread_id']."'");
				}
			}
			add_to_title($locale['global_201'].$locale['407']);
			opentable($locale['407']);
			echo "<div style='text-align:center'><br />\n".$locale['445']."<br /><br />\n";
			if ($posts > 0) { echo "<a href='".FORUM."viewthread.php?thread_id=".$_GET['thread_id']."'>".$locale['447']."</a> ::\n"; } // Pimped: constant FORUM added
			echo "<a href='".FORUM."viewforum.php?forum_id=".$_GET['forum_id']."'>".$locale['448']."</a> ::\n"; // Pimped: constant FORUM added
			echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['449']."</a><br /><br />\n</div>\n"; // Pimped: make_url
			closetable();
		}
	} else {
		$error = 0;
		if ($pdata['first_post'] == $_GET['post_id']) {
			$subject = trim(stripinput(censorwords($_POST['subject'])));
			if($settings['forum_thread_description']) $description = trim(stripinput(censorwords($_POST['description']))); // Pimped
		}
		$message = trim(stripinput(censorwords($_POST['message'])));
		$tag_name = isset($_POST['tag_name']) ? stripinput($_POST['tag_name']) : ""; // Pimped: tag
		$smileys = isset($_POST['disable_smileys'])|| preg_match("#\[code\](.*?)\[/code\]#si", $message) ? "0" : "1";
		if (iMEMBER) {
			if ($message != "") {
				if (iSUPERADMIN && isset($_POST['edit_hide']) && $_POST['edit_hide'] == 1) {
					$sql = "";
				} else {
					$sql = ", post_edituser='".$userdata['user_id']."', post_edittime='".time()."'";
				}
				$result = dbquery("UPDATE ".DB_POSTS." SET post_message='".$message."', post_smileys='$smileys'".$sql." WHERE post_id='".$_GET['post_id']."'");
				if ($pdata['first_post'] == $_GET['post_id']) { // Pimped
					if($subject != "") {
						$result = dbquery("UPDATE ".DB_THREADS." SET thread_subject='$subject' WHERE thread_id='".(int)$_GET['thread_id']."'");
					}
					if($settings['forum_thread_description'] && isset($_POST['description'])) {
						$result = dbquery("UPDATE ".DB_THREADS." SET thread_description='$description' WHERE thread_id='".(int)$_GET['thread_id']."'");
					}
				}
				if (($settings['enable_tags']) && (iMOD || (iMEMBER && $tdata['thread_author'] == $userdata['user_id']))) {
					update_tags((int)$_GET['thread_id'], "F", $tag_name); // Pimped: tag
				}
				if (isset($_POST['delete_attach']) && is_array($_POST['delete_attach'])) {
					$aresult = dbquery("SELECT attach_id, attach_name FROM ".DB_FORUM_ATTACHMENTS." WHERE post_id='".$_GET['post_id']."'");
					if (dbrows($aresult)) {
						while ($adata = dbarray($aresult)) { // Pimped: Multi-Upload
							if(in_array($adata['attach_id'], $_POST['delete_attach'])) {
								unlink(FORUM_ATT.$adata['attach_name']);
								$result = dbquery("DELETE FROM ".DB_FORUM_ATTACHMENTS." WHERE attach_id='".$adata['attach_id']."'");
								$result = dbquery("UPDATE ".DB_POSTS." SET post_attachments=post_attachments-1 WHERE post_id='".$_GET['post_id']."'");
							}
						}
					}
				}
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
										$result = dbquery("INSERT INTO ".DB_FORUM_ATTACHMENTS." (thread_id, post_id, attach_name, attach_ext, attach_size) VALUES ('".$_GET['thread_id']."', '".$_GET['post_id']."', '$fullattachname', '$attachext', '".$attach['size']."')");
										$result = dbquery("UPDATE ".DB_POSTS." SET post_attachments=post_attachments+1 WHERE post_id='".$_GET['post_id']."'");
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
				$error = 3;
			}
		} else {
			$error = 4;
		}
		redirect(FORUM."postify.php?post=edit&error=$error&forum_id=".$_GET['forum_id']."&thread_id=".$_GET['thread_id']."&post_id=".$_GET['post_id']); // Pimped: constant FORUM added
	}
} else {
	if (!isset($_POST['previewchanges']) && !isset($_POST['update_poll_title']) && !isset($_POST['update_poll_option']) && !isset($_POST['delete_poll_option']) && !isset($_POST['add_poll_option'])) {
		$subject = $pdata['thread_subject'];
		if($settings['forum_thread_description']) $description = $pdata['thread_description']; // Pimped
		$message = $pdata['post_message'];
		$disable_smileys_check = ($pdata['post_smileys'] == "0" ? " checked='checked'" : "");
		$edit_hide_check = "";
		$del_check = "";
		if ($pdata['post_author'] == $tdata['thread_author'] && $tdata['thread_poll'] == 1) {
			$result = dbquery("SELECT forum_poll_title FROM ".DB_FORUM_POLLS." WHERE thread_id='".(int)$_GET['thread_id']."'");
			if (dbrows($result)) {
				$data = dbarray($result);
				$poll_title = $data['forum_poll_title'];
				$result = dbquery("SELECT forum_poll_option_text FROM ".DB_FORUM_POLL_OPTIONS."
				WHERE thread_id='".(int)$_GET['thread_id']."' ORDER BY forum_poll_option_id ASC");
				while ($data = dbarray($result)) {
					$poll_opts[] = $data['forum_poll_option_text'];
				}
			}
		}
	}
	opentable($locale['408']);
	if (!isset($_POST['previewchanges'])) echo "<div class='tbl2 forum_breadcrumbs' style='margin-bottom:5px'><a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$settings['sitename']."</a> &raquo; ".$caption."</div>\n"; // Pimped: make_url

	echo "<form name='inputform' method='post' action='".FORUM.FUSION_SELF."?action=edit&amp;forum_id=".$_GET['forum_id']."&amp;thread_id=".$_GET['thread_id']."&amp;post_id=".$_GET['post_id']."' enctype='multipart/form-data'>\n"; // Pimped: constant FORUM added
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	if ($pdata['first_post'] == $_GET['post_id']) { 
		echo "<td width='145' class='tbl2'>".$locale['460']."</td>\n";
		echo "<td class='tbl2'><input type='text' name='subject' value='$subject' class='textbox' maxlength='100' style='width:250px' /></td>\n";
		echo "</tr>\n<tr>\n";
		if($settings['forum_thread_description']) { // Pimped
			echo "<td width='145' class='tbl2'>".$locale['460a']."</td>\n";
			echo "<td class='tbl2'><input type='text' name='description' value='$description' class='textbox' maxlength='100' style='width:250px' /></td>\n";
			echo "</tr>\n<tr>\n";
		}
	}
	echo "<td valign='top' width='145' class='tbl2'>".$locale['461']."</td>\n";
	echo "<td class='tbl1'><textarea name='message' cols='60' rows='15' class='textbox' style='width:98%'>$message</textarea></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='145' class='tbl2'>&nbsp;</td>\n";
	echo "<td class='tbl1'>".display_bbcodes("99%", "message")."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td valign='top' width='145' class='tbl2'>".$locale['463']."</td>\n";
	echo "<td class='tbl1'>\n";
	echo "<label><input type='checkbox' name='disable_smileys' value='1'".$disable_smileys_check." /> ".$locale['482']."</label><br />\n";
	if(iSUPERADMIN) {
		echo "<label><input type='checkbox' name='edit_hide' value='1'".$edit_hide_check." /> ".$locale['487']."</label><br />\n";
	}
	echo "<label><input type='checkbox' name='delete' value='1'".$del_check." /> ".$locale['484']."</label>\n";
	echo "</td>\n</tr>\n";
	if (($settings['enable_tags']) && (iMOD || (iMEMBER && $tdata['thread_author'] == $userdata['user_id']))) {
		echo edit_tags((int)$_GET['thread_id'], "F", "tbl2"); // Pimped: tag
	}
	if ($fdata['forum_attach'] && checkgroup($fdata['forum_attach'])) {
		add_to_head("<script src='".INCLUDES_JS."multiupload.js' type='text/javascript'></script>"); // Pimped: Multi-Upload
		echo "<tr>\n<td valign='top' width='145' class='tbl2'>".$locale['464']."</td>\n<td class='tbl1'>\n";
		$result = dbquery("SELECT attach_id, attach_name FROM ".DB_FORUM_ATTACHMENTS." WHERE post_id='".$_GET['post_id']."'");
		$count_atts = 0;
		if (dbrows($result)) {
			while ($adata = dbarray($result)) {
				if(file_exists(FORUM_ATT.$adata['attach_name'])) {
					echo "<label><input type='checkbox' name='delete_attach[]' value='".$adata['attach_id']."' /> ".$locale['485']."</label>\n";
					echo "<a href='".FORUM_ATT.$adata['attach_name']."'>".$adata['attach_name']."</a> [".parsebytesize(filesize(FORUM_ATT.$adata['attach_name']))."]<br />\n";
				} else {
					echo "<label><input type='checkbox' name='delete_attach[]' value='".$adata['attach_id']."' /> ".$locale['485']."</label>\n";
					echo "".$adata['attach_name']." <strong>[attachment does not exist on server anymore]</strong><br />\n";
				}
				$count_atts++;
			} // Pimped
			}
			if($count_atts < $settings['attachmentsmax_files'] ) {
				$attachtypes = explode(",", $settings['attachtypes']);
				$insert_type = ''; $x = true;
				foreach($attachtypes as $type) {
					if(substr($type, 0, 1) == '.') { $type = substr($type, 1); }
					$insert_type .= ($x == false ? '|' : '').$type;
					$x = false;
				}
			$new_max_atts = $settings['attachmentsmax_files'] - $count_atts;
			echo "<input type='file' name='attach[]' class='multi' accept='".$insert_type."'  maxlength='".$new_max_atts."' style='width:200px;' /><br />\n";
			echo "<span class='small2'>".sprintf($locale['466'], parsebytesize($settings['attachmax']), str_replace(',', ' ', $settings['attachtypes']))."</span>";
	
			}
		echo "</td>\n</tr>\n";
	}
	
	if ($fdata['forum_poll'] && checkgroup($fdata['forum_poll'])) {
		if ($tdata['thread_poll'] && ($pdata['post_author'] == $tdata['thread_author']) && ($userdata['user_id'] == $tdata['thread_author'] || iSUPERADMIN || iMOD)) {
			echo "<tr>\n<td align='center' colspan='2' class='tbl2'>".$locale['468']."</td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td width='145' class='tbl2'>".$locale['469']."</td>\n";
			echo "<td class='tbl1'><input type='text' name='poll_title' value='".$poll_title."' class='textbox' maxlength='255' style='width:150px' />\n";
			echo "<input type='submit' name='update_poll_title' value='".$locale['472']."' class='button' />\n";
			echo "<input type='submit' name='delete_poll' value='".$locale['473']."' class='button' />\n</td>\n</tr>\n";
			$i = 1;
			if (isset($poll_opts) && is_array($poll_opts)) {
				foreach ($poll_opts as $poll_option) {
					echo "<tr>\n<td width='145' class='tbl2'>".$locale['470']." ".$i."</td>\n";
					echo "<td class='tbl1'><input type='text' name='poll_options[$i]' value='".$poll_option."' class='textbox' maxlength='255' style='width:150px' />\n";
					echo "<input type='submit' name='update_poll_option[$i]' value='".$locale['472']."' class='button' />\n";
					echo "<input type='submit' name='delete_poll_option[$i]' value='".$locale['473']."' class='button' />\n</td>\n</tr>\n";
					$i++;
				}
				echo "<tr>\n<td width='145' class='tbl2'>".$locale['470']." ".$i."</td>\n";
				echo "<td class='tbl1'><input type='text' name='poll_options[$i]' class='textbox' maxlength='255' style='width:150px' />\n";
				echo "<input type='submit' name='add_poll_option[$i]' value='".$locale['471']."' class='button' /></td>\n</tr>\n";
			} else {
				echo "<tr>\n<td width='145' class='tbl2'>".$locale['470']."</td>\n";
				echo "<td class='tbl1'><input type='text' name='poll_options[$i]' class='textbox' maxlength='255' style='width:150px' />\n";
				echo "<input type='submit' name='add_poll_option[$i]' value='".$locale['471']."' class='button' /></td>\n</tr>\n";
			}
		}
	}
	
	echo "<tr>\n<td align='center' colspan='2' class='tbl1'>\n";
	echo "<input type='submit' name='previewchanges' value='".$locale['405']."' class='button' />\n";
	echo "<input type='submit' name='savechanges' value='".$locale['409']."' class='button' />\n";
	echo "</td>\n</tr>\n</table>\n</form>\n";
	closetable();
}
?>