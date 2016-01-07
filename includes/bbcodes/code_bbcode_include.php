<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: code_bbcode_include.php
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

if (defined("IS_FORUM") && IS_FORUM == true) global $data;

$code_count = substr_count($text, "[code]");
for ($i=0; $i < $code_count; $i++) {
	if (defined("IS_FORUM") && IS_FORUM == true && isset($data['post_id'])) {
	   $code_save = "<a href=\'".INCLUDES."bbcodes/code_bbcode_save.php?thread_id=".$_GET['thread_id']."&amp;post_id=".$data['post_id']."&amp;code_id=".$i."\'><img src=\'".INCLUDES."bbcodes/images/code_save.png\' alt=\'".$locale['bb_code_save']."\' title=\'".$locale['bb_code_save']."\' style=\'border:none\' /></a>&nbsp;&nbsp;";
	} else {
	   $code_save = "";
	}
	$text = preg_replace("#\[code\](.*?)\[/code\]#sie", "'<div class=\'code_bbcode\'><div class=\'tbl-border tbl2\' style=\'width:400px\'>".$code_save."<strong>".$locale['bb_code_code']."</strong></div><div class=\'tbl-border tbl1\' style=\'width:400px;white-space:nowrap;overflow:auto\'><code style=\'white-space:nowrap\'>'.formatcode('\\1').'<br /><br /><br /></code></div></div>'", $text, 1);
}
?>
