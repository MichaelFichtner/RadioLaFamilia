<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin/bewerbung.php
| pr_Bewerbungsscript v2.00
| Author: PrugnatoR
| URL: http://www.prugnator.de
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../../../maincore.php";

define("ADMIN_PANEL", true);

if (!defined("iAUTH") || $_GET['aid'] != iAUTH) redirect("../../index.php");

require_once INCLUDES."output_handling_include.php";
require_once INCLUDES."header_includes.php";
require_once THEME."theme.php";

require_once INFUSIONS."pr_bewerbung/includes/functions.inc.php";

//Locale includieren
if (file_exists(INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php")) {
	include INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."pr_bewerbung/locale/German.php";
}

$result = dbquery("SELECT * FROM ".PR_DB_BEWERBUNG." WHERE pr_status='3' ORDER BY pr_id");

//Beginn Content
opentable("Bewerbung v".$version." &raquo; Eingestellte");

	if (count_bew("3") == "0"){ echo "<center>Bisher wurden keine Bewerber eingestellt</center>"; }

	else {
		$n="0";
		while ($data = dbarray($result)){
			$n++;
				make_table($n, "3");
				
		}
	}

// Do NOT remove! / Nicht enfernen!
// Verstöße werden strafrechtlich verfolgt!
render_copy(true);
closetable();

?>