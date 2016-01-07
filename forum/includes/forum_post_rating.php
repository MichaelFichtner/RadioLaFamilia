<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/includes/forum_post_rating.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: SoBeNoFear, PhAnToM, Fangree_Craig, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

include_once LOCALE.LOCALESET."forum/main.php";

add_to_head("<script type='text/javascript'>
function giveRating(post, from, to, type){
  $('#rb_' + post).html(\"<img src='".IMAGES."ajax-loader.gif' alt='' style='vertical-align:middle;' />\");
  $('#rb_' + post).load(\"".FORUM_INC."forum_post_rating_ajax.php\"+\"?post=\"+post+\"&from=\"+from+\"&to=\"+to+\"&type=\"+type+\"&sid=\"+Math.random());
}
</script>");

$loa_rates['rating_opacity'] = 0.6;
$loa_rates['boxover_ratings'] = 1;

add_to_head("
<style type='text/css'> .ratingbox { opacity:".$loa_rates['rating_opacity']." } </style>");

// Predefine cache
$post_rating_type_cache = '';
#$post_rating_cache = '';

// Cache post rating types
function cache_post_ratings_type() {
	global $post_rating_type_cache;
	$result = dbquery("SELECT type_id, type_name, type_icon FROM ".DB_POST_RATING_TYPES);
	if (dbrows($result)) {
		$post_rating_type_cache = array();
		while ($data = dbarray($result)) {
			$post_rating_type_cache[] = array(
				"type_id" => $data['type_id'],
				"type_name" => $data['type_name'],
				"type_icon" => $data['type_icon']
			);
		}
	} else {
		$post_rating_type_cache = array();
	}
}

/*
// Cache post ratings
function cache_post_rating($thread) {
	global $post_rating_cache;
	$result = dbquery("SELECT rate_id, rate_type, rate_user, rate_post, rate_by FROM ".DB_POST_RATINGS." WHERE rate_thread='".$thread."'");
	if (dbrows($result)) {
		$post_rating_cache[$thread] = array();
		while ($data = dbarray($result)) {
			$post_rating_cache[$thread][] = array(
				"rate_id" => $data['rate_id'],
				"rate_type" => $data['rate_type'],
				"rate_user" => $data['rate_user'],
				"rate_post" => $data['rate_post'],
				"rate_by" => $data['rate_by']
			);
		}
	} else {
		$post_rating_cache[$thread] = array();
	}
}
*/

// Show post ratings
function post_ratings_do($post, $userfrom, $userto, $wrapper=true) {
	global $post_rating_type_cache; #, $post_rating_cache;
	$res = '';
	if (!$post_rating_type_cache) { cache_post_ratings_type(); }

	if (($userfrom !== $userto) && is_array($post_rating_type_cache) && count($post_rating_type_cache)) {
		if($wrapper) $res .= "<div style='float:right;' id='rb_".$post."'>";
		$res .= "<span id='ratename$post' class='small'></span>&nbsp;\n";
		
		foreach ($post_rating_type_cache as $type) {

		if(!dbrows(dbquery("SELECT * from ".DB_POST_RATINGS." where rate_type='".$type['type_id']."' and rate_user='$userto' and rate_post='$post' and rate_by='$userfrom'"))){
				$res .= "<span onMouseOver='document.getElementById(\"ratename$post\").innerHTML=\"".stripslash(parseubb($type['type_name']))."\";' onMouseOut='document.getElementById(\"ratename$post\").innerHTML=\" \"' onclick='giveRating($post, $userfrom, $userto, ".$type['type_id'].");'><img src='".IMAGES."forum_post_ratings/".$type['type_icon']."' alt='".stripslash(parseubb($type['type_name']))."' title='".stripslash(parseubb($type['type_name']))."' style='vertical-align:middle;cursor:pointer;' /></span>\n";
		}

		}
		if($wrapper) $res .= "</div>\n";
	}
	return $res;
}

function post_ratings_show($post){
	global $locale, $loa_rates;
	$res = '';
	$result = dbquery("SELECT r.*, t.*, count(t.type_name) as total from ".DB_POST_RATINGS." r
	left join ".DB_POST_RATING_TYPES." t on r.rate_type=t.type_id
	where r.rate_post='".$post."' group by r.rate_type");
	if(dbrows($result)){
		$res .= "<div style='float:left;vertical-align:middle;'>\n";
		while($data = dbarray($result)){
			$user_res = dbquery("SELECT * from ".DB_POST_RATINGS." r
			left join ".DB_USERS." u on u.user_id=r.rate_by
			where r.rate_post='$post' and r.rate_type='".$data['rate_type']."'");
			$i = 0; $users = "<strong>".$locale['fpr101']."</strong> <br />";
			while($user_data = dbarray($user_res)){
				$users .= ($i !== 0 ? "<br />" : "").$user_data['user_name'];
				$i++;
			}
			$res .= "&nbsp;<span class='ratingbox small' onmouseover='this.style.opacity=\"1\"' onmouseout='this.style.opacity=\"".$loa_rates['rating_opacity']."\"' ".($loa_rates['boxover_ratings'] ? "title='header=[".parseubb($data['type_name'])."] body=[$users]'" : "")." style='vertical-align:middle;'><img src='".IMAGES."forum_post_ratings/".$data['type_icon']."' style='vertical-align:middle;' alt='' /> x ".$data['total']."</span>";
		}
		$res .= "</div>\n";
	}
	return $res;
}
?>