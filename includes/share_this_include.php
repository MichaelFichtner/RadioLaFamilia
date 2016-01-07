<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/share_this_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if(isset($share_this)) {
	
	switch ($share_this) {
	case "thread":
		
	$id = isnum($_GET['thread_id']) ? $_GET['thread_id'] : 0;
	$subject = $fdata['thread_subject'];
	$url = $settings['siteurl'].make_url("forum/viewthread.php?thread_id=".$id, SEO_F_THREAD_A.SEO_F_THREAD_B1.$id.SEO_F_THREAD_B2, $subject,SEO_F_THREAD_C);
	$title = $locale['share_005'];
	$show_this = ($id && $subject != "" && $url != "") ? true : false;
		
	break;
	case "news":
		
	$id = isnum($_GET['readmore']) ? $_GET['readmore'] : 0;
	$subject = $news_subject;
	$url = $settings['siteurl'].make_url("news.php?readmore=".$id, SEO_NEWS_A.SEO_NEWS_B1.$id.SEO_NEWS_B2, $subject, SEO_NEWS_C);
	$title = $locale['share_006'];
	$show_this = ($id && $subject != "" && $url != "") ? true : false;
		
	break;
	case "article":
	
	$id = isnum($_GET['article_id']) ? $_GET['article_id'] : 0;
	$subject = $article_subject;
	$url = $settings['siteurl'].make_url("articles.php?article_id=".$id, SEO_ARTICLE_A.SEO_ARTICLE_B1.$id.SEO_ARTICLE_B2, $subject, SEO_ARTICLE_C);
	$title = $locale['share_007'];
	$show_this = ($id && $subject != "" && $url != "") ? true : false;
		
	break;
	default:
		$show_this = false;
	}
	
	
	
	if($show_this){
		
		opentable($title);
		echo "<table width='100%' class='tbl-border' cellspacing='1' cellpadding='0'>\n";
		echo "<tr>\n<td class='tbl2' style='width:140px; '>".$locale['share_001']."</td>\n";
		echo "<td class='tbl1'>";
		echo "<input type='text' value='".$url."' class='textbox' style='font-size:12px;width:99%' onclick='javascript:this.select();' />";
		echo "</td>\n</tr>\n";
		echo "<tr>\n<td class='tbl2' style='width:140px;'>".$locale['share_002']."</td>\n";
		echo "<td class='tbl1'>";
		echo "<input type='text' value='[url=".$url."]".$subject."[/url]' class='textbox' style='font-size:12px;width:99%' onclick='javascript:this.select();' />";
		echo "</td>\n</tr>\n";
		echo "<tr>\n<td class='tbl2' style='width:140px;'>".$locale['share_003']."</td>\n";
		echo "<td class='tbl1'>";
		echo "<input type='text' value='<a href=\"".$url."\">".$subject."</a>' class='textbox' style='font-size:12px;width:99%' onclick='javascript:this.select();' />";
		echo "</td></tr>\n\n";
		
		echo "<tr>\n<td class='tbl2' style='width:140px;'>".$locale['share_004']."</td>\n";
		echo "<td class='tbl1'>";
		
		echo '
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style">
		<a href="http://addthis.com/bookmark.php?v=250" class="addthis_button_compact">Share</a>
		<span class="addthis_separator">|</span>
		<a class="addthis_button_facebook"></a>
		<a class="addthis_button_myspace"></a>
		<a class="addthis_button_google"></a>
		<a class="addthis_button_twitter"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
		<!-- AddThis Button END -->';
		
		echo "</td></tr>\n\n";
		
		echo "</table>\n";
		closetable();
	}
}
?>