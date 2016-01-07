<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: navigation.php
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

include LOCALE.LOCALESET."admin/main.php";

if($settings['adminmenue_userinfo']) include INFUSIONS."user_info_panel/user_info_panel.php";

@list($title) = dbarraynum(dbquery("SELECT admin_title FROM ".DB_ADMIN." WHERE admin_link='".FUSION_SELF."'"));

add_to_title($locale['global_200'].$locale['global_123'].($title ? $locale['global_201'].$title : ""));

$pages = array(1 => false, 2 => false, 3 => false, 4 => false, 5 => false);
$index_link = false; $admin_nav_opts = ""; $current_page = 0;

if($settings['adminmenue_nav'] == "sdmenue") {
	include ADMIN."navigation_include_sdmenue.php";
} else {
	openside($locale['global_001']);
	$result = dbquery("SELECT admin_title, admin_page, admin_rights, admin_link FROM ".DB_ADMIN." ORDER BY admin_page DESC, admin_title ASC");
		$rows = dbrows($result);
		while ($data = dbarray($result)) {
			if ($data['admin_link'] != "reserved" && checkrights($data['admin_rights']) && array_key_exists("admin_".$data['admin_rights'], $locale) && ($_GET['pagenum'] == 1 || $_GET['pagenum'] == 2 || $_GET['pagenum'] == 3 || $_GET['pagenum'] == 4)) {
				$pages[$data['admin_page']] .= "<option value='".ADMIN.$data['admin_link'].$aidlink."'>".preg_replace("/&(?!(#\d+|\w+);)/", "&amp;", $locale["admin_".$data['admin_rights']])."</option>\n";
			} elseif ($data['admin_link'] != "reserved" && checkrights($data['admin_rights'])) {
				$pages[$data['admin_page']] .= "<option value='".ADMIN.$data['admin_link'].$aidlink."'>".preg_replace("/&(?!(#\d+|\w+);)/", "&amp;", $data['admin_title'])."</option>\n";
			}
		}
		$content = false;
		for ($i = 1; $i < 6; $i++) {
			$page = $pages[$i];
			if ($i == 1) {
				echo THEME_BULLET." <a href='".ADMIN."index.php".$aidlink."' class='side'>".$locale['ac00']."</a>\n";
				echo "<hr class='side-hr' />\n";
			}
			if ($page) {
				$admin_pages = true;
				echo "<form action='".FUSION_SELF."'>\n";
				echo "<select onchange='window.location.href=this.value' style='width:100%;' class='textbox'>\n";
				echo "<option value='".FUSION_SELF."' style='font-style:italic;' selected='selected'>".$locale['ac0'.$i]."</option>\n";
				echo $page."</select>\n</form>\n";
				$content = true;
			}
			if ($i == 5) {
				if ($content) { echo "<hr class='side-hr' />\n"; }
				echo THEME_BULLET." <a href='".BASEDIR."index.php' class='side'>".$locale['global_181']."</a>\n";
			}
		}
	closeside();
}

if($settings['adminmenue_version']) {

	function new_pif_version() {
	global $settings;
		if((time() - 60*60) > $settings['version_checker_lastcheck']) {
		$url_p = @parse_url("http://pimped-fusion.net/version.txt");
		$host = $url_p['host'];
		$port = isset($url_p['port']) ? $url_p['port'] : 80;
		$fp = @fsockopen($url_p['host'], $port, $errno, $errstr, 5);
			if(!$fp) {
				$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='' WHERE settings_name='version_checker_tempversion'");
				$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".time()."' WHERE settings_name='version_checker_lastcheck'");
				return false;
			}
		@fputs($fp, 'GET '.$url_p['path'].' HTTP/1.1'.chr(10));
		@fputs($fp, 'HOST: '.$url_p['host'].chr(10));
		@fputs($fp, 'Connection: close'.chr(10).chr(10));
		$response = @fgets($fp, 1024);
		$content = @fread($fp,1024);
		$content = preg_replace("#(.*?)text/plain(.*?)$#is","$2",$content);
		@fclose($fp);
		
		if(strpos($content,"close")!==false) { $content = explode("close", $content); $content = $content[1]; }
		
			if(preg_match("#404#",$response)) {
				$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='' WHERE settings_name='version_checker_tempversion'");
				$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".time()."' WHERE settings_name='version_checker_lastcheck'");
				return false;
			} else {
				$content = trim(str_replace("X-Pad: avoid browser bug","",$content));
				$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".$content."' WHERE settings_name='version_checker_tempversion'");
				$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".time()."' WHERE settings_name='version_checker_lastcheck'");
				return $content;
			}
		} else {
			return $settings['version_checker_tempversion'];
		}
	}

	openside($locale['ver000'], true);
	$var = '';
	if(function_exists('fsockopen')) {
		$version_new = new_pif_version();
		if(version_compare($version_new, $settings['version_pimp'], '<=') && $version_new > 0) {
			$var .= "<img src='".get_image("version")."' alt='' style='border:0px;' /><br />";
			$var .= "<span style='color:#66ff00'>".$locale['ver001']."</span>";
		} else {
			if (!empty($version_new)) {
				$var .= "<img src='".get_image("version_old")."' alt='' style='border:0px;' />";
				$var .= "<br /><span style='color:#ff0000'>".$locale['ver002']."</span><br />";
				$var .= $locale['ver003']." ".$settings['version_pimp']."<br />";
				$var .= $locale['ver004']." ".$version_new."<br />";
				}
		}
	}
	if (!isset($version_new) || $version_new == false || $version_new == '') {
		$var .= $locale['ver005']." ";
		$var .= "<a href='http://pimped-fusion.net/version.php?v=".$settings['version_pimp']."' target='_blank'>".$locale['ver006']."</a>";
	}

	echo "<div align='left'>".$var."</div>";
	closeside();

}
?>