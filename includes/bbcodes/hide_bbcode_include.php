<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: hide_bbcode_include.php
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

if (iADMIN) {
	$text = preg_replace('#\[hide\](.*?)\[/hide\]#si', '<div class=\'quote\'><strong>'.$locale['bb_hide'].'</strong><br /><span style=\'color:red;font-weight:bold\'>\1</span></div>', $text);
} else {
	$text = preg_replace('#\[hide\](.*?)\[/hide\]#si', '', $text);
}
?>