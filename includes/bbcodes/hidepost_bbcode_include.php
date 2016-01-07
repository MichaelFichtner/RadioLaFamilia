<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: hide_bbcode_include.php
|| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: Fangree Craig, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if(isset($_GET['thread_id'])) { // Fix by slaughter for comments & administration, and guests
	global $userdata;

	if(iMEMBER) {
	$hidequery = dbquery("SELECT post_author FROM ".DB_POSTS." WHERE thread_id = '".(int)$_GET['thread_id']."' AND post_author = '".(int)$userdata['user_id']."'");
	$userposted = dbarraynum($hidequery);
	}

	if(!iMEMBER || empty($userposted)) {
		$text = preg_replace('#\[hidepost\](.*?)\[/hidepost\]#si', '', $text);
	} else {
		$text = preg_replace('#\[hidepost\](.*?)\[/hidepost\]#si', '\1', $text);
	}

} elseif (iADMIN && stristr(FUSION_REQUEST, "/administration/")) {

	$text = preg_replace('#\[hidepost\](.*?)\[/hidepost\]#si', '\1', $text);

}
?>