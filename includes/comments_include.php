<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/comments_include.php
| Version: Pimped Fusion v0.09.01
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

include LOCALE.LOCALESET."comments.php";

if($settings['warning_system_comments']) {
	require_once INCLUDES."warning.inc.php";
}

function showcomments($ctype, $cdb, $ccol, $cid, $clink,
$seo_root_link = "", $a = "-", $seo_catid = "", $b = "-page-", $rowstart = "", $c = "-", $seo_subject = "", $do_not_show_avatar = false) { // Pimped
// bisher umgesetzt in: viewpage.php
//$seo_root_link="",$a="-",$seo_catid="",$b="-page-",$c="-",$seo_subject=""
//"page", "-", $_GET['page_id'], "-p-", XX "-", $cp_data['page_title']
	global $settings, $locale, $userdata, $aidlink;
	
	if($settings['showcomments_avatar'] == "1" && $do_not_show_avatar != true) {
		showcomments_avatar($ctype, $cdb, $ccol, $cid, $clink, $seo_root_link,$a,$seo_catid,$b, $rowstart, $c,$seo_subject);
	} else {

	if(URL_REWRITE && $seo_root_link != "") { $seo_link = $seo_root_link.$a.$seo_catid.$c.clean_subject_urlrewrite($seo_subject).".html";} // Pimped
	$link = FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "");
	$link = preg_replace("^(&amp;|\?)c_action=(edit|delete)&amp;comment_id=\d*^", "", $link);

	if (iMEMBER && (isset($_GET['c_action']) && $_GET['c_action'] == "delete") && (isset($_GET['comment_id']) && isnum($_GET['comment_id']))) {
		if (((iMODERATOR || iADMIN) && checkrights("C")) || (iMEMBER && dbcount("(comment_id)", DB_COMMENTS, "comment_id='".(int)$_GET['comment_id']."' AND comment_name='".(int)$userdata['user_id']."'"))) {
			$result = dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_id='".(int)$_GET['comment_id']."'".((iMODERATOR || iADMIN) ? "" : " AND comment_name='".(int)$userdata['user_id']."'"));
		}
		redirect($clink);
	}

	if ($settings['comments_enabled'] == "1") {
		if ((iMEMBER || $settings['guestposts'] == "1") && isset($_POST['post_comment'])) {

			if (iMEMBER) {
				$comment_name = $userdata['user_id'];
			} elseif ($settings['guestposts'] == "1") {
				$comment_name = trim(stripinput($_POST['comment_name']));
				$comment_name = preg_replace("(^[0-9]*)", "", $comment_name);
				if (isnum($comment_name)) { $comment_name = ""; }
				include_once INCLUDES."securimage/securimage.php";
				$securimage = new Securimage();
				if (!isset($_POST['com_captcha_code']) || $securimage->check($_POST['com_captcha_code']) == false) { redirect($link); }
			}

			$comment_message = trim(stripinput(censorwords($_POST['comment_message'])));

			if (iMEMBER && (isset($_GET['c_action']) && $_GET['c_action'] == "edit") && (isset($_GET['comment_id']) && isnum($_GET['comment_id']))) {
				$comment_updated = false;
				if (((iMODERATOR || iADMIN) && checkrights("C")) || (iMEMBER && dbcount("(comment_id)", DB_COMMENTS, "comment_id='".(int)$_GET['comment_id']."' AND comment_item_id='".$cid."' AND comment_type='".$ctype."' AND comment_name='".(int)$userdata['user_id']."' AND comment_hidden='0'"))) {
					if ($comment_message) {
						$result = dbquery("UPDATE ".DB_COMMENTS." SET comment_message="._db($comment_message)." WHERE comment_id='".(int)$_GET['comment_id']."'".((iMODERATOR || iADMIN) ? "" : " AND comment_name='".(int)$userdata['user_id']."'"));
						$comment_updated = true;
					}
				}
				if ($comment_updated) {
					$c_start = (ceil(dbcount("(comment_id)", DB_COMMENTS, "comment_id<='".(int)$_GET['comment_id']."' AND comment_item_id="._db($cid)." AND comment_type="._db($ctype)."") / 10) - 1) * 10;
				}
				redirect($clink."&amp;c_start=".(isset($c_start) && isnum($c_start) ? $c_start : ""));
			} else {
				if (!dbcount("(".$ccol.")", $cdb, $ccol."='".$cid."'")) { redirect(BASEDIR."index.php"); }
				if ($comment_name && $comment_message) {
					require_once INCLUDES."flood_include.php";
					if (!flood_control("comment_datestamp", DB_COMMENTS, "comment_ip='".USER_IP."'")) {
						$result = dbquery("INSERT INTO ".DB_COMMENTS." (comment_item_id, comment_type, comment_name, comment_message, comment_datestamp, comment_ip, comment_hidden) VALUES ("._db($cid).", "._db($ctype).", "._db($comment_name).", "._db($comment_message).", '".time()."', '".USER_IP."', '0')");
					}
				}
				$c_start = (ceil(dbcount("(comment_id)", DB_COMMENTS, "comment_item_id="._db($cid)." AND comment_type="._db($ctype)."") / 10) - 1) * 10;
				redirect($clink."&amp;c_start=".$c_start);
			}
		}

		opentable($locale['c100']);
		echo "<a id='comments' name='comments'></a>";
		$c_rows = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id="._db($cid)." AND comment_type="._db($ctype)." AND comment_hidden='0'");
		if (!isset($_GET['c_start']) && $c_rows > 10) {$_GET['c_start'] = (ceil($c_rows / 10) - 1) * 10;}
		if (!isset($_GET['c_start']) || !isnum($_GET['c_start'])) { $_GET['c_start'] = 0; }
		$result = dbquery(				
			"SELECT tcm.comment_id, tcm.comment_name, tcm.comment_datestamp, tcm.comment_message,
			tcu.user_name, tcu.user_status
			FROM ".DB_COMMENTS." tcm
			LEFT JOIN ".DB_USERS." tcu ON tcm.comment_name=tcu.user_id
			WHERE comment_item_id="._db($cid)." AND comment_type="._db($ctype)." AND comment_hidden='0'
			ORDER BY comment_datestamp ASC LIMIT ".(int)$_GET['c_start'].",10"
		);
		if (dbrows($result)) {
			$i = $_GET['c_start']+1;
			if ($c_rows > 10) {
				echo "<div style='text-align:center;margin-bottom:5px;'>".makecommentnav($_GET['c_start'], 10, $c_rows, 3, $clink."&amp;", $seo_root_link,$a,$seo_catid,$b, $rowstart, "-cstart-", $c, $seo_subject)."</div>\n";
			}
			while ($data = dbarray($result)) {
				echo "<div class='tbl2'>\n"; // im nachfolgenden FUSION_REQUEST mit FUSION_SELF."?".FUSION_QUERY ersetzt
				if (((iMODERATOR || iADMIN) && checkrights("C")) || (iMEMBER && $data['comment_name'] == $userdata['user_id'] && isset($data['user_name']))) {
					echo "<div style='float:right' class='comment_actions'><!--comment_actions-->\n<a href='".FUSION_SELF."?".FUSION_QUERY."&amp;c_action=edit&amp;comment_id=".$data['comment_id']."#edit_comment'>".$locale['c108']."</a> |\n";
					echo "<a href='".FUSION_SELF."?".FUSION_QUERY."&amp;c_action=delete&amp;comment_id=".$data['comment_id']."'>".$locale['c109']."</a>\n</div>\n";
				}
				echo "<a href='".FUSION_REQUEST."#c".$data['comment_id']."' id='c".$data['comment_id']."' name='c".$data['comment_id']."'>#".$i."</a> | ";
				if ($data['user_name']) {
					echo "<span class='comment-name'>".profile_link($data['comment_name'], $data['user_name'], $data['user_status'])."</span>\n";
				} else {
					echo "<span class='comment-name'>".$data['comment_name']."</span>\n";
				}
				echo "<span class='small'>".$locale['global_071'].showdate("longdate", $data['comment_datestamp'])."</span>\n";
				
				if($settings['warning_system_comments'] && $data['user_name']) {
					$points = show_warning_points($data['comment_name']);
					echo "&nbsp;|&nbsp;<span class='small'><a style='cursor:help;' onclick=\"warning_info();\">".$locale['WARN200']."</a></span> ";
					echo warning_profile_link("1", $data['comment_name'], $points);
				}
				
				echo "</div>\n<div class='tbl1 comment_message'><!--comment_message-->".nl2br(parseubb(parsesmileys($data['comment_message'])))."</div>\n";
				$i++;
			}
			if ((iMODERATOR || iADMIN) && checkrights("C")) {
				echo "<div align='right' class='tbl2'><a href='".ADMIN."comments.php".$aidlink."&amp;ctype=$ctype&amp;cid=$cid'>".$locale['c106']."</a></div>\n";
			}
			if ($c_rows > 10) {
				echo "<div style='text-align:center;margin-top:5px;'>".makecommentnav($_GET['c_start'], 10, $c_rows, 3, $clink."&amp;", $seo_root_link,$a,$seo_catid,$b, $rowstart, "-cstart-", $c, $seo_subject)."</div>\n";
			}
		} else {
			echo $locale['c101']."\n";
		}
		closetable();

		opentable($locale['c102']);
		if (iMEMBER && (isset($_GET['c_action']) && $_GET['c_action'] == "edit") && (isset($_GET['comment_id']) && isnum($_GET['comment_id']))) {
			$eresult = dbquery(
				"SELECT tcm.comment_id, tcm.comment_name, tcm.comment_message, tcu.user_name FROM ".DB_COMMENTS." tcm
				LEFT JOIN ".DB_USERS." tcu ON tcm.comment_name=tcu.user_id
				WHERE comment_id='".(int)$_GET['comment_id']."' AND comment_item_id="._db($cid)." AND comment_type="._db($ctype)." AND comment_hidden='0'"
			);
			if (dbrows($eresult)) {
				$edata = dbarray($eresult);
				if (((iMODERATOR || iADMIN) && checkrights("C")) || (iMEMBER && $edata['comment_name'] == $userdata['user_id'] && isset($edata['user_name']))) {
					$clink .= "&amp;c_action=edit&amp;comment_id=".$edata['comment_id'];
					$comment_message = $edata['comment_message'];
				}
			} else {
				$comment_message = "";
			}
		} else {
			$comment_message = "";
		}
		if (iMEMBER || $settings['guestposts'] == "1") {
			require_once INCLUDES."bbcode_include.php";
			echo "<a id='edit_comment' name='edit_comment'></a>\n";
			echo "<form name='inputform' method='post' action='".((URL_REWRITE && $seo_root_link != "") ? $seo_link : $clink)."'>\n"; // Pimped
			if (iGUEST) {
				echo "<div align='center' class='tbl'>\n".$locale['c104']."<br />\n";
				echo "<input type='text' name='comment_name' maxlength='30' class='textbox' style='width:360px' />\n";
				echo "</div>\n";
			}
			echo "<div align='center' class='tbl'>\n";
			echo "<textarea name='comment_message' cols='70' rows='6' class='textbox' style='width:360px'>".$comment_message."</textarea><br />\n";
			echo display_bbcodes("360px", "comment_message");
			if (iGUEST) {
				echo $locale['global_158']."<br />\n";
				echo "<img id='com_captcha' src='".INCLUDES."securimage/securimage_show.php' alt='' /><br />\n";
				echo "<a href='".INCLUDES."securimage/securimage_play.php'><img src='".INCLUDES."securimage/images/audio_icon.gif' alt='' class='tbl-border' style='margin-bottom:1px' /></a>\n";
				echo "<a href='#' onclick=\"document.getElementById('com_captcha').src = '".INCLUDES."securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."securimage/images/refresh.gif' alt='' class='tbl-border' /></a><br />\n";
				echo $locale['global_159']."<br />\n<input type='text' name='com_captcha_code' class='textbox' style='width:100px' />\n";
			}
			echo "<br />\n<input type='submit' name='post_comment' value='".($comment_message ? $locale['c103'] : $locale['c102'])."' class='button' />\n";
			echo "</div>\n</form>\n";
		} else {
			echo $locale['c105']."\n";
		}
		closetable();
	}
}
}

