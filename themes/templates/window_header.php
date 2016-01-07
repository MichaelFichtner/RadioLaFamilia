<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: themes/templates/window_header.php
| Version: Pimped Fusion v0.08.00
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
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

require_once INCLUDES."output_handling_include.php";
require_once THEME."theme.php";

if ($settings['maintenance'] == "1" && ((iMEMBER && $settings['maintenance_level'] == "1" && $userdata['user_id'] != "1") || ($settings['maintenance_level'] > "1" && $settings['maintenance_level'] > $userdata['user_level']))) { redirect(BASEDIR."maintenance.php"); }
if (iMEMBER) { $result = dbquery("UPDATE ".DB_USERS." SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".(int)$userdata['user_id']."'"); }

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>".$settings['sitename']." ".$settings['sitetitle']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='Language' content='".$locale['xml_lang']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<meta name='generator' content='Pimped-Fusion - Open Source Content Management System - v".$settings['version_pimp']."' />\n";
echo "<meta name='robots' content='index, follow' />\n";
echo "<link rel='stylesheet' href='".load_css_file()."' type='text/css' media='screen' />\n";
echo "<script type='text/javascript' src='".INCLUDES_JS."jscript.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES_JS."jquery.js'></script>\n";
require_once INCLUDES."header_includes.php";
if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "</head>\n<body>\n";

ob_start();
?>