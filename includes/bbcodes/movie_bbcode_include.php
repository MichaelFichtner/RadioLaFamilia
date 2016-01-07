<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: movie_bbcode_include.php
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

$text = preg_replace('#\[movie=youtube\](.*?)\[/movie\]#si', '<strong>'.$locale['bb_movie_youtube'].':</strong><br /><object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/\1"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/\1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', $text);
$text = preg_replace('#\[movie=google\](.*?)\[/movie\]#si', '<strong>'.$locale['bb_movie_googlevideo'].':</strong><br /><object width="425" height="350"><param name="movie" value="http://video.google.com/googleplayer.swf?docId=\1"></param><param name="wmode" value="transparent"></param><embed src="http://video.google.com/googleplayer.swf?docId=\1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>', $text);
?>