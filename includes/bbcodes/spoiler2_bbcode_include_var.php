<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: spoiler2_bbcode_include_var.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Author: Valerio Vendrame (lelebart)
| Co-Author: slaughter (some minor modifications)
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

$__BBCODE__[] = 
array(
"description"		=>	$locale["bb_spoiler2_description"],
"value"			=>	"spoiler2",
"bbcode_start"		=>	"[spoiler2]",
"bbcode_end"		=>	"[/spoiler2]",
"usage"			=>	"[spoiler2]".$locale["bb_spoiler2_usage"]."[/spoiler2]"
);
?>