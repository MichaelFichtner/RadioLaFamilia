<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: articles.php
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
include LOCALE.LOCALESET."articles.php";
if($settings['enable_lightbox'] == "1") require_once INCLUDES."lightbox/lightbox_head.php";

add_to_title($locale['global_200'].$locale['400']);

if (isset($_GET['article_id']) && isnum($_GET['article_id'])) {
	$result = dbquery(
		"SELECT ta.article_subject, ta.article_article, ta.article_breaks, ta.article_datestamp, ta.article_reads,
		ta.article_keywords, ta.article_allow_comments, ta.article_allow_ratings,
		tac.article_cat_id, tac.article_cat_name, tu.user_id, tu.user_name , tu.user_status
		FROM ".DB_ARTICLES." ta
		INNER JOIN ".DB_ARTICLE_CATS." tac ON ta.article_cat=tac.article_cat_id
		LEFT JOIN ".DB_USERS." tu ON ta.article_name=tu.user_id
		WHERE ".groupaccess('tac.article_cat_access')." AND article_id='".(int)$_GET['article_id']."' AND article_draft='0'"
	);
	if (dbrows($result)) {
		$data = dbarray($result);
		if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 1; }
		if ($_GET['page'] == 1) { 
			$result = dbquery("UPDATE ".DB_ARTICLES." SET article_reads=article_reads+1 WHERE article_id='".(int)$_GET['article_id']."'");
		}
		$article = stripslashes($data['article_article']);
		if (preg_match('<--PAGEBREAK-->', $article)) {
			$article = explode("<--PAGEBREAK-->", $article);
		} else {
			$article = explode("<!-- pagebreak -->", $article);
		}
		$pagecount = count($article);
		$article_subject = stripslashes($data['article_subject']);
		$article_info = array(
			"article_id" => (int)$_GET['article_id'],
			"cat_id" => $data['article_cat_id'],
			"cat_name" => $data['article_cat_name'],
			"user_id" => $data['user_id'],
			"user_name" => $data['user_name'],
			"user_status" => $data['user_status'],
			"article_subject" => $article_subject,
			"article_date" => $data['article_datestamp'],
			"article_breaks" => $data['article_breaks'],
			"article_comments" => dbcount("(comment_id)", DB_COMMENTS, "comment_type='A' AND comment_item_id='".(int)$_GET['article_id']."'"),
			"article_reads" => $data['article_reads'],
			"article_allow_comments" => $data['article_allow_comments']
		);
		add_to_title($locale['global_201'].$article_subject);
		set_meta('keywords', $data['article_keywords'], false);
		set_meta('description', trimlink($article_subject, 255), false);
		echo "<!--pre_article-->";
		render_article($article_subject, $article[$_GET['page'] - 1], $article_info);
		echo "<!--sub_article-->";
		if ($settings['enable_tags']) {
			require_once INCLUDES."tag_include.php";
			echo show_tags((int)$_GET['article_id'], "A");
		}
		if ($pagecount > 1) {
			echo "<div align='center' style='margin-top:5px;'>\n";
			echo pagination(true,(int)$_GET['page']-1, 1, $pagecount, 3, FUSION_SELF."?article_id=".(int)$_GET['article_id']."&amp;",
			"article","-",(int)$_GET['article_id'],"-page-","-",$article_subject);
			echo "\n</div>\n";
		}
		if ($data['article_allow_comments']) {
			require_once INCLUDES."comments_include.php";
			showcomments("A", DB_ARTICLES, "article_id", (int)$_GET['article_id'], FUSION_SELF."?article_id=".(int)$_GET['article_id'],
			"article", "-", (int)$_GET['article_id'], "-page-", (int)$_GET['page'], "-", $article_subject);
		}
		if ($data['article_allow_ratings'] && $settings['ratings_enabled'] == "1") {
			require INCLUDES."ratings_include.php";
			showratings("A", (int)$_GET['article_id'],
			FUSION_SELF."?article_id=".(int)$_GET['article_id'], "article-".(int)$_GET['article_id']."-", $article_subject); 
		}
		if($settings['sharethis_article']) {
			$share_this = "article";
			require_once INCLUDES."share_this_include.php";
		}
	} else {
		redirect(FUSION_SELF);
	}
} elseif (!isset($_GET['cat_id']) || !isnum($_GET['cat_id'])) {
	opentable($locale['400']);
	echo "<!--pre_article_idx-->\n";
	$result = dbquery("SELECT article_cat_id, article_cat_name, article_cat_description
	FROM ".DB_ARTICLE_CATS."
	WHERE ".groupaccess('article_cat_access').(!(bool)IF_MULTI_LANGUAGE ? '':" AND (article_cat_language='all' OR article_cat_language='".LANGUAGE."')")." 
	AND article_cat_parent='0'
	ORDER BY article_cat_name");
	$rows = dbrows($result);
	if ($rows) {
		$counter = 0; $columns = 2;
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
			$num = dbcount("(article_cat)", DB_ARTICLES, "article_cat='".(int)$data['article_cat_id']."' AND article_draft='0'");
			echo "<td valign='top' width='50%' class='tbl article_idx_cat_name'><!--article_idx_cat_name--><a href='".make_url("articles.php?cat_id=".$data['article_cat_id'], SEO_ARTICLE_CAT_A.SEO_ARTICLE_CAT_B1.$data['article_cat_id'].SEO_ARTICLE_CAT_B2, $data['article_cat_name'], SEO_ARTICLE_CAT_C)."'>".$data['article_cat_name']."</a> <span class='small2'>(".$num.")</span>";
			if ($data['article_cat_description'] != "") { echo "<br />\n<span class='small'>".$data['article_cat_description']."</span>"; }
			subcats($data['article_cat_id'], "1");
			echo "</td>\n";
			$counter++;
		}
		if ($counter % $columns != 0) { echo "<td valign='top' width='50%' class='tbl article_idx_cat_name'></td>\n"; }
		echo "</tr>\n</table>\n";
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['401']."<br /><br />\n</div>\n";
	}
	echo "<!--sub_article_idx-->\n";
	closetable();
} else {
	$res = 0;
	$result = dbquery("SELECT article_cat_name, article_cat_sorting, article_cat_access
	FROM ".DB_ARTICLE_CATS." WHERE article_cat_id='".(int)$_GET['cat_id']."'");
	if (dbrows($result) != 0) {
		$cdata = dbarray($result);
		if (checkgroup($cdata['article_cat_access'])) {
			$res = 1;
			add_to_title($locale['global_201'].$cdata['article_cat_name']);
			opentable($locale['400'].": ".$cdata['article_cat_name']);
			$subcats_available = false;
			subcats($_GET['cat_id'], "2");
			echo "<!--pre_article_cat-->";
			$rows = dbcount("(article_id)", DB_ARTICLES, "article_cat='".(int)$_GET['cat_id']."' AND article_draft='0'");
			if($rows != 0 && $subcats_available == true) echo "<span class='side'><strong>".$locale['400']."</strong></span><br /><br />\n";
			
			if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
			$rowstart = $_GET['page'] > 0 ? ($_GET['page']-1) * $settings['articles_per_page'] : "0";
			
			if ($rows != 0) {
				$result = dbquery(
					"SELECT article_id, article_subject, article_snippet, article_datestamp
					FROM ".DB_ARTICLES."
					WHERE article_cat='".(int)$_GET['cat_id']."' AND article_draft='0'
					ORDER BY ".$cdata['article_cat_sorting']." LIMIT ".(int)$rowstart.",".(int)$settings['articles_per_page']);
				$numrows = dbrows($result); $i = 1;
				while ($data = dbarray($result)) {
					if ($data['article_datestamp'] + 604800 > time() + ($settings['timeoffset'] * 3600)) {
						$new = "&nbsp;<span class='small'>[".$locale['402']."]</span>";
					} else {
						$new = "";
					}
					//echo "<br />";
					echo "<a href='".make_url("articles.php?article_id=".(int)$data['article_id'], SEO_ARTICLE_A.SEO_ARTICLE_B1.(int)$data['article_id'].SEO_ARTICLE_B2, $data['article_subject'], SEO_ARTICLE_C)."'>".$data['article_subject']."</a>".$new."<br />\n".stripslashes($data['article_snippet']);
				echo ($i != $numrows ? "<br /><br />\n" : "\n"); $i++;
				}
				echo "<!--sub_article_cat-->";
				closetable();
				if ($rows > $settings['articles_per_page']) echo "<div align='center' style=';margin-top:5px;'>\n".pagination(true,(int)$rowstart, (int)$settings['articles_per_page'], (int)$rows, 3, FUSION_SELF."?cat_id=".(int)$_GET['cat_id']."&amp;", "article-cat","-",(int)$_GET['cat_id'],"-page-","-",$cdata['article_cat_name'])."\n</div>\n";
			} elseif(!$subcats_available) {
				echo "<div style='text-align:center'><br />".$locale['403']."<br /><br /></div>\n";
				echo "<!--sub_article_cat-->";
				closetable();
			} else {
				echo "<!--sub_article_cat-->";
				closetable();
			}
		}
	}
	if ($res == 0) { redirect(FUSION_SELF); }
}

function subcats($id, $type="") {
    global $locale, $subcats_available;
 
	$result = dbquery("SELECT article_cat_id, article_cat_name, article_cat_description
	FROM ".DB_ARTICLE_CATS."
	WHERE ".groupaccess('article_cat_access')." AND article_cat_parent='".(int)$id."' ORDER BY article_cat_name");
	$k = dbrows($result);
	if ($k > 0) {
		if ($type == "2") {
			$counter = 0; $columns = 2;
			echo "<span class='side'><strong>".$locale['404']."</strong></span><br />\n";
			echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
			while ($data = dbarray($result)) {
				if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
				$num = dbcount("(article_cat)", DB_ARTICLES, "article_cat='".(int)$data['article_cat_id']."' AND article_draft='0'");
				if($num > 0) $subcats_available = true;
				echo "<td valign='top' width='50%' class='tbl article_idx_cat_name'><!--article_idx_cat_name--><a href='".make_url("articles.php?cat_id=".$data['article_cat_id'], SEO_ARTICLE_CAT_A.SEO_ARTICLE_CAT_B1.$data['article_cat_id'].SEO_ARTICLE_CAT_B2, $data['article_cat_name'], SEO_ARTICLE_CAT_C)."'>".$data['article_cat_name']."</a> <span class='small2'>(".$num.")</span>";
				if ($data['article_cat_description'] != "") { echo "<br />\n<span class='small'>".$data['article_cat_description']."</span>"; }
				echo "</td>\n";
				$counter++;
			}
			if ($counter % $columns != 0) { echo "<td valign='top' width='50%' class='tbl article_idx_cat_name'></td>\n"; }
			echo "</tr>\n</table>\n";
			echo "<div style='margin:5px'></div>";
		} else {	  
	        echo "<br />";
			echo "<span class='side'><strong>".$locale['404'].": </strong></span>";
			while ($data = dbarray($result)) {
				$k--;
				$num = dbcount("(article_cat)", DB_ARTICLES, "article_cat='".(int)$data['article_cat_id']."' AND article_draft='0'");
				echo "<!--article_idx_cat_name--><a href='".make_url("articles.php?cat_id=".$data['article_cat_id'], SEO_ARTICLE_CAT_A.SEO_ARTICLE_CAT_B1.$data['article_cat_id'].SEO_ARTICLE_CAT_B2, $data['article_cat_name'], SEO_ARTICLE_CAT_C)."'>".$data['article_cat_name']."</a> <span class='small2'>(".$num.")</span>";
				if ($k > 0) echo  ", ";
			}
		}
	}
}

require_once TEMPLATES."footer.php";
?>