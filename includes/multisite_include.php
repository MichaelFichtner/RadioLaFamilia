<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/multisite_include.php
| Version: Pimped Fusion v0.08.01
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
// Cookie prefix
if (!defined("COOKIE_PREFIX")) define("COOKIE_PREFIX", "pif_");
// Database table definitions
define("DB_ADMIN", DB_PREFIX."admin");
define("DB_ADMIN_LOG", DB_PREFIX."admin_log"); // Pimped
define("DB_ADMIN_NOTES", DB_PREFIX."admin_notes"); // Pimped
define("DB_ARTICLE_CATS", DB_PREFIX."article_cats");
define("DB_ARTICLES", DB_PREFIX."articles");
define("DB_BBCODES", DB_PREFIX."bbcodes");
define("DB_BLACKLIST", DB_PREFIX."blacklist");
define("DB_CAPTCHA", DB_PREFIX."captcha");
define("DB_COMMENTS", DB_PREFIX."comments");
define("DB_CUSTOM_PAGES", DB_PREFIX."custom_pages");
define("DB_DOWNLOAD_CATS", DB_PREFIX."download_cats");
define("DB_DOWNLOADS", DB_PREFIX."downloads");
define("DB_FAILED_LOGINS", DB_PREFIX."failed_logins"); // Pimped
define("DB_FAQ_CATS", DB_PREFIX."faq_cats");
define("DB_FAQS", DB_PREFIX."faqs");
define("DB_FLOOD_CONTROL", DB_PREFIX."flood_control");
define("DB_FORUM_ATTACHMENTS", DB_PREFIX."forum_attachments");
define("DB_FORUM_OBSERVER", DB_PREFIX."forum_observer"); // Pimped
define("DB_FORUM_POLL_OPTIONS", DB_PREFIX."forum_poll_options");
define("DB_FORUM_POLL_VOTERS", DB_PREFIX."forum_poll_voters");
define("DB_FORUM_POLLS", DB_PREFIX."forum_polls");
define("DB_POST_RATINGS", DB_PREFIX."post_ratings"); // Pimped
define("DB_POST_RATING_TYPES", DB_PREFIX."post_rating_types"); // Pimped
define("DB_FORUM_RANKS", DB_PREFIX."forum_ranks");
define("DB_FORUM_REPORT", DB_PREFIX."forum_report"); // Pimped
define("DB_FORUMS", DB_PREFIX."forums");
define("DB_INFUSIONS", DB_PREFIX."infusions");
define("DB_MESSAGES", DB_PREFIX."messages");
define("DB_MESSAGES_OPTIONS", DB_PREFIX."messages_options");
define("DB_NEW_USERS", DB_PREFIX."new_users");
define("DB_NEWS", DB_PREFIX."news");
define("DB_NEWS_CATS", DB_PREFIX."news_cats");
define("DB_ONLINE", DB_PREFIX."online");
define("DB_PANELS", DB_PREFIX."panels");
define("DB_PHOTO_ALBUMS", DB_PREFIX."photo_albums");
define("DB_PHOTOS", DB_PREFIX."photos");
define("DB_POLL_VOTES", DB_PREFIX."poll_votes");
define("DB_POLLS", DB_PREFIX."polls");
define("DB_POSTS", DB_PREFIX."posts");
define("DB_RATINGS", DB_PREFIX."ratings");
define("DB_REGISTRATION", DB_PREFIX."secure_registration"); // Pimped
define("DB_SESSIONS", DB_PREFIX."sessions");
define("DB_SETTINGS", DB_PREFIX."settings");
define("DB_SETTINGS_INF", DB_PREFIX."settings_inf");
define("DB_SHOUTBOX", DB_PREFIX."shoutbox");
define("DB_SITE_LINKS", DB_PREFIX."site_links");
define("DB_SMILEYS", DB_PREFIX."smileys");
define("DB_SUBMISSIONS", DB_PREFIX."submissions");
define("DB_SUSPENDS", DB_PREFIX."suspends");
define("DB_TAGS", DB_PREFIX."tags"); // Pimped
define("DB_THREAD_NOTIFY", DB_PREFIX."thread_notify");
define("DB_THREADS", DB_PREFIX."threads");
define("DB_USER_FIELD_CATS", DB_PREFIX."user_field_cats");
define("DB_USER_FIELDS", DB_PREFIX."user_fields");
define("DB_USER_GROUPS", DB_PREFIX."user_groups");
define("DB_USERS", DB_PREFIX."users");
define("DB_WARNING", DB_PREFIX."warning"); // Pimped
define("DB_WARNING_CATALOG", DB_PREFIX."warning_catalog"); // Pimped
define("DB_WARNING_SETTINGS", DB_PREFIX."warning_settings"); // Pimped
define("DB_WEBLINK_CATS", DB_PREFIX."weblink_cats");
define("DB_WEBLINKS", DB_PREFIX."weblinks");
?>