function showcomments_avatar($ctype, $cdb, $ccol, $cid, $clink,
$seo_root_link = "", $a = "-", $seo_catid = "", $b = "-page-", $rowstart = "", $c = "-", $seo_subject = "") { // Pimped

	global $settings, $locale, $userdata, $aidlink;
	if(URL_REWRITE && $seo_root_link != "") { $seo_link = $seo_root_link.$a.$seo_catid.$c.clean_subject_urlrewrite($seo_subject).".html";} // Pimped
	$link = FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "");
	$link = preg_replace("^(&amp;|\?)c_action=(edit|delete)&amp;comment_id=\d*^", "", $link);

	if (iMEMBER && (isset($_GET['c_action']) && $_GET['c_action'] == "delete") && (isset($_GET['comment_id']) && isnum($_GET['comment_id']))) {
		if (((iMODERATOR || iADMIN) && checkrights("C")) || (iMEMBER && dbcount("(comment_id)", DB_COMMENTS, "comment_id='".(int)$_GET['comment_id']."' AND comment_name='".(int)$userdata['user_id']."'"))) {
			$result = dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_id='".(int)$_GET['comment_id']."'".((iMODERATOR || iADMIN) ? "" : " AND comment_name='".(int)$userdata['user_id']."'"));
		}
		redirect($clink);
	}

	if ($settings['comments_enabled'] == "1") {
		if ((iMEMBER || $settings['guestposts'] == "1") && isset($_POST['post_comment'])) {

			if (iMEMBER) {
				$comment_name = $userdata['user_id'];
			} elseif ($settings['guestposts'] == "1") {
				$comment_name = trim(stripinput($_POST['comment_name']));
				$comment_name = preg_replace("(^[0-9]*)", "", $comment_name);
				if (isnum($comment_name)) { $comment_name = ""; }
				include_once INCLUDES."securimage/securimage.php";
				$securimage = new Securimage();
				if (!isset($_POST['com_captcha_code']) || $securimage->check($_POST['com_captcha_code']) == false) { redirect($link); }
			}

			$comment_message = trim(stripinput(censorwords($_POST['comment_message'])));

			if (iMEMBER && (isset($_GET['c_action']) && $_GET['c_action'] == "edit") && (isset($_GET['comment_id']) && isnum($_GET['comment_id']))) {
				$comment_updated = false;
				if (((iMODERATOR || iADMIN) && checkrights("C")) || (iMEMBER && dbcount("(comment_id)", DB_COMMENTS, "comment_id='".(int)$_GET['comment_id']."' AND comment_item_id='".$cid."' AND comment_type='".$ctype."' AND comment_name='".(int)$userdata['user_id']."' AND comment_hidden='0'"))) {
					if ($comment_message) {
						$result = dbquery("UPDATE ".DB_COMMENTS." SET comment_message="._db($comment_message)." WHERE comment_id='".(int)$_GET['comment_id']."'".((iMODERATOR || iADMIN) ? "" : " AND comment_name='".(int)$userdata['user_id']."'"));
						$comment_updated = true;
					}
				}
				if ($comment_updated) {
					$c_start = (ceil(dbcount("(comment_id)", DB_COMMENTS, "comment_id<='".(int)$_GET['comment_id']."' AND comment_item_id="._db($cid)." AND comment_type="._db($ctype)."") / 10) - 1) * 10;
				}
				redirect($clink."&amp;c_start=".(isset($c_start) && isnum($c_start) ? $c_start : ""));
			} else {
				if (!dbcount("(".$ccol.")", $cdb, $ccol."='".$cid."'")) { redirect(BASEDIR."index.php"); }
				if ($comment_name && $comment_message) {
					require_once INCLUDES."flood_include.php";
					if (!flood_control("comment_datestamp", DB_COMMENTS, "comment_ip='".USER_IP."'")) {
						$result = dbquery("INSERT INTO ".DB_COMMENTS." (comment_item_id, comment_type, comment_name, comment_message, comment_datestamp, comment_ip, comment_hidden) VALUES ("._db($cid).", "._db($ctype).", "._db($comment_name).", "._db($comment_message).", '".time()."', '".USER_IP."', '0')");
					}
				}
				$c_start = (ceil(dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".(int)$cid."' AND comment_type="._db($ctype)."") / 10) - 1) * 10;
				redirect($clink."&amp;c_start=".$c_start);
			}
		}

		opentable($locale['c100']);
		echo "<a id='comments' name='comments'></a>";
		$c_rows = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id="._db($cid)." AND comment_type="._db($ctype)." AND comment_hidden='0'");
		if (!isset($_GET['c_start']) && $c_rows > 10) {$_GET['c_start'] = (ceil($c_rows / 10) - 1) * 10;}
		if (!isset($_GET['c_start']) || !isnum($_GET['c_start'])) { $_GET['c_start'] = 0; }
		$result = dbquery(
			"SELECT tcm.comment_id, tcm.comment_name, tcm.comment_datestamp, tcm.comment_message,
			tcu.user_name, tcu.user_avatar, tcu.user_id, tcu.user_level, tcu.user_status
			FROM ".DB_COMMENTS." tcm
			LEFT JOIN ".DB_USERS." tcu ON tcm.comment_name=tcu.user_id
			WHERE comment_item_id="._db($cid)." AND comment_type="._db($ctype)." AND comment_hidden='0'
			ORDER BY comment_datestamp ASC LIMIT ".(int)$_GET['c_start'].",10"
		);
		if (dbrows($result)) {
			$i = $_GET['c_start']+1;
			if ($c_rows > 10) {
				echo "<div style='text-align:center;margin-bottom:5px;'>".makecommentnav($_GET['c_start'], 10, $c_rows, 3, $clink."&amp;", $seo_root_link,$a,$seo_catid,$b, $rowstart, "-cstart-", $c, $seo_subject)."</div>\n";
			}
			
			echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
			
			while ($data = dbarray($result)) {
					
				echo "<tr><td class='tbl2' width='10%' align='center'>\n";
			if ($data['user_name']) {
				echo "<span class='comment-name'>".profile_link($data['comment_name'], $data['user_name'], $data['user_status'])."</span>\n";
			} else {
				echo "<span class='comment-name'>".$data['comment_name']."</span>\n";
			}
				echo "</td>\n";
				echo "<td class='tbl2'>\n<span class='small'>".$locale['global_071'].showdate("longdate", $data['comment_datestamp'])."</span>\n"; 
				echo "<div style='float:right' class='comment_actions'>";
				    if (((iMODERATOR || iADMIN) && checkrights("C")) || (iMEMBER && $data['comment_name'] == $userdata['user_id'] && isset($data['user_name']))) {
				        echo "<!--comment_actions-->\n<a href='".FUSION_SELF."?".FUSION_QUERY."&amp;c_action=edit&amp;comment_id=".$data['comment_id']."#edit_comment'>".$locale['c108']."</a> |\n";
				        echo "<a href='".FUSION_SELF."?".FUSION_QUERY."&amp;c_action=delete&amp;comment_id=".$data['comment_id']."'>".$locale['c109']."</a> |\n";
				    }
				echo "<a href='".FUSION_REQUEST."#c".$data['comment_id']."' id='c".$data['comment_id']."' name='c".$data['comment_id']."'>#".$i."</a></div>\n";
				echo "</td>\n";
				echo "</tr>\n<tr>\n";
			    $avatar = ($data['user_avatar'] != "" && file_exists(IMAGES_AVA.$data['user_avatar'])) ? IMAGES_AVA.$data['user_avatar'] : IMAGES_AVA."noavatar.jpg";
                echo "<td class='tbl1' width='15%'>\n";
				echo "<div style='text-align:center;'><img src='".$avatar."' width='50' height='50' alt='' /></div><br />\n";
				
				if($settings['warning_system_comments'] && $data['user_name']) {
					$points = show_warning_points($data['user_id']);
					echo "<div class='commentswarnings'>";
					echo "<span class='small'><a style='cursor:help;' onclick=\"warning_info();\">".$locale['WARN200']."</a></span> ";
					echo warning_profile_link("1", $data['user_id'], $points);
					echo "</div>";
				}
				
			    echo "<span class='small2'>".$locale['c110'].number_format(dbcount("(comment_id)", DB_COMMENTS, "comment_name='".(int)$data['user_id']."'"))."<br />";
			    echo $locale['c111'].getuserlevel($data['user_level'])."</span><br />";
			    echo "</td>\n";
			    echo "<td class='tbl2' valign='top'>\n".nl2br(parseubb(parsesmileys($data['comment_message'])))."</td>\n</tr>";
				$i++;
			}
			echo "\n</table>\n";
			if ((iMODERATOR || iADMIN) && checkrights("C")) {
			    echo "<div align='right' class='tbl2'><a href='".ADMIN."comments.php".$aidlink."&amp;ctype=$ctype&amp;cid=$cid'>".$locale['c106']."</a></div>\n";
			}
			if ($c_rows > 10) {
				echo "<br /><div style='text-align:center;margin-top:5px;'>".makecommentnav($_GET['c_start'], 10, $c_rows, 3, $clink."&amp;", $seo_root_link,$a,$seo_catid,$b, $rowstart, "-cstart-", $c, $seo_subject)."</div>\n";
			}
		} else {
			echo $locale['c101']."\n";
		}
		closetable();

		opentable($locale['c102']);
		if (iMEMBER && (isset($_GET['c_action']) && $_GET['c_action'] == "edit") && (isset($_GET['comment_id']) && isnum($_GET['comment_id']))) {
			$eresult = dbquery(
				"SELECT tcm.comment_id, tcm.comment_name, tcm.comment_message, tcu.user_name FROM ".DB_COMMENTS." tcm
				LEFT JOIN ".DB_USERS." tcu ON tcm.comment_name=tcu.user_id
				WHERE comment_id='".(int)$_GET['comment_id']."' AND comment_item_id="._db($cid)." AND comment_type="._db($ctype)." AND comment_hidden='0'"
			);
			if (dbrows($eresult)) {
				$edata = dbarray($eresult);
				if (((iMODERATOR || iADMIN) && checkrights("C")) || (iMEMBER && $edata['comment_name'] == $userdata['user_id'] && isset($edata['user_name']))) {
					$clink .= "&amp;c_action=edit&amp;comment_id=".$edata['comment_id'];
					$comment_message = $edata['comment_message'];
				}
			} else {
				$comment_message = "";
			}
		} else {
			$comment_message = "";
		}
		if (iMEMBER || $settings['guestposts'] == "1") {
			require_once INCLUDES."bbcode_include.php";
			echo "<a id='edit_comment' name='edit_comment'></a>\n";
			echo "<form name='inputform' method='post' action='".((URL_REWRITE && $seo_root_link != "") ? $seo_link : $clink)."'>\n"; // Pimped
			if (iGUEST) {
				echo "<div align='center' class='tbl'>\n".$locale['c104']."<br />\n";
				echo "<input type='text' name='comment_name' maxlength='30' class='textbox' style='width:360px' />\n";
				echo "</div>\n";
			}
			echo "<div align='center' class='tbl'>\n";
			echo "<textarea name='comment_message' cols='70' rows='6' class='textbox' style='width:360px'>".$comment_message."</textarea><br />\n";
			echo display_bbcodes("360px", "comment_message");
			if (iGUEST) {
				echo $locale['global_158']."<br />\n";
				echo "<img id='com_captcha' src='".INCLUDES."securimage/securimage_show.php' alt='' /><br />\n";
				echo "<a href='".INCLUDES."securimage/securimage_play.php'><img src='".INCLUDES."securimage/images/audio_icon.gif' alt='' class='tbl-border' style='margin-bottom:1px' /></a>\n";
				echo "<a href='#' onclick=\"document.getElementById('com_captcha').src = '".INCLUDES."securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."securimage/images/refresh.gif' alt='' class='tbl-border' /></a><br />\n";
				echo $locale['global_159']."<br />\n<input type='text' name='com_captcha_code' class='textbox' style='width:100px' />\n";
			}
			echo "<br />\n<input type='submit' name='post_comment' value='".($comment_message ? $locale['c103'] : $locale['c102'])."' class='button' />\n";
			echo "</div>\n</form>\n";
		} else {
			echo $locale['c105']."\n";
		}
		closetable();
	}
}

function makecommentnav($start, $count, $total, $range = 0, $link, 
$seo_root_link = "", $a = "-", $seo_catid = "", $b = "-page-", $rowstart = "", $d = "-cstart-", $c = "-", $seo_subject = "") {

	global $locale;
	$seo_subject = clean_subject_urlrewrite($seo_subject);
	
	if(URL_REWRITE && $seo_root_link != "") { $link = $seo_root_link.$a.$seo_catid.$b.$rowstart; }
	$pg_cnt = ceil($total / $count);
	if ($pg_cnt <= 1) { return ""; }

	$idx_back = $start - $count;
	$idx_next = $start + $count;
	$cur_page = ceil(($start + 1) / $count);

	$res = $locale['global_092']." ".$cur_page.$locale['global_093'].$pg_cnt.": ";
	if ($idx_back >= 0) {
		if ($cur_page > ($range + 1)) {
			if(URL_REWRITE && $seo_root_link != "") {
				$res .= "<a href='".$link.$c.$seo_subject.".html'>1</a>...";
			} else {
				$res .= "<a href='".$link."c_start=0'>1</a>";
			}
			if ($cur_page != ($range + 2)) {
				$res .= "...";
			}
		}
	}
	$idx_fst = max($cur_page - $range, 1);
	$idx_lst = min($cur_page + $range, $pg_cnt);
	if ($range == 0) {
		$idx_fst = 1;
		$idx_lst = $pg_cnt;
	}
	for ($i = $idx_fst; $i <= $idx_lst; $i++) {
		$offset_page = ($i - 1) * $count;
		if ($i == $cur_page) {
			$res .= "<span><strong>".$i."</strong></span>";
		} else {
			if(URL_REWRITE && $seo_root_link != "") {
				$res .= "<a href='".$link.$d.$offset_page.$c.$seo_subject.".html'>".$i."</a>";
			} else {
				$res .= "<a href='".$link."c_start=".$offset_page."'>".$i."</a>";
			}
		}
	}
	if ($idx_next < $total) {
		if ($cur_page < ($pg_cnt - $range)) {
			if ($cur_page != ($pg_cnt - $range - 1)) {
				$res .= "...";
			}
			if(URL_REWRITE && $seo_root_link != ""){
				$res .= "...<a href='".$link.$d.($pg_cnt - 1) * $count.$c.$seo_subject.".html'>".$pg_cnt."</a>\n";
			} else {
				$res .= "<a href='".$link."c_start=".($pg_cnt - 1) * $count."'>".$pg_cnt."</a>\n";
			}
		}
	}

	return "<div class='pagenav'>\n".$res."</div>\n";
}

?>