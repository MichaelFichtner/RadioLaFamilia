<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Radiostatus v1.0 for PHP-Fusion 7
| Filename: gr_radiostatus_admin.php
| Author: Ralf Thieme
| Webseite: www.granade.eu
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

if (!checkrights("GRRS") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect(BASEDIR."index.php"); }
if (isset($_GET['delete']) && !isnum($_GET['delete'])) { redirect(BASEDIR."index.php"); }
if (isset($_GET['edit']) && !isnum($_GET['edit'])) { redirect(BASEDIR."index.php"); }
if (isset($_GET['up']) && !isnum($_GET['up'])) { redirect(BASEDIR."index.php"); }
if (isset($_GET['down']) && !isnum($_GET['down'])) { redirect(BASEDIR."index.php"); }
if (isset($_GET['order']) && !isnum($_GET['order'])) { redirect(BASEDIR."index.php"); }

include INFUSIONS."gr_radiostatus_panel/infusion_db.php";
if (file_exists(INFUSIONS."gr_radiostatus_panel/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_radiostatus_panel/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_radiostatus_panel/locale/German/index.php";
}

function rs_access($status=1, $select=0) {
	global $locale;
	$go = "";
	if ($status) {
		$go .= "<option value='0'".($select == 0 ? " selected='selected'" : "").">".$locale['user0']."</option>\n";
		$go .= "<option value='101'".($select == 101 ? " selected='selected'" : "").">".$locale['user1']."</option>\n";
	}
	$go .= "<option value='102'".($select == 102 ? " selected='selected'" : "").">".$locale['user2']."</option>\n";
	$go .= "<option value='103'".($select == 103 ? " selected='selected'" : "").">".$locale['user3']."</option>\n";
	$result = dbquery("SELECT * FROM ".DB_USER_GROUPS." ORDER BY group_name");
	if (dbrows($result)) {
		while($data = dbarray($result)) {
			$go .= "<option value='".$data['group_id']."'".($select == $data['group_id'] ? " selected='selected'" : "").">".$data['group_name']."</option>\n";
		}
	}
	return $go;
}

if(function_exists('fsockopen')) {
	if (isset($_GET['delete'])) {
		$data = dbarray(dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS." WHERE rs_id='".$_GET['delete']."'"));
		$result = dbquery("UPDATE ".DB_GR_RADIOSTATUS." SET rs_order=rs_order-1 WHERE rs_order>'".$data['rs_order']."'");	
		$result = dbquery("DELETE FROM ".DB_GR_RADIOSTATUS." WHERE rs_id='".$_GET['delete']."'");
		redirect(FUSION_SELF.$aidlink."&error=d1");
	} elseif (isset($_GET['edit'])) {
		$result = dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS." WHERE rs_id='".$_GET['edit']."'");
		if (dbrows($result)) {
			$data = dbarray($result);
			if (isset($_POST['save'])) {
				$name = (isset($_POST['name']) ? stripinput($_POST['name']) : $data['rs_name']);
				$ip = (isset($_POST['ip']) ? stripinput($_POST['ip']) : $data['rs_ip']);
				$port = (isset($_POST['port']) ? stripinput($_POST['port']) : $data['rs_port']);
				$pw = (isset($_POST['pw']) && $_POST['pw'] != "" ? stripinput($_POST['pw']) : $data['rs_pw']);
				$gb = (isset($_POST['gb']) && isnum($_POST['gb']) ? $_POST['gb'] : $data['rs_gb']);
				$ps = (isset($_POST['ps']) ? stripinput($_POST['ps']) : $data['rs_ps']);
				$tele = (isset($_POST['tele']) ? stripinput($_POST['tele']) : $data['rs_tele']);
				$servertyp = (isset($_POST['servertyp']) && isnum($_POST['servertyp']) ? $_POST['servertyp'] : $data['rs_servertyp']);
				$usertyp = (isset($_POST['usertyp']) && isnum($_POST['usertyp']) ? $_POST['usertyp'] : $data['rs_usertyp']);
				$status = (isset($_POST['status']) && isnum($_POST['status']) ? $_POST['status'] : $data['rs_status']);
				$gaccess = (isset($_POST['gaccess']) && isnum($_POST['gaccess']) ? $_POST['gaccess'] : $data['rs_gaccess']);
				$access = (isset($_POST['access']) && isnum($_POST['access']) ? $_POST['access'] : $data['rs_access']);
				if ($name != "" && $ip != "" && $port != "" && $pw != "") {
					$result = dbquery("UPDATE ".DB_GR_RADIOSTATUS." SET rs_name='".$name."', rs_ip='".$ip."', rs_port='".$port."', rs_pw='".$pw."', rs_gb='".$gb."', rs_ps='".$ps."', rs_tele='".$tele."', rs_servertyp='".$servertyp."', rs_usertyp='".$usertyp."', rs_status='".$status."', rs_gaccess='".$gaccess."', rs_access='".$access."' WHERE rs_id='".$_GET['edit']."'");
					redirect(FUSION_SELF.$aidlink."&amp;e0");
				} else {
					redirect(FUSION_SELF.$aidlink."&amp;e1");
				}
			}
			opentable($locale['grrs_19']);
			echo "<form method='post' action='".FUSION_SELF.$aidlink."&amp;edit=".$_GET['edit']."'>\n";
			echo "<table cellpadding='1' cellspacing='1' width='100%' class='tbl-border' align='center'>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_01'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><input type='text' name='name' class='textbox' style='width:200px;' value='".$data['rs_name']."' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_02'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><input type='text' name='ip' class='textbox' style='width:200px;' value='".$data['rs_ip']."' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_03'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><input type='text' name='port' class='textbox' style='width:200px;' value='".$data['rs_port']."' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_04'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><input type='password' name='pw' class='textbox' style='width:200px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' colspan='2'><hr class='side-hr' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_05'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><select name='gb' class='textbox' style='width:208px;'>\n<option value='1'".($data['rs_gb'] == 1 ? " selected='selected'" : "").">".$locale['grrs_13']."</option>\n<option value='0'".($data['rs_gb'] == 0 ? " selected='selected'" : "").">".$locale['grrs_14']."</option>\n";
			echo "</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_05'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><select name='gaccess' class='textbox' style='width:208px;'>\n";
			echo rs_access("0", $data['rs_gaccess']);
			echo "</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_07'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><input type='text' name='ps' class='textbox' style='width:200px;' value='".$data['rs_ps']."' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_08'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><input type='text' name='tele' class='textbox' style='width:200px;' value='".$data['rs_tele']."' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_09'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><select name='usertyp' class='textbox' style='width:208px;'>\n<option value='1'".($data['rs_usertyp'] == 1 ? " selected='selected'" : "").">".$locale['grrs_15']."</option>\n<option value='2'".($data['rs_usertyp'] == 2 ? " selected='selected'" : "").">".$locale['grrs_16']."</option>\n<option value='3'".($data['rs_usertyp'] == 3 ? " selected='selected'" : "").">".$locale['grrs_17']."</option>\n<option value='4'".($data['rs_usertyp'] == 4 ? " selected='selected'" : "").">".$locale['grrs_18']."</option>\n";
			echo "</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_10'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><select name='access' class='textbox' style='width:208px;'>\n";
			echo rs_access("1", $data['rs_access']);
			echo "</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' wight='50%'>".$locale['grrs_11'].":</td>\n";
			echo "<td class='tbl2' wight='50%'><select name='status' class='textbox' style='width:208px;'>\n<option value='1'".($data['rs_status'] == 1 ? " selected='selected'" : "").">".$locale['grrs_13']."</option>\n<option value='0'".($data['rs_status'] == 0 ? " selected='selected'" : "").">".$locale['grrs_14']."</option>\n";
			echo "</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl1' colspan='2' align='center'><input type='submit' name='save' class='textbox' value='".$locale['grrs_12']."' /></td>\n";
			echo "</tr>\n</table>\n</form>\n";
		} else {
			redirect(FUSION_SELF.$aidlink);
		}
	} elseif (isset($_GET['new'])) {
		if (isset($_POST['save'])) {
			$name = (isset($_POST['name']) ? stripinput($_POST['name']) : "");
			$ip = (isset($_POST['ip']) ? stripinput($_POST['ip']) : "");
			$port = (isset($_POST['port']) ? stripinput($_POST['port']) : "");
			$pw = (isset($_POST['pw']) ? stripinput($_POST['pw']) : "");
			$gb = (isset($_POST['gb']) && isnum($_POST['gb']) ? $_POST['gb'] : "0");
			$ps = (isset($_POST['ps']) ? stripinput($_POST['ps']) : "0");
			$tele = (isset($_POST['tele']) ? stripinput($_POST['tele']) : "0");
			$servertyp = (isset($_POST['servertyp']) && isnum($_POST['servertyp']) ? $_POST['servertyp'] : "1");
			$usertyp = (isset($_POST['usertyp']) && isnum($_POST['usertyp']) ? $_POST['usertyp'] : "1");
			$status = (isset($_POST['status']) && isnum($_POST['status']) ? $_POST['status'] : "0");
			$gaccess = (isset($_POST['gaccess']) && isnum($_POST['gaccess']) ? $_POST['gaccess'] : "102");
			$access = (isset($_POST['access']) && isnum($_POST['access']) ? $_POST['access'] : "102");
			if ($name != "" && $ip != "" && $port != "" && $pw != "") {
				$order = dbresult(dbquery("SELECT MAX(rs_order) FROM ".DB_GR_RADIOSTATUS), 0) + 1;
				$result = dbquery("INSERT INTO ".DB_GR_RADIOSTATUS." (rs_name, rs_ip, rs_port, rs_pw, rs_gb, rs_ps, rs_tele, rs_servertyp, rs_usertyp, rs_status, rs_order, rs_gaccess, rs_access) VALUES ('".$name."', '".$ip."', '".$port."', '".$pw."', '".$gb."', '".$ps."', '".$tele."', '".$servertyp."', '".$usertyp."', '".$status."', '".$order."', '".$gaccess."', '".$access."')");
				redirect(FUSION_SELF.$aidlink."&amp;n0");
			} else {
				redirect(FUSION_SELF.$aidlink."&amp;n1");
			}
		}
		opentable($locale['grrs_20']);
		echo "<form method='post' action='".FUSION_SELF.$aidlink."&amp;new'>\n";
		echo "<table cellpadding='1' cellspacing='1' width='100%' class='tbl-border' align='center'>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_01'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><input type='text' name='name' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_02'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><input type='text' name='ip' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_03'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><input type='text' name='port' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_04'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><input type='password' name='pw' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' colspan='2'><hr class='side-hr' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_05'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><select name='gb' class='textbox' style='width:208px;'>\n<option value='1'>".$locale['grrs_13']."</option>\n<option value='0'>".$locale['grrs_14']."</option>\n";
		echo "</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_05'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><select name='gaccess' class='textbox' style='width:208px;'>\n";
		echo rs_access("0");
		echo "</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_07'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><input type='text' name='ps' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_08'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><input type='text' name='tele' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_09'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><select name='usertyp' class='textbox' style='width:208px;'>\n<option value='1'>".$locale['grrs_15']."</option>\n<option value='2'>".$locale['grrs_16']."</option>\n<option value='3'>".$locale['grrs_17']."</option>\n<option value='4'>".$locale['grrs_18']."</option>\n";
		echo "</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_10'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><select name='access' class='textbox' style='width:208px;'>\n";
		echo rs_access();
		echo "</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' wight='50%'>".$locale['grrs_11'].":</td>\n";
		echo "<td class='tbl2' wight='50%'><select name='status' class='textbox' style='width:208px;'>\n<option value='1'>".$locale['grrs_13']."</option>\n<option value='0'>".$locale['grrs_14']."</option>\n";
		echo "</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl1' colspan='2' align='center'><input type='submit' name='save' class='textbox' value='".$locale['grrs_12']."' /></td>\n";
		echo "</tr>\n</table>\n</form>\n";
	} elseif (isset($_GET['up']) && isset($_GET['order'])) {
		if ($_GET['order'] > 0) {
			$data = dbarray(dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS." WHERE rs_order='".$_GET['order']."'"));
			$result = dbquery("UPDATE ".DB_GR_RADIOSTATUS." SET rs_order=rs_order+1 WHERE rs_id='".$data['rs_id']."'");
			$result = dbquery("UPDATE ".DB_GR_RADIOSTATUS." SET rs_order=rs_order-1 WHERE rs_id='".$_GET['up']."'");
		}
		redirect(FUSION_SELF.$aidlink);
	} elseif (isset($_GET['down']) && isset($_GET['order'])) {
		$link_order = dbresult(dbquery("SELECT MAX(rs_order) FROM ".DB_GR_RADIOSTATUS), 0) + 1;
		if ($_GET['order'] < $link_order) {
			$data = dbarray(dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS." WHERE rs_order='".$_GET['order']."'"));
			$result = dbquery("UPDATE ".DB_GR_RADIOSTATUS." SET rs_order=rs_order-1 WHERE rs_id='".$data['rs_id']."'");
			$result = dbquery("UPDATE ".DB_GR_RADIOSTATUS." SET rs_order=rs_order+1 WHERE rs_id='".$_GET['down']."'");
		}
		redirect(FUSION_SELF.$aidlink);
	} else {
		if (isset($_GET['error'])) {
			$_GET['error'] = stripinput($_GET['error']);
			
		}
		opentable($locale['grrs_21']);
		echo "<table cellpadding='1' cellspacing='1' width='100%' class='tbl-border' align='center'>\n<tr>\n";
		echo "<td class='tbl1'>".$locale['grrs_01']."</td>\n";
		echo "<td class='tbl1' width='100px'>".$locale['grrs_02']."</td>\n";
		echo "<td class='tbl1' width='50px'>".$locale['grrs_03']."</td>\n";
		echo "<td class='tbl1' width='70px'>".$locale['grrs_05']."</td>\n";
		echo "<td class='tbl1' width='70px'>".$locale['grrs_22']."</td>\n";
		echo "<td class='tbl1' width='100px'>".$locale['grrs_23']."</td>\n";
		echo "<td class='tbl1' width='50px'>".$locale['grrs_24']."</td>\n";
		echo "<td class='tbl1' width='10px'></td>\n";
		echo "<td class='tbl1' width='70px'></td>\n";
		echo "</tr>\n";
		$result = dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS." ORDER BY rs_order");
		if (dbrows($result)) {
			$i=1;
			while ($data = dbarray($result)) {
				echo "<tr>\n";
				echo "<td class='tbl2'>".$data['rs_name']."</td>\n";
				echo "<td class='tbl2'>".$data['rs_ip']."</td>\n";
				echo "<td class='tbl2'>".$data['rs_port']."</td>\n";
				echo "<td class='tbl2'>";
				if ($data['rs_gb']) {
					echo "<img src='".INFUSIONS."gr_radiostatus_panel/images/check.png' alt='check'>";
				} else {
					echo "<img src='".INFUSIONS."gr_radiostatus_panel/images/uncheck.png' alt='uncheck'>";
				}
				echo "</td>\n";
				echo "<td class='tbl2'>";
				if ($data['rs_usertyp'] == 1) {
					echo $locale['grrs_25'];
				} elseif ($data['rs_usertyp'] == 2) {
					echo $locale['grrs_26'];
				} elseif ($data['rs_usertyp'] == 3) {
					echo $locale['grrs_27'];
				} elseif ($data['rs_usertyp'] == 4) {
					echo $locale['grrs_28'];
				} else {
					echo $locale['grrs_25'];
				}
				echo "</td>\n";
				echo "<td class='tbl2'>";
				if ($data['rs_access'] == 0) {
					echo $locale['user0'];
				} elseif ($data['rs_access'] == 101) {
					echo $locale['user1'];
				} elseif ($data['rs_access'] == 102) {
					echo $locale['user2'];
				} elseif ($data['rs_access'] == 103) {
					echo $locale['user3'];
				} else {
					$group_result = dbquery("SELECT * FROM ".DB_USER_GROUPS." WHERE group_id='".$data['rs_access']."'");
					if (dbrows($group_result)) {
						$group_data = dbarray($group_result);
						if (checkrights("UG")) {
							echo "<a href='".ADMIN."user_groups.php".$aidlink."&amp;group_id=".$group_data['group_id']."'>".$group_data['group_name']."</a>";
						} else {
							echo $group_data['group_name'];
						}
					} else {
						echo $locale['grrs_29'];
					}
				}
				echo "/<br />";
				if ($data['rs_gaccess'] == 0) {
					echo $locale['user0'];
				} elseif ($data['rs_gaccess'] == 101) {
					echo $locale['user1'];
				} elseif ($data['rs_gaccess'] == 102) {
					echo $locale['user2'];
				} elseif ($data['rs_gaccess'] == 103) {
					echo $locale['user3'];
				} else {
					$group_result = dbquery("SELECT * FROM ".DB_USER_GROUPS." WHERE group_id='".$data['rs_gaccess']."'");
					if (dbrows($group_result)) {
						$group_data = dbarray($group_result);
						if (checkrights("UG")) {
							echo "<a href='".ADMIN."user_groups.php".$aidlink."&amp;group_id=".$group_data['group_id']."'>".$group_data['group_name']."</a>";
						} else {
							echo $group_data['group_name'];
						}
					} else {
						echo $locale['grrs_29'];
					}
				}
				echo "</td>\n";
				echo "<td class='tbl2'>";
				if ($data['rs_status']) {
					echo "<img src='".INFUSIONS."gr_radiostatus_panel/images/check.png' alt='check'>";
				} else {
					echo "<img src='".INFUSIONS."gr_radiostatus_panel/images/uncheck.png' alt='uncheck'>";
				}
				echo "</td>\n";
				echo "<td class='tbl2'>\n";
				if (1 < dbrows($result)) {
					$up = $data['rs_order'] - 1;
					$down = $data['rs_order'] + 1;
					if ($i==1) {
						echo "<a href='".FUSION_SELF.$aidlink."&amp;down=".$data['rs_id']."&amp;order=".$down."'><img src='".get_image("down")."' alt='down' style='border:0px;' /></a>";
					} elseif ($i < dbrows($result)) {
						echo "<a href='".FUSION_SELF.$aidlink."&amp;up=".$data['rs_id']."&amp;order=".$up."'><img src='".get_image("up")."' alt='up' style='border:0px;' /></a>";
						echo "<a href='".FUSION_SELF.$aidlink."&amp;down=".$data['rs_id']."&amp;order=".$down."'><img src='".get_image("down")."' alt='down' style='border:0px;' /></a>";
					} else {
						echo "<a href='".FUSION_SELF.$aidlink."&amp;up=".$data['rs_id']."&amp;order=".$up."'><img src='".get_image("up")."' alt='up' style='border:0px;' /></a>";
					}
				}
				echo "</td>\n";
				echo "<td class='tbl2'><a href='".FUSION_SELF.$aidlink."&amp;edit=".$data['rs_id']."'>".$locale['grrs_31']."</a><br /><a href='".FUSION_SELF.$aidlink."&amp;delete=".$data['rs_id']."'>".$locale['grrs_32']."</a></td>\n";
				echo "</tr>\n";
				$i++;
			}
		} else {
			echo "<tr>\n";
			echo "<td class='tbl1' colspan='9'>".$locale['grrs_30']."</td>\n";
			echo "</tr>\n";
		}
		echo "<tr>\n";
		echo "<td class='tbl1' colspan='8'></td>\n";
		echo "<td class='tbl2'><a href='".FUSION_SELF.$aidlink."&amp;new'>".$locale['grrs_33']."</a></td>\n";
		echo "</tr>\n</table>\n";
	}
} else {
	opentable($locale['grrs_21']);
	echo "<div align='center'>".$locale['grrs_34']."</div>";
}
echo "<div align='right'><a href='http://www.granade.eu/scripte/radiostatus.php' target='_blank'>Radiostatus &copy;</a></div>\n";
closetable();

require_once THEMES."templates/footer.php";
?>