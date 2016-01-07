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
require_once INCLUDES."html_buttons_include.php";

//Locale includieren
if (file_exists(INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php")) {
	include INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."pr_bewerbung/locale/German.php";
}

//Beginn Content
opentable("Bewerbung v".$version." &raquo; Setup");

$result = dbquery("SELECT * FROM ".DB_PREFIX."formulars");
$that = dbarray($result);
?>
<br /><center>
<input type="button" onclick="switch_site('<?php echo INFUSIONS."pr_bewerbung/admin/forms.php".$aidlink ?>');" class="button" value="Formularfelder editiern" />
<br /><br /></center>
<?php

if($that['pr_pnto'] == -1){
	$pn_to = $locale['user2'];
}elseif($that['pr_pnto'] == -2){
	$pn_to = $locale['user3'];
}else{
	$pn_to = $that['pr_pnto'];
}



$forum_db = dbquery("SELECT forum_id, forum_name FROM ".DB_FORUMS." WHERE forum_cat!='0' ORDER BY forum_order");

$head = $that['pr_headtext'];
echo "<form name='setup' id='setup' method='post' action'".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink.">
	<table width='90%'>
			<tr>
				<td>Formularstatus:</td>
				<td><select name='aktiv' class='textbox'>
						<option value='1' ".($that['pr_activ'] == "1" ? "selected":"").">Aktiv</option>
						<option value='0' ".($that['pr_activ'] == "0" ? "selected":"").">Inaktiv</option>
			</tr>
			<tr>
				<td>Forumtype:</td>
				<td><select name='forumtype' class='textbox'>
					<option value='0' ".($that['pr_forumtype'] == "0" ? "selected":"").">Standard</option>
					<option value='1' ".($that['pr_forumtype'] == "1" ? "selected":"").">Fusionboard</option>
			</tr>
			<tr>
				<td>Forumname: <img src='".INFUSIONS."pr_bewerbung/admin/images/qmark.png' onmouseover=\"return overlay(this, 'value_info', 'bottomleft');\" onmouseout=\"return overlayclose('value_info');\" style='vertical-align:middle;'/>
				<div id='value_info' class='infopopup'>Hier wird festgelegt wohin Bewerbungen gepostet werden sollen, wenn man im Adminbereich auf ins Forum posten klickt.</div></td>
				<td><select name='forumid' class='textbox'>";
					while($data = dbarray($forum_db)){
						echo "<option value='".$data['forum_id']."' ".($that['pr_forumid'] == $data['forum_id'] ? "selected":"").">".$data['forum_name']."</option>\n";
					}
			echo "</tr>
			<tr>
				<td>PN Benachrichtigung?: <img src='".INFUSIONS."pr_bewerbung/admin/images/qmark.png' onmouseover=\"return overlay(this, 'pn_info', 'bottomleft');\" onmouseout=\"return overlayclose('pn_info');\" style='vertical-align:middle;'/>
				<div id='pn_info' class='infopopup'>Soll eine Benachrichtigung per PN bei einer neuen Bewerbung erfolgen?<br />Falls ja muss im n&auml;chsten Schritt noch festgelegt werden, wer die Benachrichtigung erhalten soll.</div></td>
				<td><select name='pnactiv' class='textbox'>
						<option value='1' ".($that['pr_pnactiv'] == "1" ? "selected":"").">Aktiv</option>
						<option value='0' ".($that['pr_pnactiv'] == "0" ? "selected":"").">Inaktiv</option>
			</tr>
			<tr>
				<td colspan='2'>
					<input type='button' value='User' class='button' style='width:60x' onmouseover=\"return overlay(this, 'user_if', 'bottomleft');\" onmouseout=\"return overlayclose('user_if');\" onclick=\"var eingabe=window.prompt('Gebe hier bitte die ID des Users ein:',''); document.getElementById('pnto').value=eingabe; document.getElementById('pnto_look').value=eingabe;\" />
					<input type='button' value='".$locale['user2']."' class='button' style='width:60x' onmouseover=\"return overlay(this, 'admin_if', 'bottomleft');\" onmouseout=\"return overlayclose('admin_if');\" onclick=\"document.getElementById('pnto').value='-1'; document.getElementById('pnto_look').value='".$locale['user2']."';\" />
					<input type='button' value='".$locale['user3']."' class='button' style='width:60x' onmouseover=\"return overlay(this, 'sadmin_if', 'bottomleft');\" onmouseout=\"return overlayclose('sadmin_if');\" onclick=\"document.getElementById('pnto').value='-2'; document.getElementById('pnto_look').value='".$locale['user3']."';\" />
					<!--- Coming soon <input type='button' value='Usergruppe' class='button' style='width:60x' onclick=\"document.getElementById('pnto').value='GROUP,'+window.prompt('Gebe hier bitte die ID der Usergruppe ein:','')\" /> --->
					
					<div id='user_if' class='infopopup'>Die PN Benachrichtigung soll nur f&uuml;r einen User aktiviert werden.<br />Zur Festlegung des Users wird die UserID benötigt. </div>
					<div id='admin_if' class='infopopup'>Die PN Benachrichtigung wird f&uuml;r alle User aktiviert, welche min. den Status ".$locale['user2']." besitzen.</div>
					<div id='sadmin_if' class='infopopup'>Die PN Benachrichtigung wird f&uuml;r alle User aktiviert, welche den Status ".$locale['user3']." besitzen.</div>
				</td>
			</tr>
			<tr>
				<td>PN an:</td>
				<td><input type='text' name='pnto_look' id='pnto_look' value='".$pn_to."' class='textbox' readonly /><input type='hidden' name='pnto' id='pnto' value='".$that['pr_pnto']."' /> <i>(readonly!)</i></td>
			</tr>
			<tr>
				<td>Formularname:</td>
				<td><input type='text' name='fname' value='".$that['pr_formname']."' class='textbox' /></td>
			</tr>
			<tr>
				<td>Headtext:</td>
				<td><textarea name='head' rows='15' cols='54' class='textbox'>".$head."</textarea></td>
			</tr>
			<tr>
				<td colspan='2'>";
					echo "<input type='button' value='&lt;?php?&gt;' class='button' style='width:60x' onclick=\"addText('head', '&lt;?php\\n', '\\n?&gt;', 'setup');\" />\n";
					echo "<input type='button' value='&lt;br&gt;' class='button' style='width:35px' onclick=\"addText('head', '&lt;br /&gt;', '', 'setup');\" />\n";
					echo display_html("setup", "head", true, true);
				
			echo "</tr>
	</table>
	<center><input type='submit' name='save_op' value='Speichern' class='button' /></center>
</form>";

// Do NOT remove! / Nicht enfernen!
// Verstöße werden strafrechtlich verfolgt!
render_copy(true);
closetable();

?>