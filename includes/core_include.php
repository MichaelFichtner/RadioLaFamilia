<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: core_include.php
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

// Set the admin password when needed
function set_admin_pass($password) {
	global $settings, $userdata;
	if ($settings['login_method'] == "cookies") {
		if (!isset($_COOKIE[COOKIE_PREFIX.'admin']) && encrypt_pw($password) == $userdata['user_admin_password']) {
			setcookie(COOKIE_PREFIX."admin", encrypt_pw_part1($password), time() + 3600, "/", "", "0");
		}
	} elseif ($settings['login_method'] == "sessions") {
		if (!isset($_SESSION[COOKIE_PREFIX.'admin']) && encrypt_pw($password) == $userdata['user_admin_password']) {
			$_SESSION[COOKIE_PREFIX.'admin'] = encrypt_pw_part1($password);
		}
	}
}

// Check if admin password matches userdata
function check_admin_pass($password) {
	global $settings, $userdata;
	if ($settings['login_method'] == "cookies") {
		if ((isset($_COOKIE[COOKIE_PREFIX.'admin']) && encrypt_pw_part2($_COOKIE[COOKIE_PREFIX.'admin']) == $userdata['user_admin_password']) || (encrypt_pw($password) == $userdata['user_admin_password'])) {
			return true;
		} else {
			return false;
		}
	} elseif ($settings['login_method'] == "sessions") {
		if ((isset($_SESSION[COOKIE_PREFIX.'admin']) && encrypt_pw_part2($_SESSION[COOKIE_PREFIX.'admin']) == $userdata['user_admin_password']) || (encrypt_pw($password) == $userdata['user_admin_password'])) {
			return true;
		} else {
			return false;
		}
	}
}

// Create a selection list from an array created by makefilelist()
function makefileopts($files, $selected = "") {
	$res = "";
	for ($i = 0; $i < count($files); $i++) {
		$sel = ($selected == $files[$i] ? " selected='selected'" : "");
		$res .= "<option value='".$files[$i]."'$sel>".$files[$i]."</option>\n";
	}
	return $res;
}

// Making Page Navigation
function pagination($new_pagination, $start, $count, $total, $range=0, $link = "", 
$seo_root_link = "", $a = "-", $seo_catid = "", $b = "-page-", $c = "-", $seo_subject = "", $seo_end = '.html') { // Pimped

	global $locale;
	
	$pagination = ($new_pagination == true) ? "page=" : "rowstart=";

	$seo_subject = clean_subject_urlrewrite($seo_subject);
	if ($link == "") { $link = FUSION_SELF."?"; }
	if(URL_REWRITE && $seo_root_link != "") { $link = $seo_root_link.$a.$seo_catid; }

	$pg_cnt = ceil($total / $count);
	if ($pg_cnt <= 1) { return ""; }

	$idx_back = $start - $count;
	$idx_next = $start + $count;
	$cur_page = ceil(($start + 1) / $count);

	$res = $locale['global_092']." ".$cur_page.$locale['global_093'].$pg_cnt.": ";
	if ($idx_back >= 0) {
		if ($cur_page > ($range + 1)) {
			$res .= (URL_REWRITE && $seo_root_link != "") ? "<a href='".$link.$c.$seo_subject.$seo_end."'>1</a>..." : "<a href='".$link."'>1</a>..."; // Pimped
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
			$oi = ($new_pagination == true) ? $i : $offset_page; // Pimped
			$res .= (URL_REWRITE && $seo_root_link != "") ? "<a href='".$link.$b.$oi.$c.$seo_subject.$seo_end."'>".$i."</a>" : "<a href='".$link.$pagination.$oi."'>".$i."</a>";  // Pimped
		}
	}
	if ($idx_next < $total) {
		if ($cur_page < ($pg_cnt - $range)) {
			$oi = ($new_pagination == true) ? $pg_cnt : (($pg_cnt - 1) * $count); // Pimped
			$res .= (URL_REWRITE && $seo_root_link != "") ? "...<a href='".$link.$b.$oi.$c.$seo_subject.$seo_end."'>".$pg_cnt."</a>\n" : "...<a href='".$link.$pagination.$oi."'>".$pg_cnt."</a>\n"; // Pimped
		}
	}
	
	return "<div class='pagenav'>\n".$res."</div>\n";
}

function makepagenav($start,$count,$total,$range=0,$link = "") {
return pagination(false,$start,$count,$total,$range,$link);
}

