<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: downloads.php
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
include LOCALE.LOCALESET."downloads.php";

// Download File
if (isset($_GET['id']) && isnum($_GET['id'])) {
	if ($data = dbarray(dbquery("SELECT download_url, download_file, download_cat FROM ".DB_DOWNLOADS." WHERE download_id='".(int)$_GET['id']."'"))) {
		$cdata = dbarray(dbquery("SELECT download_cat_access FROM ".DB_DOWNLOAD_CATS." WHERE download_cat_id='".(int)$data['download_cat']."'"));
		if (checkgroup($cdata['download_cat_access'])) {
			$result = dbquery("UPDATE ".DB_DOWNLOADS." SET download_count=download_count+1 WHERE download_id='".(int)$_GET['id']."'");
			if (!empty($data['download_file']) && file_exists(DOWNLOADS.$data['download_file'])) {
				download_file(DOWNLOADS.$data['download_file']);
			} elseif(!empty($data['download_url'])) {
				redirect($data['download_url']);
			} else {
				die("Download file could not been found!");
			}
		} else {
			die("You have no access to the download file");
		}
	} else {
		die("Download does not exist");
	}
	redirect("downloads.php");
	exit;
}

// Download Site
require_once TEMPLATES."header.php";

add_to_title($locale['global_200'].$locale['400']);

