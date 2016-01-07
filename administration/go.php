<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: go.php
| Version: Pimped Fusion v0.07.00
+----------------------------------------------------------------------------+
| based on PHP-Fusion CMS v7.01 by Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";
require_once INCLUDES."output_handling_include.php";
include THEME."theme.php";

if (!checkrights("SU") || !iADMIN) { redirect("../index.php"); }

ob_start();

$urlprefix="";
$url = BASEDIR."index.php";
if (isset($_GET['id']) && isnum($_GET['id'])) {
	$result = dbquery("SELECT submit_criteria FROM ".DB_SUBMISSIONS." WHERE submit_type='l' AND submit_id='".$_GET['id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$submit_criteria = unserialize($data['submit_criteria']);
		$urlprefix = (strpos($submit_criteria['link_url'], "http://") === false && strpos($submit_criteria['link_url'], "https://") === false) ? "http://" : ""; // Pimped
		$url = $submit_criteria['link_url'];
	}
}

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html>\n<head>\n";
echo "<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta http-equiv='refresh' content='2; url=".$urlprefix.$url."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<meta name='generator' content='Pimped-Fusion - Open Source Content Management System - pimped-fusion.net - v".$settings['version_pimp']."' />\n";
echo "<style type='text/css'>html, body { height:100%; }</style>\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' />\n";
if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "</head>\n<body class='tbl2 setuser_body'>\n";

echo "<table style='width:100%;height:100%'>\n<tr>\n<td>\n";

echo "<table cellpadding='0' cellspacing='1' width='80%' class='tbl-border center'>\n<tr>\n";
echo "<td class='tbl1'>\n<div style='text-align:center'><!--redirect_pre_logo--><br />\n";
echo "<img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."' /><br /><br />\n";

echo "<a href='".$urlprefix.$url."' rel='nofollow' target='_blank'>".sprintf($locale['global_500'],$urlprefix.$url)."</a>";

echo "</td>\n</tr>\n</table>\n";

echo "</td>\n</tr>\n</table>\n";

echo "</body>\n</html>\n";

$output = ob_get_contents();
ob_end_clean();
echo handle_output($output);

if (ob_get_length() !== FALSE){
	ob_end_flush();
}

if ($settings['login_method'] == "sessions") {
	session_write_close();
}

mysql_close($db_connect);

?>