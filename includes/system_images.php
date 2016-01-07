<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: system_images.php
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

$_loaded_ = array();

// Smileys
cache_smileys();
$smiley_images = array();
if(is_array($smiley_cache) && count($smiley_cache) >  0) {
	foreach ($smiley_cache as $smiley) {
		$smiley_images["smiley_".$smiley['smiley_text']] = IMAGES."smiley/".$smiley['smiley_image'];
	}
}

$fusion_images = array(
	//A
	"active" => IMAGES_ICONS."active.png", // Pimped
	//B
	"blank" => THEME."images/blank.gif",
	//C
	"cancel" => IMAGES_ICONS."cancel.gif", // Pimped; Admin Notes
	//D
	"down" => THEME."images/down.gif",
	//E
	"edit" => BASEDIR."images/edit.gif",
	//F
	"folder" => THEME."forum/folder.gif",
	"folderlock" => THEME."forum/folderlock.gif",
	"foldernew" => THEME."forum/foldernew.gif",
	"forum_edit" => THEME."forum/edit.gif",
	//G
	//H
	//I
	"imagenotfound" => IMAGES."imagenotfound.jpg",
	"inactive" => IMAGES_ICONS."inactive.png", // Pimped
	"info" => IMAGES_ICONS."information.png", // Pimped
	//J
	//K
	//L
	"left" => THEME."images/left.gif",
	//M
	//N
	"newthread" => THEME."forum/newthread.gif",
	//O
	"online" => IMAGES_ICONS."online.gif", // Pimped
	"offline" => IMAGES_ICONS."offline.gif", // Pimped
	//P
	"panel_on" => THEME."images/panel_on.gif",
	"panel_off" => THEME."images/panel_off.gif",
	"pm" => THEME."forum/pm.gif",
	"pollbar" => THEME."images/pollbar.gif",
	"printer" => THEME."images/printer.gif",
	//Q
	"quote" => THEME."forum/quote.gif",
	//R
	"reply" => THEME."forum/reply.gif",
	"report" => IMAGES."forum/report.png",
	"right" => THEME."images/right.gif",
	//S
	"star" => IMAGES."star.gif",
	"stickythread" => THEME."forum/stickythread.gif",
	//T
	//U
	"up" => THEME."images/up.gif",
	//V
	"version" => IMAGES_ICONS."version.gif", // Pimped
	"version_old" => IMAGES_ICONS."version_old.gif", // Pimped
	//W
	"web" => THEME."forum/web.gif"
	//X
	//Y
	//Z
);

 // Pimped: Country Flag
$country_flag_images = array(
	"English" => IMAGES_FLAGS."gb.png",
	"German" => IMAGES_FLAGS."de.png",
	"Lithuanian" => IMAGES_FLAGS."lt.png"
);

// Pimped: Admin and Smiley Images removed; Country Flags added
$fusion_images = array_merge($fusion_images, $smiley_images, $country_flag_images);

// Pimped: News Cat Images
$_loaded_['newscatimages'] = false;

function add_newscatimages() {
global $fusion_images, $_loaded_;

	if($_loaded_['newscatimages'] !== true) {
		$result = dbquery("SELECT news_cat_image, news_cat_name FROM ".DB_NEWS_CATS);
		$nc_images = array();

		while ($data = dbarray($result)) {
			$nc_images["nc_".$data['news_cat_name']] = file_exists(IMAGES_NC.$data['news_cat_image']) ? IMAGES_NC.$data['news_cat_image'] : IMAGES."imagenotfound.jpg";
		}

		$fusion_images = array_merge($fusion_images, $nc_images);
		$_loaded_['newscatimages'] = true;
	}
}

// Pimped: Admin Images
$_loaded_['adminimages'] = false;

function add_adminimages() {
global $fusion_images, $_loaded_;

	if($_loaded_['adminimages'] !== true) {
		$result = dbquery("SELECT admin_title, admin_image, admin_page FROM ".DB_ADMIN);
		$ac_images = array();

		while($data = dbarray($result)){
			$ac_images["ac_".$data['admin_page'].$data['admin_title']] = ($data['admin_image'] != "" && file_exists(ADMIN."images/".$data['admin_image']))
			? ADMIN."images/".$data['admin_image'] : ADMIN."images/infusion_panel.gif";
		}

		$fusion_images = array_merge($fusion_images, $ac_images);
		$_loaded_['adminimages'] = true;
	}
}

function get_image($image, $alt = "", $style = "", $title = "", $atts = "", $unknown_country_flag = false) {
	global $fusion_images;
	if (isset($fusion_images[$image])) {
		$url = $fusion_images[$image];
	} else {
		$url = ($unknown_country_flag == false) ? BASEDIR."images/imagenotfound.jpg" : IMAGES_FLAGS."zz.png";
	}
	if (!$alt && !$style && !$title) {
		return $url;
	} else {
		return "<img src='".$url."' alt='".$alt."'".($style ? " style='$style'" : "").($title ? " title='".$title."'" : "")." ".$atts." />";
	}
}

function set_image($name, $new_dir){
	global $fusion_images;
	$fusion_images[$name] = $new_dir;
}

function redirect_img_dir($source, $target){
	global $fusion_images;
	$new_images = array();
	foreach ($fusion_images as $name => $url) {
		$new_images[$name] = str_replace($source, $target, $url);
	}
	$fusion_images = $new_images;
}
?>