// Format the date & time accordingly
function showdate($format, $val) {
	global $settings, $locale;
	if ($format == "shortdate" || $format == "longdate" || $format == "forumdate" || $format == "newsdate") {
		$return = strftime($settings[$format], $val + ($settings['timeoffset'] * 3600));
	} else {
		$return = strftime($format, $val + ($settings['timeoffset'] * 3600));
	}
	return $return;
	//return ($locale['charset'] == "UTF-8") ? htmlentities($return) : $return; // dirty fix for the German month "März", tell us if you have a better solution!
	// Edit 05-05-2010 the "dirty" fix does not work for my server. it only works for my localhost... wtf
}

// Translate bytes into kB, MB, GB or TB
function parsebytesize($size, $digits = 2, $dir = false) {
	$kb = 1024; $mb = 1024 * $kb; $gb= 1024 * $mb; $tb = 1024 * $gb;
	if (($size == 0) && ($dir)) { return "Empty"; }
	elseif ($size < $kb) { return $size." Bytes"; }
	elseif ($size < $mb) { return round($size / $kb,$digits)." kB"; }
	elseif ($size < $gb) { return round($size / $mb,$digits)." MB"; }
	elseif ($size < $tb) { return round($size / $gb,$digits)." GB"; }
	else { return round($size / $tb, $digits)." TB"; }
}

// Cache smileys mysql
function cache_smileys() {
	global $smiley_cache;
	$result = dbquery("SELECT smiley_code, smiley_image, smiley_text FROM ".DB_SMILEYS);
	if (dbrows($result)) {
		$smiley_cache = array();
		while ($data = dbarray($result)) {
			$smiley_cache[] = array(
				"smiley_code" => $data['smiley_code'],
				"smiley_image" => $data['smiley_image'],
				"smiley_text" => $data['smiley_text']
			);
		}
	} else {
		$smiley_cache = array();
	}
}

// Parse smiley bbcode
function parsesmileys($message) {
	global $smiley_cache;
	if (!preg_match("#(\[code\](.*?)\[/code\]|\[geshi=(.*?)\](.*?)\[/geshi\]|\[php\](.*?)\[/php\])#si", $message)) {
		if (!$smiley_cache) { cache_smileys(); }
		if (is_array($smiley_cache) && count($smiley_cache)) {
			foreach ($smiley_cache as $smiley) {
				$smiley_code = preg_quote($smiley['smiley_code'], '#');
				$smiley_image = "<img src='".get_image("smiley_".$smiley['smiley_text'])."' alt='".$smiley['smiley_text']."' style='vertical-align:middle;' />";
				$message = preg_replace("#{$smiley_code}#si", $smiley_image, $message);
			}
		}
	}
	return $message;
}

// Show smiley icons in comments, forum and other post pages
function displaysmileys($textarea, $form = "inputform") {
	global $smiley_cache;
	$smileys = ""; $i = 0;
	if (!$smiley_cache) { cache_smileys(); }
	if (is_array($smiley_cache) && count($smiley_cache)) {
		foreach ($smiley_cache as $smiley) {
			if ($i != 0 && ($i % 10 == 0)) { $smileys .= "<br />\n"; $i++; }
			$smileys .= "<img src='".get_image("smiley_".$smiley['smiley_text'])."' alt='".$smiley['smiley_text']."' onclick=\"insertText('".$textarea."', '".$smiley['smiley_code']."', '".$form."');\" />\n";
		}
	}
	return $smileys;
}

// Cache bbcode mysql
function cache_bbcode() {
	global $bbcode_cache;
	$result = dbquery("SELECT bbcode_name FROM ".DB_BBCODES." ORDER BY bbcode_order ASC");
	if (dbrows($result)) {
		$bbcode_cache = array();
		while ($data = dbarray($result)) {
			$bbcode_cache[] = $data['bbcode_name'];
		}
	} else {
		$bbcode_cache = array();
	}
}

