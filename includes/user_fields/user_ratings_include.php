<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_ratings_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: Fangree_Craig, PhAnToM, SoBeNoFear
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	//Nothing here
} elseif ($profile_method == "display") {
	add_to_head("<script src='".FORUM_INC."forum_post_rating_boxover.js' type='text/javascript'></script>");
	echo "<tr>\n";
	echo "<td width='50%' class='tbl1' style='text-align:center;vertical-align:top;'>
	<span style='font-size:12px;font-weight:bold;'>".$locale['user_ratings_101']."</span><br />";
	
	$recieves = dbquery("select *, count(t.type_name) as total from ".DB_POST_RATINGS." r
	left join ".DB_POST_RATING_TYPES." t on r.rate_type=t.type_id
	where r.rate_user='".$user_data['user_id']."' group by r.rate_type order by total desc");
	if(dbrows($recieves)){
		$counter = 0;
		while($recieve = dbarray($recieves)){
			$user_res = dbquery("select *, count(rate_by) as total from ".DB_POST_RATINGS." r
			left join ".DB_USERS." u on u.user_id=r.rate_by
			where r.rate_user='".$user_data['user_id']."' and r.rate_type='".$recieve['rate_type']."' group by r.rate_by order by total desc");
			$counter = 0; $users = "<strong>".$locale['user_ratings_102']." </strong><br />";
			while($user_d = dbarray($user_res)){
				$users .= ($counter !== 0 ? "<br />" : "").$user_d['user_name']." (".$user_d['total']."x)";
				$counter++;
			}
			if($counter !== "0") echo "<br />";
			echo "<span title='header=[".parseubb($recieve['type_name'])."] body=[$users]'><img src='".IMAGES."forum_post_ratings/".$recieve['type_icon']."' style='vertical-align:middle;' alt='' /> x ".$recieve['total']."</span>\n";
			$counter++;
		}
	} else {
	
		echo $locale['user_ratings_105'];
	
	}
	
	echo "</td>
	<td width='50%' class='tbl1' style='text-align:center;vertical-align:top;'><span style='font-size:12px;font-weight:bold;'>".$locale['user_ratings_103']."</span><br />";

	$sends = dbquery("select *, count(t.type_name) as total from ".DB_POST_RATINGS." r
	left join ".DB_POST_RATING_TYPES." t on r.rate_type=t.type_id
	where r.rate_by='".$user_data['user_id']."' group by r.rate_type order by total desc");
	if(dbrows($sends)){
		$counter = 0;
		while($send = dbarray($sends)){
			$user_res = dbquery("select *, count(rate_user) as total from ".DB_POST_RATINGS." r
			left join ".DB_USERS." u on u.user_id=r.rate_user
			where r.rate_by='".$user_data['user_id']."' and r.rate_type='".$send['rate_type']."' group by r.rate_user order by total desc");
			$counter = 0; $users = "<strong>".$locale['user_ratings_104']." </strong><br />";
			while($user_d = dbarray($user_res)){
				$users .= ($counter !== 0 ? "<br />" : "").$user_d['user_name']." (".$user_d['total']."x)";
				$counter++;
			}
			if($counter !== "0") echo "<br />";
			echo "<span title='header=[".parseubb($send['type_name'])."] body=[$users]'><img src='".IMAGES."forum_post_ratings/".$send['type_icon']."' style='vertical-align:middle;' alt='' /> x ".$send['total']."</span>\n";
			$counter++;
		}
	} else {
	
		echo $locale['user_ratings_105'];
	
	}
	
	echo "</td>
	</tr>\n";
	
} elseif ($profile_method == "validate_insert") {
	//Nothing here
} elseif ($profile_method == "validate_update") {
	//Nothing here
}
?>