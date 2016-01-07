<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: ss_feeds_admin.php
| Author: SiteMaster, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../../maincore.php";
require_once THEMES."templates/admin_header.php";
include INFUSIONS."ss_feeds_panel/infusion_db.php";
require_once INFUSIONS."ss_feeds_panel/functions.php";

if (!checkrights("SSFP") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php")) {
	include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php";
} else {
	include INFUSIONS."ss_feeds_panel/locale/English/infusion.php";
}

$chmod = INFUSIONS."ss_feeds_panel/rss";
if(file_exists($chmod) && is_writable($chmod)) {
} elseif(file_exists($chmod) && function_exists("chmod") && @chmod($chmod, 0777) && is_writable($chmod)) {
} else {
	$message = $locale['ssfp_chmod'];
	echo "<div class='admin-message'>".$message."</div>"; 
}
		

if ((isset($_GET['action']) && $_GET['action'] == "mu") && (isset($_GET['feed_id']) && isnum($_GET['feed_id']))) {
	$data = dbarray(dbquery("SELECT feed_id FROM ".DB_SS_FEEDS." WHERE feed_order='".intval($_GET['order'])."'"));
	$result = dbquery("UPDATE ".DB_SS_FEEDS." SET feed_order=feed_order+1 WHERE feed_id='".$data['feed_id']."'");
	$result = dbquery("UPDATE ".DB_SS_FEEDS." SET feed_order=feed_order-1 WHERE feed_id='".$_GET['feed_id']."'");
	redirect(FUSION_SELF.$aidlink);
} elseif ((isset($_GET['action']) && $_GET['action'] == "md") && (isset($_GET['feed_id']) && isnum($_GET['feed_id']))) {
	$data = dbarray(dbquery("SELECT feed_id FROM ".DB_SS_FEEDS." WHERE feed_order='".intval($_GET['order'])."'"));
	$result = dbquery("UPDATE ".DB_SS_FEEDS." SET feed_order=feed_order-1 WHERE feed_id='".$data['feed_id']."'");
	$result = dbquery("UPDATE ".DB_SS_FEEDS." SET feed_order=feed_order+1 WHERE feed_id='".$_GET['feed_id']."'");
	redirect(FUSION_SELF.$aidlink);
} elseif ((isset($_POST['updfrq']) && isnum($_POST['updfrq'])) && (isset($_POST['feed_id']) && isnum($_POST['feed_id']))) {
	$result = dbquery("UPDATE ".DB_SS_FEEDS." SET feed_updfrq=".$_POST['updfrq']." WHERE feed_id='".$_POST['feed_id']."'");
	redirect(FUSION_SELF.$aidlink);
} elseif (isset($_GET['enable']) && file_exists(INFUSIONS."ss_feeds_panel/feeds/".$_GET['enable'].".php")) {
	include INFUSIONS."ss_feeds_panel/feeds/".$_GET['enable']."_var.php";
	$feed_order = dbresult(dbquery("SELECT MAX(feed_order) FROM ".DB_SS_FEEDS), 0) + 1;
	if ($feed_name != ""){
		$result = dbquery("INSERT INTO ".DB_SS_FEEDS." (feed_name, feed_order) VALUES ('$feed_name', '$feed_order')");
		
		if(IF_MULTI_LANGUAGE) {
			$language_allowed = explode(",", $settings['locale_content']);
			for ($i = 0; $i < count($language_allowed); $i++) {
				$value = trim($language_allowed[$i]);
				if($value != '' && in_array($value, $language_allowed)) {
					make_rss($feed_name, 24, $value);
				}
			}
		} else {
			make_rss($feed_name, 24, false);
		}
		
	}
	redirect(FUSION_SELF.$aidlink);
} elseif ((isset($_GET['disable']) && isnum($_GET['disable']))) {
	$data = dbarray(dbquery("SELECT feed_name, feed_order FROM ".DB_SS_FEEDS." WHERE feed_id='".$_GET['disable']."'"));
	$result = dbquery("UPDATE ".DB_SS_FEEDS." SET feed_order=feed_order-1 WHERE feed_order>'".$data['feed_order']."'");
	$result = dbquery("DELETE FROM ".DB_SS_FEEDS." WHERE feed_id='".$_GET['disable']."'");
	
	
	if ($temp = opendir(INFUSIONS."ss_feeds_panel/rss/")) {
		while (false !== ($file = readdir($temp))) {
			if (!in_array($file, array("..",".","index.php")) && !is_dir(INFUSIONS."ss_feeds_panel/rss/".$file)) {
				if (preg_match("/".$data['feed_name']."/i", $file)) {
					$feed_name = explode("_", $file);
					
					 if (file_exists(INFUSIONS."ss_feeds_panel/rss/".$feed_name[0]."_".$feed_name[1]."")){
						unlink(INFUSIONS."ss_feeds_panel/rss/".$feed_name[0]."_".$feed_name[1]."");
					 } elseif (file_exists(INFUSIONS."ss_feeds_panel/rss/".$feed_name[0]."_".$feed_name[1]."_".$feed_name[2]."")){
						unlink(INFUSIONS."ss_feeds_panel/rss/".$feed_name[0]."_".$feed_name[1]."_".$feed_name[2]."");
					}
					unset($feed_name);
				}
			}
		}
		closedir($temp); 
	}
	
	 
	redirect(FUSION_SELF.$aidlink);
}

