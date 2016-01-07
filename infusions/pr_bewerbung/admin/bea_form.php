<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin/bea_form.php
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
require_once THEMES."templates/admin_header.php";
require_once INFUSIONS."pr_bewerbung/includes/functions.inc.php";

if (!iSUPERADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { die("Access denied"); }

$result = dbquery("UPDATE ".$db_prefix."users SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'");

//require_once "maincore.php";
opentable ("Feld bearbeiten");

if (isset($_GET['id']) && isset($_GET['type']) && isNum($_GET['id'])){

	$result = dbquery("SELECT * FROM ".DB_PREFIX."form_fields WHERE pr_id='".$_GET['id']."'");
	$data = dbarray($result);

   if($_GET['type'] == "" || $_GET['type'] == "itext"){
	echo "<form name='itext' method='post' action='".INFUSIONS."pr_bewerbung/admin/form_creator/edit_saver.php".$aidlink."'>\n";

		echo "<table width='100%'>
			<tr>
				<td>Beschreibung: </td>
				<td><input type='text' name='desc' size='24' class='textbox' value='".$data['pr_desc']."'></td>
			</tr>
			<tr>
				<td colspan='2'>
					Einf&uuml;gen von Sondervalues: (Diese werden direkt aus dem Profil des Users ausgelesen)
			</td>
			</tr>
			<tr>
				<td colspan='2'>
					<input type='button' value='Username' class='button' style='width:60x' onclick=\"document.getElementById('value').value='&lt;?php\\n echo \$username; \\n?&gt'\" />
					<input type='button' value='eMail' class='button' style='width:60x' onclick=\"document.getElementById('value').value='&lt;?php\\n echo \$useremail; \\n?&gt'\" />
					<input type='button' value='Geburtstag' class='button' style='width:60x' onclick=\"document.getElementById('value').value='&lt;?php\\n echo \$userbirthday; \\n?&gt'\" />
				</td>
			</tr>
			<tr>
				<td>Value: <i>(Leer lassen, wenn nicht ben&ouml;tigt)</i></td>
				<td><input type='text' name='value' id='value' size='24' class='textbox' value='".$data['pr_value']."'></td>
			</tr>
			<tr>
				<td>Pflichtfeld?: </td>
				<td><select name='pflicht' class='textbox'>
					<option value='1' ".($data['pr_pflicht'] == 1 ? "selected" : "").">Ja</option>
					<option value='0' ".($data['pr_pflicht'] == 0 ? "selected" : "").">Nein</option>
				</td>
			</tr>
		</table>\n";

	echo "<center><input type='hidden' name='type' value='itext'>
		<input type='hidden' name='id' value='".$_GET['id']."'>
		<input type='hidden' name='name' value='".$data['pr_name']."'>
		<input type='submit' value='Speichern' name='save_it' class='button'></center>\n";

	echo "</form>\n";
   }elseif($_GET['type'] == "text_field"){
	echo "<form name='text_field' method='post' action='".INFUSIONS."pr_bewerbung/admin/form_creator/edit_saver.php".$aidlink."'>\n";

		echo "<table width='100%'>
			<tr>
				<td>Beschreibung: </td>
				<td><input type='text' name='desc' size='24' class='textbox' value='".$data['pr_desc']."'></td>
			</tr>
			<tr>
				<td colspan='2'>
					Einf&uuml;gen von Sondervalues: (Diese werden direkt aus dem Profil des Users ausgelesen)
			</td>
			</tr>
			<tr>
				<td colspan='2'>
					<input type='button' value='Username' class='button' style='width:60x' onclick=\"document.getElementById('value').value='&lt;?php\\n echo \$username; \\n?&gt'\" />
					<input type='button' value='eMail' class='button' style='width:60x' onclick=\"document.getElementById('value').value='&lt;?php\\n echo \$useremail; \\n?&gt'\" />
					<input type='button' value='Geburtstag' class='button' style='width:60x' onclick=\"document.getElementById('value').value='&lt;?php\\n echo \$userbirthday; \\n?&gt'\" />
				</td>
			</tr>
			<tr>
				<td>Value: <i>(Leer lassen, wenn nicht ben&ouml;tigt)</i></td>
				<td><input type='text' name='value' id='value' size='24' class='textbox' value='".$data['pr_value']."'></td>
			</tr>
			<tr>
				<td>Pflichtfeld?: </td>
				<td><select name='pflicht' class='textbox'>
					<option value='1' ".($data['pr_pflicht'] == 1 ? "selected" : "").">Ja</option>
					<option value='0' ".($data['pr_pflicht'] == 0 ? "selected" : "").">Nein</option>
				</td>
			</tr>
		</table>\n";

	echo "<center><input type='hidden' name='type' value='text_field'>
		<input type='hidden' name='id' value='".$_GET['id']."'>
		<input type='hidden' name='name' value='".$data['pr_name']."'>
		<input type='submit' value='Speichern' name='save_it' class='button'></center>\n";

	echo "</form>\n";
   }elseif($_GET['type'] == "radio"){
 	echo "<form name='radio' method='post' action='".INFUSIONS."pr_bewerbung/admin/form_creator/edit_saver.php".$aidlink."'>\n";

		echo "<table width='100%'>
			<tr>
				<td>Beschreibung: </td>
				<td><input type='text' name='desc' size='24' class='textbox' value='".$data['pr_desc']."'></td>
			</tr>
			<tr>
				<td>Value: <img src='".INFUSIONS."pr_bewerbung/admin/images/qmark.png' onmouseover=\"return overlay(this, 'value_info', 'bottomleft');\" onmouseout=\"return overlayclose('value_info');\" style='vertical-align:middle;'/>
		<div id='value_info' class='infopopup'>Jede Auswahlm&ouml;glichkeit bitte mit einem \",\" trennen, nach der letzten Auswahlm&ouml;glichkeit wird kein Komma gesetzt.<br /><i>Beispiel:</i>Moderator, Gast-Mod, Techniker</div></td>
				<td><input type='text' name='value' size='24' class='textbox' value='".$data['pr_value']."'></td>
			</tr>
			<tr>
				<td>Pflichtfeld?: </td>
				<td><select name='pflicht' class='textbox'>
					<option value='1' ".($data['pr_pflicht'] == 1 ? "selected" : "").">Ja</option>
					<option value='0' ".($data['pr_pflicht'] == 0 ? "selected" : "").">Nein</option>
				</td>
			</tr>
		</table>\n";

	echo "<center><input type='hidden' name='type' value='radio'>
		<input type='hidden' name='id' value='".$_GET['id']."'>
		<input type='hidden' name='name' value='".$data['pr_name']."'>
		<input type='submit' value='Speichern' name='save_it' class='button'></center>\n";

	echo "</form>\n";  
   }elseif($_GET['type'] == "select"){
	echo "<form name='select' method='post' action='".INFUSIONS."pr_bewerbung/admin/form_creator/edit_saver.php".$aidlink."'>\n";

		echo "<table width='100%'>
			<tr>
				<td>Beschreibung: </td>
				<td><input type='text' name='desc' size='24' class='textbox' value='".$data['pr_desc']."'></td>
			</tr>
			<tr>
				<td>Values: <img src='".INFUSIONS."pr_bewerbung/admin/images/qmark.png' onmouseover=\"return overlay(this, 'value_info', 'bottomleft');\" onmouseout=\"return overlayclose('value_info');\" style='vertical-align:middle;'/>
		<div id='value_info' class='infopopup'>Jede Auswahlm&ouml;glichkeit bitte mit einem \",\" trennen, nach der letzten Auswahlm&ouml;glichkeit wird kein Komma gesetzt.<br /><i>Beispiel:</i>Moderator, Gast-Mod, Techniker</div></td>
				<td><input type='text' name='value' size='24' class='textbox' value='".$data['pr_value']."'></td>
			</tr>
			<tr>
				<td>Pflichtfeld?: </td>
				<td><select name='pflicht' class='textbox'>
					<option value='1' ".($data['pr_pflicht'] == 1 ? "selected" : "").">Ja</option>
					<option value='0' ".($data['pr_pflicht'] == 0 ? "selected" : "").">Nein</option>
				</td>
			</tr>
		</table>\n";

	echo "<center><input type='hidden' name='type' value='select'>
		<input type='hidden' name='id' value='".$_GET['id']."'>
		<input type='hidden' name='name' value='".$data['pr_name']."'>
		<input type='submit' value='Speichern' name='save_it' class='button'></center>\n";

	echo "</form>\n";   
   }elseif($_GET['type'] == "check"){
	echo "<form name='check' method='post' action='".INFUSIONS."pr_bewerbung/admin/form_creator/edit_saver.php".$aidlink."'>\n";

		echo "<table width='100%'>
			<tr>
				<td>Beschreibung: </td>
				<td><input type='text' name='desc' size='24' class='textbox' value='".$data['pr_desc']."'></td>
			</tr>
			<tr>
				<td>Value: <i>(Leer lassen, wenn nicht ben&ouml;tigt)</i></td>
				<td><input type='text' name='value' size='24' class='textbox' value='".$data['pr_value']."'></td>
			</tr>
			<tr>
				<td>Pflichtfeld?: </td>
				<td><select name='pflicht' class='textbox'>
					<option value='1' ".($data['pr_pflicht'] == 1 ? "selected" : "").">Ja</option>
					<option value='0' ".($data['pr_pflicht'] == 0 ? "selected" : "").">Nein</option>
				</td>
			</tr>
		</table>\n";

	echo "<center><input type='hidden' name='type' value='check'>
		<input type='hidden' name='id' value='".$_GET['id']."'>
		<input type='hidden' name='name' value='".$data['pr_name']."'>
		<input type='submit' value='Speichern' name='save_it' class='button'></center>\n";

	echo "</form>\n";   
   }else{
		echo "FEHLER";
   }

}else{
	echo "FEHLER";
}

// Do NOT remove! / Nicht enfernen!
// Verstöße werden strafrechtlich verfolgt!
render_copy(true);
closetable();

echo "</body></html>";
?>