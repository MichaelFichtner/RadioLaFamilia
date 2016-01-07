<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/ratings/ratings_type_stars.php
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
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."ratings.php";

function get_rating($id, $rtype) {
	$total = 0;
	$rows = 0;
	
	$sel = dbquery("SELECT rating_vote FROM ".DB_RATINGS." WHERE rating_item_id='".(int)$id."' AND rating_type="._db($rtype));
	
	if(dbrows($sel) != 0){
		while($data = dbarray($sel)) {	
			$total = $total + $data['rating_vote'];
			$rows++;
    	}
		return ($total/$rows);
	} else {
		return '0';
	}
	return '0';
}

function get_votes($id, $rtype){
    global $locale;
	
	$sel = dbquery("SELECT rating_vote FROM ".DB_RATINGS." WHERE rating_item_id='".(int)$id."' AND rating_type="._db($rtype));
	$rows = mysql_num_rows($sel);
	
	if ($rows == 0) {
		$votes = "0 ".$locale['r132']."";
	} else if($rows == 1) {
		$votes = "1 ".$locale['r133']."";
	} else if($rows > 1 && $rows < 5) {
		$votes = $rows." ".$locale['r134']."";
	} else {
		$votes = $rows." ".$locale['r132']."";
	}
	return $votes;
}

function showratings($rtype, $id, $dummy1 = "3rd/4th/5th parameters are needed in oldstyle rating sys", $dummy2 = 0, $dummy3 = 0) {
	global $show5, $showPerc, $showVotes, $static, $user, $userdata, $locale, $settings;
	
	if(($settings['ratings_enabled'] == "1" && $rtype != "F") || ($settings['forum_ratings'] == "1" && $rtype == "F")) {
	
	$show5 = false; $showPerc = false; $showVotes = false; $static = NULL;
	
	$boardrate = $rtype == "F" ? true : false;
	
	add_to_head("<link href='".INCLUDES_RATING."css/rating_style.css' rel='stylesheet' type='text/css' media='all' />");
	add_to_head("<script type='text/javascript' src='".INCLUDES_RATING."js/rating_update.js'></script>");
	
	if(!$boardrate) opentable($locale['r100']);
	// Check if they have already voted...
	$text = '';
	
	if (iMEMBER) { $user = $userdata['user_id']; } else { $user = "0"; }
	
	$sel = dbquery("SELECT rating_item_id FROM ".DB_RATINGS."
			WHERE rating_ip="._db(USER_IP)." AND rating_item_id='".(int)$id."' AND rating_type="._db($rtype));

	$rating = get_rating($id, $rtype);
	$rating_perc = round(($rating * 20), 2)."%";
	$rating_average = round($rating, 2);
	
	if(dbrows($sel) != 0 || $static == 'novote' || !iMEMBER) {
	
	echo "<table class='tbl2' cellspacing='0' border='0' width='100%' style='margin: 0.7em 0em 0.7em 0em;'>\n";
    echo "<tr><td width='50%' align='right' valign='middle'>\n";
	echo '<div class="rated_text">';
	echo '<strong>'.$locale['r130'].'</strong>';
	echo ' ('.$locale['r131'].' <span id="showvotes_'.$id.'" class="votesClass">'.get_votes($id, $rtype).'</span>)';
	echo '<span>&nbsp;&nbsp;</span></div>';
	echo "</td><td width='50%' align='left'>\n";
	
	echo '<ul class="star-rating2" id="rater_'.$id.'">';
	echo '<li class="current-rating" style="width:'.$rating_perc.';" id="ul_'.$id.'"></li>';
	echo "<li><a onclick='return true;' href='#' title='".$locale['r136']."' class='one-star' >1</a></li>";
	echo "<li><a onclick='return true;' href='#' title='".$locale['r137']."' class='two-stars'>2</a></li>";
	echo "<li><a onclick='return true;' href='#' title='".$locale['r138']."' class='three-stars'>3</a></li>";
	echo "<li><a onclick='return true;' href='#' title='".$locale['r139']."' class='four-stars'>4</a></li>";
	echo "<li><a onclick='return true;' href='#' title='".$locale['r140']."' class='five-stars'>5</a></li>";
	echo "</ul>";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "<div align='center' id='loading_".$id."'></div>";
	if(!$boardrate) {
	echo "<table cellpadding='0' width='100%' cellspacing='1' class='tbl2 center'>\n";
	echo "	<tr>\n";
	echo "		<td class='tbl2'><center><strong>".$locale['r150']."</strong>".$rating_average." ".$locale['r151']."</center></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	}
		
	} else {
	
    echo "<table class='tbl2' cellspacing='0' border='0' width='100%' style='margin: 0.7em 0em 0.7em 0em;'>\n";
    echo "<tr><td width='50%' align='right'>\n";
	echo '<div class="rated_text">';
		$show5bool = 'false'; $showPercbool = 'false'; $showVotesbool = 'false';
	echo '<strong>'.$locale['r130'].'</strong><span>&nbsp;&nbsp;</span>';
	echo '</div>';
    echo "</td><td width='50%' align='left'>\n";
	
	echo '<ul class="star-rating" id="rater_'.$id.'">';		
    echo '<li class="current-rating" style="width:'.$rating_perc.';" id="ul_'.$id.'"></li>';
	
	$base = INCLUDES.'ratings/';
	
    echo '<li><a onclick="rate(\'1\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.',\''.$rtype.'\', \''.$base.'\'); return false;" href="'.INCLUDES.'ratings/ratings_type_stars_process.php?id='.$id.'&rating=1&rtype='.$rtype.'" title="'.$locale['r136'].'" class="one-star" >1</a></li>';
    echo '<li><a onclick="rate(\'2\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.',\''.$rtype.'\', \''.$base.'\'); return false;" href="'.INCLUDES.'ratings/ratings_type_stars_process.php?id='.$id.'&rating=2&rtype='.$rtype.'" title="'.$locale['r137'].'" class="two-stars">2</a></li>';
    echo '<li><a onclick="rate(\'3\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.',\''.$rtype.'\', \''.$base.'\'); return false;" href="'.INCLUDES.'ratings/ratings_type_stars_process.php?id='.$id.'&rating=3&rtype='.$rtype.'" title="'.$locale['r138'].'" class="three-stars">3</a></li>';
    echo '<li><a onclick="rate(\'4\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.',\''.$rtype.'\', \''.$base.'\'); return false;" href="'.INCLUDES.'ratings/ratings_type_stars_process.php?id='.$id.'&rating=4&rtype='.$rtype.'" title="'.$locale['r139'].'" class="four-stars">4</a></li>';
    echo '<li><a onclick="rate(\'5\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.',\''.$rtype.'\', \''.$base.'\'); return false;" href="'.INCLUDES.'ratings/ratings_type_stars_process.php?id='.$id.'&rating=5&rtype='.$rtype.'" title="'.$locale['r140'].'" class="five-stars">5</a></li>';
	echo '</ul>';
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "<div align='center' id='loading_".$id."'></div>";
	if(!$boardrate) {
	echo "<table cellpadding='0' width='100%' cellspacing='1' class='tbl2 center'>\n";
	echo "	<tr>\n";
	echo "		<td class='tbl2'><center><strong>".$locale['r150']."</strong>".$rating_average." ".$locale['r151']."</center></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	}
	}
	if(!$boardrate) closetable();
	}
}

?>