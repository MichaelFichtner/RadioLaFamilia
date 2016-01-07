<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: movie_bbcode_include_var.php
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
"description"		=>	$locale["bb_movie_description"],
"value"			=>	"movie",
"bbcode_start"		=>	"[movie=]",
"bbcode_end"		=>	"[/movie]",
"usage"			=>	"[movie=".$locale['bb_movie_type']."]".$locale["bb_movie_usage"]."[/movie]",
'onclick'		=>	"return overlay(this, 'bbcode_movie_".$textarea_name."', 'rightbottom');",
'onmouseover'		=>	"",
'onmouseout'		=>	"",
'html_start'		=>	"<div id='bbcode_movie_".$textarea_name."' class='tbl1 bbcode-popup' style='display: none; border:1px solid black; position: absolute; width: auto; height: auto; text-align: center' onclick=\"overlayclose('bbcode_movie_".$textarea_name."');\">",
'includejscript'	=>	"",
'calljscript'		=>	"",
'phpfunction'		=>	"",
'html_middle'		=>	"<input type='button' value='YouTube Video' class='button' style='width:100px' onclick=\"addText('".$textarea_name."', '[movie=youtube]', '[/movie]', '".$inputform_name."');return false;\" /><br /><input type='button' value='Google Video' class='button' style='width:100px' onclick=\"addText('".$textarea_name."', '[movie=google]', '[/movie]', '".$inputform_name."');return false;\" />",
'html_end'		=>	"</div>"
);
?>