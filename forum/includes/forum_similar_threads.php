<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/includes/forum_statistics.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

	$thread_id = isnum($_GET['thread_id']) ? $_GET['thread_id'] : 0;
	
	if($thread_id){
		list($thread_subject) = dbarraynum(dbquery("SELECT thread_subject from ".DB_THREADS." WHERE thread_id=".(int)$thread_id.""));
		
		$rel_thread_res = dbquery("
		SELECT tt.thread_id, tt.thread_subject, tf.forum_id, tf.forum_name, tf.forum_access, tt.thread_postcount, tt.thread_lastpost
		FROM ".DB_THREADS." tt
		INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
		WHERE MATCH (thread_subject) AGAINST ('".$thread_subject."' IN BOOLEAN MODE) AND thread_id != ".(int)$thread_id."
		AND ".groupaccess('tf.forum_access')."
		ORDER BY tt.thread_lastpost DESC LIMIT 5");
		
		if(dbrows($rel_thread_res)){
			opentable($locale['similar_100']);
			echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n
				<tr>
					<th class='forum-caption'>".$locale['global_044']."</th>
					<th class='forum-caption'>".$locale['global_048']."</th>
					<th class='forum-caption'>".$locale['global_046']."</th>
					<th class='forum-caption'>".$locale['global_047']."</th>
				</tr>\n";
			$i = 0;
			while($thread = dbarray($rel_thread_res)){
				$i++; $row = $i % 2 ? " class='tbl1'" : " class='tbl2'";
				echo "
				<tr>
					<td".$row."><a href='".make_url(FORUM."viewthread.php?thread_id=".$thread['thread_id'], BASEDIR."forum-thread-".$thread['thread_id']."-", $thread['thread_subject'], ".html")."'>".$thread['thread_subject']."</a></td>
					<td".$row.">".$thread['forum_name']."</td>
					<td".$row.">".$thread['thread_postcount']."</td>
					<td".$row.">".showdate("forumdate", $thread['thread_lastpost'])."</td>
				</tr>";
			}
			
			echo "</table>";
			closetable();
		}
	}
?>