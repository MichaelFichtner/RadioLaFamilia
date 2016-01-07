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

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { die("Access denied"); }
if (!isset($_GET['id']) || !isNum($_GET['id'])){ die("Access denied"); }

$result = dbquery("UPDATE ".$db_prefix."users SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'");
$comment = dbarray($result);

opentable ("Kommentar bearbeiten");

if(isset($_POST['send'])){
	
	$res_save = dbquery("UPDATE ".DB_PREFIX."bewerbung SET pr_comment='".$_POST['comment']."' WHERE pr_id='".$_GET['id']."'");
	if($res_save){
		echo "Kommentar erfolgreich gespeichert!";
	}else{
		echo "Speichern ist fehlgeschlagen!";	
	}
	
}else{
$result = dbquery("SELECT pr_comment FROM ".DB_PREFIX."bewerbung WHERE pr_id='".$_GET['id']."' LIMIT 1");
$erg = dbarray($result);
echo "<form name='comment_form' method='post' action='".FUSION_SELF.$aidlink."&amp;id=".$_GET['id']."'>";
echo "<table width='95%'>
	<tr>
		<td>Kommentar:</td>
		<td><textarea type='text' name='comment' rows='5' cols='54' class='textbox'>".$erg['pr_comment']."</textarea></td>
	</tr>
</table>";
echo "<center><input type='submit' name='send' value='Speichern' class='button' /></center>";
echo "</form>";

// Do NOT remove! / Nicht enfernen!
// Verstöße werden strafrechtlich verfolgt!
echo "<br><div align='right'>Code &copy; by <a href='http://www.prugnator.de' target='_blank'>PrugnatoR</a></div>";
closetable();

}

echo "</body></html>";
?>