<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/includes/forum_post_rating_ajax.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: SoBeNoFear, PhAnToM, Fangree_Craig, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
include "../../maincore.php";
include_once LOCALE.LOCALESET."forum/main.php";
include INCLUDES."output_handling_include.php";

if (!iMEMBER) die("Access Denied");
$rated = false;

if ((isset($_GET['post']) && isnum($_GET['post'])) && (isset($_GET['from']) && isnum($_GET['from'])) && (isset($_GET['to']) && isnum($_GET['to'])) && (isset($_GET['type']) && isnum($_GET['type']))) {
	if($userdata['user_id'] != $_GET['from']) die("Access Denied: Wrong User-ID"); // Only for testing atm
	if($_GET['from'] !== $_GET['to']) {
		if(!dbcount("(rate_by)", DB_POST_RATINGS, "rate_type='".(int)$_GET ['type']."' and rate_user='".(int)$_GET['to']."' and rate_post='".(int)$_GET ['post']."' and rate_by='" .(int)$_GET ['from']."'" )) {
			if(dbquery("INSERT INTO ".DB_POST_RATINGS." (rate_type, rate_user, rate_post, rate_by) VALUES('".(int)$_GET['type']."', '".(int)$_GET['to']."', '".(int)$_GET['post']."', '".(int)$_GET['from']."')")) {
				$rated = true;
			}
		}
	}
}

if($rated) {
	echo "<span style=\"color: #00ff00;\">".$locale['fpr102']."</span>";
} else {
	echo "<span style=\"color: #ff0000;\">".$locale['fpr103']."</span>";
}
?>