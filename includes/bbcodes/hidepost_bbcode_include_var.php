<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: hide_bbcode_include_var.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: Fangree Craig, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if(isset($_GET['forum_id']) OR isset($_GET['thread_id']) 
OR stristr(FUSION_REQUEST, "/administration/") OR stristr(FUSION_REQUEST, "/guestbook/admin/")) { // Fix by slaughter for comments
$__BBCODE__[] = 
array(
"description"		=>	$locale["bb_hidepost_description"],
"value"			=>	"hidepost",
"bbcode_start"		=>	"[hidepost]",
"bbcode_end"		=>	"[/hidepost]",
"usage"			=>	"[hidepost]".$locale["bb_hidepost_usage"]."[/hidepost]"
);
}
?>