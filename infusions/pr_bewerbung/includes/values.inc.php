<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (c) 2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: includes/values.inc.php
| pr_Bewerbungsscript v2.00
| Author: PrugnatoR
| URL: http://www.prugnator.de
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

if (!defined("IN_FUSION")) { die("Access Denied"); }

// Username
$username = (iUSER ? $userdata['user_name'] : "");

// Useremail
$useremail = (iUSER ? $userdata['user_email'] : "");

// Userbirthday
if(iUSER && $userdata['user_birthdate'] != "0000-00-00" && $userdata['user_birthdate'] != ""){
	$user_birthdate = explode("-", $userdata['user_birthdate']);
	$user_month = $user_birthdate['1'];
	$user_day = $user_birthdate['2'];
	$user_year = $user_birthdate['0'];
	$userbirthday = $user_day.".".$user_month.".".$user_year;
}else{
	$userbirthday = "DD.MM.YYYY";
}

// UserID
$userid = (iUSER ? $userdata['user_id'] : "");

?>