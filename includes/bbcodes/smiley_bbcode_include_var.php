<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: smiley_bbcode_include_var.php
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
"description"		=>	$locale['bb_smiley_description'],
"value"			=>	"smiley",
"bbcode_start"		=>	"",
"bbcode_end"		=>	"",
"usage"			=>	$locale['bb_smiley_usage'],
"onclick"		=>	"return overlay(this, 'bbcode_smileys_list_".$textarea_name."', '".($p_data['panel_side']==0 ? "bottomright" : $p_data['panel_side']==1 ? "bottomleft" : "bottomright")."');",
"onmouseover"		=>	"",
"onmouseout"		=>	"",
"html_start"		=>	"<div id='bbcode_smileys_list_".$textarea_name."' class='tbl1 bbcode-popup' style='display:none;border:1px solid black;position:absolute;overflow:auto;width:400px;height:auto;' onclick=\"overlayclose('bbcode_smileys_list_".$textarea_name."');\">",
"includejscript"	=>	"",
"calljscript"		=>	"",
"phpfunction"		=>	"echo displaysmileys('$textarea_name', '$inputform_name');",
"html_middle"		=>	"",
"html_end"		=>	"</div>"
);
?>
