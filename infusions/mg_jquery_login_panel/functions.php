<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: functions.php
| Author: MarcusG
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

function daytime() {
	global $locale;
	if (date("H") <= 11) {
		return $locale['JQL_050']; // Guten Morgen
	} elseif (date("H") <= 13) {
		return $locale['JQL_051']; // Mahlzeit
	} elseif (date("H") <= 17) {
		return $locale['JQL_052']; // Guten Tag
	} elseif (date("H") <= 20) {
		return $locale['JQL_053']; // Guten Abend
	} else {
		return $locale['JQL_054']; // Gute Nacht
	}
}

function nmember_count() {
	return dbcount("(user_id)", DB_USERS, "user_status='2'");
}

function submiss_count($i) {
	return dbcount("(submit_id)",DB_SUBMISSIONS,"submit_type='$i'");
}
?>