<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: img_bbcode_include.php
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

if($settings['enable_lightbox'] == "1" && (!isset($disable_lightbox) || $disable_lightbox == false)) {

	require_once INCLUDES."lightbox/lightbox_head.php";
	$text = preg_replace("#\[img\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/img\]#sie","'<a href=\'\\1'.str_replace(array('?','&amp;','&','='),'','\\3').'\\4\' alt=\'\\3\\4\' style=\'border:0px\' class=\'fancybox\' rel=\'group\'><span style=\'display: block; width: 100px; max-height: 100px; overflow: auto;\' class=\'forum-img-wrapper\'><img src=\'\\1'.str_replace(array('?','&amp;','&','='),'','\\3').'\\4\' alt=\'\\3\\4\' style=\'border:0px\' class=\'forum-img\' /></span></a>'",$text);

} else {

	if (!function_exists("img_bbcode_callback")) {
		function img_bbcode_callback($matches) {
			if (substr($matches[3], -1, 1) != "/") {
				return "<span style='display: block; width: 300px; max-height: 300px; overflow: auto;' class='forum-img-wrapper'><img src='".$matches[1].str_replace(array("?","&amp;","&","="), "", $matches[3]).$matches[4]."' alt='".$matches[3].$matches[4]."' style='border:0px' class='forum-img' /></span>";
			} else {
				return $matches[0];
			}
		}
	}

	$text = preg_replace_callback("#\[img\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/img\]#si", "img_bbcode_callback", $text);

}
?>