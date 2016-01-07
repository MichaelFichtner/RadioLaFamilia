<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: index.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| based on PHP-Fusion CMS v7.01 by Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";
require_once TEMPLATES."admin_header.php";

if (!iADMIN || (iUSER_RIGHTS == "" && iGROUP_RIGHTS == "") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

// Add Admin Images to Image-Array
add_adminimages();

// Work out which tab is the active default (redirect if no tab available)
$default = false;
for ($i = 5; $i > 0; $i--) {
	if ($pages[$i]) { $default = $i; }
}
if (!$default) { redirect("../index.php"); }

// Ensure the admin is allowed to access the selected page
if (!$pages[$_GET['pagenum']]) { redirect("index.php".$aidlink."&pagenum=$default"); }

// Display admin panels & pages
opentable($locale['200']." - Pimped Fusion v".$settings['version_pimp']." Core v".$settings['version']); //pimped
echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
for ($i = 1; $i < 6; $i++) {
	$class = ($_GET['pagenum'] == $i ? "tbl1" : "tbl2");
	if ($pages[$i]) {
		echo "<td align='center' width='20%' class='$class'><span class='small'>\n";
		echo ($_GET['pagenum'] == $i ? "<strong>".$locale['ac0'.$i]."</strong>" : "<a href='index.php".$aidlink."&amp;pagenum=$i'>".$locale['ac0'.$i]."</a>")."</span></td>\n";
	} else {
		echo "<td align='center' width='20%' class='$class'><span class='small' style='text-decoration:line-through'>\n";
		echo $locale['ac0'.$i]."</span></td>\n";
	}
}
echo "</tr>\n<tr>\n<td colspan='5' class='tbl1'>\n";
$result = dbquery("SELECT admin_rights, admin_title, admin_link FROM ".DB_ADMIN." WHERE admin_page='".$_GET['pagenum']."' ORDER BY admin_title");
$rows = dbrows($result);
if ($rows != 0) {
	$counter = 0; $columns = 4;
	$align = $settings['adminmenue_icons'] ? "center" : "left";
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	while ($data = dbarray($result)) {
		if (checkrights($data['admin_rights']) && $data['admin_link'] != "reserved") {
			if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
			if($data['admin_link'] == "db_backup.php") { $target = " target='_blank'"; } else { $target = ""; }
			echo "<td align='$align' width='20%' class='tbl'>";
			if ($settings['adminmenue_icons'] && array_key_exists("admin_".$data['admin_rights'], $locale) && ($_GET['pagenum'] == 1 || $_GET['pagenum'] == 2 || $_GET['pagenum'] == 3 || $_GET['pagenum'] == 4)) {
				echo "<span class='small'><a href='".$data['admin_link'].$aidlink."'".$target.">";
				echo "<img src='".get_image("ac_".$_GET['pagenum'].$data['admin_title'])."' alt='".$locale["admin_".$data['admin_rights']]."' style='border:0px;' />";
				echo "<br />\n".$locale["admin_".$data['admin_rights']]."</a></span>";
			} elseif ($settings['adminmenue_icons']) {
				echo "<span class='small'><a href='".$data['admin_link'].$aidlink."'".$target.">";
				echo "<img src='".get_image("ac_".$_GET['pagenum'].$data['admin_title'])."' alt='".$data['admin_title']."' style='border:0px;' />";
				echo "<br />\n".$data['admin_title']."</a></span>";
			} elseif (array_key_exists("admin_".$data['admin_rights'], $locale) && ($_GET['pagenum'] == 1 || $_GET['pagenum'] == 2 || $_GET['pagenum'] == 3 || $_GET['pagenum'] == 4)) {
				echo "<span class='small'>".THEME_BULLET." ";
				echo "<a href='".$data['admin_link'].$aidlink."'".$target.">".$locale["admin_".$data['admin_rights']]."</a>";
				echo "</span>";
			} else {
				echo "<span class='small'>".THEME_BULLET." ";
				echo "<a href='".$data['admin_link'].$aidlink."'".$target.">".$data['admin_title']."</a>";
				echo "</span>";
			}
			echo "</td>\n";
			$counter++;
		}
	}
	echo "</tr>\n</table>\n";
}
echo "</td>\n</tr>\n</table>\n";
closetable();

// Pimped:
$members_active = dbcount("(user_id)", DB_USERS, "user_status='0'");
$members_banned = dbcount("(user_id)", DB_USERS, "user_status='1'");
$members_unactivated = dbcount("(user_id)", DB_USERS, "user_status='2'");
$members_suspended = dbcount("(user_id)", DB_USERS, "user_status='3'");
$members_security_ban = dbcount("(user_id)", DB_USERS, "user_status='4'");
$members_canceled = dbcount("(user_id)", DB_USERS, "user_status='5'");
$members_anonymized = dbcount("(user_id)", DB_USERS, "user_status='6'");
$members_markinactive = dbcount("(user_id)", DB_USERS, "user_status='7'");
if($settings['enable_deactivation'] == "1") {
	$time_overdue = time() - (86400 * $settings['deactivation_period']);
	$members_inactive = dbcount("(user_id)", DB_USERS, "user_lastvisit<'".$time_overdue."' AND user_actiontime='0' AND user_status='0'");
	#AND user_joined<'".$time_overdue."' is at the moment not a criterium for "inactive users"
}

opentable($locale['250']);
echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n<td valign='top' width='20%' class='small'>\n";
if (checkrights("M")) {
	echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=0'>".$locale['sta000']."</a> ".$members_active."<br />\n";
	echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=1'>".$locale['sta001']."</a> ".$members_banned."<br />\n";
	echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=2'>".$locale['sta002']."</a> ".$members_unactivated."<br />\n";
	echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=3'>".$locale['sta003']."</a> ".$members_suspended."<br />\n";
	echo "</td>\n<td valign='top' width='30%' class='small'>";
	echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=4'>".$locale['sta004']."</a> ".$members_security_ban."<br />\n";
	echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=5'>".$locale['sta005']."</a> ".$members_canceled."<br />\n";
	echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=6'>".$locale['sta006']."</a> ".$members_anonymized."<br />\n";
	if($settings['enable_deactivation'] == "1" || $members_markinactive) {
	echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=7'>".$locale['sta007']."</a> ".$members_markinactive."<br />\n";
	}
	if($settings['enable_deactivation'] == "1") {
	echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=8'>".$locale['sta008']."</a> ".$members_inactive."<br />\n";
	}
} else {
	echo $locale['sta000']." ".$members_active."<br />\n";
	echo $locale['sta001']." ".$members_banned."<br />\n";
	echo $locale['sta002']." ".$members_unactivated."<br />\n";
	echo $locale['sta003']." ".$members_suspended."<br />\n";
	echo "</td>\n<td valign='top' width='30%' class='small'>";
	echo $locale['sta004']." ".$members_security_ban."<br />\n";
	echo $locale['sta005']." ".$members_canceled."<br />\n";
	echo $locale['sta006']." ".$members_anonymized."<br />\n";
	if($settings['enable_deactivation'] == "1" || $members_markinactive) {
	echo $locale['sta007']." ".$members_markinactive."<br />\n";
	}
	if($settings['enable_deactivation'] == "1") {
	echo $locale['sta008']." ".$members_inactive."<br />\n";
	}
}
echo "</td>\n<td valign='top' width='25%' class='small'>
".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#news_submissions'>".$locale['254']."</a>" : $locale['254'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='n'")."<br />
".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#article_submissions'>".$locale['255']."</a>" : $locale['255'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='a'")."<br />
".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#link_submissions'>".$locale['256']."</a>" : $locale['256'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='l'")."<br />
".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#photo_submissions'>".$locale['260']."</a>" : $locale['260'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='p'")."
</td>\n<td valign='top' width='25%' class='small'>
".$locale['257']." ".dbcount("(comment_id)", DB_COMMENTS)."<br />
".$locale['258']." ".dbcount("(shout_id)", DB_SHOUTBOX)."<br />
".$locale['259']." ".dbcount("(post_id)", DB_POSTS)."<br />
".$locale['261']." ".dbcount("(photo_id)", DB_PHOTOS)."
</td>\n</tr>\n</table>\n";
closetable();


// Admin Notes Panel
// Author: Johan Wilson (Barspin)
// Contact: barspin@blendtek.net
// modified by slaughter for PiF
if($settings['adminmenue_notes']) {
include_once INCLUDES."bbcode_include.php";

add_to_head("<script type='text/javascript'>
$(function(){
$('.loding').hide();
	$('#notes_q div').hover(function() {
		$(this).find('.loding').show();
	}, function() {
		$(this).find('.loding').hide();
	});
$('#notes_q a').click(function(){
var element = $(this);
var noteid = element.attr('id');
var info = 'id=' + noteid;
$(element).find('.loding').hide();
$(element).parent('li').animate({'opacity': .5 });
$(element).append(\"<img src='".IMAGES."ajax-loader.gif' alt='Loading' style='vertical-align:middle;border:0;' />\");
$.ajax({
 type: 'GET',
 url: '".FUSION_SELF.$aidlink."',
 data: info,
 success: function(){
 element.parent().eq(0).fadeOut('fast');
 }
});
return false;
}); 
$('#notes_submit').click(function(){
var loading = $('div#note_loading').html(\"<img src='".IMAGES."ajax-loader.gif' alt='Loading' style='vertical-align:middle;border:0;' />\"); 
var content = $('#notes_content').val();
var name = $('#note_name').val();
var url = 'submit=1&content=' + content + '&submit=1&name=' + name;
var type = $('#notes_content').val();
if (type == '') {
 $(loading).hide();
} else {
 $(loading).show();
 $.ajax({
  type: 'POST',
  url: '".FUSION_SELF.$aidlink."',
  data: url,
  success: function(){
  $('div#note_loading').after(\"<div style='float:left' class='admin_note'><strong>\" + name + \"</strong>&nbsp;<img src='' alt='' style='height:16px;width:0px;' /><br /><div class='shoutboxdate'>".showdate("%d %b %H:%M", time())."</div><div class='notify'>\" + content + \"</div></div>\");
  $(loading).hide();
  $('#notes_content').val('');
  }
 });
}

return false;
});
});
</script>

<style type='text/css'>
.notify{background:#FFD6D6 none repeat scroll 0 0;border-bottom:2px solid #EF706F;border-top:2px solid #EF706F;color:#CC0000;padding:2px;margin-bottom:5px;margin-top: 2px;}
.admin_note{min-width:100px;margin:3px;padding:3px 0;list-style-type:none;overflow:auto;}
</style>");
openside($locale['note_01'], true);
echo "<div id='notes' style='float:left'>\n";
echo "<form id='notes_form' action='".FUSION_SELF.$aidlink."' method='post'>\n";
echo "<input type='hidden' name='note_name' id='note_name' value='".$userdata['user_name']."' maxlength='30' />\n";
echo "<br/>\n<textarea name='notes_content' id='notes_content' class='textbox' cols='20' rows='4' style='width:140px'></textarea><br />\n";
echo "".display_bbcodes("150px;", "notes_content", "notes_form", "smiley|b|u|url")."";
echo "<input type='submit' id='notes_submit' name='notes_submit' class='button' value='".$locale['note_02']."' />\n";
echo "</form>\n";
echo "</div><br />\n";

$result = dbquery("SELECT note_id, note_name, note_text, note_datestamp FROM ".DB_ADMIN_NOTES." ORDER BY note_id DESC");

echo "<div class='admin_notes' id='notes_q'>";
echo "<div id='note_loading'></div>\n";
while ($data = dbarray($result)) {
	echo "<div style='float:left' class='admin_note'>";
	echo "<strong>".$data['note_name']."</strong>&nbsp;";
	echo "<a id='".$data['note_id']."' href='".FUSION_SELF."?id=".$data['note_id']."'>";
	echo "<img class='loding' src='".get_image("cancel")."' title='".$locale['note_03']."?' alt='".$locale['note_03']."?' style='border:0;margin:0;vertical-align:bottom;' />";
	echo "</a><img src='' alt='' style='height:16px;width:0px;' /><br />";
	echo "<div class='shoutboxdate'>".showdate("%d %b %H:%M", $data['note_datestamp'])."</div>";
	echo "<div class='notify'>".parseubb(parsesmileys($data['note_text']), "b|i|u|url")."</div>";
	echo "</div>";
}
echo "</div>";

closeside();

if (isset($_POST['submit']) && @$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
	$content = trim(stripinput($_POST['content']));
	$name = trim(stripinput($_POST['name']));
	$ins = dbquery("INSERT INTO ".DB_ADMIN_NOTES." (note_text, note_name, note_datestamp) VALUES ("._db($content).", "._db($name).", '".time()."')");
} elseif (isset($_GET['id']) && @$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
	$id = trim(stripinput($_GET['id']));
	$delete = dbquery("DELETE FROM ".DB_ADMIN_NOTES." WHERE note_id="._db($id));
	return $locale['note_04'];
}

}

require_once TEMPLATES."footer.php";
?>