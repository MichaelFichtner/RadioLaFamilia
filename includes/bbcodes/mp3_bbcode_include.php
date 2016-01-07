<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: mp3_bbcode_include.php
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

$text = preg_replace('#\[mp3\](.*?)\[/mp3\]#si', '<strong>'.$locale['bb_mp3'].'</strong> <object type=\'application/x-shockwave-flash\' width=\'17\' height=\'17\' data=\''.INCLUDES.'bbcodes/mp3player/mp3player.swf?&amp;song_url=\1\'><param name=\'movie\' value=\''.INCLUDES.'bbcodes/mp3player/mp3player.swf?&amp;song_url=\1\'><param name=\'wmode\' value=\'transparent\'><param name=\'quality\' value=\'high\'><param name=\'bgcolor\' value=\'#eeeeee\'></object>', $text);
?>