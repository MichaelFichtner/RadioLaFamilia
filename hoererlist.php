<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Hörerlist v2.2 for PHP-Fusion 7
| Filename: hoererlist.php
| Author: Ralf Thieme (Gr@n@dE)
| Homepage: www.granade.eu
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "maincore.php";
require_once THEMES."templates/header.php";

if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $rowstart = 0; }
else { $rowstart = $_GET['rowstart']; }
if (isset($_GET['error']) && !isnum($_GET['error'])) { redirect("index.php"); }
if (isset($_GET['thanks']) && !isnum($_GET['thanks'])) { redirect("index.php"); }

include INFUSIONS."gr_hoererlist/infusion_db.php";
if (file_exists(INFUSIONS."gr_hoererlist/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_hoererlist/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_hoererlist/locale/German/index.php";
}

/*---------------------------------------------------+
| Einsellungen																			 |
+----------------------------------------------------+
| Freischalten																			 |
| 0 = Freischalten Deaktivieren											 |
| 1 = Freischalten Aktivieren												 |
+---------------------------------------------------*/
$freischalten = 1;
/*--------------------------------------------------*/

if (iMEMBER && IsSeT($_GET['hoerer']) && $_GET['hoerer'] == "ja") {
if (dbrows(dbquery("SELECT * FROM ".DB_GR_HOERERLIST." WHERE hl_user_id='".$userdata["user_id"]."'"))) { redirect(FUSION_SELF."?error=1"); }
if (IsSeT($_POST['ja'])) {
	$result = dbquery("INSERT INTO ".DB_GR_HOERERLIST." (hl_user_id, hl_free) VALUES ('".$userdata["user_id"]."', '".$freischalten."')"); 
	$result2 = dbquery("INSERT INTO ".DB_MESSAGES." (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES ('1', '".$userdata["user_id"]."', '".$locale['grhl123']."', '".$locale['grhl124']."', 'n', '0', '".time()."', '0')"); 
	redirect(FUSION_SELF."?thanks=1");
} elseif (IsSeT($_POST['nein'])) {
	redirect(FUSION_SELF."?thanks=2");
} else {
add_to_title($locale['global_200'].$locale['grhl113']);
opentable($locale['grhl113']);
echo "<div align='center'>".$locale['grhl114']."<br /><br /></div>
<form method='post' action='".FUSION_SELF."?hoerer=ja'>\n<table align='center' cellpadding='0' cellspacing='0' class='tbl-border'>\n<tr>
<td class='tbl2' width='300' align='center' colspan='2'>".$locale['grhl115']."</td>\n</tr>\n<tr>
<td class='tbl2' width='150' align='center'><input type='submit' name='ja' value='".$locale['grhl116']."' class='button' /></td>\n
<td class='tbl2' width='150' align='center'><input type='submit' name='nein' value='".$locale['grhl117']."' class='button' /></td>\n
</tr>\n</table>\n</form>\n";
}
} elseif (iMEMBER && IsSeT($_GET['thanks']) && $_GET['thanks'] == 1) {
	add_to_title($locale['global_200'].$locale['grhl121']);
	opentable($locale['grhl121']);
	echo "<div align='center'><br />".$locale['grhl118']."</div><br />\n";
} elseif (iMEMBER && IsSeT($_GET['thanks']) && $_GET['thanks'] == 2) {
	add_to_title($locale['global_200'].$locale['grhl122']);
	opentable($locale['grhl122']);
	echo "<div align='center'><br />".$locale['grhl119']."</div><br />\n";
} elseif (iMEMBER && IsSeT($_GET['error']) && $_GET['error'] == 1) {
	add_to_title($locale['global_200'].$locale['grhl122']);
	opentable($locale['grhl122']);
	echo "<div align='center'><br />".$locale['grhl120']."</div><br />\n";
} else {
	add_to_title($locale['global_200'].$locale['grhl102']);
	opentable($locale['grhl102']);
	echo "<div align='center'>".$locale['grhl110']."<br /><br />\n";
	$result = dbquery("SELECT * FROM ".DB_GR_HOERERLIST." WHERE hl_free=0 LIMIT ".$rowstart.",20");
	if (dbrows($result)) {
		$counter = 0; $columns = 4;
		$rowsend = dbrows(dbquery("SELECT * FROM ".DB_GR_HOERERLIST." WHERE hl_free=0"));
		echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n<td colspan='4' align='center' class='tbl1'>\n";
		echo makePageNav($rowstart,20,$rowsend,3)."<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
			$user_result = dbquery("SELECT user_id,user_name,user_avatar FROM ".DB_USERS." WHERE user_id='".$data['hl_user_id']."'");
			if (dbrows($user_result)) {
				$user_data = dbarray($user_result);
				if ($user_data['user_avatar'] != "" && file_exists(IMAGES."avatars/".$user_data['user_avatar'])) { $user_image = $user_data['user_avatar']; } else { $user_image = "nopic.jpg"; }
				echo "<td align='left' width='25%' class='tbl'>\n<img src='".IMAGES."avatars/".$user_image."' border='0' alt='".$user_data['user_name']."' width='100' height='100' /><br />\n<strong><a href='".BASEDIR."profile.php?lookup=".$user_data['user_id']."'>".$user_data['user_name']."</a></strong><br /><br />\n</td>\n";
				$counter++;
			} else {
				$result_user = dbquery("DELETE FROM ".DB_GR_HOERERLIST." WHERE hl_user_id='".$data['hl_user_id']."'");
			}
		}
		echo "</tr>\n</table>\n</td>\n</tr>\n</table>\n<br />";
	}	else {
		echo "<br />".$locale['grhl111']."<br /><br />\n<br />\n";
	}
	if (iMEMBER && !dbrows(dbquery("SELECT * FROM ".DB_GR_HOERERLIST." WHERE hl_user_id='".$userdata["user_id"]."'"))) { echo $locale['grhl112']."<br /><br />\n"; }
}
echo "</div>\n<div align='right'><a href='http://www.granade.eu/scripte/hoererlist.html' target='_blank'>H&ouml;rerlist &copy;</a></div>";
closetable();

require_once THEMES."templates/footer.php";
?>