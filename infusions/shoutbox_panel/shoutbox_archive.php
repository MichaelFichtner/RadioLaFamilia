<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: shoutbox_archive.php
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
require_once "../../maincore.php";
require_once THEMES."templates/header.php";

$result = dbquery("SELECT panel_access FROM ".DB_PANELS." WHERE panel_filename='shoutbox_panel' AND panel_status='1'");
if (dbrows($result)) {
	$data = dbarray($result);
	if (!checkgroup($data['panel_access'])) {
		redirect(BASEDIR."index.php");
	}
} else {
	redirect(BASEDIR."index.php");
}

if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
	if (((iMODERATOR || iADMIN) && checkrights("S")) || (iMEMBER && dbcount("(shout_id)", DB_SHOUTBOX, "shout_id='".(int)$_GET['shout_id']."' AND shout_name='".$userdata['user_id']."' AND shout_hidden='0'"))) {
		$result = dbquery("DELETE FROM ".DB_SHOUTBOX." WHERE shout_id='".(int)$_GET['shout_id']."'".((iMODERATOR || iADMIN) ? "" : " AND shout_name='".$userdata['user_id']."'"));
	}
	redirect(FUSION_SELF);
}

function sbawrap($text) {
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
		
		if (!$tags && $chars == 40) {
			$chr .= " ";
			$chars = 0;
		}
		$res .= $chr;
	}
	
	return $res;
}

add_to_title($locale['global_200'].$locale['global_155']);

