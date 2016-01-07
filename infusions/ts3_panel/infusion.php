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

include INFUSIONS."ts3_panel/infusion_db.php";

$inf_title = "Teamspeak 3 Panel";
$inf_description = "Teamspeak 3 Panel";
$inf_version = "3.1";
$inf_developer = "HappyF";
$inf_email = "happyf@gmx.net";
$inf_weburl = "http://www.xtc-radio.nl";
$inf_folder = "ts3_panel";

$inf_newtable[1] = DB_TS3_SET." (
	id INT(10) NOT NULL AUTO_INCREMENT,
	host VARCHAR(255) NOT NULL DEFAULT '',
	query_port VARCHAR(255) NOT NULL DEFAULT '',
	server_id VARCHAR(255) NOT NULL DEFAULT '',
	timeout VARCHAR(255) NOT NULL DEFAULT '',
	admin_login VARCHAR(255) NOT NULL DEFAULT '',
	admin_passw VARCHAR(255) NOT NULL DEFAULT '',
	decode_utf VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (id))";
	$inf_insertdbrow[1] = DB_TS3_SET." (host, query_port, server_id, timeout, admin_login, admin_passw, decode_utf) VALUES('127.0.0.1', '10011', '1', '2', '', '', '1')";
	$inf_droptable[1] = DB_TS3_SET;

$inf_newtable[2] = DB_TS3_CON." (
	id INT(10) NOT NULL AUTO_INCREMENT,
	jquery VARCHAR(255) NOT NULL DEFAULT '',
	refresh VARCHAR(255) NOT NULL DEFAULT '',
	hide_nick VARCHAR(255) NOT NULL DEFAULT '',
	show_pass VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (id))";
	$inf_insertdbrow[2] = DB_TS3_CON." (jquery, refresh, hide_nick, show_pass) VALUES('0', '60000', '1', '1')";
	$inf_droptable[2] = DB_TS3_CON;



$inf_adminpanel[1] = array(
	"title" => "TS 3 (Panel)",
	"panel" => "ts3_admin.php",
	"rights" => "TS3P"
);

$inf_adminpanel[2] = array(
	"title" => "TS 3 (Server)",
	"panel" => "ts3_setting.php",
	"rights" => "TS3S"
);


?>

