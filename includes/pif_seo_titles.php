<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: pif_seo_titles.php
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

# NEWS
// example: news.html
// example: news-ID-NAME.html
define("SEO_NEWS_A", "news");
define("SEO_NEWS_B1", "-");
define("SEO_NEWS_B2", "-");
define("SEO_NEWS_C", ".html");
// example: news-page-NR.html
define("SEO_NEWS_P_A", "news");
define("SEO_NEWS_P_B1", "-");
define("SEO_NEWS_P_B2", "page");
define("SEO_NEWS_P_B3", "-");
define("SEO_NEWS_P_C", ".html");

# NEWS-CATS
define("SEO_NEWSCAT_A", "news-category");
define("SEO_NEWSCAT_B1", "-");
define("SEO_NEWSCAT_B2", "-");
define("SEO_NEWSCAT_C", ".html");

# ARTICLES
// example: article-1-NAME.html
define("SEO_ARTICLE_A", "article");
define("SEO_ARTICLE_B1", "-");
define("SEO_ARTICLE_B2", "-");
define("SEO_ARTICLE_C", ".html");

# ARTICLE CATS
// example: article-cat-1-NAME.html
define("SEO_ARTICLE_CAT_A", "article-cat");
define("SEO_ARTICLE_CAT_B1", "-");
define("SEO_ARTICLE_CAT_B2", "-");
define("SEO_ARTICLE_CAT_C", ".html");

# WEBLINK
// example: weblink-1-NAME.html
define("SEO_WEBLINK_A", "weblink");
define("SEO_WEBLINK_B1", "-");
define("SEO_WEBLINK_B2", "-");
define("SEO_WEBLINK_C", ".html");

# WEBLINK-CATS
// example: weblinks-cat-ID-NAME.html
// example: weblinks-cat-ID-page-NR-NAME.html
define("SEO_WEBLINK_CAT_A", "weblinks-cat");
define("SEO_WEBLINK_CAT_B1", "-");
define("SEO_WEBLINK_CAT_P", "-page-");
define("SEO_WEBLINK_CAT_B2", "-");
define("SEO_WEBLINK_CAT_C", ".html");

# PROFILE
// User profile
define("SEO_PROFILE_A", "profile");
define("SEO_PROFILE_B1", "-");
define("SEO_PROFILE_B2", "-");
define("SEO_PROFILE_C", ".html");
// Group Profile
define("SEO_GROUP_A", "group");
define("SEO_GROUP_B1", "-");
define("SEO_GROUP_B2", "-");
define("SEO_GROUP_C", ".html");
// Edit Profile
define("SEO_PROFILE_EDIT_A", "edit_profile");
define("SEO_PROFILE_EDIT_C", ".html");

# CUSTOM PAGES
// example: page-ID-NAME.html
define("SEO_PAGE_A", "page");
define("SEO_PAGE_B1", "-");
define("SEO_PAGE_B2", "-");
define("SEO_PAGE_C", ".html");

# FORUM
// Forum Thread
// example: forum-thread-ID-NAME.html
define("SEO_F_THREAD_A", "forum-thread");
define("SEO_F_THREAD_B1", "-");
define("SEO_F_THREAD_B2", "-");
define("SEO_F_THREAD_C", ".html");

# Member List
define("SEO_MEMBERLIST_A", "members");
define("SEO_MEMBERLIST_C", ".html");

# Messages
define("SEO_MESSAGE_A", "messages");
define("SEO_MESSAGE_C", ".html");

# Submissions
define("SEO_SUBMIT_LINK_A", "submit-link");
define("SEO_SUBMIT_LINK_C", ".html");
define("SEO_SUBMIT_NEWS_A", "submit-news");
define("SEO_SUBMIT_NEWS_C", ".html");
define("SEO_SUBMIT_ARTICLE_A", "submit-article");
define("SEO_SUBMIT_ARTICLE_C", ".html");
define("SEO_SUBMIT_PHOTO_A", "submit-photo");
define("SEO_SUBMIT_PHOTO_C", ".html");

# Log Out
define("SEO_LOGOUT_A", "logout");
define("SEO_LOGOUT_C", ".html");

# INDEX
define("SEO_INDEX_A", "index"); # don't change this definition plz
define("SEO_INDEX_C", ".html");
?>