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
require_once THEMES."templates/admin_header.php";

include INFUSIONS."ts3_panel/infusion_db.php";

if (!checkrights("TS3P") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../../index.php"); }

opentable("Teamspeak 3 Seitenpanel");
if(isset($_POST['safe_settings'])) {
	$jquery = stripinput($_POST['jquery']);
	$refresh = stripinput($_POST['refresh']);
	$hide_nick = stripinput($_POST['hide_nick']);
	$show_pass = stripinput($_POST['show_pass']);
	$result = dbquery("UPDATE ".DB_TS3_CON." SET jquery='".$jquery."', refresh='".$refresh."', hide_nick='".$hide_nick."', show_pass='".$show_pass."'");
	redirect(FUSION_SELF.$aidlink);
} else {
	echo "<table align='center' cellspacing='1' cellpadding='2' width='100%'>\n";
	$res_con = dbquery("SELECT * FROM ".DB_TS3_CON."");
	while($data = dbarray($res_con)) {
		echo "<form name='ts3_settings' action='".FUSION_SELF.$aidlink."' method='post'>\n";
		echo "<tr>\n";
			echo "<td align='left' width='130px' class='tbl'><strong>jQuery notwendig?</strong></td>";
			echo "<td align='left' width='300px' class='tbl'><select name='jquery' style='width:200px;' class='textbox'>";
				if($data['jquery'] == "1") {
					echo "<option selected value='1'>Ja</option>";
					echo "<option value='0'>Nein</option>";
				} else {
					echo "<option value='1'>Ja</option>";
					echo "<option selected value='0'>Nein</option>";
				}
			echo "</select></td>";
			echo "<td align='left' class='tbl'><em>... wird jQuery als Datei ben&ouml;tigt?..</em></td>";
		echo "</tr>\n";
		echo "<tr>\n";
			echo "<td align='left' width='130px' class='tbl'><strong>Refreshzeit:</strong></td>";
			echo "<td align='left' width='300px' class='tbl'><input type='text' class='textbox' name='refresh' style='width:200px;' value='".$data['refresh']."'></td>";
			echo "<td align='left' class='tbl'><em>... Refreshzeit des Panels in Millisekunden..</em></td>";
		echo "</tr>\n";
			echo "<td align='left' width='130px' class='tbl'><strong>Nickname:</strong></td>";
			echo "<td align='left' width='300px' class='tbl'><select name='hide_nick' style='width:200px;' class='textbox'>";
				if($data['hide_nick'] == "1") {
					echo "<option selected value='1'>Ja</option>";
					echo "<option value='0'>Nein</option>";
				} else {
					echo "<option value='1'>Ja</option>";
					echo "<option selected value='0'>Nein</option>";
				}
			echo "</select></td>";
			echo "<td align='left' class='tbl'><em>... Nicknamen-Feld anzeigen?..</em></td>";
		echo "<tr>\n";
			echo "<td align='left' width='130px' class='tbl'><strong>Passwort:</strong></td>";
			echo "<td align='left' width='300px' class='tbl'><select name='show_pass' style='width:200px;' class='textbox'>";
				if($data['show_pass'] == "1") {
					echo "<option selected value='1'>Ja</option>";
					echo "<option value='0'>Nein</option>";
				} else {
					echo "<option value='1'>Ja</option>";
					echo "<option selected value='0'>Nein</option>";
				}
			echo "</select></td>";
			echo "<td align='left' class='tbl'><em>... Passwort-Feld anzeigen?..</em></td>";
		echo "</tr>\n";
		echo "<tr>\n<td colspan='3' align='left' class='tbl'><input type='submit' name='safe_settings' value='Speichern' class='button'></td></tr>\n";
		echo "</form>\n";
	}
	echo "</table>\n";
}	
closetable();



require_once THEMES."templates/footer.php";
?>