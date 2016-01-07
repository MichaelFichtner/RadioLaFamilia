<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin/forum_post.inc.php
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

$result = dbquery("SELECT * FROM ".DB_PREFIX."formulars");
$that = dbarray($result);

/* --------------------------------- CONFIG ------------------------------------------- */

$forum_id = $that['pr_forumid']; // This is the ID of the boardcategorie where the thread should be posted
$poster_id = $userdata['user_id']; // The member with this ID is the poster of the thread
$board_type = $that['pr_forumtype']; // 0=Standard || 1=Fusionboard  // Which Forum you use

/* --------------------------------- CONFIG END --------------------------------------- */


/* --------------------------------- DO NOT CHANGE SOMETHING UNDER THESE LINE --------- */

if(isset($_GET['id'])){
if (!isNum($_GET['id'])){ die("Access denied"); }

$result = dbquery("SELECT * FROM ".DB_PREFIX."bewerbung WHERE pr_id='".$_GET['id']."'");
$erg = dbarray($result);
$thread_titel = "Bewerbung von ".$erg['pr_bname'];
$nachricht = "Wir haben eine neue Bewerbung: \n";

$result = dbquery("SELECT * FROM ".DB_PREFIX."form_fields WHERE pr_toform = '1'");
while ($data = dbarray($result)){
	$value_norm = $erg['pr_'.$data['pr_name']];
	$value = htmlspecialchars($value_norm,ENT_QUOTES);
	$value = preg_replace("/&#039;/","", $value);
	$nachricht .= "\n".$data['pr_desc'].": ".$value." ";
}

/* This Code based on a Code from ghost2k from a post on the board of the German supportsite */

if ($board_type == "1"){

// v7 Fusionboard
$result1 = dbquery("INSERT INTO ".DB_THREADS." (forum_id, thread_subject, thread_author, thread_views, thread_lastpost, thread_lastpostid, thread_lastuser, thread_postcount, thread_poll, thread_sticky, thread_locked) VALUES('".$forum_id."', '".$thread_titel."', '".$poster_id."', '0', '".time()."', '0', '".$poster_id."', '1', '0', '0', '0')");
$thread_id = mysql_insert_id();
$result2 = dbquery("INSERT INTO ".DB_POSTS." (forum_id, thread_id, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_edituser, post_edittime) VALUES ('".$forum_id."', '".$thread_id."', '".$nachricht."', '1', '0', '".$poster_id."', '".time()."', '".USER_IP."', '0', '0')");
$post_id = mysql_insert_id();
$result3 = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='".time()."', forum_postcount=forum_postcount+1, forum_threadcount=forum_threadcount+1, forum_lastuser='".$poster_id."' WHERE forum_id='".$forum_id."'");
$result4 = dbquery("UPDATE ".DB_THREADS." SET thread_lastpostid='".$post_id."' WHERE thread_id='".$thread_id."'");
$result5 = dbquery("UPDATE ".DB_USERS." SET user_posts=user_posts+1 WHERE user_id='".$poster_id."'");

}else{

// v7 Standard
$result1 = dbquery("INSERT INTO ".DB_THREADS." (forum_id, thread_subject, thread_author, thread_views, thread_lastpost, thread_lastpostid, thread_lastuser, thread_postcount, thread_poll, thread_sticky, thread_locked) VALUES('".$forum_id."', '".$thread_titel."', '".$poster_id."', '0', '".time()."', '0', '".$poster_id."', '1', '0', '0', '0')");
$thread_id = mysql_insert_id();
$result2 = dbquery("INSERT INTO ".DB_POSTS." (forum_id, thread_id, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_edituser, post_edittime) VALUES ('".$forum_id."', '".$thread_id."', '".$nachricht."', '1', '0', '".$poster_id."', '".time()."', '".USER_IP."', '0', '0')");
$post_id = mysql_insert_id();
$result3 = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='".time()."', forum_postcount=forum_postcount+1, forum_threadcount=forum_threadcount+1, forum_lastuser='".$poster_id."' WHERE forum_id='".$forum_id."'");
$result4 = dbquery("UPDATE ".DB_THREADS." SET thread_lastpostid='".$post_id."' WHERE thread_id='".$thread_id."'");
$result5 = dbquery("UPDATE ".DB_USERS." SET user_posts=user_posts+1 WHERE user_id='".$poster_id."'");

}

if($result1 && $result2 && $result3 && $result4 && $result5){
	$status = "<center><font color='green'>Erfolgreich gepostet!</font></center><br />";
}else{
	$status = "<center><font color='red'>Posting fehlgeschlagen!</font></center><br />";
}

}


?>