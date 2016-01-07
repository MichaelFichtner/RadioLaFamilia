<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: functions_mysql_dev_include.php
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

// Mysql Functions

function dbquery($query, $give_error = true) {
	global $mysql_queries_count, $mysql_queries_time;
	$mysql_queries_count++;
	
	$query_time = get_microtime();	
	$result = @mysql_query($query);
	$query_time = substr((get_microtime() - $query_time),0,7);
	
	$mysql_queries_time[$mysql_queries_count] = array($query_time, $query);

	if (!$result) {
		if($give_error) echo mysql_error();
		return false;
	} else {
		return $result;
	}	
}

function unbdbquery($query, $give_error = true) {
	global $mysql_queries_count, $mysql_queries_time;
	$mysql_queries_count++;
	
	$query_time = get_microtime();	
	$result = @mysql_unbuffered_query($query);
	$query_time = substr((get_microtime() - $query_time),0,7);
	
	$mysql_queries_time[$mysql_queries_count] = array($query_time, $query);

	if (!$result) {
		if($give_error) echo mysql_error();
		return false;
	} else {
		return $result;
	}	
}

function dbcount($field, $table, $conditions = "") {
	global $mysql_queries_count, $mysql_queries_time;
	$mysql_queries_count++;

	$query_time = get_microtime();
	$cond = ($conditions ? " WHERE ".$conditions : "");
	$result = @mysql_query("SELECT Count".$field." FROM ".$table.$cond);
	$query_time = substr((get_microtime() - $query_time),0,7);
	
	$mysql_queries_time[$mysql_queries_count] = array($query_time, "SELECT COUNT".$field." FROM ".$table.$cond);
	
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		$rows = mysql_result($result, 0);
		return $rows;
	}
}

function dbresult($query, $row) {
	global $mysql_queries_count, $mysql_queries_time; $mysql_queries_count++;
	
	$query_time = get_microtime();
	$result = @mysql_result($query, $row);
	$query_time = substr((get_microtime() - $query_time),0,7);
	
	$mysql_queries_time[$mysql_queries_count] = array($query_time, $query);
	
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbrows($query) {
	$result = @mysql_num_rows($query);
	return $result;
}

function dbarray($query) {
	$result = @mysql_fetch_assoc($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbarraynum($query) {
	$result = @mysql_fetch_row($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbinsert_id() {
	return mysql_insert_id();
}

function dbresult_free($res) {
	mysql_free_result($res); 
}

function dbconnect($db_host, $db_user, $db_pass, $db_name, $db_charset = 'UTF8') {
	global $db_connect;
	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><strong>Unable to establish connection to MySQL</strong><br />".mysql_errno()." : ".mysql_error()."</div>");
	} elseif (!$db_select) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><strong>Unable to select MySQL database</strong><br />".mysql_errno()." : ".mysql_error()."</div>");
	} else {
		$db_charset = mysql_set_charset($db_charset, $db_connect);
	}
}

if(function_exists('mysql_set_charset') === false) {
	/**
	* Sets the client character set.
	* Note: This function requires MySQL 5.0.7 or later.
	* @see http://www.php.net/mysql-set-charset
	* @param string $charset A valid character set name
	* @param resource $link_identifier The MySQL connection
	* @return TRUE on success or FALSE on failure
	*/
	function mysql_set_charset($charset, $link_identifier = null) {
		if ($link_identifier == null) {
			return mysql_query('SET NAMES "'.$charset.'"');
		} else {
			return mysql_query('SET NAMES "'.$charset.'"', $link_identifier);
		}
	}
}
?>