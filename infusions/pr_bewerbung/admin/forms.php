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

if (!defined("iAUTH") || $_GET['aid'] != iAUTH || !iADMIN) die("Access denied!");

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

$result = dbquery("SELECT * FROM ".DB_PREFIX."form_fields WHERE pr_toform = '1'");

//Beginn Content
opentable("Bewerbung v".$version." &raquo; Formular Admin");

echo '<center><input type="button" value="neues Feld" class="button" onclick="popup=window.open(\''.INFUSIONS.'pr_bewerbung/admin/new_form.php'.$aidlink.'\',\'popup\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=550,height=550,left=50,top=50\'); return false;" /></center><br /><br />';

echo "<table class='tbl' align='center' width='90%'>
	<tr>
		<td><b>Beschreibung</b></td>
		<td><b>Type</b></td>
		<td><b>Aktionen</b></td>
	</tr>
";

while ($data=dbarray($result)){
	echo "<tr>
		<td>".utf8_encode($data['pr_desc'])."</td>
		<td>".($data['pr_pflicht'] == 1 ? "Pflichtfeld" : "freiwilliges Feld")."</td>
		<td>";
			if($data['pr_id'] != "1" && $data['pr_id'] != "2"){
				echo "<a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;form_del=".$data['pr_id']."' onclick=\"return confirm('Wirklich l&ouml;schen?');\"><img src='".INFUSIONS."pr_bewerbung/admin/images/delete_cross.gif' border='0' title='L&ouml;schen' alt='L&ouml;schen' /></a>&nbsp;&nbsp;&nbsp;";
			}else{
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			echo '<a href="#" onclick="popup=window.open(\''.INFUSIONS.'pr_bewerbung/admin/bea_form.php'.$aidlink.'&amp;id='.$data['pr_id'].'&amp;type='.$data['pr_type'].'\',\'popup\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=550,height=550,left=50,top=50\'); return false;"><img src="'.IMAGES.'edit.gif" border="0" title="Bearbeiten" alt="Bearbeiten" /></a>';
			// Not included yet
			/*echo "<a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;form_up=".$data['pr_id']."'><img src='".THEME."images/up.gif' border='0' title='Hoch' alt='Hoch' /></a>
			<a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;form_down=".$data['pr_id']."'><img src='".THEME."images/down.gif' border='0' title='Runter' alt='Runter' /></a>";*/
		echo "</td>
	</tr>";

}

echo "</table>";


// Do NOT remove! / Nicht enfernen!
// Verstöße werden strafrechtlich verfolgt!
render_copy(true);
closetable();

?>