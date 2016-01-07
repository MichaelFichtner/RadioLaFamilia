<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: warnings.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter, pirdani, emblinux
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";
require_once TEMPLATES."admin_header.php";

if (!checkrights("WAR") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

include INCLUDES."warning.inc.php";

if(!isset($_GET['site']) || !isnum($_GET['site']) ) {
	$site = 1;
} else {
	$site = $_GET['site'];
}

if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "dels") {
		$message = L_WARN_MSG_REASON_DEL;
	} elseif ($_GET['status'] == "delf") {
		$message = L_WARN_MSG_REASON_DEL_ERROR;
	} elseif ($_GET['status'] == "wim") {
		$message = L_WARN_MSG_MISSING;
	} elseif ($_GET['status'] == "wis") {
		$message = L_WARN_MSG_REASON_ADDED;
	} elseif ($_GET['status'] == "wie") {
		$message = L_WARN_MSG_REASON_EDITED;
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}

opentable(L_WARN_ADMIN_TITLE);
echo "
<table width='99%' align='center' cellpadding='1' cellspacing='1' class='tbl-border'>
<tr>
<td width='25%' align='center' valign='middle' class='".($site == 1 ? 'tbl1' : 'tbl2')."'>
	<a href='".ADMIN."warnings.php".$aidlink."&amp;site=1'>".L_WARN_ADMIN_REASON."</a>
</td>
<td width='25%' align='center' valign='middle' class='".($site == 2 ? 'tbl1' : 'tbl2')."'>
	<a href='".ADMIN."warnings.php".$aidlink."&amp;site=2'>".L_WARN_ADMIN_SET."</a>
</td>
<td width='25%' align='center' valign='middle' class='".($site == 3 ? 'tbl1' : 'tbl2')."'>
	<a href='".ADMIN."warnings.php".$aidlink."&amp;site=3'>".L_WARN_ADMIN_STAT."</a>
</td>
<td width='25%' align='center' valign='middle' class='".($site == 4 ? 'tbl1' : 'tbl2')."'>
	<a href='".ADMIN."warnings.php".$aidlink."&amp;site=4'>".L_WARN_ADMIN_CLEAN."</a>
</td>
</tr>
</table><br />";

closetable();

