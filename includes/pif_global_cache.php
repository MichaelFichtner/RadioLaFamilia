<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: pif_global_cache.php
| Version: Pimped Fusion v0.08.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

function pif_cache($cache) {
global $pif_cache, $pif_global;

switch($cache)
{
case "total_reg_users":
  	if(!array_key_exists("total_reg_users", $pif_cache) || $pif_cache['total_reg_users'] == '') {
		$in = ""; foreach($pif_global['visible_members'] as $value) { $in .= ($in != '' ? ", " : ""); $in .= $value; }
		$pif_cache['total_reg_users'] = number_format(dbcount("(user_id)", DB_USERS, "user_status IN(".$in.")"));
	}
break;
case "newest_reg_member":
	if(!array_key_exists("newest_reg_member", $pif_cache) || !is_array($pif_cache['newest_reg_member'])) {
		$pif_cache['newest_reg_member'] = array();
		list($pif_cache['newest_reg_member']['user_id'], $pif_cache['newest_reg_member']['user_name'], $pif_cache['newest_reg_member']['user_status']) = 
		dbarraynum(dbquery("SELECT user_id, user_name, user_status FROM ".DB_USERS." WHERE user_status='0' ORDER BY user_joined DESC LIMIT 0,1"));
	}
break;
case "online_users":
	if(!array_key_exists("online_users", $pif_cache) || !is_array($pif_cache['online_users'])) {
		$pif_cache['online_users'] = array();
		$result = dbquery("SELECT ton.online_user, tu.user_id, tu.user_name, tu.user_status, tu.user_level FROM ".DB_ONLINE." ton
		LEFT JOIN ".DB_USERS." tu ON ton.online_user=tu.user_id");
		$pif_cache['online_users']['guests'] = 0; $pif_cache['online_users']['members'] = array();
		while ($data = dbarray($result)) {
			if ($data['online_user'] == "0") {
				$pif_cache['online_users']['guests']++;
			} else {
				array_push($pif_cache['online_users']['members'],
					array("user_id" => $data['user_id'], "user_name" => $data['user_name'], "user_status" => $data['user_status'], "user_level" => $data['user_level']));
			}
		}
	}
break;
default:
	echo "Cache Error";
}

}
?>