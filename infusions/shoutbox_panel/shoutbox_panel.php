<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: shoutbox_panel.php
| Version: Pimped Fusion v0.08.01
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

#$link = FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "");
$link  = FUSION_REQUEST; // testing
$link = preg_replace("^(&amp;|\?)s_action=(edit|delete)&amp;shout_id=\d*^", "", $link);
$sep = stristr($link, "?") ? "&amp;" : "?";

if (iMEMBER && (isset($_GET['s_action']) && $_GET['s_action'] == "delete") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
	if (((iMODERATOR || iADMIN) && checkrights("S")) || (iMEMBER && dbcount("(shout_id)", DB_SHOUTBOX, "shout_id='".(int)$_GET['shout_id']."' AND shout_name='".$userdata['user_id']."'"))) {
		$result = dbquery("DELETE FROM ".DB_SHOUTBOX." WHERE shout_id='".(int)$_GET['shout_id']."'".((iMODERATOR || iADMIN) ? "" : " AND shout_name='".$userdata['user_id']."'"));
	}
	redirect($link);
}

function sbwrap($text) {
	global $locale;
	$i = 0; $tags = 0; $chars = 0; $res = "";
	
	$str_len = strlen($text);
	
	for ($i = 0; $i < $str_len; $i++) {
		$chr = mb_substr($text, $i, 1, $locale['charset']);
		if ($chr == "<") {
			if (mb_substr($text, ($i + 1), 6, $locale['charset']) == "a href" || mb_substr($text, ($i + 1), 3, $locale['charset']) == "img") {
				$chr = " ".$chr;
				$chars = 0;
			}
			$tags++;
		} elseif ($chr == "&") {
			if (mb_substr($text, ($i + 1), 5, $locale['charset']) == "quot;") {
				$chars = $chars - 5;
			} elseif (mb_substr($text, ($i + 1), 4, $locale['charset']) == "amp;" || mb_substr($text, ($i + 1), 4, $locale['charset']) == "#39;" || mb_substr($text, ($i + 1), 4, $locale['charset']) == "#92;") {
				$chars = $chars - 4;
			} elseif (mb_substr($text, ($i + 1), 3, $locale['charset']) == "lt;" || mb_substr($text, ($i + 1), 3, $locale['charset']) == "gt;") {
				$chars = $chars - 3;
			}
		} elseif ($chr == ">") {
			$tags--;
		} elseif ($chr == " ") {
			$chars = 0;
		} elseif (!$tags) {
			$chars++;
		}
		
		if (!$tags && $chars == 18) {
			$chr .= "<br />";
			$chars = 0;
		}
		$res .= $chr;
	}
	
	return $res;
}

if($settings['warning_system_shoutbox']) {
	require_once INCLUDES."warning.inc.php";
}

