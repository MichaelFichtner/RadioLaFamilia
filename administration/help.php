<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: help.php
| Version: Pimped Fusion v0.08.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";
require_once TEMPLATES."window_header.php";
include LOCALE.LOCALESET."admin/help.php";

$pages = array('site_links', 'panel_editor', 'welcome_message');

if (!isset($_GET['page']) || !in_array($_GET['page'], $pages) || !iADMIN) { die("Error"); }

$result = dbquery("UPDATE ".DB_USERS." SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".(int)$userdata['user_id']."'");

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>".$settings['sitename']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
<link rel='stylesheet' href='".THEME."styles.css' type='text/css'>
</head>
<body>";


switch ($_GET['page']) {
case "site_links":

opentable($locale['HELP101']);
echo "
<div class='tbl2'><strong>".$locale['HELP102']."</strong></div>
<div class='tbl'>
<br />
".$locale['HELP103']."<br />
<br />
</div>
<div class='tbl2'><strong>".$locale['HELP104']."</strong></div>
<div class='tbl'>
<br />
".$locale['HELP105']."<br />
<br />
</div>
";
echo "<div class='tbl2'><strong>".$locale['HELP001']."</strong></div>
<div class='tbl'><br />".$locale['HELP002']."<br /></div>";
closetable();

break;
case "panel_editor":

opentable($locale['HELP110']);
echo "
<div class='tbl2'><strong>".$locale['HELP111']."</strong></div>
<div class='tbl'><br /><pre>
openside(\"Your Title\");
// Your Code
closeside();
</pre><br /><br /></div>
<div class='tbl2'><strong>".$locale['HELP112']."</strong></div>
<div class='tbl'><br /><pre>
openside(\"Your Title\", true, \"on\");
// Your Code
closeside();
</pre><br /><br /></div>
<div class='tbl2'><strong>".$locale['HELP113']."</strong></div>
<div class='tbl'><br /><pre>
openside(\"Your Title\", true, \"off\");
// Your Code
closeside();
</pre><br /><br /></div>
";
echo "<div class='tbl2'><strong>".$locale['HELP001']."</strong></div>
<div class='tbl'><br />".$locale['HELP002']."<br /></div>";
closetable();

break;
case "welcome_message":
opentable($locale['HELP120']);
echo "
<div class='tbl2'><strong>".$locale['HELP121']."</strong></div>
<div class='tbl'><br />
".$locale['HELP122']."<br />
<br />
{SITETITLE} - ".$locale['HELP123'].".<br />
{SENDER} - ".$locale['HELP124'].".<br />
{RECEIVER} - ".$locale['HELP125'].".<br />
<br /><br /></div>
";
echo "<div class='tbl2'><strong>".$locale['HELP001']."</strong></div>
<div class='tbl'><br />".$locale['HELP002']."<br /></div>";
closetable();


break;
case "help_example_2":
	echo "";
break;
}

echo "<br />";

require_once TEMPLATES."window_footer.php";
?>