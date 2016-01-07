<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Teamlist v2.2 for PHP-Fusion 7
| Filename: gr_teamlist_admin.php
| Author: Ralf Thieme (Gr@n@dE)
| HP: www.granade.eu
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

include INFUSIONS."gr_teamlist/infusion_db.php";
if (!checkrights("GRTL") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../../index.php"); }
if (IsSeT($_GET['id']) && !isnum($_GET['id'])) { redirect("../../index.php"); }

if (file_exists(INFUSIONS."gr_teamlist/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_teamlist/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_teamlist/locale/German/index.php";
}

if (IsSeT($_GET['groups_del']) && isnum($_GET['id'])) {
	$data = dbarray(dbquery("SELECT * FROM ".DB_GR_TEAMLIST_GROUP." WHERE tlg_id='".$_GET['id']."'"));
	$result = dbquery("UPDATE ".DB_GR_TEAMLIST_GROUP." SET tlg_position=tlg_position-1 WHERE tlg_position>'".$data['tlg_position']."'");	
	$result = dbquery("DELETE FROM ".DB_GR_TEAMLIST_GROUP." WHERE tlg_id='".$_GET['id']."'");
	$result = dbquery("DELETE FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_groups='".$_GET['id']."'");
	redirect(FUSION_SELF.$aidlink);
} elseif (IsSeT($_GET['groups_up']) && isnum($_GET['id']) && isnum($_GET['order'])) {
	if ($_GET['order'] > 0) {
		$data = dbarray(dbquery("SELECT * FROM ".DB_GR_TEAMLIST_GROUP." WHERE tlg_position='".$_GET['order']."'"));
		$result = dbquery("UPDATE ".DB_GR_TEAMLIST_GROUP." SET tlg_position=tlg_position+1 WHERE tlg_id='".$data['tlg_id']."'");
		$result = dbquery("UPDATE ".DB_GR_TEAMLIST_GROUP." SET tlg_position=tlg_position-1 WHERE tlg_id='".$_GET['id']."'");
	}
	redirect(FUSION_SELF.$aidlink);
} elseif (IsSeT($_GET['groups_down']) && isnum($_GET['id']) && isnum($_GET['order'])) {
	$link_order = dbresult(dbquery("SELECT MAX(tlg_position) FROM ".DB_GR_TEAMLIST_GROUP.""), 0) + 1;
	if ($_GET['order'] < $link_order) {
		$data = dbarray(dbquery("SELECT * FROM ".DB_GR_TEAMLIST_GROUP." WHERE tlg_position='".$_GET['order']."'"));
		$result = dbquery("UPDATE ".DB_GR_TEAMLIST_GROUP." SET tlg_position=tlg_position-1 WHERE tlg_id='".$data['tlg_id']."'");
		$result = dbquery("UPDATE ".DB_GR_TEAMLIST_GROUP." SET tlg_position=tlg_position+1 WHERE tlg_id='".$_GET['id']."'");
	}
	redirect(FUSION_SELF.$aidlink."&page=".$_GET['page']);
}	elseif (IsSeT($_GET['groups_edit']) && isnum($_GET['id'])) {
	if (IsSeT($_POST['save'])) {
		$teil1 = stripinput($_POST['teil1']);
		$teil2 = stripinput($_POST['teil2']);
		if ($teil1 != "" && $teil2 != "" && isnum($_POST['teil3'])) {
			$result = dbquery("UPDATE ".DB_GR_TEAMLIST_GROUP." SET tlg_name='".$teil1."', tlg_pic='".$teil2."', tlg_status='".$_POST['teil3']."' WHERE tlg_id='".$_GET['id']."'");
			redirect(FUSION_SELF.$aidlink);
		} else {
			redirect(FUSION_SELF.$aidlink."&groups_edit&id=".$_GET['id']);
		}
	} else {
		opentable($locale['grtl241']);
		$result = dbquery("SELECT * FROM ".DB_GR_TEAMLIST_GROUP." WHERE tlg_id='".$_GET['id']."'");
		$tl_group = dbarray($result);
		echo "<form action='".FUSION_SELF.$aidlink."&groups_edit&id=".$_GET['id']."' method='post'><table align='center' class='tbl-border' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='tbl1'>".$locale['grtl238']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><input type='text' name='teil1' class='textbox' value='".$tl_group['tlg_name']."' style='width: 200px;' maxlength='100' /></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl239']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'>";
				$image_files = makefilelist(INFUSIONS."gr_teamlist/images/group/", ".|..|index.php", true);
				$image_list = makefileopts($image_files);
				echo "<select name='teil2' class='textbox' style='width:200px;'>
				<option selected value='".$tl_group['tlg_pic']."'>".$tl_group['tlg_pic']."</option>
				<option value=''>-----------------------</option>
				$image_list</select></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl246']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><select name='teil3' class='textbox' style='width:200px;'>
				<option".($tl_group['tlg_status'] == 1 ? " selected" : "")." value='1'>".$locale['grtl247']."</option><option".($tl_group['tlg_status'] == 0 ? " selected" : "")." value='0'>".$locale['grtl248']."</option></select></td>
			</tr>
			<tr>
				<td align='center' classe='tbl2' colspan='2'><br /><input name='save' type='submit' class='button' value='".$locale['grtl236']."' /><br /><br /></td>
			</tr>
			</table></form>
			<a href='".FUSION_SELF.$aidlink."'>".$locale['grtl234']."</a>";		
	}
} elseif (IsSeT($_GET['groups_add'])) {
	if (IsSeT($_POST['save'])) {
		$teil1 = stripinput($_POST['teil1']);
		$teil2 = stripinput($_POST['teil2']);
		if ($teil1 != "" && $teil2 != "" && isnum($_POST['teil3'])) {
			$link_order = dbresult(dbquery("SELECT MAX(tlg_position) FROM ".DB_GR_TEAMLIST_GROUP.""), 0) + 1;
			$result = dbquery("INSERT INTO ".DB_GR_TEAMLIST_GROUP." (tlg_name, tlg_pic, tlg_position, tlg_status) VALUES('".$teil1."', '".$teil2."', '".$link_order."', '".$_POST['teil3']."')"); 
			redirect(FUSION_SELF.$aidlink);
		} else {
			redirect(FUSION_SELF.$aidlink."&groups_add");
		}
	} else {
		opentable($locale['grtl237']);
		echo "<form action='".FUSION_SELF.$aidlink."&groups_add' method='post'><table align='center' class='tbl-border' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='tbl1'>".$locale['grtl238']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><input type='text' name='teil1' class='textbox' style='width: 200px;' maxlength='100' /></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl239']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'>";
				$image_files = makefilelist(INFUSIONS."gr_teamlist/images/group/", ".|..|index.php", true);
				$image_list = makefileopts($image_files);
				echo "<select name='teil2' class='textbox' style='width:200px;'>
				$image_list</select></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl246']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><select name='teil3' class='textbox' style='width:200px;'>
				<option value='0'>".$locale['grtl248']."</option><option value='1'>".$locale['grtl247']."</option></select></td>
			</tr>
			<tr>
				<td align='center' classe='tbl2' colspan='2'><br /><input name='save' type='submit' class='button' value='".$locale['grtl233']."' /><br /><br /></td>
			</tr>
			</table></form>
			<a href='".FUSION_SELF.$aidlink."'>".$locale['grtl234']."</a>";		
	}
} elseif (IsSeT($_GET['users_del']) && isnum($_GET['id'])) {
	$data = dbarray(dbquery("SELECT * FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_id='".$_GET['id']."'"));
	$result = dbquery("UPDATE ".DB_GR_TEAMLIST_USERS." SET tlu_position=tlu_position-1 WHERE tlu_position>'".$data['tlu_position']."'");	
	$result = dbquery("DELETE FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_id='".$_GET['id']."'");
	redirect(FUSION_SELF.$aidlink);
} elseif (IsSeT($_GET['users_up']) && isnum($_GET['id']) && isnum($_GET['order']) && isnum($_GET['groups'])) {
	if ($_GET['order'] > 0) {
		$data = dbarray(dbquery("SELECT * FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_position='".$_GET['order']."' AND tlu_groups='".$_GET['groups']."'"));
		$result = dbquery("UPDATE ".DB_GR_TEAMLIST_USERS." SET tlu_position=tlu_position+1 WHERE tlu_id='".$data['tlu_id']."'");
		$result = dbquery("UPDATE ".DB_GR_TEAMLIST_USERS." SET tlu_position=tlu_position-1 WHERE tlu_id='".$_GET['id']."'");
	}
	redirect(FUSION_SELF.$aidlink);
} elseif (IsSeT($_GET['users_down']) && isnum($_GET['id']) && isnum($_GET['order']) && isnum($_GET['groups'])) {
	$link_order = dbresult(dbquery("SELECT MAX(tlu_position) FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_groups='".$_GET['groups']."'"), 0) + 1;
	if ($_GET['order'] < $link_order) {
		$data = dbarray(dbquery("SELECT * FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_position='".$_GET['order']."' AND tlu_groups='".$_GET['groups']."'"));
		$result = dbquery("UPDATE ".DB_GR_TEAMLIST_USERS." SET tlu_position=tlu_position-1 WHERE tlu_id='".$data['tlu_id']."'");
		$result = dbquery("UPDATE ".DB_GR_TEAMLIST_USERS." SET tlu_position=tlu_position+1 WHERE tlu_id='".$_GET['id']."'");
	}
	redirect(FUSION_SELF.$aidlink);
}	elseif (IsSeT($_GET['users_edit']) && isnum($_GET['id'])) {
	if (IsSeT($_POST['save'])) {
		$teil1 = stripinput($_POST['teil1']);
		$teil2 = stripinput($_POST['teil2']);
		$teil3 = stripinput($_POST['teil3']);
		$teil4 = stripinput($_POST['teil4']);
		$bild = stripinput($_POST['bild']);
		if ($teil1 != "" && $teil2 != "" && isnum($_POST['teil5']) && isnum($_POST['teil6']) && isnum($_GET['groups']) && isnum($_POST['id'])) {
			if ($bild != "" && file_exists(INFUSIONS."gr_teamlist/images/team/".$bild)) {
				$bild_save = $bild;
			} else {
				$bild_save = "";
			}
			if ($_GET['groups'] != $_POST['teil6']) {
				$data = dbarray(dbquery("SELECT * FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_id='".$_POST['id']."'"));
				$result = dbquery("UPDATE ".DB_GR_TEAMLIST_USERS." SET tlu_position=tlu_position-1 WHERE tlu_position>'".$data['tlu_position']."' AND tlu_groups='".$_GET['groups']."'");	
				$link_order = dbresult(dbquery("SELECT MAX(tlu_position) FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_groups='".$_POST['teil6']."'"), 0) + 1;
				$order = ", tlu_position='".$link_order."'";
			} else {
				$order = "";
			}
			$result = dbquery("UPDATE ".DB_GR_TEAMLIST_USERS." SET tlu_pic='".$bild_save."', tlu_rname='".$teil1."', tlu_abteil='".$teil2."', tlu_feld1='".$teil3."', tlu_feld2='".$teil4."', tlu_status='".$_POST['teil5']."', tlu_groups='".$_POST['teil6']."'".$order." WHERE tlu_id='".$_GET['id']."'");
			redirect(FUSION_SELF.$aidlink);
		} else {
			redirect(FUSION_SELF.$aidlink."&users_edit&id=".$_GET['id']);
		}
	} else {
		opentable($locale['grtl235']);
		$result = dbquery("SELECT * FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_id='".$_GET['id']."'");
		$tl_users = dbarray($result);
		if ($tl_users['tlu_userid'] > 0){
			$result2 = dbquery("SELECT user_id, user_name FROM ".DB_USERS." WHERE user_id='".$tl_users['tlu_userid']."'");
			$tl_users2 = dbarray($result2);
			$user_name = $tl_users2['user_name'];
		} else {
			$user_name = "Kein Profil";
		}
		echo "<form action='".FUSION_SELF.$aidlink."&users_edit&id=".$_GET['id']."&groups=".$tl_users['tlu_groups']."' method='post'><table align='center' class='tbl-border' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='tbl1'>".$locale['grtl221']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><input type='hidden' name='id' class='textbox' value='".$tl_users['tlu_id']."' style='width: 200px;' maxlength='50' />
				<input type='text' class='textbox' value='".$user_name."' style='width: 200px;' readonly='readonly' /></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl242']." <span style='color:#ff0000'>**</span></td>
				<td class='tbl2'>";
				$image_files = makefilelist(INFUSIONS."gr_teamlist/images/team/", ".|..|index.php", true);
				$image_list = makefileopts($image_files, $tl_users['tlu_pic']);
				echo "<select name='bild' class='textbox' style='width:200px;'>
				<option value=''>".$locale['grtl243']."</option>
				$image_list</select></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl224']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><input type='text' name='teil1' class='textbox' value='".$tl_users['tlu_rname']."' style='width: 200px;' maxlength='50' /></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl225']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><input type='text' name='teil2' class='textbox' value='".$tl_users['tlu_abteil']."' style='width: 200px;' maxlength='50' /></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl226']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><textarea name='teil3' class='textbox' cols='34' rows='3'>".$tl_users['tlu_feld1']."</textarea></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl227']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><textarea name='teil4' class='textbox' cols='34' rows='3'>".$tl_users['tlu_feld2']."</textarea></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl228']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><select name='teil5' class='textbox' style='width: 200px;'>
					<option".($tl_users['tlu_status'] == 1 ? " selected='selected'" : "")." value='1'>".$locale['grtl229']."</option>
					<option".($tl_users['tlu_status'] == 0 ? " selected='selected'" : "")." value='0'>".$locale['grtl230']."</option></select></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl231']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><select name='teil6' class='textbox' style='width: 200px;'><option value='-'>".$locale['grtl222']."</option>";
				$result = dbquery("SELECT tlg_id, tlg_name FROM ".DB_GR_TEAMLIST_GROUP." ORDER BY tlg_name");
				while ($group_list = dbarray($result)) {
					echo "<option".($tl_users['tlu_groups'] == $group_list['tlg_id'] ? " selected='selected'" : "")." value='".$group_list['tlg_id']."'>".$group_list['tlg_name']."</option>";
				}
				echo "</select></td>
			</tr>
			<tr>
				<td align='center' classe='tbl2' colspan='2'>".$locale['grtl244']."<br />".$locale['grtl245']."<br /><br /><input name='save' type='submit' class='button' value='".$locale['grtl236']."'><br /><br /></td>
			</tr>
			</table></form>
			<a href='".FUSION_SELF.$aidlink."'>".$locale['grtl234']."</a>";		
	}
} elseif (IsSeT($_GET['users_add'])) {
	if (IsSeT($_POST['save'])) {
		$teil2 = stripinput($_POST['teil2']);
		$teil3 = stripinput($_POST['teil3']);
		$teil4 = stripinput($_POST['teil4']);
		$teil5 = stripinput($_POST['teil5']);
		$bild = stripinput($_POST['bild']);
		if (isnum($_POST['teil1']) && $teil2 != "" && $teil3 != "" && isnum($_POST['teil6']) && isnum($_POST['teil7'])) {
			if ($bild != "" && file_exists(INFUSIONS."gr_teamlist/images/team/".$bild)) {
				$bild_save = $bild;
			} else {
				$bild_save = "";
			}
			$link_order = dbresult(dbquery("SELECT MAX(tlu_position) FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_groups='".$_POST['teil7']."'"), 0) + 1;
			$result = dbquery("INSERT INTO ".DB_GR_TEAMLIST_USERS." (tlu_userid, tlu_pic, tlu_rname, tlu_abteil, tlu_feld1, tlu_feld2, tlu_status, tlu_groups, tlu_position) VALUES('".$_POST['teil1']."', '".$bild_save."', '".$teil2."', '".$teil3."', '".$teil4."', '".$teil5."', '".$_POST['teil6']."', '".$_POST['teil7']."', '".$link_order."')"); 
			redirect(FUSION_SELF.$aidlink);
		} else {
			redirect(FUSION_SELF.$aidlink."&users_add");
		}
	} else {
		opentable($locale['grtl220']);
		echo "<form action='".FUSION_SELF.$aidlink."&users_add' method='post'><table align='center' class='tbl-border' cellpadding='0' cellspacing='0'>
			<tr>
				<td class='tbl1'>".$locale['grtl221']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><select name='teil1' class='textbox' style='width: 200px;'><option value='-'>".$locale['grtl222']."</option><option value='0'>".$locale['grtl223']."</option>";
				$result = dbquery("SELECT user_id, user_name FROM ".DB_USERS." ORDER BY user_level DESC, user_name");
				while ($member_list = dbarray($result)) {
					echo "<option value='".$member_list['user_id']."'>".$member_list['user_name']."</option>\n";
				}
				echo "</select></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl242']." <span style='color:#ff0000'>**</span></td>
				<td class='tbl2'>";
				$image_files = makefilelist(INFUSIONS."gr_teamlist/images/team/", ".|..|index.php", true);
				$image_list = makefileopts($image_files);
				echo "<select name='bild' class='textbox' style='width:200px;'>
				<option value=''>".$locale['grtl243']."</option>
				$image_list</select></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl224']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><input type='text' name='teil2' class='textbox' style='width: 200px;' maxlength='50' /></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl225']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><input type='text' name='teil3' class='textbox' style='width: 200px;' maxlength='50' value='Member' /></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl226']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><textarea name='teil4' class='textbox' cols='34' rows='3'></textarea></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl227']."</td>
				<td class='tbl2'><textarea name='teil5' class='textbox' cols='34' rows='3'></textarea></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl228']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><select name='teil6' class='textbox' style='width: 200px;'>
					<option value='-'>".$locale['grtl222']."</option><option value='1'>".$locale['grtl229']."</option><option value='0'>".$locale['grtl230']."</option></select></td>
			</tr>
			<tr>
				<td class='tbl1'>".$locale['grtl231']." <span style='color:#ff0000'>*</span></td>
				<td class='tbl2'><select name='teil7' class='textbox' style='width: 200px;'><option value='-'>".$locale['grtl222']."</option>";
				$result = dbquery("SELECT tlg_id, tlg_name FROM ".DB_GR_TEAMLIST_GROUP." ORDER BY tlg_name");
				while ($group_list = dbarray($result)) {
					echo "<option value='".$group_list['tlg_id']."'>".$group_list['tlg_name']."</option>";
				}
				echo "</select></td>
			</tr>
			<tr>
				<td align='center' classe='tbl2' colspan='2'>".$locale['grtl244']."<br />".$locale['grtl245']."<br /><br /><input name='save' type='submit' class='button' value='".$locale['grtl233']."'><br /><br /></td>
			</tr>
			</table></form>
			<a href='".FUSION_SELF.$aidlink."'>".$locale['grtl234']."</a>";		
	}
} else {
	opentable($locale['grtl210']);
	echo "<a href='".INFUSIONS."gr_teamlist/gr_teamlist_admin.php".$aidlink."&amp;groups_add'>".$locale['grtl211']."</a> | <a href='".INFUSIONS."gr_teamlist/gr_teamlist_admin.php".$aidlink."&amp;users_add'>".$locale['grtl212']."</a>";
	closetable();
	tablebreak();
	opentable($locale['grtl102']);
	$result = dbquery("SELECT * FROM ".DB_GR_TEAMLIST_GROUP." ORDER BY tlg_position");
	if (dbrows($result) != 0) {
		$i=1;
		while ($tl_group = dbarray($result)) {
			echo "<div align='center'><br /><img border='0' src='".INFUSIONS."gr_teamlist/images/group/".$tl_group['tlg_pic']."' alt='".$tl_group['tlg_name']."' onClick='javascript:Toggle(".$tl_group['tlg_id'].")' style='cursor:pointer' />\n<span id='ToggleRow_".$tl_group['tlg_id']."' style='display:".($tl_group['tlg_status'] == 1 ? "block" : "none")."'>";
			echo "<table width='550' class='tbl-border' cellpadding='0' cellspacing='0' align='center'>
			<tr>
				<td class='tbl1' width='200'>".$locale['grtl213']."</td>
				<td class='tbl1' width='200'>".$locale['grtl214']."</td>
				<td class='tbl1' width='50'>".$locale['grtl215']."</td>
				<td class='tbl1' width='100' colspan='2'>".$locale['grtl216']."</td>
			</tr>";
			$result2 = dbquery("SELECT * FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_groups='".$tl_group['tlg_id']."' ORDER BY tlu_position");
			$j=1;
			while ($tl_users = dbarray($result2)) {
				echo "<tr>
				<td class='tbl2'>".$tl_users['tlu_rname']."</td>
				<td class='tbl2'>".$tl_users['tlu_abteil']."</td>
				<td class='tbl2'>";
				if ($tl_users['tlu_status'] == 1) {
					echo "<img border='0' src='".INFUSIONS."gr_teamlist/images/aktiv.gif' alt='Aktiv' width='9' height='12' /> ";
				} else {
					echo "<img border='0' src='".INFUSIONS."gr_teamlist/images/inaktiv.gif' alt='Inaktiv' width='9' height='12' /> ";
		    }
		    echo "</td>
				<td class='tbl2' width='50'>
				<a href='".FUSION_SELF.$aidlink."&users_edit&id=".$tl_users['tlu_id']."'>".$locale['grtl216']."</a>
				<a href='".FUSION_SELF.$aidlink."&users_del&id=".$tl_users['tlu_id']."'>".$locale['grtl217']."</a>
				</td>\n<td class='tbl2' width='50'>\n";
				if (1 < dbrows($result2)) {
					$up = $tl_users['tlu_position'] - 1;
					$down = $tl_users['tlu_position'] + 1;
					if ($j==1) {
						echo "<a href='".FUSION_SELF.$aidlink."&amp;users_down&amp;groups=".$tl_group['tlg_id']."&amp;order=".$down."&amp;id=".$tl_users['tlu_id']."'><img src='".THEME."images/down.gif' alt='' title='' style='border:0px;' /></a>";
					} elseif ($j < dbrows($result2)) {
						echo "<a href='".FUSION_SELF.$aidlink."&amp;users_up&amp;groups=".$tl_group['tlg_id']."&amp;order=".$up."&amp;id=".$tl_users['tlu_id']."'><img src='".THEME."images/up.gif' alt='' title='' style='border:0px;' /></a>";
						echo "<a href='".FUSION_SELF.$aidlink."&amp;users_down&amp;groups=".$tl_group['tlg_id']."&amp;order=".$down."&amp;id=".$tl_users['tlu_id']."'><img src='".THEME."images/down.gif' alt='' title='' style='border:0px;' /></a>";
					} else {
						echo "<a href='".FUSION_SELF.$aidlink."&amp;users_up&amp;groups=".$tl_group['tlg_id']."&amp;order=".$up."&amp;id=".$tl_users['tlu_id']."'><img src='".THEME."images/up.gif' alt='' title='' style='border:0px;' /></a>";
					}
				}
				echo "</td>\n</tr>\n";
				$j++;
			}
			echo "<tr>\n<td class='tbl1' colspan='4' align='center'><a href='".FUSION_SELF.$aidlink."&groups_edit&id=".$tl_group['tlg_id']."'>".$locale['grtl218']."</a> || <a href='".FUSION_SELF.$aidlink."&groups_del&id=".$tl_group['tlg_id']."'>".$locale['grtl219']."</a><br />";
			if (1 < dbrows($result)) {
				$up = $tl_group['tlg_position'] - 1;
				$down = $tl_group['tlg_position'] + 1;
				if ($i==1) {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;groups_down&amp;order=".$down."&amp;id=".$tl_group['tlg_id']."'><img src='".THEME."images/down.gif' alt='' title='' style='border:0px;' /></a>";
				} elseif ($i < dbrows($result)) {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;groups_up&amp;order=".$up."&amp;id=".$tl_group['tlg_id']."'><img src='".THEME."images/up.gif' alt='' title='' style='border:0px;' /></a>";
					echo "<a href='".FUSION_SELF.$aidlink."&amp;groups_down&amp;order=".$down."&amp;id=".$tl_group['tlg_id']."'><img src='".THEME."images/down.gif' alt='' title='' style='border:0px;' /></a>";
				} else {
					echo "<a href='".FUSION_SELF.$aidlink."&amp;groups_up&amp;order=".$up."&amp;id=".$tl_group['tlg_id']."'><img src='".THEME."images/up.gif' alt='' title='' style='border:0px;' /></a>";
				}
			}
			$i++;
			echo "</td>\n</tr>\n</table>\n<br />\n</span>\n</div>\n";
		}
	}	else {
		echo "<div align='center'><br />".$locale['grtl209']."<br /></div>";
	}
}
echo "<br />\n<div align='right'><a href='http://www.granade.eu/scripte/teamlist.html' target='_blank'>&copy;</a></div>";
closetable();

echo '<script type="text/javascript">
function Toggle(id) {
  spanid1 = "ToggleRow_"+id;
  val = document.getElementById(spanid1).style.display;
  if (val == "none") {
    document.getElementById(spanid1).style.display = "block";
  }
  else {
    document.getElementById(spanid1).style.display = "none";
  }
}
</script>';

require_once THEMES."templates/footer.php";
?>