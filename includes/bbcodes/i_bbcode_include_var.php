<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: i_bbcode_include_var.php
| Version: Pimped Fusion v0.09.00
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

$__BBCODE__[] = 
array(
"description"		=>	$locale['bb_i_description'],
"value"			=>	"i",
"bbcode_start"		=>	"[i]",
"bbcode_end"		=>	"[/i]",
"usage"			=>	"[i]".$locale['bb_i_usage']."[/i]"
);
?>