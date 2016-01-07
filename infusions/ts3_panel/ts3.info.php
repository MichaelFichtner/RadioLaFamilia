<?php
// -------------------------------------------------------
// PHP-Fusion Content Management System
// Copyright (C) 2002 - 2008 Nick Jones
// http://www.php-fusion.co.uk/
// -------------------------------------------------------
// Author: Markus (HappyF)
// Web: www.xtc-radio.nl
// -------------------------------------------------------
// This program is released as free software under the
// Affero GPL license. You can redistribute it and/or
// modify it under the terms of this license which you
// can read by viewing the included agpl.txt or online
// at www.gnu.org/licenses/agpl.html. Removal of this
// copyright header is strictly prohibited without
// written permission from the original author(s).
// -------------------------------------------------------
require_once "../../maincore.php";

include INFUSIONS."ts3_panel/infusion_db.php";
require_once(INFUSIONS."ts3_panel/data/tsstatus.php");

echo "<link rel='stylesheet' type='text/css' href='".INFUSIONS."ts3_panel/data/ts3.css' media='screen'>\n";
echo "<script type='text/javascript' src='".INFUSIONS."ts3_panel/data/ts3.class.js'></script>\n";

$res_db_set = dbquery("SELECT * FROM ".DB_TS3_SET);
	while($set = dbarray($res_db_set)) {
		$host = $set['host'];
		$query_port = $set['query_port'];
		$server_id = $set['server_id'];
		$timeout = $set['timeout'];
		$admin_login = $set['admin_login'];
		$admin_passw = $set['admin_passw'];
		$decode_utf = $set['decode_utf'];
	}

$res_db_con = dbquery("SELECT * FROM ".DB_TS3_CON);
	while($con = dbarray($res_db_con)) {
		$nick = $con['hide_nick'];
		$pass = $con['show_pass'];
	}
	
	if($nick == "1") { $this_nick = true; } else { $this_nick = false; }
	if($pass == "1") { $this_pass = true; } else { $this_pass = false; }
	

	$tsstatus = new TSStatus($host, $query_port, $server_id);
	
	$tsstatus->imagePath = INFUSIONS."ts3_panel/images/";
	
	$tsstatus->showNicknameBox = $this_nick;
	$tsstatus->showPasswordBox = $this_pass;
	
	$tsstatus->decodeUTF8 = $decode_utf;
	$tsstatus->timeout = $timeout;
	$tsstatus->setLoginPassword($admin_login, $admin_passw);
	echo $tsstatus->render();
	echo $ts3_panel_show;

?>