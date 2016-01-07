<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: smileys.php
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
include LOCALE.LOCALESET."admin/smileys.php";

if (!checkrights("SM") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "sn") {
		$message = $locale['410'];
	} elseif ($_GET['status'] == "su") {
		$message = $locale['411'];
	} elseif ($_GET['status'] == "del") {
		$message = $locale['412'];
	} elseif ($_GET['status'] == "sue") {
		$message = $locale['413']."<br />\n<span class='small'>".$locale['415']."</span>";
	} elseif ($_GET['status'] == "sne") {
		$message = $locale['414']."<br />\n<span class='small'>".$locale['415']."</span>";
	} elseif ($_GET['status'] == "noe") {
		$message = "The following error has occured:"."<br />\n<span class='small'>"."No Text/Code entered or no Image selected"."</span>";
	}
	if (isset($message)) { echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
}

if (isset($_POST['save_smiley'])) {
	if (QUOTES_GPC) {
		$_POST['smiley_code'] = stripslashes($_POST['smiley_code']);
	}
	$smiley_code = str_replace(array("\"", "'", "\\", '\"', "\'", "<", ">"), "", $_POST['smiley_code']);
	$smiley_image = stripinput($_POST['smiley_image']);
	$smiley_text = stripinput($_POST['smiley_text']);
	if ($smiley_code && $smiley_text && $smiley_image) {
		if (isset($_GET['smiley_id']) && isnum($_GET['smiley_id'])) {
			if (!dbcount("(smiley_id)", DB_SMILEYS, "smiley_code='".$smiley_code."' AND smiley_id!='".$_GET['smiley_id']."'")) {
				$result = dbquery("UPDATE ".DB_SMILEYS." SET smiley_code='".$smiley_code."', smiley_image='".$smiley_image."', smiley_text='".$smiley_text."' WHERE smiley_id='".$_GET['smiley_id']."'");
				log_admin_action("admin-3", "admin_smileys_edited"); // Log Admin's Action
				redirect(FUSION_SELF.$aidlink."&status=su");
			} else {
				redirect(FUSION_SELF.$aidlink."&status=sue");
			}
		} else {
			if (!dbcount("(smiley_id)", DB_SMILEYS, "smiley_code='".$smiley_code."'") && $smiley_image) {
				$result = dbquery("INSERT INTO ".DB_SMILEYS." (smiley_code, smiley_image, smiley_text) VALUES ('".$smiley_code."', '".$smiley_image."', '".$smiley_text."')");
				log_admin_action("admin-3", "admin_smileys_saved"); // Log Admin's Action
				redirect(FUSION_SELF.$aidlink."&status=sn");
			} else {
				redirect(FUSION_SELF.$aidlink."&status=sne");
			}
		}
	} else {
		redirect(FUSION_SELF.$aidlink."&status=noe");
	}
}

if ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['smiley_id']) && isnum($_GET['smiley_id']))) {
	$result = dbquery("DELETE FROM ".DB_SMILEYS." WHERE smiley_id='".$_GET['smiley_id']."'");
	log_admin_action("admin-3", "admin_smileys_deleted"); // Log Admin's Action
	redirect(FUSION_SELF.$aidlink."&status=del");
}