// Parse bbcode
function parseubb($text, $selected = false, $disable_lightbox = false) {
	global $bbcode_cache, $settings; // Pimped: $settings added
	if (!$bbcode_cache) { cache_bbcode(); }
	if (is_array($bbcode_cache) && count($bbcode_cache)) {
		if ($selected) { $sel_bbcodes = explode("|", $selected); }
		foreach ($bbcode_cache as $bbcode) {
			if ($selected && in_array($bbcode, $sel_bbcodes)) {
				if (file_exists(INCLUDES."bbcodes/".$bbcode."_bbcode_include.php")) {
					if (file_exists(LOCALE.LOCALESET."bbcodes/".$bbcode.".php")) {
						include (LOCALE.LOCALESET."bbcodes/".$bbcode.".php");
					} elseif (file_exists(LOCALE."English/bbcodes/".$bbcode.".php")) {
						include (LOCALE."English/bbcodes/".$bbcode.".php");
					}
					include (INCLUDES."bbcodes/".$bbcode."_bbcode_include.php");
				}
			} elseif (!$selected) {
				if (file_exists(INCLUDES."bbcodes/".$bbcode."_bbcode_include.php")) {
					if (file_exists(LOCALE.LOCALESET."bbcodes/".$bbcode.".php")) {
						include (LOCALE.LOCALESET."bbcodes/".$bbcode.".php");
					} elseif (file_exists(LOCALE."English/bbcodes/".$bbcode.".php")) {
						include (LOCALE."English/bbcodes/".$bbcode.".php");
					}
					include (INCLUDES."bbcodes/".$bbcode."_bbcode_include.php");
				}
			}
		}
	}	
	$text = descript($text, false);
	return $text;
}

// Highlights given words in subject
function highlight_words($word, $subject) {
	for($i = 0, $l = count($word); $i < $l; $i++) {
		$word[$i] = str_replace(array("\\", "+", "*", "?", "[", "^", "]", "$", "(", ")", "{", "}", "=", "!", "<", ">", "|", ":", "#", "-", "_"), "", $word[$i]);
		if (!empty($word[$i])) {
			$subject = preg_replace("/($word[$i])(?![^<]*>)/i", "<span style='background-color:yellow;color:#333;font-weight:bold;padding-left:2px;padding-right:2px'>\${1}</span>", $subject);
		}
	}
	return $subject;
}

// Replace offensive words with the defined replacement word
function censorwords($text) {
	global $settings;
	if ($settings['bad_words_enabled'] == "1" && $settings['bad_words'] != "" ) {
		$word_list = explode("\r\n", $settings['bad_words']);
		for ($i=0; $i < count($word_list); $i++) {
			if ($word_list[$i] != "") {
				$word_list[$i] = preg_quote($word_list[$i], "#"); // Pimped: fix
				$text = preg_replace("#".$word_list[$i]."#si", $settings['bad_word_replace'], $text);
			}
		}
	}
	return $text;
}


// Javascript email encoder by Tyler Akins
// http://rumkin.com/tools/mailto_encoder/
function hide_email($email, $title = "", $subject = "") {
	if (strpos($email, "@")) {
		$parts = explode("@", $email);
		$MailLink = "<a href='mailto:".$parts[0]."@".$parts[1];
		if ($subject != "") { $MailLink .= "?subject=".urlencode($subject); }
		$MailLink .= "'>".($title?$title:$parts[0]."@".$parts[1])."</a>";
		$MailLetters = "";
		for ($i = 0; $i < strlen($MailLink); $i++) {
			$l = substr($MailLink, $i, 1);
			if (strpos($MailLetters, $l) === false) {
				$p = rand(0, strlen($MailLetters));
				$MailLetters = substr($MailLetters, 0, $p).$l.substr($MailLetters, $p, strlen($MailLetters));
			}
		}
		$MailLettersEnc = str_replace("\\", "\\\\", $MailLetters);
		$MailLettersEnc = str_replace("\"", "\\\"", $MailLettersEnc);
		$MailIndexes = "";
		for ($i = 0; $i < strlen($MailLink); $i ++) {
			$index = strpos($MailLetters, substr($MailLink, $i, 1));
			$index += 48;
			$MailIndexes .= chr($index);
		}
		$MailIndexes = str_replace("\\", "\\\\", $MailIndexes);
		$MailIndexes = str_replace("\"", "\\\"", $MailIndexes);
		
		$res = "<script type='text/javascript'>";
		$res .= "ML=\"".str_replace("<", "xxxx", $MailLettersEnc)."\";";
		$res .= "MI=\"".str_replace("<", "xxxx", $MailIndexes)."\";";
		$res .= "ML=ML.replace(/xxxx/g, '<');";
		$res .= "MI=MI.replace(/xxxx/g, '<');";	$res .= "OT=\"\";";
		$res .= "for(j=0;j < MI.length;j++){";
		$res .= "OT+=ML.charAt(MI.charCodeAt(j)-48);";
		$res .= "}document.write(OT);";
		$res .= "</script>";
	
		return $res;
	} else {
		return $email;
	}
}

?>