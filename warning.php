<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: warning.php
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
require_once "maincore.php";
require_once TEMPLATES."header.php";

require_once LOCALE.LOCALESET."warning.php";
require_once INCLUDES."warning.inc.php";

if ($settings['warning_set_visible'] == "0" && !iMEMBER) {
	opentable("Warning System");
	echo "<br />No Access for Guests<br /><br />";
	closetable();
	require_once TEMPLATES."footer.php";
	exit;
}

if(isset($_GET['lookup']) && isnum($_GET['lookup'])) {
    $lookup = $_GET['lookup'];
	define("WARN_K", "USER");
} elseif (isset($_POST['lookup']) && isnum($_POST['lookup'])) {
    $lookup = $_POST['lookup'];
	define("WARN_K", "USER");
} else {
	$lookup = 0;
}

if(isset($_GET['postid']) && isnum($_GET['postid'])) {
	$postid = $_GET['postid'];
	define("WARN_K", "ID");
} elseif (isset($_POST['postid']) && isnum(($_POST['postid']))) {
	$postid = $_POST['postid'];
	define("WARN_K", "ID");
} else {
	$postid = 0;
}

if(!defined('WARN_K')) { redirect(make_url("index.php", BASEDIR."index", "", ".html")); }

if (isset($_GET['warnid']) && isnum($_GET['warnid'])) {
	$warnid = $_GET['warnid'];
} elseif(isset($_POST['warnid']) && isnum($_POST['warnid'])) {
	$warnid = $_POST['warnid'];
} elseif(isset($_POST['warnid']) || isset($_GET['warnid'])) {
	redirect(make_url("index.php", BASEDIR.SEO_INDEX_A, "", SEO_INDEX_C));
} else {
	$warnid = false;
}

if(WARN_K == "ID") {
	$query_warning = dbquery("SELECT post_author FROM ".DB_POSTS." WHERE post_id='".(int)$postid."'");
	if(dbrows($query_warning) != 1) { redirect(BASEDIR."index.php"); }
	$data_warning = dbarray($query_warning);
	$warn_user_id = $data_warning['post_author'];
} else {
	$warn_user_id = $lookup;
}

if(WARN_K == "USER") {
	define("WARN_KIND", "Other");
} else {
	define("WARN_KIND", "Forum");
}

###### Get an Array with the Warned User's Data
$data_warned_user = dbarray(dbquery("SELECT user_id, user_name, user_status FROM ".DB_USERS." WHERE user_id='".(int)$warn_user_id."'"));
######

