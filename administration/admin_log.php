<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: admin_log.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";
require_once TEMPLATES."admin_header.php";
include LOCALE.LOCALESET."admin/admin_log.php";

if (!checkrights("AL") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

function getusername($id) {
	$result = dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id="._db($id));
	$data = dbarray($result);
	return $data['user_name'];
}

function getuserstatus($id) {
	$result = dbquery("SELECT user_status FROM ".DB_USERS." WHERE user_id="._db($id));
	$data = dbarray($result);
	return $data['user_status'];
}

opentable($locale['log100']);

$limit = 50; //rowstar limit

$get_section = array('forum', 'admin-1', 'admin-2', 'admin-3', 'admin-4', 'registration', 'cleanup');

if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
$_GET['rowstart'] = $_GET['page'] > 0 ? ($_GET['page']-1) * $limit : "0";

if (!isset($_GET['section']) || !in_array($_GET['section'], $get_section)) { $_GET['section'] = "forum"; }

// Navigation
echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
echo "<td align='center' width='15%' class='tbl1'><span class='small'>\n";
echo ($_GET['section'] == "forum" ? "<strong>".$locale['log101']."</strong>" : "<a href='admin_log.php".$aidlink."&amp;section=forum'>".$locale['log101']."</a>");
echo "</span></td>\n
<td align='center' width='15%' class='tbl2'><span class='small'>\n";
echo ($_GET['section'] == "admin-1" ? "<strong>".$locale['log102']."</strong>" : "<a href='admin_log.php".$aidlink."&amp;section=admin-1'>".$locale['log102']."</a>");
echo "</span></td>\n
<td align='center' width='15%' class='tbl1'><span class='small'>\n";
echo ($_GET['section'] == "admin-2" ? "<strong>".$locale['log103']."</strong>" : "<a href='admin_log.php".$aidlink."&amp;section=admin-2'>".$locale['log103']."</a>");
echo "</span></td>\n
<td align='center' width='15%' class='tbl2'><span class='small'>\n";
echo ($_GET['section'] == "admin-3" ? "<strong>".$locale['log104']."</strong>" : "<a href='admin_log.php".$aidlink."&amp;section=admin-3'>".$locale['log104']."</a>");
echo "</span></td>\n
<td align='center' width='15%' class='tbl1'><span class='small'>\n";
echo ($_GET['section'] == "admin-4" ? "<strong>".$locale['log105']."</strong>" : "<a href='admin_log.php".$aidlink."&amp;section=admin-4'>".$locale['log105']."</a>");
echo "</span></td>\n
<td align='center' width='15%' class='tbl2'><span class='small'>\n";
echo ($_GET['section'] == "registration" ? "<strong>"."Registration"."</strong>" : "<a href='admin_log.php".$aidlink."&amp;section=registration'>"."Registration"."</a>");
if(iSUPERADMIN) {
echo "</span></td>\n
<td align='center' width='15%' class='tbl1'><span class='small'>\n";
echo ($_GET['section'] == "cleanup" ? "<strong>".$locale['log106']."</strong>" : "<a href='admin_log.php".$aidlink."&amp;section=cleanup'>".$locale['log106']."</a>");
}
echo "</span></td>\n";
echo "</tr></table><br /><br />";
// Navigation end


// Delete Logs
if($_GET['section'] == "cleanup") {

if(isset($_POST['cleanup']) && iSUPERADMIN){
	$time_kind = isset($_POST['time']) ? $_POST['time'] : '';
	$time_del = time(); $log = "log_all";
	if($time_kind==="w") { $time_del=$time_del-604800; $log = "log_week"; } // week
	if($time_kind==="m") { $time_del=$time_del-2592000; $log = "log_month"; } // month
	if($time_kind==="y") { $time_del=$time_del-31536000; $log = "log_year"; } // year

	$result = dbquery("DELETE FROM ".DB_ADMIN_LOG." WHERE datestamp < '".$time_del."'");
	log_admin_action("admin-2", "admin_del_log", "", "", $log);
	echo "<p>".$locale['log_delete']."</p><br />";
}

if(iSUPERADMIN){
	echo "<form name='cleanup' method='post' action='".FUSION_SELF.$aidlink."&amp;section=".$_GET['section']."'>\n";
	echo $locale['log_cleanup']." \n";
	echo "<select name='time' class='textbox'>\n";
	echo "<option value=''>".$locale['log_all']."</option>\n";
	echo "<option value='w'>".$locale['log_week']."</option>\n"; 
	echo "<option value='m'>".$locale['log_month']."</option>\n"; 
	echo "<option value='y'>".$locale['log_year']."</option>\n"; 
	echo "</select>\n";
	echo "<input type='submit' name='cleanup' value='".$locale['log_cleanup']."' class='button' onclick=\"return confirm('".$locale['log_confirm']."');\">";
	echo "</form><br />\n";
} else {
	echo $locale['log_super'];
}


} else {


if($_GET['section'] == "admin-1" || $_GET['section'] == "admin-2" || $_GET['section'] == "admin-3" || $_GET['section'] == "admin-4") {

	$r = dbquery("SELECT log_id FROM ".DB_ADMIN_LOG." WHERE cat = '".$_GET['section']."'");	
	$rows = dbrows($r);
	$result = dbquery("SELECT tm.*, tu.user_id,user_name,user_status FROM ".DB_ADMIN_LOG." tm
	INNER JOIN ".DB_USERS." tu ON tm.u_id=tu.user_id 
	WHERE tm.cat = '".$_GET['section']."'
	ORDER BY datestamp DESC LIMIT ".(int)$_GET['rowstart'].",".(int)$limit);
	
} elseif($_GET['section'] == "registration") {

	$r = dbquery("SELECT log_id FROM ".DB_ADMIN_LOG." WHERE cat = 'registration'");	
	$rows = dbrows($r);
	$result = dbquery("SELECT tm.* FROM ".DB_ADMIN_LOG." tm
	WHERE tm.cat = '".$_GET['section']."'
	ORDER BY datestamp DESC LIMIT ".(int)$_GET['rowstart'].",".(int)$limit);
	
} else {

	$r = dbquery("SELECT log_id FROM ".DB_ADMIN_LOG." WHERE cat = 'forum'");	
	$rows = dbrows($r);
	$result = dbquery("SELECT tm.*,tf.*, tu.user_id,user_name,user_status FROM ".DB_ADMIN_LOG." tm
	INNER JOIN ".DB_USERS." tu ON tm.u_id=tu.user_id 
	INNER JOIN ".DB_FORUMS." tf ON tm.forum_id=tf.forum_id 
	WHERE tm.cat = 'forum'
	ORDER BY datestamp DESC LIMIT ".(int)$_GET['rowstart'].",".(int)$limit);
}


if (!dbrows($result)) {
	echo $locale['log_nologs']."<br /><br />";
} else {

echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
if($_GET['section'] == "registration") {
	echo "<td class='tbl1'>"."User"."</td>\n";
	echo "<td class='tbl1'>"."E-Mail"."</td>\n";
} else {
	echo "<td class='tbl1'>".$locale['110']."</td>\n";
}

echo "<td class='tbl1'>".$locale['111']."</td>\n";
if($_GET['section'] == "forum") echo "<td class='tbl1'>".$locale['112']."</td>\n";
if($_GET['section'] == "forum") echo "<td class='tbl1'>".$locale['113']."</td>\n";
echo "<td class='tbl1'>".$locale['114']."</td>\n";
echo "<td class='tbl1'>".$locale['115']."</td>\n";
echo "</tr>\n";

$i = 0;
while ($data = dbarray($result)) {

$class = ($i % 2 == 0 ? "tbl2" : "tbl1");

// User
if($_GET['section'] == "registration") {
	$arr = explode('#', $data['subject']);
	if($data['u_id'] > 0) {
		$status = getuserstatus($data['u_id']);
		echo "<td class='".$class."'>".profile_link($data['u_id'], $arr['0'], $status)."</a></td>\n";
		echo "<td class='".$class."'>".$arr['1']."</td>\n";
	} else {
		echo "<td class='".$class."'>".$arr['0']."</td>\n";
		echo "<td class='".$class."'>".$arr['1']."</td>\n";
	}
} else {
	echo "<td class='".$class."'>".profile_link($data['u_id'], $data['user_name'], $data['user_status'])."</td>\n";
}

// Action
echo "<td class='".$class."'>";
// Registration
if($data['cat'] == "registration") {

$ary = explode('##', $data['action']);
foreach($ary AS $arrydata) {
	$arz = explode('#', $arrydata);
	
	switch($arz['0']) {
	case 1:
		echo "Frage: ".$arz['1']." Antwort: ".$arz['2'];
		echo "<br />";
		break;
	case 2:
		echo "secI: ".$arz['1'];
		echo "<br />";
		break;
	case 3:
		echo "ReCap: ".$arz['1']." ".$arz['2'];
		echo "<br />";
		break;
	}
}

}
// Forum
elseif($data['action'] == "delete") {
echo $locale['120']; }
elseif($data['action'] == "delete_2") {
echo $locale['121']; }
elseif($data['action'] == "delete_post") {
echo $locale['122']; }
elseif($data['action'] == "renew") {
echo  $locale['123'];}
elseif($data['action'] == "lock") {
echo $locale['124']; }
elseif($data['action'] == "unlock") {
echo $locale['125']; }
elseif($data['action'] == "sticky") {
echo $locale['126']; }
elseif($data['action'] == "nonsticky") {
echo $locale['127']; }
elseif($data['action'] == "move") {
echo $locale['128']; } 
elseif($data['action'] == "move_posts") {
echo $locale['129']; } 
// Administration:

// Content Administration:
elseif($data['action'] == "admin_article_cat_deleted") {
echo $locale['ac102']." ".$data['subject'].""; }
elseif($data['action'] == "admin_article_cat_edited") {
echo $locale['ac101']." ".$data['subject'].""; }
elseif($data['action'] == "admin_article_cat_added") {
echo $locale['ac100']." ".$data['subject'].""; }
elseif($data['action'] == "admin_article_deleted") {
echo $locale['a102']." ".$data['subject'].""; }
elseif($data['action'] == "admin_article_edited") {
echo $locale['a101']." ".$data['subject'].""; }
elseif($data['action'] == "admin_article_added") {
echo $locale['a100']." ".$data['subject'].""; }
elseif($data['action'] == "admin_contact_page") {
echo "Contact Page updated"; }
elseif($data['action'] == "admin_custompage_added") {
echo "Custom Page added: ".$data['subject'].""; }
elseif($data['action'] == "admin_custompage_edited") {
echo "Custom Page edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_custompage_deleted") {
echo "Custom Page deleted: ".$data['subject'].""; }
elseif($data['action'] == "admin_custompage_preview") {
echo "Custom Page previewd: ".$data['subject'].""; }
elseif($data['action'] == "admin_dlcat_added") {
echo "Download Cat added: ".$data['subject'].""; }
elseif($data['action'] == "admin_dlcat_edited") {
echo "Download Cat edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_dlcat_deleted") {
echo "Download Cat deleted: ".$data['subject'].""; }
elseif($data['action'] == "admin_dl_added") {
echo "Download added: ".$data['subject'].""; }
elseif($data['action'] == "admin_dl_edited") {
echo "Download edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_dl_deleted") {
echo "Download deleted: ".$data['subject'].""; }
elseif($data['action'] == "admin_faq_added") {
echo "FAQ added: ".$data['subject'].""; }
elseif($data['action'] == "admin_faq_edited") {
echo "FAQ edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_faq_deleted") {
echo "FAQ deleted: ".$data['subject'].""; }
elseif($data['action'] == "admin_faqcat_added") {
echo "FAQ Cat added: ".$data['subject'].""; }
elseif($data['action'] == "admin_faqcat_edited") {
echo "FAQ Cat edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_faqcat_deleted") {
echo "FAQ Cat deleted: ".$data['subject'].""; }
elseif($data['action'] == "admin_forumcat_added") {
echo "Forum Cat added: ".$data['subject'].""; }
elseif($data['action'] == "admin_forumcat_edited") {
echo "Forum Cat edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_forumcat_deleted") {
echo "Forum Cat deleted: ".$data['subject'].""; }
elseif($data['action'] == "admin_forum_added") {
echo "Forum added: ".$data['subject'].""; }
elseif($data['action'] == "admin_forum_edited") {
echo "Forum edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_forum_deleted") {
echo "Forum deleted: ".$data['subject'].""; }
elseif($data['action'] == "admin_image_added") {
echo "Image added: ".$data['subject'].""; }
elseif($data['action'] == "admin_image_deleted") {
echo "Image deleted: ".$data['subject'].""; }

elseif($data['action'] == "admin_news_added") {
echo "News added: ".$data['subject'].""; }
elseif($data['action'] == "admin_news_edited") {
echo "News edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_news_deleted") {
echo "News deleted: ".$data['subject'].""; }

elseif($data['action'] == "admin_newscat_added") {
echo "News Cat added: ".$data['subject'].""; }
elseif($data['action'] == "admin_newscat_edited") {
echo "News Cat edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_newscat_deleted") {
echo "News Cat deleted: ".$data['subject'].""; }

elseif($data['action'] == "admin_poll_added") {
echo "Poll added: ".$data['subject'].""; }
elseif($data['action'] == "admin_poll_edited") {
echo "Poll edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_poll_deleted") {
echo "Poll deleted: ".$data['subject'].""; }

elseif($data['action'] == "admin_wlcat_added") {
echo "Weblink Cat added: ".$data['subject'].""; }
elseif($data['action'] == "admin_wlcat_edited") {
echo "Weblink Cat edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_wlcat_deleted") {
echo "Weblink Cat deleted: ".$data['subject'].""; }

elseif($data['action'] == "admin_wl_added") {
echo "Weblink added: ".$data['subject'].""; }
elseif($data['action'] == "admin_wl_edited") {
echo "Weblink edited: ".$data['subject'].""; }
elseif($data['action'] == "admin_wl_deleted") {
echo "Weblink deleted: ".$data['subject'].""; }

elseif($data['action'] == "admin_wel_panel_edited") {
echo "Welcome Panel updated"; }


// User Administration:
elseif($data['action'] == "admin_del_log") {
echo $locale['130']." (".$locale[$data['subject']].")"; }
elseif($data['action'] == "admin_adminrights_added") {
echo $locale['131']." ".getusername($data['subject']);  }
elseif($data['action'] == "admin_adminrights_removed") {
echo $locale['132']." ".getusername($data['subject']);  }
elseif($data['action'] == "admin_adminrights_changed") {
echo $locale['133']." ".getusername($data['subject']);  }
elseif($data['action'] == "admin_groupadminrights_changed") {
echo "Groupadminrights changed: ".getgroupname($data['subject']); } // needs to be localized
elseif($data['action'] == "admin_blacklist_added") {
echo $locale['134'];  }
elseif($data['action'] == "admin_blacklist_removed") {
echo $locale['135'];  }
elseif($data['action'] == "admin_blacklist_changed") {
echo $locale['136'];  }
elseif($data['action'] == "admin_moderator_added") {
echo "Moderator added:"." ".getusername($data['subject']); } // needs to be localized
elseif($data['action'] == "admin_moderator_removed") {
echo "Moderator removed:"." ".getusername($data['subject']); } // needs to be localized
elseif($data['action'] == "admin_forumrank_added") {
echo $locale['137'];  }
elseif($data['action'] == "admin_forumrank_removed") {
echo $locale['138'];  }
elseif($data['action'] == "admin_forumrank_changed") {
echo $locale['139'];  }
// System Administration:
elseif($data['action'] == "admin_adssystem_in_save") {
echo "Advertising in Forum Index changed"; } // needs to be localized
elseif($data['action'] == "admin_adssystem_vf_save") {
echo "Advertising in Forum Threads changed"; } // needs to be localized
elseif($data['action'] == "admin_banners_save") {
echo $locale['140']; }
elseif($data['action'] == "admin_banners_preview") {
echo $locale['141']; }
elseif($data['action'] == "admin_bbcode_enable") {
echo $locale['142']." (".$data['subject'].")"; }
elseif($data['action'] == "admin_bbcode_disable") {
echo $locale['143']." (".$data['subject'].")"; }
elseif($data['action'] == "admin_bbcode_tested") {
echo $locale['143a']; }
elseif($data['action'] == "admin_dbbackup_create") {
echo $locale['144']; }
elseif($data['action'] == "admin_dbbackup_restore") {
echo $locale['145']; }
elseif($data['action'] == "admin_infusion_installed") {
echo $locale['146']." (".$data['subject'].")"; }
elseif($data['action'] == "admin_infusion_deleted") {
echo $locale['147']." (".$data['subject'].")"; }
elseif($data['action'] == "admin_panel_deleted") {
echo $locale['148']; }
elseif($data['action'] == "admin_panel_status0") {
echo $locale['149']; }
elseif($data['action'] == "admin_panel_status1") {
echo $locale['150']; }
elseif($data['action'] == "admin_panel_saved") {
echo $locale['151']; }
elseif($data['action'] == "admin_panel_updated") {
echo $locale['152']; }
elseif($data['action'] == "admin_panel_previewed") {
echo $locale['153']; }
elseif($data['action'] == "admin_seo_settings_saved") {
echo $locale['154'];}
elseif($data['action'] == "admin_sitelinks_deleted") {
echo $locale['155']; }
elseif($data['action'] == "admin_sitelinks_edited") {
echo $locale['156']; }
elseif($data['action'] == "admin_sitelinks_saved") {
echo $locale['157']; }
elseif($data['action'] == "admin_smileys_deleted") {
echo $locale['158']; }
elseif($data['action'] == "admin_smileys_edited") {
echo $locale['159']; }
elseif($data['action'] == "admin_smileys_saved") {
echo $locale['160']; }
elseif($data['action'] == "admin_upgrade") {
echo $locale['161']; }
// Administration Settings:
elseif($data['action'] == "admin_settings_adminmenue_save") {
echo "Adminmenue Settings saved"; } // localize
elseif($data['action'] == "admin_settings_dl_save") {
echo $locale['170']; }
elseif($data['action'] == "admin_settings_forum_save") {
echo $locale['171']; }
elseif($data['action'] == "admin_settings_forum_recount") {
echo $locale['172']; }
elseif($data['action'] == "admin_settings_ipp_save") {
echo $locale['173']; }
elseif($data['action'] == "admin_settings_language_save") {
echo $locale['174']; }
elseif($data['action'] == "admin_settings_main_save") {
echo $locale['175']; }
elseif($data['action'] == "admin_settings_messages_save") {
echo $locale['176']; }
elseif($data['action'] == "admin_settings_misc_save") {
echo $locale['177']; }
elseif($data['action'] == "admin_settings_welcome_pm_save") {
echo $locale['177b']; }
elseif($data['action'] == "admin_settings_news_save") {
echo $locale['178']; }
elseif($data['action'] == "admin_settings_photo_save") {
echo $locale['179']; }
elseif($data['action'] == "admin_settings_photo_del_watermarks") {
echo $locale['180']; }
elseif($data['action'] == "admin_settings_registration_save") {
echo $locale['181']; }
elseif($data['action'] == "admin_settings_registration_questions_save") {
echo $locale['182']; }
elseif($data['action'] == "admin_settings_registration_questions_updated") {
echo $locale['183']; }
elseif($data['action'] == "admin_settings_registration_questions_deleted") {
echo $locale['184']; }
elseif($data['action'] == "admin_settings_security_save") {
echo $locale['185']; }
elseif($data['action'] == "admin_settings_time_save") {
echo $locale['186']; }
elseif($data['action'] == "admin_settings_users_save") {
echo $locale['187']; }
// else
else {
echo "unkown ->".$data['action'];
}

echo "</td>\n";


if($data['cat'] == "forum") {
// Location:
echo "<td class='".$class."'>";
// Forum: Forum-Cat
if($data['action'] == "move" OR $data['action'] == "move_posts") {
	$result_temp = dbquery("SELECT forum_name FROM ".DB_FORUMS." WHERE forum_id='".$data['movedto_forum_id']."'");
	$data_temp = dbarray($result_temp);
	echo $locale['190'].$locale['191']."<a href='".FORUM."viewforum.php?forum_id=".$data['forum_id']."'>".$data['forum_name']."</a>".$locale['192']."<a href='".FORUM."viewforum.php?forum_id=".$data['movedto_forum_id']."'>".$data_temp['forum_name']."</a>";
} else {
	echo $locale['190']."<a href='".FORUM."viewforum.php?forum_id=".$data['forum_id']."'>".$data['forum_name']."</a>\n";
}

echo "</td>";
}

// Forum: Thread
if($data['cat'] == "forum") {
echo "<td class='".$class."'>";
if($data['action'] == "delete" || $data['action'] == "delete_2") {
	echo $locale['193']."<span class='small2'>".$data['subject']."</span><br />".$locale['194']."<span class='small2'>".$data['forum_id']."</span><br />".$locale['195']."<span class='small2'>".$data['thread_id']."</span>\n";
} elseif($data['action'] == "delete_post") {
	$result_temp = dbquery("SELECT thread_subject FROM ".DB_THREADS." WHERE thread_id='".$data['thread_id']."'");
	if (dbrows($result_temp)) {
	echo "<a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."'>".$data['subject']."</a>\n";
	} else {
	echo $locale['193']."<span class='small2'>".$data['subject']."</span><br />".$locale['194']."<span class='small2'>".$data['forum_id']."</span><br />".$locale['195']."<span class='small2'>".$data['thread_id']."</span>\n";
	}
} elseif($data['action'] == "move_posts") {
	$result_temp = dbquery("SELECT thread_subject FROM ".DB_THREADS." WHERE thread_id='".$data['movedto_thread_id']."'");
	$data_temp = dbarray($result_temp);
	echo $locale['191']."<a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."'>".$data['subject']."</a>".$locale['192']."<a href='".FORUM."viewthread.php?thread_id=".$data['movedto_thread_id']."'>".$data_temp['thread_subject']."</a>\n";
} else {
	echo "<a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."'>".$data['subject']."</a>\n";
}
echo "</td>";
}

// IP
echo "<td class='".$class."'>".$data['log_ip']."</td>\n";

// Date
echo "<td class='".$class."'>".showdate("forumdate", $data['datestamp'])."</td>\n";
echo "<tr>\n</tr>\n";

$i++;

}

echo "</table>\n";


} // end: if $rows > 0




if ($rows > $limit) {
	echo "\n<div align=center>".pagination(true,$_GET['rowstart'],$limit, $rows,3,FUSION_SELF.$aidlink."&amp;section=".$_GET['section']."&amp;")."</div>\n";
}

} // end: cleanup

closetable();

require_once TEMPLATES."footer.php";
?>