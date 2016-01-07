<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Hörerlist v2.2 for PHP-Fusion 7
| Filename: gr_hoererlist_admin.php
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
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";

if (!checkrights("GRHL") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../../index.php"); }
if (IsSeT($_GET['id']) && !isnum($_GET['id'])) { redirect("../../index.php"); }

include INFUSIONS."gr_hoererlist/infusion_db.php";
if (file_exists(INFUSIONS."gr_hoererlist/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_hoererlist/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_hoererlist/locale/German/index.php";
}

if (IsSeT($_GET['delete']) && isnum($_GET['id'])) {
	$result = dbquery("DELETE FROM ".DB_GR_HOERERLIST." WHERE hl_id='".$_GET['id']."'");
	redirect(FUSION_SELF.$aidlink);
} elseif (IsSeT($_GET['free']) && isnum($_GET['id'])) {
	$data = dbarray(dbquery("SELECT * FROM ".DB_GR_HOERERLIST." WHERE hl_id='".$_GET['id']."'"));
	if ($data['hl_free'] == 1) {
	$result = dbquery("UPDATE ".DB_GR_HOERERLIST." SET hl_free='0' WHERE hl_id='".$_GET['id']."'");
	$result2 = dbquery("INSERT INTO ".DB_MESSAGES." (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES ('".$data['hl_user_id']."', '1', '".$locale['grhl133']."', '".$locale['grhl134']."', 'n', '0', '".time()."', '0')"); 
	}
	redirect(FUSION_SELF.$aidlink);
} else {
opentable($locale['grhl125']);
echo "<br /><table align='center' class='tbl-border'>\n<tr>\n<td width='350' class='tbl2'>".$locale['grhl126']."</td>\n<td width='100' class='tbl2'>".$locale['grhl127']."</td>\n<td width='100' class='tbl2'>".$locale['grhl128']."</td>\n<td width='80' class='tbl2'>".$locale['grhl129']."</td>\n</tr>";
$result = dbquery("SELECT * FROM ".DB_GR_HOERERLIST."");
if (dbrows($result)) {
$i = 1;
while ($data = dbarray($result)) {
	$user_result = dbarray(dbquery("SELECT user_name,user_email FROM ".DB_USERS." WHERE user_id='".$data['hl_user_id']."'"));
	echo "<tr>\n<td class='".($i % 2 == 0 ? "tbl2" : "tbl1")."'><a href='".BASEDIR."profile.php?lookup=".$data['hl_user_id']."'>".$user_result['user_name']."</a></td>\n<td class='".($i % 2 == 0 ? "tbl2" : "tbl1")."'><a href='".BASEDIR."messages.php?msg_send=".$data['hl_user_id']."' target='_blank'>".$locale['grhl130']."</a></td>\n<td class='".($i % 2 == 0 ? "tbl2" : "tbl1")."'><a href='mailto:".$user_result['user_email']."' target='_blank'>".$locale['grhl130']."</a></td>\n<td class='".($i % 2 == 0 ? "tbl2" : "tbl1")."'>";
	if ($data['hl_free'] == 1) echo "<a href='".FUSION_SELF.$aidlink."&free&id=".$data['hl_id']."'>".$locale['grhl131']."</a><br />";
	echo "<a href='".FUSION_SELF.$aidlink."&delete&id=".$data['hl_id']."'>".$locale['grhl132']."</a></td>\n</tr>";
	$i++;
}
} else {
	echo "<tr>\n<td class='tbl1' colspan='4' align='center'>".$locale['grhl111']."</td>\n</tr>\n";
}
	echo "</table>";	
}
echo "<div align='right'><a href='http://www.granade.eu/scripte/hoererlist.html' target='_blank'>H&ouml;rerlist &copy;</a></div>";
closetable();

require_once THEMES."templates/footer.php";
?>