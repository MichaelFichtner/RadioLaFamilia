<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings.php
| Version: Pimped Fusion v0.05.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../../maincore.php";
require_once TEMPLATES."header.php";

if (!iMEMBER) { redirect("../../index.php"); }

if(!IF_MULTI_LANGUAGE_FORUM || !isset($userdata['user_forumpanellocale'])) { redirect("../../index.php"); }

opentable($locale['global_064']);
if(isset($_POST['user_forumpanellocale'])) {
	$language = $_POST['user_forumpanellocale'];
	$result = dbquery("UPDATE ".DB_USERS." SET user_forumpanellocale="._db($language)." WHERE user_id='".(int)$userdata['user_id']."'");
	redirect(INFUSIONS."forum_threads_list_panel/settings.php");
}

echo "<br /><form name='inputform' method='post' action='".INFUSIONS."forum_threads_list_panel/settings.php'>
<table cellpadding='0' cellspacing='1' width='500' class='center'>\n<tr>\n";
echo "<td class='tbl' width='50%'>".$locale['global_065']."</td>\n";
echo "<td class='tbl' width='50%'><select name='user_forumpanellocale' class='textbox' style='width:100px;'>\n".make_admin_language_opts((isset($userdata['user_forumpanellocale']) ? $userdata['user_forumpanellocale'] : ""))."\n</select></td>\n";
echo "<tr><td></td><td><input type='submit' name='save_settings' value='".$locale['global_066']."' class='button' /></td></tr>";
echo "</tr>\n</table></form><br />";

require_once INFUSIONS."forum_threads_list_panel/threads_list_navigation.php";
closetable();

require_once TEMPLATES."footer.php";