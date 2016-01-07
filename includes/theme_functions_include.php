<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/theme_functions_include.php
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
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

function load_css_file() {
	global $load_css_file;
		return (isset($load_css_file) && $load_css_file != "" && file_exists($load_css_file) ? $load_css_file : THEME."styles.css");
}

function check_panel_status($side) {
	
	global $settings;
	
	$exclude_list = "";
	
	if ($side == "left") {
		if ($settings['exclude_left'] != "") {
			$exclude_list = explode("\r\n", $settings['exclude_left']);
		}
	} elseif ($side == "upper") {
		if ($settings['exclude_upper'] != "") {
			$exclude_list = explode("\r\n", $settings['exclude_upper']);
		}
	} elseif ($side == "lower") {
		if ($settings['exclude_lower'] != "") {
			$exclude_list = explode("\r\n", $settings['exclude_lower']);
		}
	} elseif ($side == "right") {
		if ($settings['exclude_right'] != "") {
			$exclude_list = explode("\r\n", $settings['exclude_right']);
		}
	}
	
	if (is_array($exclude_list)) {
		$script_url = explode("/", $_SERVER['PHP_SELF']);
		$url_count = count($script_url);
		$base_url_count = substr_count(BASEDIR, "/")+1;
		$match_url = "";
		while ($base_url_count != 0) {
			$current = $url_count - $base_url_count;
			$match_url .= "/".$script_url[$current];
			$base_url_count--;
		}
		if (!in_array($match_url, $exclude_list) && !in_array($match_url.(FUSION_QUERY ? "?".FUSION_QUERY : ""), $exclude_list)) {
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}

function showbanners() {
	global $settings;
	ob_start();
	if ($settings['sitebanner2']) {
		eval("?><div style='float: right;'>".stripslashes($settings['sitebanner2'])."</div>\n<?php ");
	}
	if ($settings['sitebanner1']) {
		eval("?>".stripslashes($settings['sitebanner1'])."\n<?php ");
	} elseif ($settings['sitebanner']) {
		echo "<a href='".$settings['siteurl']."'><img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."' style='border: 0;' /></a>\n";
	} else {
		echo "<a href='".$settings['siteurl']."'>".$settings['sitename']."</a>\n";
	}	
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

function showsublinks($sep = "&middot;", $class = "") {
global $navigation_cache;

	if ($navigation_cache == false) { navigation_cache(); }

	if (is_array($navigation_cache) && count($navigation_cache)) {
		$res = "<ul>\n";
		for ($i = 0; $i < count($navigation_cache); $i++) {
			if ($navigation_cache[$i]['link_url'] != "---" && $navigation_cache[$i]['link_position'] >= '2') {
				$link_target = $navigation_cache[$i]['link_window'] == "1" ? " target='_blank'" : "";
				$li_class = ($i == 0 ? " class='first-link".($class ? " $class" : "")."'" : ($class ? " class='$class'" : ""));
				if (strstr($navigation_cache[$i]['link_url'], "http://") || strstr($navigation_cache[$i]['link_url'], "https://")) {
					$res .= "<li".$li_class.">".$sep."<a href='".$navigation_cache[$i]['link_url']."'$link_target><span>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</span></a></li>\n";
				} elseif(URL_REWRITE && $navigation_cache[$i]['link_seo_url'] != '') {
					$res .= "<li".$li_class.">".$sep."<a href='".BASEDIR.$navigation_cache[$i]['link_seo_url']."'$link_target><span>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</span></a></li>\n";
				} else {
					$res .= "<li".$li_class.">".$sep."<a href='".BASEDIR.$navigation_cache[$i]['link_url']."'$link_target><span>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</span></a></li>\n";
				}
			}
		}
	$res .= "</ul>\n";
	return $res;
	}
}

function showsubdate() {
	global $settings;
	return ucwords(showdate($settings['subheaderdate'], time()));
}

function newsposter($info, $sep = "", $class = "") {
	global $locale; $res = "";
	$link_class = $class ? " class='$class' " : "";
	$res = THEME_BULLET." <span ".$link_class.">".profile_link($info['user_id'], $info['user_name'], $info['user_status'])."</span> ";
	$res .= $locale['global_071'].showdate("newsdate", $info['news_date']);
	$res .= $info['news_ext'] == "y" || $info['news_allow_comments'] ? $sep."\n" : "\n";
	return "<!--news_poster-->".$res;
}

function newsopts($info, $sep, $class = "") {
	global $locale, $settings; $res = "";
	$link_class = $class ? " class='$class' " : "";
	if (!isset($_GET['readmore']) && $info['news_ext'] == "y") $res = "<a href='".make_url("news.php?readmore=".$info['news_id'], "news-".$info['news_id']."-", $info['news_subject'], ".html")."'".$link_class.">".$locale['global_072']."</a> ".$sep." "; // Pimped: make_url
	if ($info['news_allow_comments'] && $settings['comments_enabled'] == "1") { $res .= "<a href='".make_url("news.php?readmore=".$info['news_id'], "news-".$info['news_id']."-", $info['news_subject'], ".html")."#comments'".$link_class.">".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a> ".$sep." "; } // Pimped: make_url
	if ($info['news_ext'] == "y" || ($info['news_allow_comments'] && $settings['comments_enabled'] == "1")) { $res .= $info['news_reads'].$locale['global_074']."\n ".$sep." "; }
	$res .= "<a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' title='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>\n";
	return "<!--news_opts-->".$res;
}

function newscat($info, $sep = "", $class = "") {
	global $locale; $res = "";
	$link_class = $class ? " class='$class' " : "";
	$res .= $locale['global_079'];
	if ($info['cat_id']) {
		$res .= "<a href='".make_url("news_cats.php?cat_id=".$info['news_id'], "news-category-".$info['cat_id']."-", $info['cat_name'], ".html")."'$link_class>".$info['cat_name']."</a>"; // Pimped: make_url
	} else {
		$res .= "<a href='".make_url("news_cats.php?cat_id=0", "news-category-0-", "no defined category", ".html")."'$link_class>".$locale['global_080']."</a>"; // Pimped: make_url
	}
	return "<!--news_cat-->".$res." $sep ";
}

function articleposter($info, $sep = "", $class = "") {
	global $locale, $settings; $res = "";
	$link_class = $class ? " class='$class' " : "";
	$res = THEME_BULLET." ".$locale['global_070']."<span ".$link_class.">".profile_link($info['user_id'], $info['user_name'], $info['user_status'])."</span>\n";
	$res .= $locale['global_071'].showdate("newsdate", $info['article_date']);
	$res .= ($info['article_allow_comments'] && $settings['comments_enabled'] == "1" ? $sep."\n" : "\n");
	return "<!--article_poster-->".$res;
}

function articleopts($info, $sep) {
	global $locale, $settings; $res = "";
	if ($info['article_allow_comments'] && $settings['comments_enabled'] == "1") { $res = "<a href='".make_url("articles.php?article_id=".$info['article_id'], "article-".$info['article_id']."-", $info['article_subject'], ".html")."#comments'>".$info['article_comments'].($info['article_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a> ".$sep."\n"; } // Pimped make_url
	$res .= $info['article_reads'].$locale['global_074']." ".$sep."\n";
	$res .= "<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>\n";
	return "<!--article_opts-->".$res;
}

function articlecat($info, $sep = "", $class = "") {
	global $locale; $res = "";
	$link_class = $class ? " class='$class' " : "";
	$res .= $locale['global_079'];
	if ($info['cat_id']) {
		$res .= "<a href='".make_url("articles.php?cat_id=".$info['cat_id'], "article-cat-".$info['cat_id']."-", $info['cat_name'], ".html")."'$link_class>".$info['cat_name']."</a>"; // Pimped: make_url
	} else {
		$res .= "<a href='".make_url("articles.php?cat_id=".$info['cat_id'], "article-cat-".$info['cat_id']."-", $locale['global_080'], ".html")."'$link_class>".$locale['global_080']."</a>"; // Pimped: make_url
	}
	return "<!--article_cat-->".$res." $sep ";
}

function itemoptions($item_type, $item_id) { // Pimped for Custom Pages
	global $locale, $aidlink; $res = "";
	if ($item_type == "N") {
		if (iADMIN && checkrights($item_type)) { $res .= "<!--article_news_opts--> &middot; <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$item_id."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a>\n"; }
	} elseif ($item_type == "A") {
		if (iADMIN && checkrights($item_type)) { $res .= "<!--article_admin_opts--> &middot; <a href='".ADMIN."articles.php".$aidlink."&amp;action=edit&amp;article_id=".$item_id."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a>\n"; }
	} elseif ($item_type == "CP") {
		if (iADMIN && checkrights($item_type)) { $res .= "<br /><!--custom_pages_admin_opts--> &middot; <a href='".ADMIN."custom_pages.php".$aidlink."&amp;edit=1&amp;page_id=".$item_id."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a>\n"; }
	}
	return $res;
}

function showrendertime($queries = true) {
	global $locale, $mysql_queries_count;
	$res = "<!--rendertime-->";
	$res .= sprintf($locale['global_172'], substr((get_microtime() - START_TIME),0,4));
	$res .= ($queries ? " - ".$mysql_queries_count." ".$locale['global_173'] : "");
	return $res;
}

function showcopyright($class = "") { // Pimped
	global $settings;
	$link_class = $class ? " class='$class' " : "";
	$res = "Powered by <a href='http://www.pimped-fusion.net'".$link_class." title='Pimped-Fusion - Open Source Content Management System'>Pimped-Fusion</a> copyright &copy; 2009 - ".date("Y")."<br />\n";
	$res .= "Powered by <a href='http://www.php-fusion.co.uk'".$link_class.">PHP-Fusion</a> copyright &copy; 2002 - ".date("Y")." by Nick Jones.<br />\n";
	$res .= "Released as free software without warranties under <a href='http://www.fsf.org/licensing/licenses/agpl-3.0.html'".$link_class.">GNU Affero GPL</a> v3.\n";
	return $res;
}

function showcounter() {
	global $locale, $settings;
	if ($settings['visitorcounter_enabled']) {
		return "<!--counter-->".number_format($settings['counter'])." ".($settings['counter'] == 1 ? $locale['global_170'] : $locale['global_171']);
	} else {
		return '';
	}
}

function panelbutton($state, $bname) {
	if (isset($_COOKIE["fusion_box_".$bname])) {
		if ($_COOKIE["fusion_box_".$bname] == "none") {
			$state = "off";
		} else {
			$state = "on";
		}
	}
	return "<img src='".get_image("panel_".($state == "on" ? "off" : "on"))."' id='b_$bname' class='panelbutton' alt='' onclick=\"javascript:flipBox('$bname')\" />";
}

function panelstate($state, $bname) {
	if (isset($_COOKIE["fusion_box_".$bname])) {
		if ($_COOKIE["fusion_box_".$bname] == "none") {
			$state = "off";
		} else {
			$state = "on";
		}
	}
	return "<div id='box_$bname'".($state == "off" ? " style='display:none'" : "").">\n";
}

// Render News with 2 Columns
if(!function_exists("render_news_two_columns")) {
function render_news_two_columns($news_subject, $news_news, $news_info) {
global $result, $locale, $aidlink, $rows, $nrows, $ncount, $news_, $i, $rc, $ncolumn;
	if ($rows <= 2 || $ncount == 1) {
	$news_[0] .= "<table width='100%' cellpadding='0' cellspacing='0'>\n";
	$news_[0] .= "<tr>\n<td class='tbl2'><strong>".$news_subject."</strong></td>\n</tr>\n";
	$news_[0] .= "<tr>\n<td class='tbl1' style='text-align:justify'>".$news_news."</td>\n</tr>\n";
	$news_[0] .= "<tr>\n<td align='center' class='tbl2'>\n";
	$news_[0] .= "<span class='small2'>".THEME_BULLET." ".profile_link($news_info['user_id'], $news_info['user_name'], $news_info['user_status'])." ".$locale['global_071'].showdate("longdate", $news_info['news_date'])." &middot;\n";
	if ($news_info['news_ext'] == "y" || $news_info['news_allow_comments']) {
		$news_[0] .= $news_info['news_ext'] == "y" ? "<a href='".make_url("news.php?readmore=".$news_info['news_id'], "news-".$news_info['news_id']."-", $news_info['news_subject'], ".html")."' title='".$news_info['news_subject']."'>".$locale['global_072']."</a> &middot;\n" : ""; // Pimped: make-url
		$news_[0] .= $news_info['news_allow_comments'] ? "<a href='".make_url("news.php?readmore=".$news_info['news_id'], "news-".$news_info['news_id']."-", $news_info['news_subject'], ".html")."'>".$news_info['news_comments'].($news_info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a> &middot;\n" : ""; // Pimped: make-url
		$news_[0] .= $news_info['news_reads'].$locale['global_074']." &middot;\n";
	}
	$news_[0] .= "<a href='print.php?type=N&amp;item_id=".$news_info['news_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>";
	if (checkrights("N")) { $news_[0] .= " &middot;  <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$news_info['news_id']."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a></span>\n"; } else { $news_[0] .= "</span>\n"; }
	$news_[0] .= "</td>\n</tr>\n</table>\n";
	if ($ncount != $rows) { $news_[0] .= "<div><img src='".get_image("blank")."' alt='' width='1' height='8' /></div>\n"; }
	} else {
	if ($i == $nrows && $ncolumn != 2) { $ncolumn = 2; $i = 0; }
	$row_color = ($rc % 2 == 0 ? "tbl2" : "tbl1");
	$news_[$ncolumn] .= "<table width='100%' cellpadding='0' cellspacing='0'>\n";
	$news_[$ncolumn] .= "<tr>\n<td class='tbl2'><strong>".$news_subject."</strong></td>\n</tr>\n";
	$news_[$ncolumn] .= "<tr>\n<td class='tbl1' style='text-align:justify'>".$news_news."</td>\n</tr>\n";
	$news_[$ncolumn] .= "<tr>\n<td align='center' class='tbl2'>\n";
	$news_[$ncolumn] .= "<span class='small2'>".THEME_BULLET." ".profile_link($news_info['user_id'], $news_info['user_name'], $news_info['user_status'])." ".$locale['global_071'].showdate("longdate", $news_info['news_date']);
	if ($news_info['news_ext'] == "y" || $news_info['news_allow_comments']) {
		$news_[$ncolumn] .= "<br />\n";
		$news_[$ncolumn] .= $news_info['news_ext'] == "y" ? "<a href='".make_url("news.php?readmore=".$news_info['news_id'], "news-".$news_info['news_id']."-", $news_info['news_subject'], ".html")."'  title='".$news_info['news_subject']."'>".$locale['global_072']."</a> &middot;\n" : ""; // Pimped: make-url
		$news_[$ncolumn] .= $news_info['news_allow_comments'] ? "<a href='".make_url("news.php?readmore=".$news_info['news_id'], "news-".$news_info['news_id']."-", $news_info['news_subject'], ".html")."#comments'>".$news_info['news_comments'].($news_info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a> &middot;\n" : ""; // Pimped: make-url
		$news_[$ncolumn] .= $news_info['news_reads'].$locale['global_074']." &middot;\n";
	} else {
		$news_[$ncolumn] .= " &middot;\n";
	}
	$news_[$ncolumn] .= "<a href='print.php?type=N&amp;item_id=".$news_info['news_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>\n";
	if (checkrights("N")) { $news_[$ncolumn] .= " &middot; <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$news_info['news_id']."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a></span>\n"; } else { $news_[$ncolumn] .= "</span>\n"; }
	$news_[$ncolumn] .= "</td>\n</tr>\n</table>\n";
	if ($ncolumn == 1 && $i < ($nrows - 1)) { $news_[$ncolumn] .= "<div><img src='".get_image("blank")."' alt='' width='1' height='8' /></div>\n"; }
	if ($ncolumn == 2 && $i < (dbrows($result) - $nrows - 2)) { $news_[$ncolumn] .= "<div><img src='".get_image("blank")."' alt='' width='1' height='8' /></div>\n"; }
	$i++; $rc++;
	}
$ncount++;
}
}

if(!function_exists("show_news_two_columns")) {
	function show_news_two_columns() {
	global $locale, $news_;
		opentable($locale['global_077']);
		echo "<table cellpadding='0' cellspacing='0' style='width:100%'>\n<tr>\n<td colspan='3' style='width:100%'>\n";
		echo $news_[0];
		echo "</td>\n</tr>\n<tr>\n<td style='width:50%;vertical-align:top;'>\n";
		echo $news_[1];
		echo "</td>\n<td style='width:10px'><img src='".get_image("blank")."' alt='' width='10' height='1' /></td>\n<td style='width:50%;vertical-align:top;'>\n";
		echo $news_[2];
		echo "</td>\n</tr>\n</table>\n";
		closetable();
	}
}

// v6 compatibility
function opensidex($title, $state = "on") {
if(DEBUGING) {
echo "<strong>old function ".__FUNCTION__." is used</strong><br />";
//echo "<pre>".print_r(debug_backtrace(), true)."</pre>";
}
	openside($title, true, $state);

}

function closesidex() {
if(DEBUGING) {
echo "<strong>old function ".__FUNCTION__." is used</strong><br />";
//echo "<pre>".print_r(debug_backtrace(), true)."</pre>";
}
	closeside();

}

function tablebreak() {
if(DEBUGING) {
echo "<strong>old function ".__FUNCTION__." is used</strong><br />";
//echo "<pre>".print_r(debug_backtrace(), true)."</pre>";
}
	return true;
}
?>