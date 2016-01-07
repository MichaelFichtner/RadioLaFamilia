<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/file.php
| Version: Pimped Fusion v0.06.00
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";

if (isset($_GET['getfile']) && isnum($_GET['getfile'])) {
	$result = dbquery("SELECT attach_name, thread_id FROM ".DB_FORUM_ATTACHMENTS." WHERE attach_id='".(int)$_GET['getfile']."'"); // Pimped: Multi-Upload
	if (dbrows($result)) {
		$data = dbarray($result);
		
		$result_f = dbquery(
		"SELECT t.thread_id, f.forum_cat, f.forum_access,
		f2.forum_name AS forum_cat_name
		FROM ".DB_THREADS." t
		LEFT JOIN ".DB_FORUMS." f ON t.forum_id=f.forum_id
		LEFT JOIN ".DB_FORUMS." f2 ON f.forum_cat=f2.forum_id
		WHERE t.thread_id='".(int)$data['thread_id']."' LIMIT 1");
		
		if (dbrows($result_f) && file_exists(FORUM_ATT.$data['attach_name'])) {
			$fdata = dbarray($result_f);
			if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); }
			$result = dbquery("UPDATE ".DB_FORUM_ATTACHMENTS." SET attach_counter=attach_counter+1 WHERE attach_id='".(int)$_GET['getfile']."'"); // Pimped
			download_file(FORUM_ATT.$data['attach_name']);
		} else {
			redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html"));
		}
	}
	exit;
}
?>