opentable($locale['global_155']);
if (iMEMBER || $settings['guestposts'] == "1") {
	include_once INCLUDES."bbcode_include.php";
	if (isset($_POST['post_archive_shout'])) {
		$flood = false;
		if (iMEMBER) {
			$archive_shout_name = $userdata['user_id'];
		} elseif ($settings['guestposts'] == "1") {
			$archive_shout_name = trim(stripinput($_POST['archive_shout_name']));
			$archive_shout_name = preg_replace("(^[0-9]*)", "", $archive_shout_name);
			if (isnum($archive_shout_name)) { $archive_shout_name = ""; }
			include_once INCLUDES."securimage/securimage.php";
			$securimage = new Securimage();
			if (!isset($_POST['captcha_code']) || $securimage->check($_POST['captcha_code']) == false) { redirect($link); }
		}
		$archive_shout_message = str_replace("\n", " ", $_POST['archive_shout_message']);
		$archive_shout_message = preg_replace("/^(.{255}).*$/", "$1", $archive_shout_message);
		$archive_shout_message = trim(stripinput(censorwords($archive_shout_message)));
		if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
			$comment_updated = false;
			if (((iMODERATOR || iADMIN) && checkrights("S")) || (iMEMBER && dbcount("(shout_id)", DB_SHOUTBOX, "shout_id='".(int)$_GET['shout_id']."' AND shout_name='".$userdata['user_id']."' AND shout_hidden='0'"))) {
				if ($archive_shout_message) {
					$result = dbquery("UPDATE ".DB_SHOUTBOX." SET shout_message='$archive_shout_message'
					WHERE shout_id='".(int)$_GET['shout_id']."'".((iMODERATOR || iADMIN) ? "" : " AND shout_name='".$userdata['user_id']."'"));
				}
			}
			redirect(FUSION_SELF);
		} elseif ($archive_shout_name && $archive_shout_message) {
			require_once INCLUDES."flood_include.php";
			if (!flood_control("shout_datestamp", DB_SHOUTBOX, "shout_ip='".USER_IP."'")) {
				$result = dbquery("INSERT INTO ".DB_SHOUTBOX." (shout_name, shout_message, shout_datestamp, shout_ip, shout_hidden, shout_language) VALUES ('$archive_shout_name', '$archive_shout_message', '".time()."', '".USER_IP."', '0', '".$settings['locale']."')");
			}
			redirect(FUSION_SELF);
		}
	}
	if (iMEMBER && (isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
		$esresult = dbquery(
			"SELECT ts.shout_id, ts.shout_name, ts.shout_message, tu.user_id, tu.user_name
			FROM ".DB_SHOUTBOX." ts
			LEFT JOIN ".DB_USERS." tu ON ts.shout_name=tu.user_id
			WHERE ts.shout_id='".(int)$_GET['shout_id']."' AND shout_hidden='0'"
		);
		if (dbrows($esresult)) {
			$esdata = dbarray($esresult);
			if (((iMODERATOR || iADMIN) && checkrights("S")) || (iMEMBER && $esdata['shout_name'] == $userdata['user_id'] && isset($esdata['user_name']))) {
				if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
					$edit_url = "?action=edit&amp;shout_id=".$esdata['shout_id'];
				} else {
					$edit_url = "";
				}
				$archive_shout_link = FUSION_SELF.$edit_url;
				$archive_shout_message = $esdata['shout_message'];
			}
		} else {
			$archive_shout_link = FUSION_SELF;
			$archive_shout_message = "";
		}
	} else {
		$archive_shout_link = FUSION_SELF;
		$archive_shout_message = "";
	}
	echo "<form name='archive_form' method='post' action='".$archive_shout_link."'>\n";
	echo "<div style='text-align:center'>\n";
	if (iGUEST) {
		echo $locale['global_151']."<br />\n";
		echo "<input type='text' name='archive_shout_name' value='' class='textbox' maxlength='30' style='width:200px;' /><br />\n";
		echo $locale['global_152']."<br />\n";
	}
	echo "<textarea name='archive_shout_message' rows='4' cols='50' class='textbox'>".$archive_shout_message."</textarea><br />\n";
	echo "<div style='text-align:center'>".display_bbcodes("100%", "archive_shout_message", "archive_form", "smiley|b|i|u|url|color")."</div>\n";
	if (iGUEST) {
		echo $locale['global_158']."<br />\n";
		echo "<img id='captcha' src='".INCLUDES."securimage/securimage_show.php' alt='' /><br />\n";
    echo "<a href='".INCLUDES."securimage/securimage_play.php'><img src='".INCLUDES."securimage/images/audio_icon.gif' alt='' class='tbl-border' style='margin-bottom:1px' /></a>\n";
    echo "<a href='#' onclick=\"document.getElementById('captcha').src = '".INCLUDES."securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."securimage/images/refresh.gif' alt='' class='tbl-border' /></a><br />\n";
		echo $locale['global_159']."<br />\n<input type='text' name='captcha_code' class='textbox' style='width:100px' /><br />\n";
	}
	echo "<br /><input type='submit' name='post_archive_shout' value='".$locale['global_153']."' class='button' />\n";
	echo "</div>\n</form>\n<br />\n";
} else {
	echo "<div style='text-align:center'>".$locale['global_154']."</div>\n";
}
$rows = dbcount("(shout_id)", DB_SHOUTBOX, "shout_hidden='0'");
if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
$_GET['rowstart'] = $_GET['page'] > 0 ? ($_GET['page']-1) * "20" : "0";
if ($rows != 0) {
	$result = dbquery(
		"SELECT s.shout_id, s.shout_name, s.shout_message, s.shout_datestamp, u.user_name, u.user_status
		FROM ".DB_SHOUTBOX." s
		LEFT JOIN ".DB_USERS." u ON s.shout_name=u.user_id
		WHERE s.shout_hidden='0'
		".(!(bool)IF_MULTI_LANGUAGE || !$settings['locale_multi_shout'] ? '':" AND (s.shout_language='all' OR s.shout_language='".LANGUAGE."')")."
		ORDER BY s.shout_datestamp DESC LIMIT ".(int)$_GET['rowstart'].",20");
	while ($data = dbarray($result)) {
		echo "<div class='tbl2'>\n";
		if (((iMODERATOR || iADMIN) && checkrights("S")) || (iMEMBER && $data['shout_name'] == $userdata['user_id'] && isset($data['user_name']))) {
			echo "<div style='float:right'>\n<a href='".FUSION_SELF."?action=edit&amp;shout_id=".$data['shout_id']."'>".$locale['global_076']."</a> |\n";
			echo "<a href='".FUSION_SELF."?action=delete&amp;shout_id=".$data['shout_id']."'>".$locale['global_157']."</a>\n</div>\n";
		}
		if ($data['user_name']) {
			echo "<span class='comment-name'>".profile_link($data['shout_name'], $data['user_name'], $data['user_status'])."</span>\n";
		} else {
			echo "<span class='comment-name'>".$data['shout_name']."</span>\n";
		}
		echo "<span class='small'>".showdate("longdate", $data['shout_datestamp'])."</span>";
		echo "</div>\n<div class='tbl1'>\n".sbawrap(parseubb(parsesmileys($data['shout_message']), "b|i|u|url|color"))."</div>\n";
	}
} else {
	echo "<div style='text-align:center'><br />\n".$locale['global_156']."<br /><br />\n</div>\n";
}
closetable();

echo "<div align='center' style='margin-top:5px;'>\n".pagination(true,$_GET['rowstart'], 20, $rows, 3, FUSION_SELF."?")."\n</div>\n";

require_once THEMES."templates/footer.php";
?>