if (!isset($_GET['cat_id']) || !isnum($_GET['cat_id'])) {
	opentable($locale['400']);
	echo "<!--pre_download_idx-->\n";
	$result = dbquery("SELECT download_cat_id, download_cat_name, download_cat_description
	FROM ".DB_DOWNLOAD_CATS." WHERE ".groupaccess('download_cat_access')." AND download_cat_parent='0' ORDER BY download_cat_name"); // Pimped
	$rows = dbrows($result);
	if ($rows) {
		$counter = 0; $columns = 2;
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
			$num = dbcount("(download_cat)", DB_DOWNLOADS, "download_cat='".(int)$data['download_cat_id']."'");
			echo "<td valign='top' width='50%' class='tbl download_idx_cat_name'><!--download_idx_cat_name--><a href='".make_url("downloads.php?cat_id=".$data['download_cat_id'], "download-cat-".$data['download_cat_id']."-", $data['download_cat_name'], ".html")."'>".$data['download_cat_name']."</a> <span class='small2'>($num)</span>";
			if ($data['download_cat_description'] != "") { echo "<br />\n<span class='small'>".$data['download_cat_description']."</span>"; }
			subcats($data['download_cat_id'], '1'); // Pimped
			echo "</td>\n" ;
			$counter++;
		}
		if ($counter % $columns != 0) { echo "<td valign='top' width='50%' class='tbl1 download_idx_cat_name'></td>\n"; }
		echo "</tr>\n</table>\n";
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['430']."<br /><br />\n</div>\n";
	}
	echo "<!--sub_download_idx-->";
	closetable();
} else {
	$res = 0;
	$result = dbquery("SELECT download_cat_name, download_cat_access, download_cat_sorting
	FROM ".DB_DOWNLOAD_CATS." WHERE download_cat_id='".(int)$_GET['cat_id']."'");
	if (dbrows($result) != 0) {
		$cdata = dbarray($result);
		if (checkgroup($cdata['download_cat_access'])) {
			$res = 1;
			add_to_title($locale['global_201'].$cdata['download_cat_name']);
			opentable($locale['400'].": ".$cdata['download_cat_name']);
			$subcats_available = false; // Pimped
			subcats($_GET['cat_id'], '2'); // Pimped
			echo "<!--pre_download_cat-->";
			$rows = dbcount("(download_id)", DB_DOWNLOADS, "download_cat='".(int)$_GET['cat_id']."'");
			if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
			$_GET['rowstart'] = $_GET['page'] > 0 ? ($_GET['page']-1) * (int)$settings['downloads_per_page'] : "0";
			if ($rows != 0) {
				$result = dbquery("SELECT download_id, download_title, download_description, download_license, download_os,
				download_version, download_filesize, download_datestamp, download_count
				FROM ".DB_DOWNLOADS." WHERE download_cat='".(int)$_GET['cat_id']."'
				ORDER BY ".$cdata['download_cat_sorting']." LIMIT ".(int)$_GET['rowstart'].",".(int)$settings['downloads_per_page']);
				$numrows = dbrows($result); $i = 1;
				while ($data = dbarray($result)) {
					if ($data['download_datestamp'] + 604800 > time() + ($settings['timeoffset'] * 3600)) {
						$new = " <span class='small'>".$locale['410']."</span>";
					} else {
						$new = "";
					}
					echo "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
					echo "<tr>\n<td colspan='3' class='forum-caption'><strong>".$data['download_title']."</strong> $new</td>\n</tr>\n";
					if ($data['download_description']) { echo "<tr>\n<td colspan='3' class='tbl1'>".nl2br(stripslashes($data['download_description']))."</td>\n</tr>\n"; }
					echo "<tr>\n<td width='30%' class='tbl2'><strong>".$locale['411']."</strong> ".$data['download_license']."</td>\n<td width='30%' class='tbl1'><strong>".$locale['412']."</strong> ".$data['download_os']."</td>\n";
					echo "<td width='40%' class='tbl2'><strong>".$locale['413']."</strong> ".$data['download_version']."</td>\n</tr>\n<tr>\n<td width='30%' class='tbl2'><strong>".$locale['414']."</strong> ".showdate("shortdate", $data['download_datestamp'])."</td>\n";
					echo "<td width='30%' class='tbl1'><strong>".$locale['415']."</strong> ".$data['download_count']."</td>\n<td width='40%' class='tbl2'><a href='".make_url("downloads.php?id=".$data['download_id'], "download-".$data['download_id']."-", $data['download_title'], ".html")."' target='_blank'>".$locale['416']."</a>".( $data['download_filesize'] == '' ? '' : " (".$data['download_filesize'].")")."</td>\n</tr>\n";
					echo "</table>\n";
					if ($i != $numrows) { echo "<div style='text-align:center'><img src='".get_image("blank")."' alt='' height='15' width='1' /></div>\n"; $i++; }
				}
				closetable();
				if ($rows > (int)$settings['downloads_per_page']) { echo "<div align='center' style=';margin-top:5px;'>\n".pagination(true,$_GET['rowstart'], (int)$settings['downloads_per_page'], $rows, 3, FUSION_SELF."?cat_id=".(int)$_GET['cat_id']."&amp;", "download-cat", "-", (int)$_GET['cat_id'], "-page-", "-", $cdata['download_cat_name'])."\n</div>\n"; }
			} elseif(!$subcats_available) {
				echo "<div style='text-align:center'><br />".$locale['431']."<br /><br /></div>\n";
				echo "<!--sub_download_cat-->";
				closetable();
			} else {
				echo "<!--sub_download_cat-->";
				closetable();
			}
		}
	}
	if ($res == 0) { redirect(FUSION_SELF); }
}

function subcats($id, $type="") {
    global $locale, $subcats_available;
 
	$result = dbquery("SELECT download_cat_id, download_cat_name, download_cat_description FROM ".DB_DOWNLOAD_CATS." WHERE ".groupaccess('download_cat_access')." AND download_cat_parent='".$id."' ORDER BY download_cat_name");
	$k = dbrows($result);
	if ($k > 0) {
		if ($type == "2") {
			$counter = 0; $columns = 2;
			echo "<table width='100%' cellpadding='0' cellspacing='0' class='tbl-border'>\n<tr>\n";
			echo "<td class='tbl2' colspan='".$columns."'><span class='side'><strong>".$locale['417']."</strong></span></td><tr>\n";
			while ($data = dbarray($result)) {
				if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
				$num = dbcount("(download_cat)", DB_DOWNLOADS, "download_cat='".(int)$data['download_cat_id']."'");
				if($num > 0) $subcats_available = true;
				echo "<td valign='top' width='50%' class='tbl1 download_idx_cat_name'><!--download_idx_cat_name--><a href='".make_url("downloads.php?cat_id=".$data['download_cat_id'], "download-cat-".$data['download_cat_id']."-", $data['download_cat_name'], ".html")."'>".$data['download_cat_name']."</a> <span class='small2'>($num)</span>";
				if ($data['download_cat_description'] != "") { echo "<br />\n<span class='small'>".$data['download_cat_description']."</span>"; }
				echo "</td>\n" ;
				$counter++;
			}
			if ($counter % $columns != 0) { echo "<td valign='top' width='50%' class='tbl1 download_idx_cat_name'></td>\n"; }
			echo "</tr>\n</table>\n";
			echo "<div style='margin:5px'></div>";
		} else {
			echo "<br />";	
			echo "<span class='side'><strong>".$locale['417'].": </strong></span>";
			while ($data = dbarray($result)) {
				$k--;
				$num = dbcount("(download_cat)", DB_DOWNLOADS, "download_cat='".(int)$data['download_cat_id']."'");
				echo "<a href='".make_url("downloads.php?cat_id=".$data['download_cat_id'], "download-cat-".$data['download_cat_id']."-", $data['download_cat_name'], ".html")."'>".$data['download_cat_name']."</a> <span class='small2'>(".$num.")</span>";
				if ($k > 0) echo  ", ";
			}
		}
	}
}

require_once TEMPLATES."footer.php";
?>