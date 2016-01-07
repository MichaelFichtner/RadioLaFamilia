<?php
// -------------------------------------------------------
// PHP-Fusion Content Management System
// Copyright (C) 2002 - 2008 Nick Jones
// http://www.php-fusion.co.uk/
// -------------------------------------------------------
// Project: 	TS3 Viewer v1.0 für PHPFusion v7.x
// Author: 		HappyF
// Releasedate: Feb. 2010
// Version: 	1.0
// Web:			www.PHPFusion-Tools.de
// -------------------------------------------------------
// This program is released as free software under the
// Affero GPL license. You can redistribute it and/or
// modify it under the terms of this license which you
// can read by viewing the included agpl.txt or online
// at www.gnu.org/licenses/agpl.html. Removal of this
// copyright header is strictly prohibited without
// written permission from the original author(s).
// -------------------------------------------------------

include INFUSIONS."pft_ts3/infusion_db.php";

$inf_title = "Teamspeak 3 Viewer";
$inf_description = "Teamspeak 3 Viewer";
$inf_version = "1.0";
$inf_developer = "Markus V.";
$inf_email = "support@phpfusion-tools.de";
$inf_weburl = "http://www.PHPFusion-Tools.de";
$inf_folder = "pft_ts3";

$inf_newtable[1] = DB_TS3C." (
ts3c_id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
ip varchar(200) NOT NULL default '',
port varchar(200) NOT NULL default '',
telnet varchar(200) NOT NULL default '',
banner varchar(200) NOT NULL default '',
legend varchar(200) NOT NULL default '',
useron varchar(200) NOT NULL default '',
stats varchar(200) NOT NULL default '',
PRIMARY KEY (ts3c_id)
) ";

$inf_insertdbrow[1] = DB_TS3C." (ip, port, telnet, banner, legend, useron, stats) VALUES ('127.0.0.1', '9987', '10011', '0', '1', '1', '1')";

$inf_droptable[1] = DB_TS3C;

$inf_adminpanel[1] = array(
	"title" => "TS3 Admin",
	"image" => "pft.gif",
	"panel" => "admin.php",
	"rights" => "PFT3"
);

$inf_sitelink[1] = array(
	"title" => "Teamspeak 3",
	"url" => "pft_ts3view.php",
	"visibility" => "0" // 0 - Guest / 101 - Member / 102 - Admin / 103 - Super Admin.
);
?>