$available_feeds = array(); $enabled_feeds = array();

if ($temp = opendir(INFUSIONS."ss_feeds_panel/feeds/")) {
	while (false !== ($file = readdir($temp))) {
		if (!in_array($file, array("..",".","index.php")) && !is_dir(INFUSIONS."ss_feeds_panel/feeds/".$file)) {
			if (preg_match("/_var.php/i", $file)) {
				$feed_name = explode("_", $file);
				$available_feeds[] = $feed_name[0]."_".$feed_name[1];
				unset($feed_name);
			}
		}
	}
	closedir($temp); 
}
sort($available_feeds);

opentable($locale['ssfp_100']);
echo "			<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
echo "				<tr>\n";

$result = dbquery("SELECT * FROM ".DB_SS_FEEDS." ORDER BY feed_order");
if (dbrows($result)) {
	echo "					<td width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['ssfp_105']."</strong></td>\n";
	echo "					<td class='tbl2' style='white-space:nowrap'><strong>".$locale['ssfp_106']."</strong></td>\n";
	echo "					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['ssfp_005']."</td>\n";
	echo "					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['ssfp_001']."</td>\n";
	echo "					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'></td>\n";
	echo "					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'></td>\n";
	echo "				</tr>\n";
	
	$i = 1; $k = 0;
	
	while($data = dbarray($result)) {
		$row_color = ($k % 2 == 0 ? "tbl1" : "tbl2");
		$rows = dbcount("(feed_id)", DB_SS_FEEDS, "");
		if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$data['feed_name'].".php")) {
			include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$data['feed_name'].".php";
		}else{
			include INFUSIONS."ss_feeds_panel/locale/English/feeds/".$data['feed_name'].".php";
		}
		include INFUSIONS."ss_feeds_panel/feeds/".$data['feed_name']."_var.php";
		$enabled_feeds[] = $data['feed_name'];
		echo "				<tr>\n";
		echo "					<td width='1%' class='".$row_color."' style='white-space:nowrap'>".$feed_title."</td>\n";
		echo "					<td class='".$row_color."' style='white-space:nowrap'>".$feed_desc."</td>\n";
		echo "					<td class='".$row_color."' align='center' style='white-space:nowrap'>\n";
		echo "						<a style='cursor:help;' href='javascript:void(window.open(\"ss_feeds_code.php".$aidlink."&amp;feed_name=".$data["feed_name"]."&amp;updfrq=".$data['feed_updfrq']."\",\"\",\"width=500,height=600,scrollbars=yes\"));'>\n";
		echo "							<img src='".INFUSIONS."ss_feeds_panel/images/makecode.png' title='".$feed_title."' alt='".$feed_title."' style='border: 0;' />\n";
		echo "						</a>\n";
		echo "					</td>\n";
		echo "					<td class='".$row_color."' style='white-space:nowrap'>\n";
		echo "						<form name='updfrqform_".$data['feed_id']."' method='post' action='".FUSION_SELF.$aidlink."'>\n";
		echo "							<select name='updfrq' class='textbox' onchange=\"submit();\">\n";
		echo "								<option value='1'".($data['feed_updfrq'] == "1" ? " selected='selected'" : "").">1".$locale['ssfp_002']."</option>\n";
		echo "								<option value='3'".($data['feed_updfrq'] == "3" ? " selected='selected'" : "").">3".$locale['ssfp_003']."</option>\n";
		echo "								<option value='6'".($data['feed_updfrq'] == "6" ? " selected='selected'" : "").">6".$locale['ssfp_003']."</option>\n";
		echo "								<option value='9'".($data['feed_updfrq'] == "9" ? " selected='selected'" : "").">9".$locale['ssfp_003']."</option>\n";
		echo "								<option value='12'".($data['feed_updfrq'] == "12" ? " selected='selected'" : "").">12".$locale['ssfp_003']."</option>\n";
		echo "								<option value='15'".($data['feed_updfrq'] == "15" ? " selected='selected'" : "").">15".$locale['ssfp_003']."</option>\n";
		echo "								<option value='18'".($data['feed_updfrq'] == "18" ? " selected='selected'" : "").">18".$locale['ssfp_003']."</option>\n";
		echo "								<option value='21'".($data['feed_updfrq'] == "21" ? " selected='selected'" : "").">21".$locale['ssfp_003']."</option>\n";
		echo "								<option value='24'".($data['feed_updfrq'] == "24" ? " selected='selected'" : "").">".$locale['ssfp_004']."</option>\n";
		echo "							</select>\n";
		echo "							<input type='hidden' name='feed_id' value='".$data['feed_id']."' />\n";
		echo "						</form>\n";
		echo "					</td>\n";
		echo "					<td width='1%' class='".$row_color."' style='white-space:nowrap'>".$data['feed_order'];
		if ($rows != 1) {
			$up = $data['feed_order'] - 1;
			$down = $data['feed_order'] + 1;
			if ($i == 1) {
				echo "						<a href='".FUSION_SELF.$aidlink."&amp;action=md&amp;order=$down&amp;feed_id=".$data['feed_id']."'><img src='".THEME."images/down.gif' alt='".$locale['ssfp_107']."' title='".$locale['ssfp_107']."' style='border:0px;' /></a>\n";
			} elseif ($i < $rows) {
				echo "						<a href='".FUSION_SELF.$aidlink."&amp;action=mu&amp;order=$up&amp;feed_id=".$data['feed_id']."'><img src='".THEME."images/up.gif' alt='".$locale['ssfp_108']."' title='".$locale['ssfp_108']."' style='border:0px;' /></a>\n";
				echo "						<a href='".FUSION_SELF.$aidlink."&amp;action=md&amp;order=$down&amp;feed_id=".$data['feed_id']."'><img src='".THEME."images/down.gif' alt='".$locale['ssfp_107']."' title='".$locale['ssfp_107']."' style='border:0px;' /></a>\n";
			} else {
				echo "						<a href='".FUSION_SELF.$aidlink."&amp;action=mu&amp;order=$up&amp;feed_id=".$data['feed_id']."'><img src='".THEME."images/up.gif' alt='".$locale['ssfp_108']."' title='".$locale['ssfp_108']."' style='border:0px;' /></a>\n";
			}
		}
		$i++; $k++;
		echo "					</td>\n";
		echo "					<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;disable=".$data['feed_id']."'>".$locale['ssfp_109']."</a></td>\n";
		echo "				</tr>\n";		
	}
} else {
	echo "					<td align='center' class='tbl1'>".$locale['ssfp_102']."</td>\n";
	echo "				</tr>\n";
}
echo "			</table>\n";
closetable();

