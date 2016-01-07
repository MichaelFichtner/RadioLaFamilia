<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Sendeplan v2.1 for PHP-Fusion 7
| Filename: gr_sendeplan_admin.php
| Author: Ralf Thieme (Gr@n@dE)
| Homepage: www.granade.eu
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";

if (!checkrights("GRSP") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

include INFUSIONS."gr_sendeplan/infusion_db.php";
if (file_exists(INFUSIONS."gr_sendeplan/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_sendeplan/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_sendeplan/locale/German/index.php";
}

opentable($locale['grsp104']);
if (IsSeT($_POST['save'])) {
	if ($_POST['offstart'] == 0 || $_POST['offstop']== 0) {
		$offstart = 0;
		$offstop = 0;
	} else {
		$offstart = $_POST['offstart'];
		$offstop = $_POST['offstop'];
	}
	$result = dbquery("UPDATE ".DB_GR_SENDEPLAN_SETTINGS." SET 	grss_sgroup='".$_POST['sgroup']."', grss_ggroup='".$_POST['ggroup']."', grss_agroup='".$_POST['agroup']."', grss_rhythmus='".$_POST['rhythmus']."', grss_djon='".$_POST['djon']."', grss_week='".$_POST['week']."', grss_djedit='".$_POST['djedit']."', grss_djoff='".$_POST['djoff']."', grss_replay='".$_POST['replay']."', grss_preview='".$_POST['preview']."', grss_djpic='".$_POST['djpic']."', grss_autodjpic='".$_POST['autodjpic']."',	grss_offstart='".$offstart."', grss_offstop='".$offstop."', grss_offmsg='".stripinput($_POST['offmsg'])."' WHERE grss_id='1'");
	redirect(FUSION_SELF.$aidlink."&status=1");
} else {
	if (IsSeT($_GET['status']) && $_GET['status'] == 1) {
		echo "<div align='center'>".$locale['grsp141']."</div><br />";
	}
	$result = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN_SETTINGS."");
	$sps = dbarray($result);
	echo '<form method="post" action="'.FUSION_SELF.$aidlink.'"><table width="80%" align="center" class="tbl-border">
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp145'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="sgroup" class="textbox" style="width: 200px;">\n';
		if ($sps['grss_sgroup'] == 0) { echo '<option value="">'.$locale['grsp128'].'</option>\n'; }
		$result = dbquery("SELECT * FROM ".DB_USER_GROUPS."");
		while ($data = dbarray($result)) {
			echo "<option".($sps['grss_sgroup'] == $data['group_id'] ? " selected" : "")." value='".$data['group_id']."'>[".$data['group_id']."] ".$data['group_name']."</option>\n";
		}
		echo '</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp146'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="ggroup" class="textbox" style="width: 200px;">\n';
		if ($sps['grss_ggroup'] == 0) { echo '<option value="">'.$locale['grsp128'].'</option>\n'; }
		$result = dbquery("SELECT * FROM ".DB_USER_GROUPS."");
		while ($data = dbarray($result)) {
			echo "<option".($sps['grss_ggroup'] == $data['group_id'] ? " selected" : "")." value='".$data['group_id']."'>[".$data['group_id']."] ".$data['group_name']."</option>\n";
		}
		echo '</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp147'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="agroup" class="textbox" style="width: 200px;">\n';
		if ($sps['grss_agroup'] == 0) { echo '<option value="">'.$locale['grsp128'].'</option>\n'; }
		$result = dbquery("SELECT * FROM ".DB_USER_GROUPS."");
		while ($data = dbarray($result)) {
			echo "<option".($sps['grss_agroup'] == $data['group_id'] ? " selected" : "")." value='".$data['group_id']."'>[".$data['group_id']."] ".$data['group_name']."</option>\n";
		}
		echo '</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp150'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="rhythmus" class="textbox" style="width: 200px;">
			<option'.($sps['grss_rhythmus'] == 1 ? ' selected' : '').' value="1">'.$locale['grsp148'].'</option>
			<option'.($sps['grss_rhythmus'] == 2 ? ' selected' : '').' value="2">'.$locale['grsp149'].'</option>
		</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp151'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="djon" class="textbox" style="width: 200px;">
			<option'.($sps['grss_djon'] == 1 ? ' selected' : '').' value="1">'.$locale['grsp143'].'</option>
			<option'.($sps['grss_djon'] == 0 ? ' selected' : '').' value="0">'.$locale['grsp144'].'</option>
		</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp162'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="week" class="textbox" style="width: 200px;">
			<option'.($sps['grss_week'] == 1 ? ' selected' : '').' value="1">'.$locale['grsp163'].'</option>
			<option'.($sps['grss_week'] == 0 ? ' selected' : '').' value="0">'.$locale['grsp164'].'</option>
		</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp152'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="djedit" class="textbox" style="width: 200px;">
			<option'.($sps['grss_djedit'] == 1 ? ' selected' : '').' value="1">'.$locale['grsp143'].'</option>
			<option'.($sps['grss_djedit'] == 0 ? ' selected' : '').' value="0">'.$locale['grsp144'].'</option>
		</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp153'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="djoff" class="textbox" style="width: 200px;">
			<option'.($sps['grss_djoff'] == 1 ? ' selected' : '').' value="1">'.$locale['grsp143'].'</option>
			<option'.($sps['grss_djoff'] == 0 ? ' selected' : '').' value="0">'.$locale['grsp144'].'</option>
		</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp154'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="replay" class="textbox" style="width: 200px;">
			<option'.($sps['grss_replay'] == 1 ? ' selected' : '').' value="1">'.$locale['grsp143'].'</option>
			<option'.($sps['grss_replay'] == 0 ? ' selected' : '').' value="0">'.$locale['grsp144'].'</option>
		</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp155'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="preview" class="textbox" style="width: 200px;">
			<option'.($sps['grss_preview'] == 1 ? ' selected' : '').' value="1">'.$locale['grsp143'].'</option>
			<option'.($sps['grss_preview'] == 0 ? ' selected' : '').' value="0">'.$locale['grsp144'].'</option>
		</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp160'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="djpic" class="textbox" style="width: 200px;">
			<option'.($sps['grss_djpic'] == 1 ? ' selected' : '').' value="1">'.$locale['grsp143'].'</option>
			<option'.($sps['grss_djpic'] == 0 ? ' selected' : '').' value="0">'.$locale['grsp144'].'</option>
		</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp161'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="autodjpic" class="textbox" style="width: 200px;">
			<option'.($sps['grss_autodjpic'] == 1 ? ' selected' : '').' value="1">'.$locale['grsp143'].'</option>
			<option'.($sps['grss_autodjpic'] == 0 ? ' selected' : '').' value="0">'.$locale['grsp144'].'</option>
		</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp156'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="offstart" class="textbox" style="width: 200px;">
		<option value="0">'.$locale['grsp159'].'</option>\n';
			$i=1;
			$i2=0;
			while ($i < 163) {
				echo "<option".($sps['grss_offstart'] == $i ? " selected" : "")." value='".$i."'>".$i2." Uhr</option>\n";
				$i = $i+7;
				$i2++;
			}
			echo '</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp157'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><select name="offstop" class="textbox" style="width: 200px;">
		<option value="0">'.$locale['grsp159'].'</option>\n';
			$i=1;
			$i2=1;
			while ($i < 163) {
				echo "<option".($sps['grss_offstop'] == $i ? " selected" : "")." value='".$i."'>".$i2." Uhr</option>\n";
				$i = $i+7;
				$i2++;
			}
			echo '</select></td>
	</tr>
	<tr>
		<td class="tbl1" align="right">'.$locale['grsp158'].'<span style="color:#ff0000">*</span></td>
		<td class="tbl2"><input type="text" name="offmsg" class="textbox" value="'.$sps['grss_offmsg'].'" style="width: 200px;" /></td>
	</tr>
	<tr>
		<td class="tbl1" align="center" colspan="2"><input type="submit" name="save" value="'.$locale['grsp142'].'" class="button" /></td>
	</tr>
</table>
</form>';
}

echo "<div align='right'><a href='http://www.granade.eu/scripte/sendeplan.html' target='_blank'>Sendeplan &copy;</a></div>";
closetable();

require_once THEMES."templates/footer.php";
?>