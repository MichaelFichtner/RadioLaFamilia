<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin/new_form.php
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

//Locale includieren
if (file_exists(INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php")) {
	include INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."pr_bewerbung/locale/German.php";
}

if (!iSUPERADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { die("Access denied"); }

$result = dbquery("UPDATE ".$db_prefix."users SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'");

echo "<script type='text/javascript'>
<!--
var waitText = '<div align=\"center\" style=\"padding-top: 81px; padding-bottom: 81px; font-weight: bold;\">Loading <img src=\"".INFUSIONS."pr_bewerbung/admin/images/loading.gif\" width=\"16\" height=\"16\" alt=\"Loading\"></ div>';
//-->
</script>";

require_once INFUSIONS."pr_bewerbung/includes/form_creator.inc.php";
opentable ("Neues Feld");

if(!isset($_POST['type']) || $_POST['type'] != "select" && $_POST['type'] != "radio"){
   echo "<table width='95%'>
	<tr>
		<td>Type:</td>
		<td>"; ?>
		<select name="type" class="textbox" onChange="switch_form(this.value);">
				<?php 
				echo "
				<option value=''>Bitte w&auml;hlen</option>
				<option value='".INFUSIONS."pr_bewerbung/admin/form_creator/itext.php".$aidlink."'>Inputtext</option>
				<option value='".INFUSIONS."pr_bewerbung/admin/form_creator/select.php".$aidlink."'>Select</option>
				<option value='".INFUSIONS."pr_bewerbung/admin/form_creator/radio.php".$aidlink."'>Radio</option>
				<option value='".INFUSIONS."pr_bewerbung/admin/form_creator/check.php".$aidlink."'>Checkbox</option>
				<option value='".INFUSIONS."pr_bewerbung/admin/form_creator/text_field.php".$aidlink."'>Textfield</option>
		</select></td>
	<tr>
   </table>";
   echo "<div id='inputfull' name='inputfull'></div>";
}elseif($_POST['type'] == "select"){
   echo "<form name='select' method='post' action='form_creator/saver.php".$aidlink."'>";
   echo "<table width='100%'>";
	
	for($i=1; $i<=$_POST['values']; $i++){
		echo "<tr>
		     <td>Value ".$i.": </td>
		     <td><input type='text' name='value".$i."' class='textbox'></td>
		</tr>";
	}
	form_daten();
  echo "</table><center><input type='hidden' name='type' value='select' /><input type='submit' value='Speichern' name='save_it' class='button' /></center>";

  echo "</form>";
}elseif($_POST['type'] == "radio"){
	echo "<form name='radio' method='post' action='form_creator/saver.php".$aidlink."'>";
   echo "<table width='100%'>";
	
	for($i=1; $i<=$_POST['values']; $i++){
		echo "<tr>
		     <td>Value ".$i.": </td>
		     <td><input type='text' name='value".$i."' class='textbox'></td>
		</tr>";
	}
	form_daten();
  echo "</table><center><input type='hidden' name='type' value='radio' /><input type='submit' value='Speichern' name='save_it' class='button' /></center>";

  echo "</form>";
}else{
	echo "Unknown Error";
}
// Do NOT remove! / Nicht enfernen!
// Verstöße werden strafrechtlich verfolgt!
echo "<br><div align='right'>Code &copy; by <a href='http://www.prugnator.de' target='_blank'>PrugnatoR</a></div>";
closetable();

echo "</body></html>";
?>