switch($site) {

// Reasons
case 1:

	if(isset($_GET['warnid']) && isnum($_GET['warnid'])) {
		$warning_id = $_GET['warnid'];
	} elseif(isset($_POST['warnid']) && isnum($_POST['warnid'])) {
		$warning_id = $_POST['warnid'];
	} else {
		$warning_id = false;
	}

	if(isset($_GET['action']) && $_GET['action'] == "delete") {
		$result = dbquery("DELETE FROM ".DB_WARNING_CATALOG." WHERE warn_id='".(int)$warning_id."'");
		redirect(FUSION_SELF.$aidlink."&amp;site=1&amp;status=".($result ? "dels" : "delf"));
	}

	if(isset($_POST['submit'])) {
		$warn_type = $_POST['warn_type'];
		$warn_subject = $_POST['warn_subject'];
		$warn_point = $_POST['warn_point'];
		$warn_time = $_POST['warn_time'];
		if($warn_subject != "" && $warn_time != "" && $warn_point != "") {
			if($warning_id) {
				$result = dbquery("UPDATE ".DB_WARNING_CATALOG." SET
					warn_kind="._db($warn_type).", 
					warn_subject="._db($warn_subject).", 
					warn_point="._db($warn_point).", 
					warn_length="._db($warn_time)." 
					WHERE warn_id="._db($warning_id)."");
				redirect(FUSION_SELF.$aidlink."&amp;site=1&amp;status=wie");
			} else {
				$result = dbquery("INSERT INTO ".DB_WARNING_CATALOG." (warn_kind, warn_subject, warn_point, warn_length)
					VALUES ("._db($warn_type).", "._db($warn_subject).", "._db($warn_point).", "._db($warn_time).");");
				redirect(FUSION_SELF.$aidlink."&amp;site=1&amp;status=wis");
			}
		} else {
			redirect(FUSION_SELF.$aidlink."&amp;site=1&amp;status=wim");
		}
	}

	opentable((isset($_GET['action']) && $_GET['action'] == "edit") ? L_WARN_ADMIN_1_TITED : L_WARN_ADMIN_1_TITAD);
		echo "<br />
		<form action='".ADMIN."warnings.php".$aidlink."&amp;site=1' method='POST'>";
		if(isset($_GET['action']) && $_GET['action'] == "edit") { 
			$data = dbarray(dbquery("SELECT warn_subject, warn_point, warn_length, warn_kind
			FROM ".DB_WARNING_CATALOG." WHERE warn_id='".(int)$warning_id."'"));
			$subject = $data['warn_subject'];
			$points = $data['warn_point'];
			$length = $data['warn_length'];
			$kind = $data['warn_kind'];
			echo "<input type='hidden' name='warnid' value='".$warning_id."'>";
		} else {
			$subject = "";
			$points = "";
			$length = "";
			$kind = "";
		}
		echo "	
		<table width='80%' cellpadding='5' cellspacing='0' border='0' align='center'>
			<tr class='tbl2'>
				<td align='left' valign='top' width='100'><strong>".L_WARN_ADMIN_1_REASON."</strong></td>
				<td align='left' valign='top'><input type='text' name='warn_subject' value='".$subject."' class='textbox' style='width:300px;'></td>
			</tr>
			<tr class='tbl1'>
				<td align='left' valign='top' width='100'><strong>".L_WARN_ADMIN_1_TYPE."</strong></td>
				<td align='left' valign='top'>
				<select name='warn_type' class='textbox' style='width:100px;'>\n
				<option".($kind  == "Forum" ? " selected='selected'" : "")." value='Forum'>".L_WARN_ADMIN_1_FORUM."</option>\n
				<option".($kind  == "Other" ? " selected='selected'" : "")." value='Other'>".L_WARN_ADMIN_1_GENERAL."</option>\n
				</select></td>\n
			</tr>
			<tr class='tbl2'>
				<td align='left' valign='top' width='100'><strong>".L_WARN_ADMIN_1_DURATION."</strong></td>
				<td align='left' valign='top'>
					<input type='text' name='warn_time' value='".$length."' class='textbox' style='width:50px;'>
					<strong>".L_WARN_ADMIN_1_DAYS."</strong> <i>".L_WARN_ADMIN_1_DAYS2."</i>
				</td>
			</tr>
			<tr class='tbl1'>
				<td align='left' valign='top' width='100'><strong>".L_WARN_ADMIN_1_POINTS."</strong></td>
				<td align='left' valign='top'>
					<input type='text' name='warn_point' value='".$points."' class='textbox' maxlength='3' style='width:50px;'>
				</td>
			</tr>
			<tr>
				<td align='right' valign='top' width='100'></td>
				<td align='right' valign='top'>
					<input type='submit' name='submit' value='".( (isset($_GET['action']) && $_GET['action'] == "edit" ) ? L_WARN_ADMIN_1_EDIT : L_WARN_ADMIN_1_ADD)."' class='button'>
				</td>
			</tr>
		</table>
		</form>
		<br />";
	closetable();

	opentable(L_WARN_ADMIN_1_TITRE);
	$result = dbquery("SELECT warn_id, warn_subject, warn_kind, warn_point, warn_length
	FROM ".DB_WARNING_CATALOG." ORDER BY warn_kind DESC, warn_subject ASC");
		$a = 1;
	if(dbrows($result)) {
		echo "
		<table width='80%' cellpadding='5' cellspacing='0' border='0' align='center'>
		<tr class='tbl2'>
			<td align='left' valign='top'><strong>".L_WARN_ADMIN_1_REASON."</strong></td>
			<td width='100' align='center' valign='top'><strong>".L_WARN_ADMIN_1_TYPE."</strong></td>
			<td width='100' align='center' valign='top'><strong>".L_WARN_ADMIN_1_POINTS."</strong></td>
			<td width='100' align='left' valign='top'><strong>".L_WARN_ADMIN_1_DURATION."</strong></td>
			<td width='100' align='center' valign='top'><strong>".L_WARN_ADMIN_1_OPTION."</strong></td>
		</tr>";
		while($data = dbarray($result)) {
			echo "<tr".($a%2==0 ? " class='tbl2'" : " class='tbl1'").">
					<td align='left' valign='top'>".$data['warn_subject']."</td>
					<td align='center' valign='top'>";
					if($data['warn_kind'] == "Forum") {
						echo L_WARN_ADMIN_1_FORUM;
					} elseif($data['warn_kind'] == "Other") {
						echo L_WARN_ADMIN_1_GENERAL;
					}
					echo "</td>
					<td align='center' valign='top'>".$data['warn_point']."</td>
					<td align='left' valign='top'>".$data['warn_length']." ".L_WARN_ADMIN_1_DAYS."</td>
					<td align='center' valign='top'>
					<a href='".ADMIN."warnings.php".$aidlink."&amp;site=1&amp;action=edit&amp;warnid=".$data['warn_id']."'>".L_WARN_ADMIN_1_O_EDIT."</a>
					<a href='".ADMIN."warnings.php".$aidlink."&amp;site=1&amp;action=delete&amp;warnid=".$data['warn_id']."'>".L_WARN_ADMIN_1_O_DELETE."</a>
					</td>
				</tr>";
			$a++;
		}
		echo "</table><br />";
	} else {
		echo "<br /><div>".L_WARN_ADMIN_1_NOREASONS."</div><br /><br />";
	}
	closetable();

break;

case 2:

if(isset($_POST['save_warning'])) {
	$error = 0;
	$usergroup = ($_POST['warning_group'] != "" && $_POST['warning_group'] != 0 ? stripinput($_POST['warning_group']) : nMODERATOR);
	if(!set_mainsetting('warning_system', stripinput($_POST['warning_system']))) { $error = 1; }
	if(!set_mainsetting('warning_set_visible', stripinput($_POST['warning_set_visible']))) { $error = 1; }
	if(!set_mainsetting('warning_system_shoutbox', stripinput($_POST['warning_system_shoutbox']))) { $error = 1; }
	if(!set_mainsetting('warning_system_comments', stripinput($_POST['warning_system_comments']))) { $error = 1; }
	if(!set_mainsetting('warning_set_usergroup', $usergroup)) { $error = 1; }
	if(!set_mainsetting('warning_set_send_pm', stripinput($_POST['warning_send_pm']))) { $error = 1; }
	if(!set_mainsetting('warning_set_pm_from', stripinput($_POST['warning_pm_from']))) { $error = 1; }
	if(!set_mainsetting('warning_set_pm_to', stripinput($_POST['warning_pm_to']))) { $error = 1; }
	
	redirect(FUSION_SELF.$aidlink."&site=2&error=".$error); // Msg: saved or not
}


opentable(L_WARN_ADMIN_2_TITLE);
	
	echo "<br />";
	echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."&amp;site=2'>\n";
	echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
	echo "<td width='50%' class='tbl'>".L_WARN_ADMIN_2_ACTIV."</td>\n";
	echo "<td width='50%' class='tbl'>
		<select name='warning_system' class='textbox'>
			<option value='1' ".($settings['warning_system'] == 1 ? "selected" : "").">".L_YES."</option>
			<option value='0' ".($settings['warning_system'] == 0 ? "selected" : "").">".L_NO."</option>
		</select>
		</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='50%' class='tbl'>".L_WARN_ADMIN_2_VISIBLE."</td>\n";
	echo "<td width='50%' class='tbl'>
			<select name='warning_set_visible' class='textbox'>
				<option value='1' ".($settings['warning_set_visible'] == 1 ? "selected" : "").">".L_PUBLIC."</option>
				<option value='0' ".($settings['warning_set_visible'] == 0 ? "selected" : "").">".L_MEMBER."</option>
			</select>
		</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='50%' class='tbl'>".L_WARN_ADMIN_2_SHOUTS."</td>";
	echo "<td width='50%' class='tbl'>
			<select name='warning_system_shoutbox' class='textbox'>
				<option value='1' ".($settings['warning_system_shoutbox'] == 1 ? "selected" : "").">".L_YES."</option>
				<option value='0' ".($settings['warning_system_shoutbox'] == 0 ? "selected" : "").">".L_NO."</option>
			</select>
		</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='50%' class='tbl'>".L_WARN_ADMIN_2_COMMENTS."</td>";
	echo "<td width='50%' class='tbl'>
			<select name='warning_system_comments' class='textbox'>
				<option value='1' ".($settings['warning_system_comments'] == 1 ? "selected" : "").">".L_YES."</option>
				<option value='0' ".($settings['warning_system_comments'] == 0 ? "selected" : "").">".L_NO."</option>
			</select>
		</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='50%' class='tbl'>".L_WARN_ADMIN_2_USERGROUP."</td>";
	echo "<td width='50%' class='tbl'>
			<select name='warning_group' style='width:100%;' class='textbox'>";
				$visibility_opts = "<option value='0'>".L_SELECTPLEASE."</option>"; $sel = "";
				$user_groups = getusergroups(0,0,1,1,1,0,1);
				while(list($key, $user_group) = each($user_groups)){
					$sel = ($settings['warning_set_usergroup'] == $user_group['0'] ? " selected='selected'" : "");
					$visibility_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
				}
				echo $visibility_opts;
				
	echo "</select>
		</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='50%' class='tbl'>".L_WARN_ADMIN_2_SENDPM."</td>";
	echo "<td width='50%' class='tbl'>
			<select name='warning_send_pm' class='textbox'>
				<option value='1' ".($settings['warning_set_send_pm'] == 1 ? "selected" : "").">".L_YES."</option>
				<option value='0' ".($settings['warning_set_send_pm'] == 0 ? "selected" : "").">".L_NO."</option>
			</select>
		</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='50%' class='tbl'>".L_WARN_ADMIN_2_SENDER."</td>";
	echo "<td width='50%' class='tbl'>
			<select name='warning_pm_from' style='width:100%;' class='textbox'>
				<option value='0' ".($settings['warning_set_pm_from'] == 0 ? "selected" : "").">".L_WARN_ADMIN_2_SENDER_SELECT."</option>";
				$result = dbquery("SELECT user_id, user_name FROM ".DB_USERS." WHERE user_level>".nMEMBER." ORDER BY user_level DESC, user_name");
				while ($data = dbarray($result)) {
					echo "<option value='".$data['user_id']."' ".($settings['warning_set_pm_from'] == $data['user_id'] ? "selected" : "").">".$data['user_name']."</option>";
				}
echo "</select>
		</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='50%' class='tbl'>".L_WARN_ADMIN_2_RECEIVE_MSG."</td>";
	echo "<td width='50%' class='tbl'>
			<select name='warning_pm_to' style='width:100%;' class='textbox'>";
				$result = dbquery("SELECT user_id, user_name FROM ".DB_USERS." WHERE user_level>".nMEMBER." ORDER BY user_level DESC, user_name");
				while ($data = dbarray($result)) {
					echo "<option value='".$data['user_id']."' ".($settings['warning_set_pm_to'] == $data['user_id'] ? "selected" : "").">".$data['user_name']."</option>";
				}
	echo "</select>
		</td>
		</tr>\n<tr>\n
			<td class='tbl' align='right' valign='top' colspan='2'>
				<input type='submit' name='save_warning' value='".L_SAVE."' class='button'>
			</td>
		</tr>
	</table>
	<br />
	</form>";
closetable();

break;

case 3:


$logs_per_page = 10;

$result = dbquery("SELECT user_id FROM ".DB_WARNING." ORDER BY warn_datestamp DESC");
$warn_anz = dbrows($result);

if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
$rowstart = $_GET['page'] > 0 ? ($_GET['page']-1) * $logs_per_page : "0";

	$result = dbquery("SELECT user_id, warn_subject, warn_kind, warn_message, warn_point, warn_admin, warn_datestamp, post_id
	FROM ".DB_WARNING." ORDER BY warn_datestamp DESC
	LIMIT ".$rowstart.",".$logs_per_page."");
	$numwarnings = dbrows($result);

if ($numwarnings != 0) {
opentable(L_WARN_ADMIN_3_WARN_LOG);
	echo "<br />
	<table width='80%' cellpadding='5' cellspacing='0' border='0' align='center'>
		<tr class='tbl2'>
			<td align='center' valign='top' width='100'><strong>".L_WARN_ADMIN_3_MEMBER."</strong></td>
			<td align='center' valign='top' width='*'><strong>".L_WARN_ADMIN_3_REASON."</strong></td>
			<td align='center' valign='top' width='50'><strong>".L_WARN_ADMIN_3_POINTS."</strong></td>
			<td align='center' valign='top' width='100'><strong>".L_WARN_ADMIN_3_MODERATOR."</strong></td>
			<td align='center' valign='top' width='70'><strong>".L_WARN_ADMIN_3_DATE."</strong></td>
		</tr>";
		$a = 1;
		while($data = dbarray($result)) {
			$subject_data = dbarray(dbquery("SELECT warn_subject FROM ".DB_WARNING_CATALOG." WHERE warn_id='".$data['warn_subject']."'"));
			echo "<tr class='".($a%2==0 ? "tbl2" : "tbl1")."'>
							<td align='left' valign='top'>".warning_user($data['user_id'])."</td>
							<td align='left' valign='top'><b>".$subject_data['warn_subject']."  (Kind: ".$data['warn_kind'].")</b><br />".$data['warn_message']."<br />".($data['warn_kind'] == "Other" ? "" : warning_forum_link($data['post_id']))."</td>
							<td align='center' valign='top'>".$data['warn_point']."</td>
							<td align='right' valign='top'>".warning_user($data['warn_admin'])."</td>
							<td align='right' valign='top'>".date(L_WARN_ADMIN_3_DATE_FORM, $data['warn_datestamp'])."</td>
						</tr>";
			$a++;
		}
	echo "</table><br />";
	if ($warn_anz > $logs_per_page) echo "<div align='center' style='margin-top:5px;'>\n".pagination(true,$rowstart,$logs_per_page,$warn_anz,5,ADMIN."warnings.php".$aidlink."&amp;site=3&amp;")."\n</div>\n";
closetable();

}

opentable(L_WARN_ADMIN_3_STATS);
	echo "<br />
	<table width='80%' cellpadding='5' cellspacing='0' border='0' align='center'>
		<tr class='tbl2'>
			<td align='center' valign='top' colspan='2'><strong>".L_WARN_ADMIN_3_NUM."</strong></td>
		</tr>
		<tr class='tbl1'>
			<td align='left' valign='top'>".L_WARN_ADMIN_3_TOTAL."</td>
			<td width='200' align='right' valign='top'>".number_format(dbcount("(warn_id)", DB_WARNING), 0, ",", ".")."</td>
		</tr>
		<tr class='tbl1'>
			<td align='left' valign='top'></td>
			<td width='200' align='right' valign='top'><br /></td>
		</tr>
		<tr class='tbl2'>
			<td align='center' valign='top' colspan='2'><strong>".L_WARN_ADMIN_3_REASONS."</strong></td>
		</tr>";
		$result = dbquery("SELECT warn_id, warn_subject FROM ".DB_WARNING_CATALOG." ORDER BY warn_subject");
		$a = 1;
		while($data = dbarray($result)) {
			echo "<tr class='".($a%2==0 ? "tbl2" : "tbl1")."'>
					<td align='left' valign='top'>".$data['warn_subject'].":</td>
					<td width='200' align='right' valign='top'>".number_format(dbcount("(warn_id)", DB_WARNING, "warn_subject='".(int)$data['warn_id']."'"), 0, ",", ".")."</td>
				</tr>";
		$a++;
		}
echo "</table><br />";
closetable();


break;

case 4:


if(isset($_POST['btnDEL']) && isset($_POST['chkYes']) && $_POST['chkYes'] == "YES") {
 if(isset($_POST['optDel'])) {
	switch($_POST['optDel']) {
		case 1:
			$result = dbquery("SELECT warn_subject, warn_datestamp FROM ".DB_WARNING." ORDER BY warn_datestamp");
			while($data = dbarray($result)) {
				$data2 = dbarray(dbquery("SELECT warn_length FROM ".DB_WARNING_CATALOG." WHERE warn_id='".$data['warn_subject']."'"));
				if(($data['warn_datestamp']+($data2['warn_length']*86400))<=(date('U')-31536000)) {
					$sql3 = dbquery("DELETE FROM ".DB_WARNING." WHERE warn_id='".$data['warn_subject']."';");
				}
			}
			break;
		case 2:
			$result = dbquery("SELECT warn_subject, warn_datestamp FROM ".DB_WARNING." ORDER BY warn_datestamp");
			while($data = dbarray($result)) {
				$data2 = dbarray(dbquery("SELECT warn_length FROM ".DB_WARNING_CATALOG." WHERE warn_id='".$data['warn_subject']."'"));
				if(($data['warn_datestamp']+($data2['warn_length']*86400))<=(date('U')-63072000)) {
					$sql3 = dbquery("DELETE FROM ".DB_WARNING." WHERE warn_id='".$data['warn_subject']."';");
				}
			}
			break;
		case 3:
			$result = dbquery("SELECT warn_subject, warn_datestamp FROM ".DB_WARNING." ORDER BY warn_datestamp");
			while($data = dbarray($result)) {
				$data2 = dbarray(dbquery("SELECT warn_length FROM ".DB_WARNING_CATALOG." WHERE warn_id='".$data['warn_subject']."'"));
				if(($data['warn_datestamp']+($data2['warn_length']*86400))<=date('U')) {
				  $sql3 = dbquery("DELETE FROM ".DB_WARNING." WHERE warn_id='".$data['warn_subject']."';");
				}
			}
			break;
		case 4:
			$result = dbquery("DELETE FROM ".DB_WARNING.";");
			break;
		case 5:
			$result = dbquery("TRUNCATE TABLE ".DB_WARNING.";");
			break;
		case 6:
			$result = dbquery("TRUNCATE TABLE ".DB_WARNING_CATALOG.";");
			break;
	}
	redirect(ADMIN."warnings.php".$aidlink."&amp;site=4");
 }
}

opentable(L_WARN_ADMIN_4_CLEANUP);
	echo "<br />
	<form action='".ADMIN."warnings.php".$aidlink."&amp;site=4' method='post'>
	<table width='80%' cellpadding='5' cellspacing='0' border='0' align='center'>
		<tr class='tbl2'>
			<td align='left' valign='top' colspan='2'><strong>".L_WARN_ADMIN_4_DELETE."</strong></td>
		</tr>
		<tr class='tbl1'>
			<td width='50' align='center' valign='top'><input type='radio' name='optDel' value='1'></td>
			<td align='left' valign='top'>".L_WARN_ADMIN_4_EXP2Y."</td>
		</tr>
		<tr class='tbl2'>
			<td width='50' align='center' valign='top'><input type='radio' name='optDel' value='2'></td>
			<td align='left' valign='top'>".L_WARN_ADMIN_4_EXP1Y."</td>
		</tr>
		<tr class='tbl1'>
			<td width='50' align='center' valign='top'><input type='radio' name='optDel' value='3'></td>
			<td align='left' valign='top'>".L_WARN_ADMIN_4_ALLEX."</td>
		</tr>
		<tr class='tbl2'>
			<td width='50' align='center' valign='top'><input type='radio' name='optDel' value='4'></td>
			<td align='left' valign='top'>".L_WARN_ADMIN_4_ALLWA."</td>
		</tr>
		<tr class='tbl1'>
			<td width='50' align='center' valign='top'><input type='radio' name='optDel' value='5'></td>
			<td align='left' valign='top'>".L_WARN_ADMIN_4_ALLTRUN."</td>
		</tr>
		<tr class='tbl2'>
			<td width='50' align='center' valign='top'><input type='radio' name='optDel' value='6'></td>
			<td align='left' valign='top'>".L_WARN_ADMIN_4_ALLREATRUN."</td>
		</tr>
		<tr class='tbl1'>
			<td width='50' align='center' valign='top'><input type='checkbox' name='chkYes' value='YES'></td>
			<td align='left' valign='top'><strong>".L_WARN_ADMIN_4_IKNOW."</strong></td>
		</tr>
		<tr class='tbl2'>
			<td align='right' valign='top' colspan='2'><input type='submit' name='btnDEL' value='".L_WARN_ADMIN_4_DELETE1."' class='button'></td>
		</tr>
	</table>
	<br />
	</form>";
closetable();

break;

}

require_once TEMPLATES."footer.php";
?>