opentable($locale['ssfp_101']);
echo "			<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>\n";
echo "				<tr>\n";
if (count($available_feeds) != count($enabled_feeds)) {
	echo "					<td width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['ssfp_105']."</strong></td>\n";
	echo "					<td class='tbl2' style='white-space:nowrap'><strong>".$locale['ssfp_106']."</strong></td>\n";
	echo "					<td align='center' width='1%' class='tbl2' style='white-space:nowrap'></td>\n";
	echo "				</tr>\n";
	
	$i = 0;
	
	for ($k = 0; $k < count($available_feeds); $k++) {
		if (!in_array($available_feeds[$k], $enabled_feeds)) {
			if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$available_feeds[$k].".php")) {
				include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."feeds/".$available_feeds[$k].".php";
			}else{
				include INFUSIONS."ss_feeds_panel/locale/English/feeds/".$available_feeds[$k].".php";
			}
			include INFUSIONS."ss_feeds_panel/feeds/".$available_feeds[$k]."_var.php";
			$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "				<tr>\n";
			echo "					<td width='1%' class='".$row_color."' style='white-space:nowrap'>".$feed_title."</td>\n";
			echo "					<td class='".$row_color."' style='white-space:nowrap'>".$feed_desc."</td>\n";

			$result = dbquery("SHOW TABLES LIKE '%".$db_prefix.$feed_tablename."%'");

			if (dbrows($result)) {
				echo "					<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;enable=".$available_feeds[$k]."'>".$locale['ssfp_110']."</a></td>\n";
			} else {
				echo "					<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>".$locale['ssfp_104']."</td>\n";
			}
			echo "				</tr>\n";
			$i++;
		}
	}
} else {
	echo "					<td align='center' class='tbl1'>".$locale['ssfp_103']."</td>\n";
	echo "				</tr>\n";
}
echo "			</table>\n";
closetable();

require_once THEMES."templates/footer.php";
?>