openside($locale['global_150'], true);
if (iMEMBER || $settings['guestposts'] == "1") {
	include_once INCLUDES."bbcode_include.php";
	if (isset($_POST['post_shout'])) {
		$flood = false;
		if (iMEMBER) {
			$shout_name = $userdata['user_id'];
		} elseif ($settings['guestposts'] == "1") {
			$shout_name = trim(stripinput($_POST['shout_name']));
			$shout_name = preg_replace("(^[0-9]*)", "", $shout_name);
			if (isnum($shout_name)) { $shout_name = ""; }
			include_once INCLUDES."securimage/securimage.php";
			$securimage = new Securimage();
			if (!isset($_POST['sb_captcha_code']) || $securimage->check($_POST['sb_captcha_code']) == false) { redirect($link); }
		}
		$shout_message = str_replace("\n", " ", $_POST['shout_message']);
		$shout_message = preg_replace("/^(.{255}).*$/", "$1", $shout_message);
		$shout_message = trim(stripinput(censorwords($shout_message)));
		if (iMEMBER && (isset($_GET['s_action']) && $_GET['s_action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
			$comment_updated = false;
			if (((iMODERATOR || iADMIN) && checkrights("S")) || (iMEMBER && dbcount("(shout_id)", DB_SHOUTBOX, "shout_id='".(int)$_GET['shout_id']."' AND shout_name='".$userdata['user_id']."'"))) {
				if ($shout_message) {
					$result = dbquery("UPDATE ".DB_SHOUTBOX." SET shout_message='$shout_message' WHERE shout_id='".(int)$_GET['shout_id']."'".((iMODERATOR || iADMIN) ? "" : " AND shout_name='".$userdata['user_id']."'"));
				}
			}
			redirect($link);
		} elseif ($shout_name && $shout_message) {
			require_once INCLUDES."flood_include.php";
			if (!flood_control("shout_datestamp", DB_SHOUTBOX, "shout_ip='".USER_IP."'")) {
				$result = dbquery("INSERT INTO ".DB_SHOUTBOX." (shout_name, shout_message, shout_datestamp, shout_ip, shout_hidden, shout_language) VALUES ('$shout_name', '$shout_message', '".time()."', '".USER_IP."', '0', '".$settings['locale']."')");
			}
		}
		redirect($link);
	}
	if (iMEMBER && (isset($_GET['s_action']) && $_GET['s_action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
		$esresult = dbquery(
			"SELECT ts.shout_id, ts.shout_name, ts.shout_message, tu.user_id, tu.user_name
			FROM ".DB_SHOUTBOX." ts
			LEFT JOIN ".DB_USERS." tu ON ts.shout_name=tu.user_id
			WHERE ts.shout_id='".(int)$_GET['shout_id']."'"
		);
		if (dbrows($esresult)) {
			$esdata = dbarray($esresult);
			if (((iMODERATOR || iADMIN) && checkrights("S")) || (iMEMBER && $esdata['shout_name'] == $userdata['user_id'] && isset($esdata['user_name']))) {
				if ((isset($_GET['s_action']) && $_GET['s_action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
					$edit_url = $sep."s_action=edit&amp;shout_id=".$esdata['shout_id'];
				} else {
					$edit_url = "";
				}
				$shout_link = $link.$edit_url;
				$shout_message = $esdata['shout_message'];
			}
		} else {
			$shout_link = $link;
			$shout_message = "";
		}
	} else {
		$shout_link = $link;
		$shout_message = "";
	}
	
	echo "<a id='edit_shout' name='edit_shout'></a>\n";
	echo "<form name='shout_form' method='post' action='".$shout_link."'>\n";
	if (iGUEST) {
		echo $locale['global_151']."<br />\n";
		echo "<input type='text' name='shout_name' value='' class='textbox' maxlength='30' style='width:140px' /><br />\n";
		echo $locale['global_152']."<br />\n";
	}
	echo "<textarea name='shout_message' rows='4' cols='20' class='textbox' style='width:140px'>".$shout_message."</textarea><br />\n";
	echo display_bbcodes("150px;", "shout_message", "shout_form", "smiley|b|u|url|color")."\n";
	if (iGUEST) {
		echo $locale['global_158']."<br />\n";
		echo "<img id='sb_captcha' src='".INCLUDES."securimage/securimage_show.php' alt='' /><br />\n";
    echo "<a href='".INCLUDES."securimage/securimage_play.php'><img src='".INCLUDES."securimage/images/audio_icon.gif' alt='' class='tbl-border' style='margin-bottom:1px' /></a>\n";
    echo "<a href='#' onclick=\"document.getElementById('sb_captcha').src = '".INCLUDES."securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."securimage/images/refresh.gif' alt='' class='tbl-border' /></a><br />\n";
		echo $locale['global_159']."<br />\n<input type='text' name='sb_captcha_code' class='textbox' style='width:100px' /><br />\n";
	}
	echo "<br /><input type='submit' name='post_shout' value='".$locale['global_153']."' class='button' />\n";
	echo "</form>\n<br />\n";
} else {
	echo "<div style='text-align:center'>".$locale['global_154']."</div><br />\n";
}
$numrows = dbcount("(shout_id)", DB_SHOUTBOX, "shout_hidden='0'");
$result = dbquery(
	"SELECT ts.shout_id, ts.shout_name, ts.shout_message, ts.shout_datestamp, tu.user_id, tu.user_name, tu.user_status, tu.user_avatar
	FROM ".DB_SHOUTBOX." ts
	LEFT JOIN ".DB_USERS." tu ON ts.shout_name=tu.user_id
	WHERE ts.shout_hidden='0'
	".(!(bool)IF_MULTI_LANGUAGE || !$settings['locale_multi_shout'] ? '':" AND (ts.shout_language='all' OR ts.shout_language='".LANGUAGE."')")."
	ORDER BY ts.shout_datestamp DESC LIMIT 0,".$settings['numofshouts']
);
if (dbrows($result)) {
	$i = 0;
	while ($data = dbarray($result)) {
		
		echo "<div class='shoutboxavatar' style='float:left;margin-right:7px;margin-bottom:5px;'>";
			if ($data['user_name'] && $settings['shoutbox_showavatar'] && $data['user_avatar'] && file_exists(IMAGES_AVA.$data['user_avatar'])) {
				$avatar = "<img src='".IMAGES_AVA.$data['user_avatar']."' width='30px' height='30px' border='0' title='User Avatar' alt='User Avatar' /> "; 
			} elseif($data['user_name'] && $settings['shoutbox_showavatar']) {
				$avatar = "<img src='".IMAGES_AVA."noavatar_2.jpg' width='30px' height='30px' border='0' title='No User Avatar' alt='No User Avatar' /> ";
			} else {
				$avatar = "";
			}
		echo $avatar."</div>\n";
		
		echo "<div class='shoutboxname' >";
		if ($data['user_name']) {
			echo "<span class='side'>".profile_link($data['shout_name'], $data['user_name'], $data['user_status'])."</span>\n";
		} else {
			echo $data['shout_name']."\n";
		}
		echo "</div>\n";
		echo "<div class='shoutboxdate'>".showdate("shortdate", $data['shout_datestamp'])."</div>";
		if($settings['warning_system_shoutbox']) {
			$points = show_warning_points($data['user_id']);
			echo "<div class='shoutboxwarnings'>";
			echo "<span class='small'><a style='cursor:help;' onclick=\"warning_info();\">".$locale['WARN200']."</a></span> ";
			echo warning_profile_link("1", $data['user_id'], $points);
			echo "</div>";
		}
		echo "<div class='shoutbox' style='clear:left;'>".sbwrap(parseubb(parsesmileys($data['shout_message']), "b|i|u|url|color"))."</div>\n";
		if (((iMODERATOR || iADMIN) && checkrights("S")) || (iMEMBER && $data['shout_name'] == $userdata['user_id'] && isset($data['user_name']))) {
			echo "[<a href='".$link.$sep."s_action=edit&amp;shout_id=".$data['shout_id']."#edit_shout"."' class='side'>".$locale['global_076']."</a>]\n";
			echo "[<a href='".$link.$sep."s_action=delete&amp;shout_id=".$data['shout_id']."' class='side'>".$locale['global_157']."</a>]<br />\n";
		}
		$i++;
		if ($i != $numrows) { echo "<br />\n"; }
	}
	if ($numrows > $settings['numofshouts']) {
		echo "<div style='text-align:center'>\n<a href='".INFUSIONS."shoutbox_panel/shoutbox_archive.php' class='side'>".$locale['global_155']."</a>\n</div>\n";
	}
} else {
	echo "<div>".$locale['global_156']."</div>\n";
}
closeside();
?>