if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['smiley_id'])&& isnum($_GET['smiley_id']))) {
	$result = dbquery("SELECT smiley_code, smiley_image, smiley_text FROM ".DB_SMILEYS." WHERE smiley_id='".$_GET['smiley_id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$smiley_code = $data['smiley_code'];
		$smiley_image = $data['smiley_image'];
		$smiley_text = $data['smiley_text'];
		$form_action = FUSION_SELF.$aidlink."&amp;smiley_id=".$_GET['smiley_id'];
		$form_title = $locale['402'];
	} else {
		redirect(FUSION_SELF.$aidlink);
	}
} else {
	$smiley_code = "";
	$smiley_image = "";
	$smiley_text = "";
	$form_action = FUSION_SELF.$aidlink;
	$form_title = $locale['401'];
}
opentable($form_title);
$image_files = makefilelist(IMAGES."smiley/", ".|..|index.php", true);
$image_list = "<option value=''>&nbsp;</option>\n";
$image_list .= makefileopts($image_files, $smiley_image);
echo "<form name='smiley_form' method='post' action='".$form_action."'>\n";
echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
echo "<td class='tbl'>".$locale['420']."</td>\n";
echo "<td class='tbl'><input type='text' name='smiley_code' value='".$smiley_code."' class='textbox' style='width:100px' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['421']."</td>\n";
echo "<td class='tbl'><select name='smiley_image' id='smiley_image' class='textbox' onchange=\"PreviewSmiley();\">".$image_list."</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['424']."</td>\n";
echo "<td class='tbl'><img src='".($smiley_image ? IMAGES."smiley/".$smiley_image : IMAGES."imagenotfound.jpg")."' alt='smiley' style='border:none' id='smiley_preview' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['422']."</td>\n";
echo "<td class='tbl'><input type='text' name='smiley_text' value='".$smiley_text."' class='textbox' style='width:100px' /></td>\n</tr>\n";
echo "<tr>\n<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='save_smiley' value='".$locale['423']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
echo "<script type='text/javascript'>\n";
echo "function PreviewSmiley() {\n";
echo "\tvar selectSmiley = document.getElementById('smiley_image');\n";
echo "\tvar imageSmiley = document.getElementById('smiley_preview');\n";
echo "\tvar optionValue = selectSmiley.options[selectSmiley.selectedIndex].value;\n";
echo "\tif (optionValue!='') {\n";
echo "\t\timageSmiley.src = '".IMAGES."smiley/' + optionValue;\n";
echo "\t} else {\n";
echo "\t\timageSmiley.src = '".IMAGES."imagenotfound.jpg';\n";
echo "\t}\n";
echo "}\n";
echo "function ConfirmDelete() {\n";
echo "return confirm('".$locale['416']."');\n";
echo "}\n";
echo "</script>\n";
closetable();

// Active Smilies
$result_get_active_smiley = dbquery("SELECT smiley_id, smiley_code, smiley_image, smiley_text FROM ".DB_SMILEYS." ORDER BY smiley_text");

$used_smiley = array();
$output_active_smiley = "";
if (dbrows($result_get_active_smiley)) {
	$i = 0;
	while ($data = dbarray($result_get_active_smiley)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
		$used_smiley[] = $data['smiley_image'];
		$output_active_smiley .= "<tr>\n<td class='".$row_color."'>".$data['smiley_code']."</td>\n";
		$output_active_smiley .= "<td class='".$row_color."'><img src='".IMAGES."smiley/".$data['smiley_image']."' alt='".$data['smiley_text']."' /></td>\n";
		$output_active_smiley .= "<td class='".$row_color."'>".$data['smiley_text']."</td>\n";
		$output_active_smiley .= "<td class='".$row_color."' width='1%' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;smiley_id=".$data['smiley_id']."'>".$locale['434']."</a> -\n";
		$output_active_smiley .= "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;smiley_id=".$data['smiley_id']."' onclick=\"return ConfirmDelete();\">".$locale['435']."</a></td>\n</tr>\n";
		$i++;
	}
	
} 

// Inactive Smilies
$i = 0;
$output_inactive_smiley = "";
foreach ($image_files as $value) {
    if(!in_array($value, $used_smiley)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");

		$output_inactive_smiley .= "<tr>\n";
		$output_inactive_smiley .= "<form name='smiley_form' method='post' action='".FUSION_SELF.$aidlink."'>";
		$output_inactive_smiley .= "<td class='".$row_color."'><img src='".IMAGES."smiley/".$value."' alt='".$value."' /><input type='hidden' name='smiley_image' value='".$value."'></td>\n";
		$output_inactive_smiley .= "<td class='".$row_color."'><input type='text' name='smiley_code' value='' class='textbox' style='width:100px' /></td>\n";
		$output_inactive_smiley .= "<td class='".$row_color."'><input type='text' name='smiley_text' value='' class='textbox' style='width:100px' /></td>\n";
		$output_inactive_smiley .= "<td class='".$row_color."' width='1%' style='white-space:nowrap'><input type='submit' name='save_smiley' value='".$locale['423']."' class='button' /></td>\n";
		$output_inactive_smiley .= "</form>";
		$output_inactive_smiley .= "</tr>\n";
		$i++;

	}
}

// Show Inactive Smiles
if($output_inactive_smiley != "") {
	opentable($locale['437']);
	echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n";
	echo "<tr>\n";
	echo "<td class='tbl2'><strong>".$locale['431']."</strong></td>\n";
	echo "<td class='tbl2'><strong>".$locale['430']."</strong></td>\n";
	echo "<td class='tbl2'><strong>".$locale['432']."</strong></td>\n";
	echo "<td class='tbl2' width='1%' style='white-space:nowrap'><strong>".$locale['433']."</strong></td>\n</tr>\n";
	echo $output_inactive_smiley;
	echo "</table>\n";
	closetable();
}

// Show active Smilies
opentable($locale['400']);
if($output_active_smiley != "") {
	echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n";
	echo "<tr>\n<td class='tbl2'><strong>".$locale['430']."</strong></td>\n";
	echo "<td class='tbl2'><strong>".$locale['431']."</strong></td>\n";
	echo "<td class='tbl2'><strong>".$locale['432']."</strong></td>\n";
	echo "<td class='tbl2' width='1%' style='white-space:nowrap'><strong>".$locale['433']."</strong></td>\n</tr>\n";
	echo $output_active_smiley;
	echo "</table>\n";
} else {
	echo "<div style='text-align:center'><br />\n".$locale['436']."<br /><br />\n</div>\n";
}
closetable();

require_once TEMPLATES."footer.php";
?>