<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: members.php
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
require_once "maincore.php";
require_once TEMPLATES."header.php";
include LOCALE.LOCALESET."members.php";

add_to_title($locale['global_200'].$locale['400']);

$items_per_page = 25;

opentable($locale['400']);
if (iMEMBER) {
	if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
	$rowstart = $_GET['page'] > 0 ? ($_GET['page']-1) * $items_per_page : "0";
	if (!isset($_GET['sortby']) || !preg_match("/^[0-9a-zA-Z]$/", $_GET['sortby'])) { $_GET['sortby'] = "all"; } // Pimped: a-z allowed for seo url-rewrite
	$sortby = ($_GET['sortby'] == "all" ? "" : "user_name LIKE '".stripinput($_GET['sortby'])."%'");
	#
	$show = ((checkrights("M") && iADMIN) ? $pif_global['visible_members_admin'] : $pif_global['visible_members']);
	$in = ''; // Pimped
	foreach($show as $value) {
		$in .= ($in != '' ? ", " : "");
		$in .= "$value";
	}
	$where = ($in != '' ? " user_status IN($in)" : "");
	if($where != '' && $sortby != '') {
		$insert = " WHERE ".$sortby." AND ".$where."";
	} elseif($where == '' && $sortby != '') {
		$insert = " WHERE ".$sortby;
	} elseif($where != '' && $sortby == '') {
		$insert = " WHERE ".$where;
	} else {
		$insert = '';
	}
	$result = dbquery("SELECT user_id FROM ".DB_USERS.$insert); // Pimped
	$rows = dbrows($result);
	if ($rows) {
		$i = 0;
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		echo "<td class='tbl2'><strong>".$locale['401']."</strong></td>\n";
		echo "<td class='tbl2'><strong>".$locale['405']."</strong></td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['402']."</strong></td>\n";
		echo "</tr>\n";

		$result = dbquery("SELECT user_id, user_name, user_status, user_groups, user_level FROM ".DB_USERS.$insert." ORDER BY user_level DESC, user_name LIMIT ".(int)$rowstart.",".(int)$items_per_page); // Pimped
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
			echo "<tr>\n<td class='".$cell_color."'>\n".profile_link($data['user_id'], $data['user_name'], $data['user_status'])."</td>\n";
			$groups = "";
			$user_groups = explode(".", $data['user_groups']);
			$j = 0;
			foreach ($user_groups as $key => $value) {
				if ($value) {
					$groups .= group_link($value, getgroupname($value)).($j < count($user_groups)-1 ? ", " : ""); // Pimped: Url-rewrite
				}
				$j++;
			}
			echo "<td class='".$cell_color."'>\n".($groups ? $groups : ($data['user_level']==nSUPERADMIN ? $locale['407'] : $locale['406']))."</td>\n"; // Pimped
			echo "<td align='center' width='1%' class='".$cell_color."' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>\n</tr>";
		}
		echo "</table>\n"; 
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['403'].$_GET['sortby']."<br /><br />\n</div>\n";
	}
	$search = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
		"S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9"
	);
	echo "<hr />\n<table cellpadding='0' cellspacing='1' class='tbl-border center' style='margin-top:10px;'>\n<tr>\n";
	echo "<td rowspan='2' class='tbl2'><a href='".make_url(FUSION_SELF."?sortby=all", "members", "", ".html")."'>".$locale['404']."</a></td>"; // Pimped: make_url
	for ($i = 0; $i < 36 != ""; $i++) {
		echo "<td align='center' class='tbl1'><div class='small'><a href='".make_url(FUSION_SELF."?sortby=".$search[$i], "members-sortby-", $search[$i], ".html")."'>".$search[$i]."</a></div></td>"; // Pimped: make_url
		echo ($i == 17 ? "<td rowspan='2' class='tbl2'><a href='".make_url(FUSION_SELF."?sortby=all", "members", "", ".html")."'>".$locale['404']."</a></td>\n</tr>\n<tr>\n" : "\n"); // Pimped: make_url
	}
	echo "</tr>\n</table>\n";
	echo "<div align='right' style='margin-top:1px;'>
	<form name='searchform' method='get' action='".BASEDIR."search.php'>
	<input type='hidden' name='stype' value='members'>
	<input type='text' name='stext' id='search' class='textbox' value='".$locale['420']."' onfocus='this.value=\"\"' style='width:105px;'>
	<input type='submit' name='search' value='".$locale['421']."' class='button'>
	</form>
	</div>\n";
	
} else {
	redirect(make_url("index.php", "index", "", ".html")); // Pimped: make_url
}
closetable();
if ($rows > $items_per_page) {
	echo "<div align='center' style='margin-top:5px;'>".pagination(true, $rowstart, $items_per_page, $rows, 3, FUSION_SELF."?sortby=".$_GET['sortby']."&amp;", "members", "-sortby-", $_GET['sortby'], "-p-", "", "")."</div>\n";
}

require_once TEMPLATES."footer.php";
?>