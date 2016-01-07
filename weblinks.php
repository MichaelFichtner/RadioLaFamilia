<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: weblinks.php
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
include LOCALE.LOCALESET."weblinks.php";

if (isset($_GET['weblink_id']) && isnum($_GET['weblink_id'])) {
	$res = 0;
	if ($data = dbarray(dbquery("SELECT weblink_url, weblink_cat FROM ".DB_WEBLINKS." WHERE weblink_id='".(int)$_GET['weblink_id']."'"))) {
		$cdata = dbarray(dbquery("SELECT weblink_cat_access FROM ".DB_WEBLINK_CATS." WHERE weblink_cat_id='".(int)$data['weblink_cat']."'"));
		if (checkgroup($cdata['weblink_cat_access'])) {
			$res = 1;
			$result = dbquery("UPDATE ".DB_WEBLINKS." SET weblink_count=weblink_count+1 WHERE weblink_id='".(int)$_GET['weblink_id']."'");
			redirect($data['weblink_url']);
		}
	}
	if ($res == 0) { redirect(FUSION_SELF); }
}

add_to_title($locale['global_200'].$locale['400']);

if (!isset($_GET['cat_id']) || !isnum($_GET['cat_id'])) {
	opentable($locale['400']);
	$result = dbquery("SELECT weblink_cat_id, weblink_cat_name, weblink_cat_description
	FROM ".DB_WEBLINK_CATS." WHERE ".groupaccess('weblink_cat_access')."".
	(!(bool)IF_MULTI_LANGUAGE ? '':" AND (weblink_cat_language='all' OR weblink_cat_language='".LANGUAGE."')")." AND weblink_cat_parent='0'
	ORDER BY weblink_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$counter = 0; $columns = 2; 
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
			$num = dbcount("(weblink_cat)", DB_WEBLINKS, "weblink_cat='".(int)$data['weblink_cat_id']."'");
			echo "<td valign='top' width='50%' class='tbl'>";
			echo "<a href='".make_url("weblinks.php?cat_id=".$data['weblink_cat_id'], SEO_WEBLINK_CAT_A.SEO_WEBLINK_CAT_B1.$data['weblink_cat_id'].SEO_WEBLINK_CAT_B2, $data['weblink_cat_name'], SEO_WEBLINK_CAT_C)."'>".$data['weblink_cat_name']."</a> ";
			echo "<span class='small2'>(".$num.")</span>";
			if ($data['weblink_cat_description'] != "") { echo "<br />\n<span class='small'>".$data['weblink_cat_description']."</span>"; }
			subcats($data['weblink_cat_id'], "1");
			echo "</td>\n";
			$counter++;
		}
		if ($counter % $columns != 0) { echo "<td valign='top' width='50%' class='tbl'></td>\n"; }
		echo "</tr>\n</table>\n";
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['430']."<br /><br />\n</div>\n";
	}
	closetable();
} else {
	$res = 0;
	$result = dbquery("SELECT weblink_cat_name, weblink_cat_sorting, weblink_cat_access
	FROM ".DB_WEBLINK_CATS." WHERE weblink_cat_id='".(int)$_GET['cat_id']."'");
	if (dbrows($result) != 0) {
		$cdata = dbarray($result);
		if (checkgroup($cdata['weblink_cat_access'])) {
			$res = 1;
			add_to_title($locale['global_201'].$cdata['weblink_cat_name']);
			opentable($locale['400'].": ".$cdata['weblink_cat_name']);
			$rows = dbcount("(weblink_id)", DB_WEBLINKS, "weblink_cat='".(int)$_GET['cat_id']."'");
			if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
			$_GET['rowstart'] = $_GET['page'] > 0 ? ($_GET['page']-1) * (int)$settings['links_per_page'] : "0";
			subcats($_GET['cat_id'], "2");
			if ($rows != 0) {
				$result = dbquery("SELECT weblink_id, weblink_name, weblink_description, weblink_datestamp, weblink_count
				FROM ".DB_WEBLINKS." WHERE weblink_cat='".(int)$_GET['cat_id']."'
				ORDER BY ".$cdata['weblink_cat_sorting']." LIMIT ".(int)$_GET['rowstart'].",".(int)$settings['links_per_page']);
				$numrows = dbrows($result); $i = 1;
				while ($data = dbarray($result)) {
					if ($data['weblink_datestamp']+604800 > time()+($settings['timeoffset']*3600)) {
						$new = " <span class='small'>".$locale['410']."</span>";
					} else {
						$new = "";
					}
					echo "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
					echo "<tr>\n<td colspan='2' class='tbl2'>";
					echo "<a href='".make_url("weblinks.php?weblink_id=".(int)$data['weblink_id'], SEO_WEBLINK_A.SEO_WEBLINK_B1.(int)$data['weblink_id'].SEO_WEBLINK_B2, $data['weblink_name'], SEO_WEBLINK_C)."' target='_blank'>".$data['weblink_name']."</a>";
					echo $new."</td>\n</tr>\n";
					if ($data['weblink_description'] != "") {
						echo "<tr>\n<td colspan='2' class='tbl1'>".nl2br(stripslashes($data['weblink_description']))."</td>\n</tr>\n";
					}
					echo "<tr>\n<td width='30%' class='tbl2'><strong>".$locale['411']."</strong> ".showdate("shortdate", $data['weblink_datestamp'])."</td>\n";
					echo "<td width='70%' class='tbl1'><strong>".$locale['412']."</strong> ".$data['weblink_count']."</td>\n</tr>\n</table>\n";
					if ($i != $numrows) { echo "<div align='center'><img src='".get_image("blank")."' alt='' height='15' width='1' /></div>\n"; $i++; }
				}
				closetable();
				if ($rows > (int)$settings['links_per_page']) {
					echo "<div align='center' style='margin-top:5px;'>\n".pagination(true,(int)$_GET['rowstart'], (int)$settings['links_per_page'], $rows, 3, FUSION_SELF."?cat_id=".(int)$_GET['cat_id']."&amp;", SEO_WEBLINK_CAT_A, SEO_WEBLINK_CAT_B1, (int)$_GET['cat_id'], SEO_WEBLINK_CAT_P, SEO_WEBLINK_CAT_B2, $cdata['weblink_cat_name'], SEO_WEBLINK_CAT_C)."\n</div>\n";
					}
			} else {
				echo "<div style='text-align:center'>".$locale['431']."</div>\n";
				closetable();
			}
		}
	}
	if ($res == 0) { redirect(FUSION_SELF); }
}

function subcats($id, $type = "") {
    global $locale;
	$result = dbquery("SELECT weblink_cat_id, weblink_cat_name, weblink_cat_description
	FROM ".DB_WEBLINK_CATS." WHERE ".groupaccess('weblink_cat_access')." AND weblink_cat_parent='".(int)$id."'
	ORDER BY weblink_cat_name");
	$k = dbrows($result);
	if ($k > 0) {
		if ($type == "2") {
			$counter = 0; $columns = 2; 
			echo "<span class='side'><strong>".$locale['413']."</strong></span><br />\n";
			echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
			while ($data = dbarray($result)) {
				if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
				$num = dbcount("(weblink_cat)", DB_WEBLINKS, "weblink_cat='".(int)$data['weblink_cat_id']."'");
				echo "<td valign='top' width='50%' class='tbl'><a href='".make_url("weblinks.php?cat_id=".$data['weblink_cat_id'], SEO_WEBLINK_CAT_A.SEO_WEBLINK_CAT_B1.$data['weblink_cat_id'].SEO_WEBLINK_CAT_B2, $data['weblink_cat_name'], SEO_WEBLINK_CAT_C)."'>".$data['weblink_cat_name']."</a> <span class='small2'>(".$num.")</span>";
				if ($data['weblink_cat_description'] != "") { echo "<br />\n<span class='small'>".$data['weblink_cat_description']."</span>"; }
				echo "</td>\n";
				$counter++;
			}
			if ($counter % $columns != 0) { echo "<td valign='top' width='50%' class='tbl'></td>\n"; }
			echo "</tr>\n</table>\n";
			echo "<div style='margin:5px'></div>";
			echo "<br />";
		} else {
			echo "<br />";
			echo "<span class='side'><strong>".$locale['413'].": </strong></span>";
			while ($data = dbarray($result)) {
				$k--;
				$num = dbcount("(weblink_cat)", DB_WEBLINKS, "weblink_cat='".(int)$data['weblink_cat_id']."'");
				echo "<a href='".make_url("weblinks.php?cat_id=".$data['weblink_cat_id'], SEO_WEBLINK_CAT_A.SEO_WEBLINK_CAT_B1.$data['weblink_cat_id'].SEO_WEBLINK_CAT_B2, $data['weblink_cat_name'], SEO_WEBLINK_CAT_C)."'>".$data['weblink_cat_name']."</a> <span class='small2'>(".$num.")</span>";
				if ($k > 0) echo  ", ";
			}
		}
	}
}

require_once TEMPLATES."footer.php";
?>