<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: news_cats.php
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
include LOCALE.LOCALESET."news_cats.php";

add_to_title($locale['global_200'].$locale['400']);
add_newscatimages();

opentable($locale['400']);
if (isset($_GET['cat_id']) && isnum($_GET['cat_id'])) {
	$res = 0;
	$result = dbquery("SELECT news_cat_name FROM ".DB_NEWS_CATS." WHERE news_cat_id='".(int)$_GET['cat_id']."'");
	if (dbrows($result) || $_GET['cat_id'] == 0) {
		$data = dbarray($result);
		$rows = dbcount("(news_id)", DB_NEWS, "news_cat='".(int)$_GET['cat_id']."' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") AND news_draft='0'");
		if ($rows) {
			$res = 1;
			echo "<!--pre_news_cat--><table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
			if ($_GET['cat_id'] != 0) {
				echo "<tr>\n<td width='150' class='tbl1' style='vertical-align:top'><!--news_cat_image--><img src='".get_image("nc_".$data['news_cat_name'])."' alt='".$data['news_cat_name']."' /><br /><br />\n";
				echo "<strong>".$locale['401']."</strong> ".$data['news_cat_name']."<br />\n<strong>".$locale['402']."</strong> $rows</td>\n";
				echo "<td class='tbl1' style='vertical-align:top'>\n";
			} else {
				echo "<tr>\n<td width='150' class='tbl1' style='vertical-align:top'>".$locale['403']."<br />\n";
				echo "<strong>".$locale['401']."</strong> $rows</td>\n<td class='tbl1' style='vertical-align:top'><!--news_cat_news-->\n";
			}
			$result2 = dbquery("SELECT news_id, news_subject
			FROM ".DB_NEWS."
			WHERE news_cat='".(int)$_GET['cat_id']."' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().")
				AND (news_end='0'||news_end>=".time().") AND news_draft='0'
			ORDER BY news_datestamp DESC");
			while ($data2 = dbarray($result2)) {
				echo THEME_BULLET." <a href='".make_url("news.php?readmore=".$data2['news_id'], "news-".$data2['news_id']."-", $data2['news_subject'], ".html")."'>".$data2['news_subject']."</a><br />\n";
			}
			echo "</td>\n</tr>\n<tr>\n<td colspan='2' class='tbl1' style='text-align:center'>".THEME_BULLET." <a href='".make_url(FUSION_SELF, "news-category", "", ".html")."'>".$locale['406']."</a>";
			echo "</td>\n</tr>\n</table><!--sub_news_cat-->\n";
		}
	}
	if (!$res) { redirect(FUSION_SELF); }
} else {
	$res = 0;
	$result = dbquery("SELECT news_cat_id, news_cat_name, news_cat_language FROM ".DB_NEWS_CATS." ORDER BY news_cat_language, news_cat_name");
	###
	$result_count = dbquery("SELECT news_cat, COUNT(news_id) AS news_count FROM ".DB_NEWS." WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") AND news_draft='0' GROUP BY news_cat");
	$news_counts = array();
	while($data_count = dbarray($result_count)) {
		$news_counts[$data_count['news_cat']] = $data_count['news_count'];
	}
	###
	if (dbrows($result)) {
		echo "<!--pre_news_cat_idx--><table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
		while ($data = dbarray($result)) {
			$rows = isset($news_counts[$data['news_cat_id']]) ? $news_counts[$data['news_cat_id']] : "0";
			echo "<tr>\n<td width='150' class='tbl1' style='vertical-align:top'><!--news_cat_image--><img src='".get_image("nc_".$data['news_cat_name'])."' alt='".$data['news_cat_name']."' /><br /><br />\n";
			echo "<strong>".$locale['401']."</strong> ".$data['news_cat_name']."<br />\n";
			if(IF_MULTI_LANGUAGE) echo "<strong>".$locale['408']."</strong> ".$data['news_cat_language']."<br />\n";
			echo "<strong>".$locale['402']."</strong> $rows</td>\n";
			echo "<td class='tbl1' style='vertical-align:top'><!--news_cat_news-->\n";
			if ($rows) {
				$result2 = dbquery("SELECT news_id, news_subject
				FROM ".DB_NEWS."
				WHERE news_cat='".$data['news_cat_id']."' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().")
					AND (news_end='0'||news_end>=".time().") AND news_draft='0'
				ORDER BY news_datestamp DESC LIMIT 10");
				while ($data2 = dbarray($result2)) {
					echo THEME_BULLET." <a href='".make_url("news.php?readmore=".$data2['news_id'], "news-".$data2['news_id']."-", $data2['news_subject'], ".html")."'>".$data2['news_subject']."</a><br />\n";
				}
				if ($rows > 10) { echo "<div style='text-align:right'>".THEME_BULLET." <a href='".FUSION_SELF."?cat_id=".$data['news_cat_id']."'>".$locale['405']."</a></div>\n"; }
			} else {
				echo THEME_BULLET." ".$locale['404']."\n";
			}
			echo "</td>\n</tr>\n";
		}
		$res = 1;
	}
	$result = dbquery("SELECT news_id, news_subject
	FROM ".DB_NEWS."
	WHERE news_cat='0' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")
		AND news_draft='0'
	ORDER BY news_datestamp DESC LIMIT 10");
	if (dbrows($result)) {
		if ($res == 0) { echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n"; }
		$nrows = dbcount("(news_id)", DB_NEWS, "news_cat='0' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") AND news_draft='0'");
		echo "<tr>\n<td width='150' class='tbl1' style='vertical-align:top'>".$locale['403']."<br />\n";
		echo "<strong>".$locale['402']."</strong> $nrows</td>\n<td class='tbl1' style='vertical-align:top'>\n";
		while ($data = dbarray($result)) {
			echo THEME_BULLET." <a href='".make_url("news.php?readmore=".$data['news_id'], "news-".$data['news_id']."-", $data['news_subject'], ".html")."'>".$data['news_subject']."</a><br />\n";
		}
		$res = 1;
		if ($nrows > 10) { echo "<div style='text-align:right'>".THEME_BULLET." <a href='".FUSION_SELF."?cat_id=0'>".$locale['405']."</a></div>\n"; }
		echo "</td>\n</tr>\n";
	}
	if ($res == 1) {
		echo "</table><!--sub_news_cat_idx-->\n";
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['407']."<br /><br />\n</div>\n";
	}
}
closetable();

require_once TEMPLATES."footer.php";
?>