## Give a Warning to a user
if(isset($_POST['btnSubmitWarning']) && !empty($_POST['warn_subject']) && !empty($_POST['warn_message']) && checkgroup($settings['warning_set_usergroup'])) {
    if ($warnid) {
		$sql = dbquery("UPDATE ".DB_WARNING." SET warn_subject="._db($_POST['warn_subject']).",
		warn_message="._db($_POST['warn_message']).", warn_point="._db($_POST['warn_point'])." WHERE warn_id='".(int)$warnid."'");
    } else {
        #if (WARN_K == "USER" OR (isset($_POST['postid']) && $_POST['postid'] > 0 )) {
			if(WARN_K == "ID") { $insert_id = $postid; } else { $insert_id = $warn_user_id; }
			if(new_warning_post($insert_id, $_POST['warn_subject'], $_POST['warn_message'], ($_POST['warn_point']=="" ? warning_points($_POST['warn_subject']) : $_POST['warn_point']), WARN_KIND) == FALSE) { ### Funktion wieder anpassen in der warnings.inc.php
			opentable($locale['WARN400']);
			echo "<br /><center>".$locale['WARN401']."</center><br /><br />";
			closetable();
			}
		#}
    }
}

# Delete Warning of a user
if(isset($_GET['opt']) && $_GET['opt'] == "delete" && $warnid && checkgroup($settings['warning_set_usergroup'])) {
	$del = dbquery("DELETE FROM ".DB_WARNING." WHERE warn_id='".(int)$warnid."'");
}

# The Form to warn a user or edit a warning
if (checkgroup($settings['warning_set_usergroup'])) {
	if(WARN_K == "USER") {
		echo "<form action='".BASEDIR."warning.php?lookup=".$lookup."' method='POST'>";
	} else {
		echo "<form action='".BASEDIR."warning.php?postid=".$postid."' method='POST'>";
	}
    if (isset($_GET['opt']) && $_GET['opt'] == "edit") {
		$editwarndata = dbarray(dbquery("SELECT * FROM ".DB_WARNING." WHERE warn_id='".(int)$warnid."'"));
		echo "<input type='hidden' name='warnid' value='".$warnid."'>";
		$form_titel = $locale['WARN402'];
		$form_subject = $editwarndata['warn_subject'];
		$form_message = $editwarndata['warn_message'];
		$form_points = $editwarndata['warn_point'];
		$form_submit = $locale['WARN403'];
	} else {
		$form_titel = $locale['WARN404'];
		$form_subject = NULL;
		$form_message = NULL;
		$form_points = NULL;
		$form_submit = $locale['WARN405'];
	}
	
	opentable($form_titel);
	echo "<br />
	<table width='80%' cellpadding='2' cellspacing='0' border='0' align='center'>
		<tr class='tbl2'>
			<td align='left' valign='top' width='200'>".$locale['WARN406']."</td>
			<td align='left' valign='top'>
				<select name='warn_subject' class='textbox' style='width:100%;'>";
					$warn_catalog = dbquery("SELECT warn_id, warn_subject, warn_point FROM ".DB_WARNING_CATALOG." WHERE warn_kind='".WARN_KIND."' ORDER BY warn_point, warn_subject");
					while($wcdata = dbarray($warn_catalog)) {
						echo "<option ".($form_subject==$wcdata['warn_id'] ? "selected" : "")." value='".$wcdata['warn_id']."'>".$wcdata['warn_subject']." (".$wcdata['warn_point'].")</option>";
					}
	echo "	  </select>
			</td>
		</tr>
		<tr>
			<td align='left' valign='top' width='200'>".$locale['WARN407']."</td>
			<td align='left' valign='top'><textarea class='textbox' style='width:100%; height:50px;' name='warn_message'>".$form_message."</textarea></td>
		</tr>
		<tr class='tbl2'>
			<td align='left' valign='top' width='200'>".$locale['WARN408']."</td>
			<td align='left' valign='top'><input type='text' class='textbox' name='warn_point' value='".$form_points."' maxlength='3' size='5'> ".$locale['WARN409']."</td>
		</tr>";
if(WARN_K == "ID") {
	echo"<tr>
			<td align='left' valign='top' width='200'>".$locale['WARN422']."</td>
			<td align='left' valign='top'>".warning_forum_link($postid)."</td>
		</tr>
		<tr>
			<td align='left' valign='top' width='200'></td>
			<td align='left' valign='top'>".$locale['WARN423']."<a href='".BASEDIR."warning.php?lookup=".$warn_user_id."'>".$locale['WARN424']."</a>".$locale['WARN425']."</td>
		</tr>"; }
	echo"<tr>
			<td align='left' valign='top' width='200'></td>
			<td align='right' valign='top'><input type='submit' name='btnSubmitWarning' value='".$form_submit."' class='textbox'></td>
		</tr>
	</table>
	</form>
	<br />";
	closetable();
}


// Show valid warnings
$sql_warning_archiv = dbquery("SELECT warn_id, warn_kind, post_id, warn_subject, warn_message, warn_point, warn_admin, warn_datestamp FROM ".DB_WARNING." WHERE user_id = '".$warn_user_id."' ORDER BY warn_datestamp");
$counter = 0;
$count_verwarn_points = 0;
while ($data_warning_archiv = dbarray($sql_warning_archiv)) {
	$data_warning_archiv2 = dbarray(dbquery("SELECT warn_subject, warn_kind, warn_length FROM ".DB_WARNING_CATALOG." WHERE warn_id='".$data_warning_archiv['warn_subject']."'"));
	if(($data_warning_archiv['warn_datestamp']+($data_warning_archiv2['warn_length']*86400))>date("U")) {
		if ($counter == 0) {
			opentable($locale['WARN410']." (".
			profile_link($data_warned_user['user_id'], $data_warned_user['user_name'], $data_warned_user['user_status'], "white").")");
			echo "<table width='80%' cellpadding='2' cellspacing='0' border='0' align='center'>";
		}
		echo "<tr>";
		echo 	"<td colspan='2' align='left' valign='top'>&nbsp;</td>";
		echo "</tr>";
		echo "<tr class='tbl2'>";
		echo 	"<td align='left' valign='top'>";
		echo 	"<strong>".$data_warning_archiv2['warn_subject']."  (".$locale['WARN426']." ".$data_warning_archiv2['warn_kind'].")</strong>";
		echo 	"</td>";
		echo 	"<td align='right' valign='top'>";
		if(checkgroup($settings['warning_set_usergroup'])) {
			## hier in zukunft: 2 verschiedene Links:
			## -Forum Warning
			## -Allgemeine Warnnings
			if($data_warning_archiv['warn_kind'] == "Forum") {
				echo "<a href='".BASEDIR."warning.php?postid=".$data_warning_archiv['post_id']."&amp;warnid=".$data_warning_archiv['warn_id']."&amp;opt=edit'>";
				echo "<img src='".IMAGES."edit.gif' alt='".$locale['WARN411']."' title='".$locale['WARN411']."' border='0'>";
				echo "</a>";
				echo "<a href='".BASEDIR."warning.php?postid=".$data_warning_archiv['post_id']."&amp;&amp;warnid=".$data_warning_archiv['warn_id']."&amp;opt=delete'>";
				echo "<img src='".IMAGES."delete.gif' alt='".$locale['WARN412']."' title='".$locale['WARN412']."' border='0'>";
				echo "</a>";
			} else {
				echo "<a href='".BASEDIR."warning.php?lookup=".$warn_user_id."&amp;warnid=".$data_warning_archiv['warn_id']."&amp;opt=edit'>";
				echo "<img src='".IMAGES."edit.gif' alt='".$locale['WARN411']."' title='".$locale['WARN411']."' border='0'>";
				echo "</a>";
				echo "<a href='".BASEDIR."warning.php?lookup=".$warn_user_id."&amp;&amp;warnid=".$data_warning_archiv['warn_id']."&amp;opt=delete'>";
				echo "<img src='".IMAGES."delete.gif' alt='".$locale['WARN412']."' title='".$locale['WARN412']."' border='0'>";
				echo "</a>";
			}
		}
		echo 	"</td>";
		echo "</tr>";
		echo "<tr>";
		echo 	"<td align='left' valign='top'>";
		if($data_warning_archiv['post_id'] > 0 ) {
			echo warning_forum_link($data_warning_archiv['post_id']);
		}
		echo 	"<br />";
		echo 	nl2br($data_warning_archiv['warn_message']);
		echo 	"</td>";
		echo 	"<td width='170' align='right' valign='top'>";
		echo 	"<strong>".$data_warning_archiv['warn_point']."</strong> ".($data_warning_archiv['warn_point']==1 ? $locale['WARN413'] : $locale['WARN414'])."<br />";
		
		$data_w = dbarray(dbquery("SELECT warn_length FROM ".DB_WARNING_CATALOG." WHERE warn_id='".(int)$data_warning_archiv['warn_subject']."'"));
		$warning_length = $data_warning_archiv['warn_datestamp'] + ($data_w['warn_length'] * 86400);
		
		echo 	$locale['WARN415']." <strong>".date("d.m.Y", $warning_length)."</strong><br />";
		echo 	"<i>".date($locale['WARN416'], $data_warning_archiv['warn_datestamp']).", ".warning_user($data_warning_archiv['warn_admin'])."</i>";
		echo 	"</td>";
		echo "</tr>";
		$counter++;
		$count_verwarn_points = $count_verwarn_points + $data_warning_archiv['warn_point'];
	}
}

if($counter != 0) {
	echo "<tr>";
	echo	"<td colspan='2' align='left' valign='top'>&nbsp;</td>";
	echo "</tr>";
	echo "<tr>";
	echo	"<td align='left' valign='top'>&nbsp;</td>";
	echo	"<td align='center' valign='top' class='tbl2'>";
	echo	"<strong>".$locale['WARN417']." ".number_format($count_verwarn_points)." ".($count_verwarn_points==1 ? $locale['WARN413'] : $locale['WARN414'])."</strong>";
	echo	"</td>";
	echo "</tr>";
	echo "</table><br />";
	closetable();
} else {
	opentable($locale['WARN418']);
	echo "<br /><center>".$locale['WARN419']."</center><br /><br />";
	closetable();
}

// Show unvalid warnings
$sql_warning_archiv = dbquery("SELECT post_id, warn_subject, warn_message, warn_point, warn_admin, warn_datestamp FROM ".DB_WARNING."
WHERE user_id = '".$warn_user_id."' ORDER BY warn_datestamp");
$counter = 0;
while($data_warning_archiv = dbarray($sql_warning_archiv)) {
	$data_warning_archiv2 = dbarray(dbquery("SELECT warn_subject, warn_kind, warn_length FROM ".DB_WARNING_CATALOG."
	WHERE warn_id='".$data_warning_archiv['warn_subject']."'"));
	if(($data_warning_archiv['warn_datestamp'] + ($data_warning_archiv2['warn_length']*86400)) <= date("U")) {
		if($counter == 0) {
			opentable($locale['WARN420']);
			echo "<table width='80%' cellpadding='2' cellspacing='0' border='0' align='center'>";
		}
		echo "<tr>";
		echo	"<td colspan='2' align='left' valign='top'>&nbsp;</td>";
		echo "</tr>";
		echo "<tr class='tbl2'>";
		echo "<td align='left' valign='top'><strong>".$data_warning_archiv2['warn_subject']."  (Kind: ".$data_warning_archiv2['warn_kind'].")</strong></td>";
		echo "<td align='right' valign='top'></td>";
		echo "</tr>";
		echo "<tr>";
		echo	"<td align='left' valign='top'>";
		if ($data_warning_archiv['post_id']>0 ) { 
			echo warning_forum_link($data_warning_archiv['post_id']);
		}
		echo 	"<br />".nl2br($data_warning_archiv['warn_message']);
		echo 	"</td>";
		echo 	"<td width='170' align='right' valign='top'>";
		echo	"<strong>".$data_warning_archiv['warn_point']."</strong> ";
		echo	($data_warning_archiv['warn_point']==1 ? $locale['WARN413'] : $locale['WARN414'])."<br />";
		
		$data_w = dbarray(dbquery("SELECT warn_length FROM ".DB_WARNING_CATALOG." WHERE warn_id='".(int)$data_warning_archiv['warn_subject']."'"));
		$warning_length = $data_warning_archiv['warn_datestamp'] + ($data_w['warn_length'] * 86400);
		
		echo 	$locale['WARN421']." ".date("d.m.Y", $warning_length)."<br />";
		echo 	"<i>".date($locale['WARN416'], $data_warning_archiv['warn_datestamp']).", ".warning_user($data_warning_archiv['warn_admin'])."</i>";
		echo 	"</td>";
		echo "</tr>";
		$counter++;
	}
}
if($counter != 0) { 
	echo "</table><br />";
	closetable();
}

require_once TEMPLATES."footer.php";
?>