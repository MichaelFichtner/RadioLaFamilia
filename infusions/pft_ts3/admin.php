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

require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";

if (!defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect(BASEDIR."index.php"); }

include INFUSIONS."pft_ts3/infusion_db.php";
//include INFUSIONS."pft_ts3/config/pft_ts3config.php";

if(isset($_GET['section'])) {
 $section = stripinput($_GET['section']);
} else {
 $section = "config";
}

if (isset($_GET['rowstart']) AND isnum($_GET['rowstart'])) {
 $rowstart = stripinput($_GET['rowstart']);
} else {
 $rowstart = 0;
}

opentable("Xtreme Tools - Teamspeak3 Viewer");

echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>";

echo "<tr>

<td width='25%' class=".($section == "config" ? "tbl1" : "tbl2")." align='left'>&middot;&nbsp;<span class='small'>".($section == "config" ? "Einstellungen" : "<a class='small' href='".FUSION_SELF.$aidlink."&section=config'><b>Einstellungen</b></a>")."</span></td>

<td width='25%' class=".($section == "restart" ? "tbl1" : "tbl2")." align='left'>&middot;&nbsp;<span class='small'>".($section == "restart" ? "Starten / Stoppen" : "<a class='small' href='".FUSION_SELF.$aidlink."&section=restart'><b>Starten / Stoppen</b></a>")."</span></td>

<td width='25%' class=".($section == "server" ? "tbl1" : "tbl2")." align='left'>&middot;&nbsp;<span class='small'>".($section == "server" ? "Serverconfig" : "<a class='small' href='".FUSION_SELF.$aidlink."&section=server'><b>Serverconfig</b></a>")."</span></td>

</tr></table>";
echo "<p><div align='right'>&nbsp;&nbsp;&copy; 2010 by <a target='_blank' href='http://www.phpfusion-tools.de' title='PHPFusion-Tools.de'>PHPFusion-Tools.de</a></div>";
closetable();
tablebreak();

switch($section) {
case "config":
@include(INFUSIONS."pft_ts3/ts3_config.php");
break;

case "restart":
opentable("Server Funktionen");
echo "<center>Diese Funktion gibt es nur in der Premiumversion!</center>";
closetable();
break;

case "server":
opentable("Server Einstellungen");
echo "<center>Diese Funktion gibt es nur in der Premiumversion!</center>";
closetable();
break;
}
require_once THEMES."templates/footer.php";
?>