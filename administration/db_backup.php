<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: db_backup.php
| Version: Pimped Fusion v0.06.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";

if (!checkrights("DB") || !defined("iAUTH") || $_GET['aid'] != iAUTH) {
	redirect("../index.php");
} else {

	if($settings['login_method'] == "sessions") {
	$user_pass = (preg_check("/^[0-9a-z]{32}$/", $_SESSION[COOKIE_PREFIX.'user_pass']) ? $_SESSION[COOKIE_PREFIX.'user_pass'] : "");
	$cookie_value = $userdata['user_id'].".".$user_pass;
	$cookie_exp =  time() + 3600 * 3;
	setcookie(COOKIE_PREFIX."user", $cookie_value, $cookie_exp, "/", "", "0");
	}

	redirect(ADMIN."db_backup");
}

?>