<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/ratings/ratings_type_stars_process.php
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
require_once "../../maincore.php";

if($settings['ratings_enabled'] == "1" || $settings['forum_ratings'] == "1") {

header("Cache-Control: no-cache");
header("Pragma: nocache");

// escape variables
function escape($val) {
	$val = trim($val);
	if(get_magic_quotes_gpc()) { $val = stripslashes($val); }
	return mysql_real_escape_string($val);
}

// IF JAVASCRIPT IS ENABLED

if($_POST) {

	$id = escape($_POST['id']);
	$rating = (int)$_POST['rating'];
    $rtype = $_POST['rtype'];
	
	if (iMEMBER) { $userdata_id = $userdata['user_id']; } else { $userdata_id = "0"; }
	
	if($rating <= 5 && $rating >= 1) {
	
		if ((dbarray(dbquery("SELECT rating_item_id FROM ".DB_RATINGS." WHERE rating_item_id='".(int)$id."' AND rating_type="._db($rtype)." AND rating_ip="._db(USER_IP)." AND rating_user = '".(int)$userdata_id."'"))) || isset($_COOKIE['has_voted_'.$id]) || !iMEMBER) {
		
			echo 'already_voted'; exit;
			
		} else {
		
			$result = dbquery("INSERT INTO ".DB_RATINGS." (rating_item_id, rating_type, rating_user, rating_vote, rating_datestamp, rating_ip) VALUES ('".(int)$id."', "._db($rtype).", '".(int)$userdata_id."', '".(int)$rating."', '".time()."', "._db(USER_IP).")");
			
			$total = 0;
			$rows = 0;
			
			$sel = dbquery("SELECT rating_vote FROM ".DB_RATINGS." WHERE rating_item_id = '".(int)$id."' AND rating_type = "._db($rtype));
			if(dbrows($sel) != 0) {
			while($data = dbarray($sel)){
			
				$total = $total + $data['rating_vote'];
				$rows++;
			}
			
			$perc = ($total/$rows) * 20;
			
			echo round($perc,2); exit;
			//echo round($perc/5)*5;
		    } else { return '0'; exit; }
		}	
	}
}

// IF JAVASCRIPT IS DISABLED

if($_GET){

	$id = escape(isnum($_GET['id']));
	$rating = (int) $_GET['rating'];
	$rtype = $_GET['rtype'];
	
	if (iMEMBER) { $userdata_id = $userdata['user_id']; } else { $userdata_id = "0"; }
	
	// If you want people to be able to vote more than once, comment the entire if/else block block and uncomment the code below it.
	
	if($rating <= 5 && $rating >= 1){
	
		if ((dbarray(dbquery("SELECT rating_item_id FROM ".DB_RATINGS." WHERE rating_item_id='".(int)$id."' AND rating_type="._db($rtype)." AND rating_ip="._db(USER_IP)." AND rating_user = '".(int)$userdata_id."'"))) || isset($_COOKIE['has_voted_'.$id]) || !iMEMBER) {
		
			echo 'already_voted'; exit;
			
		} else {
				
			$result = dbquery("INSERT INTO ".DB_RATINGS." (rating_item_id, rating_type, rating_user, rating_vote, rating_datestamp, rating_ip) VALUES ('".(int)$id."', "._db($rtype).", '".(int)$userdata_id."', '".(int)$rating."', '".time()."', "._db(USER_IP).")");
			
		}
		
		header("Location:".REDIRECT_TO.""); die;
		
	} else { echo 'You cannot rate this more than 5 or less than 1 <a href="'.REDIRECT_TO.'">back</a>'; }
	
}

}
?>