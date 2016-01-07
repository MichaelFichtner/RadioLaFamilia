<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/ratings/ratings_forum.php
| Version: Pimped Fusion v0.09.00
| Authors: slaughter, Fangree_Craig
+----------------------------------------------------------------------------+
| Dynamic Star Rating Redux, Developed by Jordan Boesch, www.boedesign.com
| Licensed under Creative Commons
| http://creativecommons.org/licenses/by-nc-nd/2.5/ca/
| Used CSS from komodomedia.com.
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

include_once LOCALE.LOCALESET."ratings.php";

function ratings_forum($thread_id) {
	global $settings, $locale;
	$text = "";
	if($settings['forum_ratings'] && isnum($thread_id)) {
		$result = dbquery("SELECT SUM(rating_vote) AS sum, COUNT(rating_vote) AS count
		FROM ".DB_RATINGS." WHERE rating_type='F' AND rating_item_id='".(int)$thread_id."'");
		$data = dbarray($result);
		$rating = ($data['count'] ? $data['sum'] / $data['count'] : 0);

		if ($rating > 0) {
			$rounded = round($rating);
			$text = "<img src='".IMAGES."ratings/".$rounded.".gif' alt='".$locale['r130'].$rounded."' title='".
			$locale['r130'].round($rating, 2)." ".sprintf($locale['r135'], $data['count'])."' style='vertical-align:middle; border: 0;' />\n";
		}
	}
	return $text;
}

?>