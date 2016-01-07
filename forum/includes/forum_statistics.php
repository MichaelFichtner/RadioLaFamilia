<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/includes/forum_statistics.php
| Version: Pimped Fusion v0.09.01
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }
if (!defined("INCLUDE_FORUM_STATISTICS")) { die("Access Denied"); }

include LOCALE.LOCALESET."forum/statistics.php";

if(!function_exists("array_combine")){
	function array_combine($array1, $array2) {
		$combined_array = array();
		$array1 = array_values($array1);
		$array2 = array_values($array2);
		foreach($array1 as $key => $value)
			$combined_array[(string)$value] = $array2[$key];
		return $combined_array;
	}
}

function update_stats() {
	global $stats;
	$values = implode("|", $stats);

	dbquery("UPDATE ".DB_SETTINGS." SET settings_value="._db($values)." WHERE settings_name='forum_statistics'");
}

$stat_values = explode("|", $settings['forum_statistics']);
$stat_keys = array("max_online_users");
$stats = array_combine($stat_keys, $stat_values);

$rowspan = "1";

	// Forum Stats
	if($settings['forum_statistics_forumstats']) {
		list($posts) = dbarraynum(dbquery("SELECT SUM(forum_postcount) FROM ".DB_FORUMS));
		$posts = empty($posts) ? 0 : $posts;
		list($threads) = dbarraynum(dbquery("SELECT SUM(forum_threadcount) FROM ".DB_FORUMS));
		$threads = empty($threads) ? 0 : $threads;
		list($age) = dbarraynum(dbquery("SELECT user_joined from ".DB_USERS." WHERE user_id=1"));
		$age = empty($age) ? 0 : $age;
		$threadspday = round_num($threads/((time() - $age)/(3600*24)));
		$postspday = round_num($posts/((time() - $age)/(3600*24)));
		$rowspan++;
	}
	
	// Top Posters
	if($settings['forum_statistics_topposters']) {
		list($tposter_id, $tposter_name, $tposter_status, $tposter_posts) = dbarraynum(dbquery("SELECT user_id, user_name, user_status, user_posts FROM ".DB_USERS." ORDER BY user_posts DESC LIMIT 1"));
		list($aposter_id, $aposter_name, $aposter_status, $aposter_ppday) = dbarraynum(dbquery("SELECT user_id, user_name, user_status, (user_posts/((".time()."-user_joined)/(24*3600))) FROM ".DB_USERS." WHERE user_joined < (".time()."-(3600*24)) ORDER BY user_posts DESC LIMIT 1"));
		$rowspan++;
	}
	
	// User Stats
	if($settings['forum_statistics_userstats']) {
		pif_cache("online_users");
		$total_online = $pif_cache['online_users']['guests'] + count($pif_cache['online_users']['members']);
		list($max_online, $max_online_time) = explode(":", $stats['max_online_users']);
		if($total_online > $max_online) {
			$stats['max_online_users'] = $total_online.":".time();
			update_stats();
			$max_online = $total_online;
			$max_online_time = time();
		}
		$rowspan++;
	}
	
	// Members Today Online
	if($settings['forum_statistics_todayonline']) {
	$result = dbquery("SELECT user_id, user_name, user_level, user_status FROM ".DB_USERS." WHERE user_lastvisit > UNIX_TIMESTAMP(CURDATE()) AND user_status = '0' ORDER BY user_lastvisit DESC");
	$today_rows = dbrows($result);
	$today_online = array();
	if ($today_rows) {
		while ($data = dbarray($result)) {
			$today_online[] = array("user_id" => $data['user_id'], "user_name" => $data['user_name'], "user_level" => $data['user_level']);
		
		}
	}
	$rowspan++;
	}
	
	// Today's birthday
	if($settings['forum_statistics_birthday']) {
		$today_birthday = "";
		$birthday = false;
		if(iMEMBER) {
			if (isset($userdata['user_birthdate'])) { $birthday = true; }
		} else {
			$result = dbquery("SELECT field_name FROM ".DB_USER_FIELDS." WHERE field_name='user_birthdate'");
			$birthday = dbresult($result,0);
		}
	
		if($birthday) {
			$result = dbquery("SELECT user_id, user_name, user_level, user_status, user_birthdate
				FROM ".DB_USERS." WHERE user_birthdate LIKE '____-".date("m")."-".date("d")."'");
			$birthday_rows = dbrows($result);
			if ($birthday_rows) {
				while ($data = dbarray($result)) {
					
					$birthdate = explode("-", $data['user_birthdate']);
					$year = date("Y") - $birthdate[0];
					
					if($today_birthday == "") {
						if($birthday_rows == 1) {
							$today_birthday .= $locale['forum_stats_114'];
						} else {
							$today_birthday .= sprintf($locale['forum_stats_115'], $birthday_rows);
						}
					} else {
						$today_birthday .= ", ";
					}
					$today_birthday .= profile_link($data['user_id'], $data['user_name'], $data['user_status'], "", $data['user_name'], (isset($data['user_level']) && $data['user_level'] > nMEMBER ? "font-weight: bold;" : ""))." (".$year.")";
				}
				$rowspan++;
			}
		}
	}

	// SHOW STATS
	$class = "0";
	
	opentable($locale['forum_stats_100']);
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n\t<tr>
		<th class='forum-caption' width='1%' style='white-space: no-wrap;' rowspan='".$rowspan."'><img alt='".$locale['forum_stats_101']."' src='".IMAGES."forum/forum_stats.png' /></th></tr>";
	
	// Forum Stats
	if($settings['forum_statistics_forumstats']) {
		$class_color = ($class % 2 == 0 ? "tbl1" : "tbl2"); $class++;
		echo "
		<tr>
			<td class='".$class_color."'>
				<strong>".number_format($threads)."</strong> ".$locale['forum_stats_102']." ::
				<strong>".number_format($posts)."</strong> ".$locale['forum_stats_104']." ::
				<strong>".$threadspday."</strong> ".$locale['forum_stats_103']." ::
				<strong>".$postspday."</strong> ".$locale['forum_stats_105']."
			</td>
		</tr>\n";
	}
	
	// Top Posters
	if($settings['forum_statistics_topposters']) { // Pimped: profile_link added
		$class_color = ($class % 2 == 0 ? "tbl1" : "tbl2"); $class++;
		echo "<tr>
			<td class='".$class_color."'>
				".$locale['forum_stats_106'].": ".profile_link($tposter_id, $tposter_name, $tposter_status)." (".$tposter_posts." ".$locale['forum_stats_104'].") ::
				".$locale['forum_stats_107'].": ".profile_link($aposter_id, $aposter_name, $aposter_status)." (".round($aposter_ppday, 2)." ".$locale['forum_stats_105'].")
			</td>
		</tr>\n";
	}
	
	// User Stats
	if($settings['forum_statistics_userstats']) { // Pimped: profile_link added
		$class_color = ($class % 2 == 0 ? "tbl1" : "tbl2"); $class++;
		pif_cache("total_reg_users");
		pif_cache("newest_reg_member");
		echo "<tr>
			<td class='".$class_color."'>
			".$locale['forum_stats_109'].": <strong>".$pif_cache['total_reg_users']."</strong> :: ".$locale['forum_stats_111'].": ".profile_link($pif_cache['newest_reg_member']['user_id'], $pif_cache['newest_reg_member']['user_name'], $pif_cache['newest_reg_member']['user_status'])."<br/>
			".$locale['forum_stats_108'].": <strong>".$pif_cache['online_users']['guests']."</strong> ".$locale['forum_stats_113'].", <strong>".count($pif_cache['online_users']['members'])."</strong> ".$locale['forum_stats_112'].":\n ".user_list(0, $pif_cache['online_users']['members'])."<br/>
			".sprintf($locale['forum_stats_110'], "<strong>".$max_online."</strong>", showdate("forumdate", $max_online_time))."
			</td>
		</tr>\n";
	}
	
	// Members Today Online
	if($settings['forum_statistics_todayonline']) {
	$class_color = ($class % 2 == 0 ? "tbl1" : "tbl2"); $class++;
	$user_list = user_list(0, $today_online);
	echo "<tr>
			<td class='".$class_color."'>".($user_list != "" ? ( $today_rows > 1 ? sprintf($locale['forum_stats_116'], $today_rows) : $locale['forum_stats_117']).$user_list : $locale['forum_stats_118'])."</td>
		</tr>\n";
	}

	// Members Today Birthday
	if($settings['forum_statistics_birthday']) {
		if($today_birthday != "") {
			$class_color = ($class % 2 == 0 ? "tbl1" : "tbl2"); $class++;
			echo "<tr><td class='".$class_color."'>".$today_birthday."</td></tr>\n";
		}
	}

	echo "</table>\n";
	
	closetable();

?>