<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum_include.php
| Version: Pimped Fusion v0.08.00
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

$imagetypes = array(".bmp", ".gif", ".iff", ".jpg", ".jpeg", ".png", ".psd", ".tiff", ".wbmp");

function attach_replace_space($file) {
	$file = str_replace (" ", "_", $file);
	return $file;
}

function attach_replace_dot($file) {
	$file = str_replace (".", "_", $file);
	return $file;
}

function attach_name($file, $give_name = false, $give_ext = false) {
	$file = strtolower($file);
	$file_name = attach_replace_dot(substr($file, 0, strrpos($file, ".")));
	$file_ext = strrchr($file, ".");
	
	if($give_name) {
		return $file_name;
	} elseif($give_ext) {
		return $file_ext;
	} else {
		$i = 1;
		$file1 = $file_name.$file_ext;
		while (file_exists(FORUM_ATT.$file1)) {
			$file1 = $file_name."_".$i.$file_ext;
			$i++;
		}
		return $file1;
	}
}

function forum_rank_cache() { // Pimped for Group Ranks
	global $settings, $forum_mod_rank_cache, $forum_group_rank_cache, $forum_rank_cache;
	$forum_mod_rank_cache = array();
	$forum_group_rank_cache = array();
	$forum_rank_cache = array();
	if ($settings['forum_ranks']) {
		$result = dbquery("SELECT rank_title, rank_image, rank_posts, rank_apply, rank_group FROM ".DB_FORUM_RANKS." ORDER BY rank_apply DESC, rank_posts ASC");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				if ($data['rank_apply'] > nMEMBER) {
					$forum_mod_rank_cache[] = $data;
				} elseif ($data['rank_group'] > 0) {
					$forum_group_rank_cache[] = $data;
				} else {
					$forum_rank_cache[] = $data;
				}
			}
		}
	}
}

function show_forum_rank($posts, $level, $group = '') { // Pimped for Group Ranks
	global $locale, $forum_mod_rank_cache, $forum_group_rank_cache, $forum_rank_cache, $settings;
	$res = "";
	if ($settings['forum_ranks']) {
		if (!$forum_rank_cache) { forum_rank_cache(); }
		if ($level > nMEMBER) {
			if (is_array($forum_mod_rank_cache) && count($forum_mod_rank_cache)) {
				for ($i = 0; $i < count($forum_mod_rank_cache) && !$res; $i++) {
					if ($level == $forum_mod_rank_cache[$i]['rank_apply']) {
						$res = $forum_mod_rank_cache[$i]['rank_title']."<br />\n<img src='".RANKS.$forum_mod_rank_cache[$i]['rank_image']."' alt='' style='border:0' />";
					}
				}
			}
		}
		# Group Ranks: ->
		if (!$res) {
		if ($group != '') {
			if (is_array($forum_group_rank_cache) && count($forum_group_rank_cache)) {
				for ($i = 0; $i < count($forum_group_rank_cache) && !$res; $i++) {
					if (in_array($forum_group_rank_cache[$i]['rank_group'], explode(".", $group))) {
						$res = $forum_group_rank_cache[$i]['rank_title']."<br />\n<img src='".RANKS.$forum_group_rank_cache[$i]['rank_image']."' alt='' style='border:0' />";
					}
				}
			}
		}
		}
		# <-
		if (!$res) {
			if (is_array($forum_rank_cache) && count($forum_rank_cache)) {
				for ($i = 0; $i < count($forum_rank_cache); $i++) {
					if ($posts >= $forum_rank_cache[$i]['rank_posts']) {
						$res = $forum_rank_cache[$i]['rank_title']."<br />\n<img src='".RANKS.$forum_rank_cache[$i]['rank_image']."' alt='' style='border:0' />";
					}
				}
				if (!$res) {
					$res = $forum_rank_cache[0]['rank_title']."<br />\n<img src='".RANKS.$forum_rank_cache[0]['rank_image']."' alt='' style='border:0' />";
				}
			}
		}
	}
	return $res;
}

function display_image($file) { // Pimped
global $settings;
	$size = @getimagesize(FORUM_ATT.$file);
	
	if ($size[0] > 300 || $size[1] > 200) {
		if ($size[0] <= $size[1]) {
			$img_w = round(($size[0] * 200) / $size[1]);
			$img_h = 200;
		} elseif ($size[0] > $size[1]) {
			$img_w = 300;
			$img_h = round(($size[1] * 300) / $size[0]);
		} else {
			$img_w = 300;
			$img_h = 200;
		}
	} else {
		$img_w = $size[0];
		$img_h = $size[1];
	}
	
	if ($size[0] != $img_w || $size[1] != $img_h) {
		if($settings['enable_lightbox'] == "1") {
			$res = "<a class='fancybox' href='".FORUM_ATT.$file."'><img src='".FORUM_ATT.$file."' width='".$img_w."' height='".$img_h."' style='border:0' rel='group' alt='' /></a>";
		} else {
			$res = "<a href='".FORUM_ATT.$file."'><img src='".FORUM_ATT.$file."' width='".$img_w."' height='".$img_h."' style='border:0' alt='' /></a>";
		}
	} else {
		$res = "<img src='".FORUM_ATT.$file."' width='".$img_w."' height='".$img_h."' style='border:0' alt='' />";
	}
	
	return $res;
}

function report_spam($post) {
global $locale;

return "<a href=\"#\" onclick=\"window.open('".FORUM."includes/forum_report_spam.php?post_id=".$post."', 'NewWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=200,height=100')\">".get_image("report", $locale['580'], "border:0;vertical-align:middle", $locale['580'])."</a>";
}

?>