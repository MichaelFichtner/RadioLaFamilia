<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: bbcode_include.php
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

$text = preg_replace('#\[tlen\](.*?)\[/tlen\]#si', '<strong>'.$locale['bb_tlen_usage'].':</strong> <img src=\'http://status.tlen.pl/?u=\1&amp;t=1\' alt=\'\1\' border=\'0\' style=\'vertical-align:middle\'><a href=\'tlen:tlen://chat|\1|/\' target=\'_blank\'>\1</a>', $text);
?>