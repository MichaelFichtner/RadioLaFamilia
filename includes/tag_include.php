<?php/*---------------------------------------------------------------------------+| Pimped-Fusion Content Management System| Copyright (C) 2009 - 2010| http://www.pimped-fusion.net+----------------------------------------------------------------------------+| Filename: tag_include.php| Version: Pimped Fusion v0.09.00+----------------------------------------------------------------------------+| Authors: Keddy, slaughter+----------------------------------------------------------------------------+| This program is released as free software under the Affero GPL license.| You can redistribute it and/or modify it under the terms of this license| which you can read by viewing the included agpl.txt or online| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is| strictly prohibited without written permission from the original author(s).+---------------------------------------------------------------------------*/if (!defined("PIMPED_FUSION")) { die("Access Denied"); }include LOCALE.LOCALESET."tag.php";
function add_tags($type, $class = "tbl") {	global $locale, $settings;
	$res = '';	
	if ($settings['enable_tags']) {
		$res .= "<tr>\n<td valign='top' width='100' class='".$class."'>".$locale['tag_add']."</td>\n";		$res .= "<td class='tbl1'>";		$res .= "<input type='text' name='tag_name' value='' class='textbox' style='width:285px;' /><br />";		$res .= "<span class='small'>".$locale['tag_words']."</span></td>\n";		$res .= "</tr>\n";
	}
	return $res;
}
function edit_tags($item_id, $type, $class = "tbl") {
	global $locale, $settings;
	$res = '';	if ($settings['enable_tags']) {		
		$data = dbarray(dbquery("SELECT tag_name FROM ".DB_TAGS." WHERE tag_item_id='".(int)$item_id."' AND tag_type="._db($type).""));		
		$res .= "<tr>\n<td valign='top' width='100' class='".$class."'>".$locale['tag_add']."</td>\n";
		$res .= "<td class='tbl1'>";
		$res .= "<input type='text' name='tag_name' value='".$data['tag_name']."' class='textbox' style='width:285px;' /><br />";		$res .= "<span class='small'>".$locale['tag_words']."</span></td>\n";
	}
	return $res;
}
function insert_tags($item_id, $type, $name) {	global $settings;		if ($settings['enable_tags']) {		
		$result = dbquery("INSERT INTO ".DB_TAGS." (tag_item_id, tag_type, tag_name) VALUES ('".(int)$item_id."', "._db($type).", "._db($name).")");		}
}
function update_tags($item_id, $type, $name) {	global $settings;		if ($settings['enable_tags']) {				$result = dbquery("SELECT tag_name FROM ".DB_TAGS." WHERE tag_item_id='".(int)$item_id."' AND tag_type="._db($type)."");		if(dbrows($result)) {
			$result = dbquery("UPDATE ".DB_TAGS." SET tag_name="._db($name)." WHERE tag_item_id='".(int)$item_id."' AND tag_type="._db($type)."");		} else {			$result = dbquery("INSERT INTO ".DB_TAGS." (tag_item_id, tag_type, tag_name) VALUES ('".(int)$item_id."', "._db($type).", "._db($name).")");		}	}
}
function delete_tags($item_id, $type) {
	$result = dbquery("DELETE FROM ".DB_TAGS." WHERE tag_item_id='".(int)$item_id."' AND tag_type="._db($type)."");	return ($result ? true : false);
}
function show_tags($item_id, $type) {
	global $settings, $locale;		if ($settings['enable_tags']) {		
		$result = dbquery("SELECT tag_name FROM ".DB_TAGS." WHERE tag_item_id='".(int)$item_id."' AND tag_type="._db($type));		
		if (dbrows($result)) {
			$data = dbarray($result);			if($data['tag_name'] != "") {				switch($type) {					case "N":						$title = $locale['tag_news'];						break;					case "A":						$title = $locale['tag_articles'];						break;					case "C":						$title = $locale['tag_custom'];						break;					case "F":						$title = $locale['tag_thread'];						break;					default:						$title = $locale['tag_custom'];				}				opentable($title);								$tag_a = explode(",", $data['tag_name']);				$tag_r = "";				if(is_array($tag_a) && count($tag_a)) {					foreach($tag_a as $tag_e) {						$tag_e = trim($tag_e);						$tag_r .= ($tag_r ? ", " : "")."<a href='".BASEDIR."tag.php?tag=".$tag_e."'>".$tag_e."</a>";					}				} else {					$tag_a = trim($tag_a);					$tag_r = "<a href='".BASEDIR."tag.php?tag=".$tag_a."'>".$tag_a."</a>";				}									echo $tag_r;								closetable();			}
		}	}
}
?>