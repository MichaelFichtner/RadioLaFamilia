<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: news.php
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

if($settings['enable_lightbox'] == "1") require_once INCLUDES."lightbox/lightbox_head.php";

// Predefined variables, do not edit these values
if ($settings['news_style'] == "1") { $i = 0; $rc = 0; $ncount = 1; $ncolumn = 1; $news_[0] = ""; $news_[1] = ""; $news_[2] = ""; } else { $i = 1; }

add_to_title($locale['global_200'].$locale['global_077']);
add_newscatimages();

if (!isset($_GET['readmore']) || !isnum($_GET['readmore'])) {
if(IF_MULTI_LANGUAGE) {
	$tnresult = dbquery("SELECT count(tn.news_id) FROM ".DB_NEWS." tn
			LEFT OUTER JOIN ".DB_NEWS_CATS." tc ON tn.news_cat=tc.news_cat_id
			WHERE ".groupaccess('tn.news_visibility')." AND (tn.news_start='0'||tn.news_start<=".time().") AND (tn.news_end='0'||tn.news_end>=".time().")
			AND tn.news_draft='0' AND ((tc.news_cat_language='all' OR tc.news_cat_language='".LANGUAGE."') OR tn.news_cat='0')");
	$rows = dbresult($tnresult,0);
} else {
	$rows = dbcount("(news_id)", DB_NEWS, groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND 
	(news_end='0'||news_end>=".time().") AND news_draft='0' ");
}
	if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
	$rowstart = $_GET['page'] > 0 ? ($_GET['page']-1) * $settings['newsperpage'] : "0";
	
	if ($rows) {
		$result = dbquery("SELECT tn.news_id, tn.news_cat, news_subject, news_image_t2, news_news,
			news_extended, news_breaks, news_datestamp, news_reads, news_sticky, news_allow_comments,
			tc.news_cat_id, tc.news_cat_name, tc.news_cat_image,
			tu.user_id, tu.user_name, tu.user_status
			FROM ".DB_NEWS." tn
			LEFT JOIN ".DB_USERS." tu ON tn.news_name=tu.user_id
			LEFT JOIN ".DB_NEWS_CATS." tc ON tn.news_cat=tc.news_cat_id
			WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") AND news_draft='0'
			".(!(bool)IF_MULTI_LANGUAGE ? '':" AND ((tc.news_cat_language='all' OR tc.news_cat_language='".LANGUAGE."') OR tn.news_cat='0')")."
			ORDER BY news_sticky DESC, news_datestamp DESC LIMIT ".(int)$rowstart.",".(int)$settings['newsperpage']);
		$numrows = dbrows($result);
		if ($settings['news_style'] == "1") { $nrows = round((dbrows($result) - 1) / 2); }
		while ($data = dbarray($result)) {
			$news_cat_image = "";
			$news_subject = "<a name='news_".$data['news_id']."' id='news_".$data['news_id']."'></a>".stripslashes($data['news_subject']);
			if($data['news_cat'] == "0") {
				$seo_newscatname = "no defined category";
			} else {
				$seo_newscatname = $data['news_cat_name'];
			}
			$news_cat_image = "<a href='".($settings['news_image_link'] == 0 ? make_url(BASEDIR."news_cats.php?cat_id=".$data['news_cat'], 
			BASEDIR.SEO_NEWSCAT_A.SEO_NEWSCAT_B1.$data['news_cat'].SEO_NEWSCAT_B2, $seo_newscatname, SEO_NEWSCAT_C) : 
			make_url(BASEDIR."news.php?readmore=".$data['news_id'], 
			BASEDIR.SEO_NEWS_A.SEO_NEWS_B1.$data['news_id'].SEO_NEWS_B2, $data['news_subject'], SEO_NEWS_C))."'>";
			if ($data['news_image_t2'] && $settings['news_image_frontpage'] == 0) {
				$news_cat_image .= "<img src='".IMAGES_N_T.$data['news_image_t2']."' alt='".$data['news_subject']."' class='news-category' /></a>";
			} elseif ($data['news_cat_image']) {
				$news_cat_image .= "<img src='".get_image("nc_".$data['news_cat_name'])."' alt='".$data['news_cat_name']."' class='news-category' /></a>";
			} else {
				$news_cat_image = "";
			}
			$news_news = $data['news_breaks'] == "y" ? nl2br(stripslashes($data['news_news'])) : stripslashes($data['news_news']);
			if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
			$news_info = array(
				"news_id" => $data['news_id'],
				"user_id" => $data['user_id'],
				"user_name" => $data['user_name'],
				"user_status" => $data['user_status'],
				"news_date" => $data['news_datestamp'],
				"news_subject" => $data['news_subject'],
				"cat_id" => $data['news_cat'],
				"cat_name" => $data['news_cat_name'],
				"news_ext" => $data['news_extended'] ? "y" : "n",
				"news_reads" => $data['news_reads'],
				"news_comments" => dbcount("(comment_id)",DB_COMMENTS, "comment_type='N' AND comment_item_id='".$data['news_id']."' AND comment_hidden='0'"),
				"news_allow_comments" => $data['news_allow_comments'],
				"news_sticky" => $data['news_sticky']
			);
			if ($settings['news_style'] == "1") {
				render_news_two_columns($news_subject, $news_news, $news_info);
			} else {
				echo "<!--news_prepost_".$i."-->\n";
				$i++;
				render_news($news_subject, $news_news, $news_info);
			}
		}
		if ($settings['news_style'] == "1") {
			show_news_two_columns();
		}
		echo "<!--sub_news_idx-->\n";
		if ($rows > $settings['newsperpage']) {
			echo "<div align='center' style=';margin-top:5px;'>\n";
			echo pagination(true,(int)$rowstart,(int)$settings['newsperpage'],(int)$rows,3, "", 
			SEO_NEWS_P_A,"","",SEO_NEWS_P_B1.SEO_NEWS_P_B2.SEO_NEWS_P_B3,"", "", SEO_NEWS_P_C);
			echo "\n</div>\n";
		}
	} else {
		opentable($locale['global_077']);
		echo "<div style='text-align:center'><br />\n".$locale['global_078']."<br /><br />\n</div>\n";
		closetable();
	}
} else {
	$result = dbquery("SELECT tn.news_subject, tn.news_cat, tn.news_image, tn.news_image_t1, tn.news_news, tn.news_extended, tn.news_breaks, 
		tn.news_datestamp, tn.news_reads, tn.news_sticky, tn.news_keywords, tn.news_allow_comments, tn.news_allow_ratings,
		tc.news_cat_id, tc.news_cat_name, tc.news_cat_image, tu.user_id, tu.user_name, tu.user_status
		FROM ".DB_NEWS." tn
		LEFT JOIN ".DB_USERS." tu ON tn.news_name=tu.user_id
		LEFT JOIN ".DB_NEWS_CATS." tc ON tn.news_cat=tc.news_cat_id
		WHERE ".groupaccess('news_visibility')." AND news_id='".(int)$_GET['readmore']."' AND news_draft='0'");
	if (dbrows($result)) {
		$data = dbarray($result);
		if (!isset($_POST['post_comment']) && !isset($_POST['post_rating'])) {
			$result2 = dbquery("UPDATE ".DB_NEWS." SET news_reads=news_reads+1 WHERE news_id='".(int)$_GET['readmore']."'");
			$data['news_reads']++;
		}
		$news_cat_image = "";
		$news_subject = $data['news_subject'];
		if ($data['news_image_t1'] && $settings['news_image_readmore'] == "0") {
			$img_size = @getimagesize(IMAGES_N.$data['news_image']);
			$news_cat_image = "<a href=\"javascript:;\" onclick=\"window.open('".IMAGES_N.$data['news_image'].
			"','','scrollbars=yes,toolbar=no,status=no,resizable=yes,width=".($img_size[0]+20).",height=".($img_size[1]+20).
			"')\"><img src='".IMAGES_N_T.$data['news_image_t1']."' alt='".$data['news_subject']."' class='news-category' /></a>";
		} elseif ($data['news_cat_image']) {
			$news_cat_image = "<a href='".make_url("news_cats.php?cat_id=".$data['news_cat'], 
			SEO_NEWSCAT_A.SEO_NEWSCAT_B1.$data['news_cat'].SEO_NEWSCAT_B2, $data['news_cat_name'], SEO_NEWSCAT_C).
			"'><img src='".get_image("nc_".$data['news_cat_name'])."' alt='".$data['news_cat_name']."' class='news-category' /></a>";
		}
		$news_news = stripslashes($data['news_extended'] ? $data['news_extended'] : $data['news_news']);
		if ($data['news_breaks'] == "y") { $news_news = nl2br($news_news); }
		if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
		$news_info = array(
			"news_id" => (int)$_GET['readmore'],
			"user_id" => $data['user_id'],
			"user_name" => $data['user_name'],
			"user_status" => $data['user_status'],
			"news_date" => $data['news_datestamp'],
			"news_subject" => $data['news_subject'],
			"cat_id" => $data['news_cat'],
			"cat_name" => $data['news_cat_name'],
			"news_ext" => "n",
			"news_reads" => $data['news_reads'],
			"news_comments" => 
				dbcount("(comment_id)", DB_COMMENTS, "comment_type='N' AND comment_item_id='".(int)$_GET['readmore']."' AND comment_hidden='0'"),
			"news_allow_comments" => $data['news_allow_comments'],
			"news_sticky" => $data['news_sticky']
		);
		add_to_title($locale['global_201'].$news_subject);
		set_meta('keywords', $data['news_keywords'], false);
		set_meta('description', trimlink($news_subject, 255), false);
		echo "<!--news_pre_readmore-->";
		render_news($news_subject, $news_news, $news_info);
		echo "<!--news_sub_readmore-->";
		if ($settings['enable_tags']) {
			require_once INCLUDES."tag_include.php";
			echo show_tags((int)$_GET['readmore'], "N");
		}
		if ($data['news_allow_comments']) {
			require_once INCLUDES."comments_include.php";
			showcomments("N", DB_NEWS, "news_id", (int)$_GET['readmore'], FUSION_SELF."?readmore=".(int)$_GET['readmore']);
		}
		if ($data['news_allow_ratings'] && $settings['ratings_enabled'] == "1") {
			require INCLUDES."ratings_include.php";
			showratings("N", (int)$_GET['readmore'], FUSION_SELF."?readmore=".(int)$_GET['readmore']); 
		}
		if($settings['sharethis_news']) {
			$share_this = "news";
			require_once INCLUDES."share_this_include.php";
		}
	} else {
		redirect(FUSION_SELF);
	}
}

require_once TEMPLATES."footer.php";
?>