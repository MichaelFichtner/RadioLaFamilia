<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: maincore.php
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
if (preg_match("/maincore.php/i", $_SERVER['PHP_SELF'])) { die(); }

// Debuging functions true/false
define("DEBUGING", true); # should be "false" on live sites

// Show all type of erros for development
error_reporting(-1); #error_reporting(E_ALL);

// Calculate script start/end time
function get_microtime() {
	list($usec, $sec) = explode(" ", microtime()); 
	return ((float)$usec + (float)$sec); 
}

// Define script start time
define("START_TIME", get_microtime());

// Prevent PHP E_STRICT Warnings
if(function_exists("date_default_timezone_set") && function_exists("date_default_timezone_get"))
@date_default_timezone_set(@date_default_timezone_get());

// Prevent any possible XSS attacks via $_GET.
if (strip_get($_GET)) {
	die("Prevented a XSS attack through a GET variable!");
}

// Filter the $_GET var
for(reset($_GET); list($key,$value) = each($_GET); ) $_GET[$key] = secure_get($value);

// Start Output Buffering
//ob_start("ob_gzhandler"); //Uncomment this line and comment the one below to enable output compression.
ob_start();

// HTML Output for Error Msg
$die1 = "<div style='font-family:Verdana;font-size:11px;text-align:center;'><strong>";
$die2 = "</strong><br /></div>";

// Locate config.php and set the basedir path
$folder_level = ''; $i = 0;
while (!file_exists($folder_level."config.php")){
	$i++;
	if ($i == 7 || file_exists($folder_level."maincore.php")) { die($die1."config.php not found!".$die2); }
	$folder_level .= "../"; 
}
if (!require_once $folder_level."config.php") die($die1."config.php not found!".$die2);

define("BASEDIR", $folder_level);

// If config.php is empty, activate setup.php script
if (!isset($db_name)) { redirect("_install/setup.php"); }

// Include Mysql Functions and Multisite Definitions
if(DEBUGING) {
	if (!require_once BASEDIR."includes/functions_mysql_dev_include.php") die($die1."functions_mysql_include.php not found!".$die2);
} else {
	if (!require_once BASEDIR."includes/functions_mysql_include.php") die($die1."functions_mysql_include.php not found!".$die2);
}
if (!require_once BASEDIR."includes/multisite_include.php") die($die1."multisite_include.php not found!".$die2);

// Establish mySQL database connection
$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
unset($db_host, $db_user, $db_pass, $db_name);

// MySQL Count and debug
$mysql_queries_count = 0; $mysql_queries_time = array();

// Fetch the Site Settings from the database and store them in the $settings variable // Pimped: optimised
$settings = array();
$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS, false);
if(!dbrows($result)) { die($die1."Settings could not been loaded</strong><br />".mysql_errno()." : ".mysql_error()."</div>"); }
while ($data = dbarray($result)) {
	$settings[$data['settings_name']] = $data['settings_value'];
}

// Language Switcher // Pimped
$language_files = makefilelist(BASEDIR."locale/", ".|..", true, "folders");
$language_allowed = explode(",", $settings['locale_content']);
if(isset($_COOKIE['user_language']) && $_COOKIE['user_language'] != '' && preg_match("/^[0-9a-zA-Z_]+$/", $_COOKIE['user_language']) && in_array($_COOKIE['user_language'], $language_files) && $settings['locale_multi'] == 1 && in_array($_COOKIE['user_language'], $language_allowed)) {
	$settings['locale'] = $_COOKIE['user_language'];
	$cookie_user_language = $_COOKIE['user_language'];
} else {
	$cookie_user_language = false;
}

// Sanitise $_SERVER globals
$_SERVER['PHP_SELF'] = cleanurl($_SERVER['PHP_SELF']);
$_SERVER['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ? cleanurl($_SERVER['QUERY_STRING']) : "";
$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? cleanurl($_SERVER['REQUEST_URI']) : "";
$PHP_SELF = cleanurl($_SERVER['PHP_SELF']);
// Common definitions
define("IN_FUSION", true);
define("PIMPED_FUSION", true);
define("FUSION_REQUEST", isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "" ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
define("FUSION_QUERY", isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
define("HTTP_REFERER", isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '');
define("REDIRECT_TO", ((HTTP_REFERER == '' || (strpos(HTTP_REFERER,"setuser.php")!==false) || (strpos(HTTP_REFERER,"/administration/")!==false)) ? $settings['opening_page'] : HTTP_REFERER));
define("USER_IP", $_SERVER['REMOTE_ADDR']);
define("QUOTES_GPC", (ini_get('magic_quotes_gpc') ? true : false));
// Path definitions
define("ADMIN", BASEDIR."administration/");
define("DOWNLOADS", BASEDIR."downloads/");
define("IMAGES", BASEDIR."images/");
define("IMAGES_AVA", IMAGES."avatars/");
define("IMAGES_A", IMAGES."articles/");
define("IMAGES_N", IMAGES."news/");
define("IMAGES_N_T", IMAGES."news/thumbs/");
define("IMAGES_NC", IMAGES."news_cats/");
define("IMAGES_F", IMAGES."forum/");
define("IMAGES_FC", IMAGES."forum_cats/");
define("IMAGES_FLAGS", IMAGES."user_flags/");
define("IMAGES_ICONS", IMAGES."icons/");
define("RANKS", IMAGES."ranks/");
define("INCLUDES", BASEDIR."includes/");
define("INCLUDES_JS", INCLUDES."jscripts/");
define("INCLUDES_RATING", INCLUDES."ratings/");
define("LOCALE", BASEDIR."locale/");
define("LOCALESET", $settings['locale']."/");
define("FORUM", BASEDIR."forum/");
define("FORUM_INC", FORUM."includes/");
define("FORUM_ATT", FORUM."attachments/");
define("INFUSIONS", BASEDIR."infusions/");
define("PHOTOS", IMAGES."photoalbum/");
define("THEMES", BASEDIR."themes/");
// Settings
define("IF_MULTI_LANGUAGE", ($settings['locale_multi'] == "1" ? true : false));
define("IF_MULTI_LANGUAGE_FORUM", ($settings['locale_multi_forum'] == "1" ? true : false));
define("LANGUAGE", $settings['locale']);
if(!file_exists(BASEDIR.".htaccess")) {
	define("MOD_REWRITE_ABLE", false);
} elseif(function_exists("apache_get_modules")) {
	define("MOD_REWRITE_ABLE", in_array('mod_rewrite', @apache_get_modules()) ? true : false);
} else {
	define("MOD_REWRITE_ABLE", true);
}
define("URL_REWRITE", (MOD_REWRITE_ABLE && $settings['seo_url_rewrite'] == "1" ? true : false));

// Redirects to the index if the URL is invalid (eg. file.php/folder/)
if ($_SERVER['SCRIPT_NAME'] != $_SERVER['PHP_SELF']) { redirect($settings['siteurl']); }

// Predefine mysql_cache variables
$smiley_cache = ''; $bbcode_cache = ''; $groups_cache = ''; $forum_rank_cache = ''; $forum_group_rank_cache = ''; $forum_mod_rank_cache = ''; $navigation_cache = false; // Pimped

// PiF Global Settings
$pif_global = array();
$pif_global['visible_members'] = array(0, 3, 7); // Visible in Memberlist, profile_link(), pif_cache("total_reg_users")
$pif_global['visible_members_admin'] = array(0, 3, 7);
$pif_global['visible_members_pro'] = array(0, 1, 3, 7); // Allowed to show Profile
$pif_global['visible_members_admin_pro'] = array(0, 1, 2, 3, 4, 5, 6, 7);
$pif_global['can_recieve_pm'] = array(0, 3);

// PiF Global Cache
$pif_cache = array();

// Tooltip Script included?
$_TOOLTIP_ = false;

function add_tooltip() {
global $_TOOLTIP_;
	if($_TOOLTIP_ == false) {
		add_to_head("<script type='text/javascript' src='".INCLUDES_JS."tooltip.js'></script>\n");
		$_TOOLTIP_ = true;
	}
}

// Required Includes
if (!require_once INCLUDES."user_levels_include.php") die($die1."user_levels_include.php not found!".$die2);
if (!require_once INCLUDES."encrypt_password_include.php") die($die1."encrypt_password_include.php not found!".$die2);
if (!require_once INCLUDES."functions_include.php") die($die1."functions_include.php not found!".$die2);
if (!require_once INCLUDES."pif_seo_titles.php") die($die1."pif_seo_titles.php not found!".$die2);
if (!require_once INCLUDES."pif_seo_functions.php") die($die1."pif_seo_functions.php not found!".$die2);
if (!require_once INCLUDES."core_include.php") die($die1."core_include.php not found!".$die2);

// Initialise the $locale array
$locale = array();

// Load the Global language file
include LOCALE.LOCALESET."global.php";

// Check if users full or partial ip is blacklisted
$sub_ip1 = substr(USER_IP, 0, strlen(USER_IP) - strlen(strrchr(USER_IP, ".")));
$sub_ip2 = substr($sub_ip1, 0, strlen($sub_ip1) - strlen(strrchr($sub_ip1, ".")));

if (dbcount("(blacklist_id)", DB_BLACKLIST, "blacklist_ip="._db(USER_IP)." OR blacklist_ip="._db($sub_ip1)." OR blacklist_ip="._db($sub_ip2))) {
	redirect("http://www.google.com/"); die();
}

// Check that site or user theme exists
function theme_exists($theme) {
	if (!file_exists(THEMES) || !is_dir(THEMES)) {
		return false;	
	} elseif (file_exists(THEMES.$theme."/theme.php") && file_exists(THEMES.$theme."/styles.css")) {
		define("THEME", THEMES.$theme."/");
		return true;
	} else {
		$dh = opendir(THEMES);
		while (false !== ($entry = readdir($dh))) {
			if ($entry != "." && $entry != ".." && is_dir(THEMES.$entry)) {
				if (file_exists(THEMES.$entry."/theme.php") && file_exists(THEMES.$entry."/styles.css")) {
					define("THEME", THEMES.$entry."/");
					return true;
					exit;
				}
			}
		}
		closedir($dh);
		if (!defined("THEME")) {
			return false;
		}
	}
}

// Call the required login method
if ($settings['login_method'] == "cookies" || (isset($MYSQL_DUMPER) && $MYSQL_DUMPER == true)) {
	if (!require_once INCLUDES."cookie_include.php") die($die1."cookie_include.php not found!".$die2);
} elseif ($settings['login_method'] == "sessions") {
	if (!require_once INCLUDES."session_include.php") die($die1."<strong>session_include.php not found!".$die2);
}

if(!$cookie_user_language && $settings['locale_multi'] == 1 && isset($userdata['user_language']) && $userdata['user_language'] != "") {
	setcookie("user_language", $userdata['user_language'], time() + 3600*24*30, "/", "", "0");
}

// Path definition II
define("TEMPLATES", THEMES."templates/");

// Redirect browser using header or script function
function redirect($location, $script = false) {
	if (!$script) {
		header("Location: ".str_replace("&amp;", "&", $location));
		exit;
	} else {
		echo "<script type='text/javascript'>document.location.href='".str_replace("&amp;", "&", $location)."'</script>\n";
		exit;
	}
}

// Clean URL Function, prevents entities in server globals
function cleanurl($url) {
	$bad_entities = array("&", "\"", "'", '\"', "\'", "<", ">", "(", ")", "*");
	$safe_entities = array("&amp;", "", "", "", "", "", "", "", "", "");
	$url = str_replace($bad_entities, $safe_entities, $url);
	return $url;
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (!is_array($text)) {
		if (QUOTES_GPC) $text = stripslashes($text);
		$search = array("&", "\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
		$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
		$text = str_replace($search, $replace, $text);
	} else {
		while (list($key, $value) = each($text)) {
			$text[$key] = stripinput($value);			
		}
	}
	return $text;
}

// Secure db-query
function _db($string) {
	if(is_numeric($string)) {
		return $string;
	} else{
		return "'".mysql_real_escape_string($string)."'";
	}
}

// Secure the $_GET var
function secure_get($check_url) {
	$check_url = trim($check_url);
	$check_url = str_replace("<","&#60;", $check_url);
	$check_url = str_replace(">","&#62;", $check_url);
	$check_url = str_replace("#","&#35;", $check_url);
	$check_url = str_replace("(","&#40;", $check_url);
	$check_url = str_replace(")","&#41;", $check_url);
	return $check_url;
}
 
// Prevent any possible XSS attacks via $_GET.
function strip_get($check_url) {
	$return = false;
	if (is_array($check_url)) {
		foreach ($check_url as $value) {
			$return = strip_get($value);
			if ($return == true) { return true; }	
		}
	} else {
		$check_url = str_replace("\"", "", $check_url);
		$check_url = str_replace("\'", "", $check_url);
		if ((preg_match("/<[^>]*script*\"?[^>]*>/i", $check_url)) || (preg_match("/<[^>]*object*\"?[^>]*>/i", $check_url)) ||
			(preg_match("/<[^>]*iframe*\"?[^>]*>/i", $check_url)) || (preg_match("/<[^>]*applet*\"?[^>]*>/i", $check_url)) ||
			(preg_match("/<[^>]*meta*\"?[^>]*>/i", $check_url)) || (preg_match("/<[^>]*style*\"?[^>]*>/i", $check_url)) ||
			(preg_match("/<[^>]*form*\"?[^>]*>/i", $check_url)) || (preg_match("/\([^>]*\"?[^)]*\)/i", $check_url))) {
			$return = true;	
		}
	}
	return $return;
}

// Strip Slash Function, only stripslashes if magic_quotes_gpc is on
function stripslash($text) {
	if (QUOTES_GPC) { $text = stripslashes($text); }
	return $text;
}

// Add Slash Function, add correct number of slashes depending on quotes_gpc
function addslash($text) {
	if (!QUOTES_GPC) {
		$text = addslashes(addslashes($text));
	} else {
		$text = addslashes($text);
	}
	return $text;
}

// htmlentities is too agressive so we use this function
function phpentities($text) {
	$search = array("&", "\"", "'", "\\", "<", ">");
	$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&lt;", "&gt;");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Trim a line of text to a preferred length
function trimlink($text, $length) {
	$dec = array("&", "\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&amp;", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$text = str_replace($enc, $dec, $text);
	if (strlen($text) > $length) $text = substr($text, 0, ($length-3))."...";
	$text = str_replace($dec, $enc, $text);
	return $text;
}

// Validate numeric input
function isnum($value) {
	if (!is_array($value)) {
		return (preg_match("/^[0-9]+$/", $value));
	} else {
		return false;
	}
}

// Validate numeric input, may also be negative // created for Pimped-Fusion
function isnum_neg($value) {
	if(is_numeric($value) && !is_array($value)) {
		return true;
	} else {
		return false;
	}
}

// Custom preg-match function
function preg_check($expression, $value) {
	if (!is_array($value)) {
		return preg_match($expression, $value);
	} else {
		return false;
	}
}

// Create a list of files or folders and store them in an array
// You may filter out extensions by adding them to $extfilter as:
// $ext_filter = "gif|jpg"
function makefilelist($folder, $filter, $sort = true, $type = "files", $ext_filter = "") {
	$res = array();
	$filter = explode("|", $filter);
	if ($type == "files" && !empty($ext_filter)) {
		$ext_filter = explode("|", strtolower($ext_filter));
	}
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == "files" && !in_array($file, $filter)) {
			if (!empty($ext_filter)) {
				if (!in_array(substr(strtolower(stristr($file, '.')), +1), $ext_filter) && !is_dir($folder.$file)) { $res[] = $file; }
			} else {
				if (!is_dir($folder.$file)) { $res[] = $file; }
			}
		} elseif ($type == "folders" && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) { $res[] = $file; }
		}
	}
	closedir($temp);
	if ($sort) { sort($res); }
	return $res;
}

// Format spaces and tabs in code bb tags
function formatcode($text) {
	$text = str_replace("  ", "&nbsp; ", $text);
	$text = str_replace("  ", " &nbsp;", $text);
	$text = str_replace("\t", "&nbsp; &nbsp;", $text);
	$text = preg_replace("/^ {1}/m", "&nbsp;", $text);
	return $text;
}

// This function sanitises news & article submissions
function descript($text, $striptags = true) {
	// Convert problematic ascii characters to their true values
	$search = array("40","41","58","65","66","67","68","69","70",
		"71","72","73","74","75","76","77","78","79","80","81",
		"82","83","84","85","86","87","88","89","90","97","98",
		"99","100","101","102","103","104","105","106","107",
		"108","109","110","111","112","113","114","115","116",
		"117","118","119","120","121","122"
		);
	$replace = array("(",")",":","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z"
		);
	$entities = count($search);
	for ($i=0; $i < $entities; $i++) {
		$text = preg_replace("#(&\#)(0*".$search[$i]."+);*#si", $replace[$i], $text);
	}
	$text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
	$text = preg_replace('#(<[^>]+[/\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onfocus|onload|xmlns)[^>]*>#iU', ">", $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
	$text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
	if ($striptags) {
		do {
			$thistext = $text;
			$text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
		} while ($thistext != $text);
	}
	return $text;
}

// Scan image files for malicious code
function verify_image($file) {
	$image_safe = true;
	$imginfo = getimagesize($file);
	if ($imginfo === false) {
		return false;
	} else {
		$txt = file_get_contents($file);
		if ($txt === false) {
			return false;
		} else {
			if (preg_match('#&(quot|lt|gt|nbsp|<?php);#i', $txt)) { $image_safe = false; }
			elseif (preg_match("#&\#x([0-9a-f]+);#i", $txt)) { $image_safe = false; }
			elseif (preg_match('#&\#([0-9]+);#i', $txt)) { $image_safe = false; }
			elseif (preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt)) { $image_safe = false; }
			elseif (preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt)) { $image_safe = false; }
			elseif (preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt)) { $image_safe = false; }
			elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt)) { $image_safe = false; }
			elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt)) { $image_safe = false; }
			elseif (preg_match("#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt)) { $image_safe = false; }
		}
	}
	return $image_safe;
}

// Display the user's level
function getuserlevel($userlevel) {
	global $locale;
	if ($userlevel == nMEMBER) { return $locale['user1']; 
	} elseif ($userlevel == nMODERATOR) { return $locale['userf1'];
	} elseif ($userlevel == nADMIN) { return $locale['user2'];
	} elseif ($userlevel == nSUPERADMIN) { return $locale['user3']; }
}

// Check if Administrator/Member of Admin Group has correct rights assigned
function checkrights($right) {
	if (iSUPERADMIN || 
	(iMODERATOR && in_array($right, explode(".", iUSER_RIGHTS))) || 
	(iADMIN && in_array($right, explode(".", iUSER_RIGHTS))) ||
	(iGROUP_ADMIN && in_array($right, explode(".", iGROUP_RIGHTS))) ) {
		return true;
	} else {
		return false;
	}
}

// Check accesses of a user
function checkgroup($groups) {
	if (iSUPERADMIN) {
		if($groups == nONLYGUEST) {
		return false;
		} else { 
		return true; 
		}
	} elseif (iADMIN) {
		if($groups == nGUEST || $groups == nMEMBER || $groups == nMODERATOR || $groups == nADMIN) {
			return true;
		} else {
			$users_groups = iUSER_GROUPS. "." .nGUEST. "." .nMEMBER. "." .nMODERATOR. "." .nADMIN;
		}
	} elseif (iMODERATOR) {
		if($groups == nGUEST || $groups == nMEMBER || $groups == nMODERATOR) {
			return true;
		} else {
			$users_groups = iUSER_GROUPS. "." .nGUEST. "." .nMEMBER. "." .nMODERATOR;
		}
	} elseif (iMEMBER) {
		if($groups == nGUEST || $groups == nMEMBER) {
			return true;
		} else {
			$users_groups = iUSER_GROUPS. "." .nGUEST. "." .nMEMBER;
		}
	} elseif($groups == nGUEST || $groups == nONLYGUEST) {
		return true;
	} else {
		#return false;
		$users_groups = nGUEST;
	}
	$retrieve_groups = explode('.', $groups);
	foreach ($retrieve_groups AS $retrieved) {
		if (in_array($retrieved, explode('.', $users_groups))) {
		return true;
		}
	}
return false;
}

// Cache groups mysql // Pimped: optimised
function cache_groups() {
	global $groups_cache;
	$result = dbquery("SELECT group_id, group_name, group_rights, group_description FROM ".DB_USER_GROUPS." ORDER BY group_id ASC");
	if (dbrows($result)) {
		$groups_cache = array();
		while ($data = dbarray($result)) {
			$groups_cache[] = $data;
		}
	} else {
		$groups_cache = array();
	}
}

// Compile access levels & user group array // Pimped for "guests only"
function getusergroups($guests = 1, $members = 1,  $moderators = 1, $admins = 1, $superadmins = 1, $only_guests = 0, $show_groups = 1, $array = '') {
	global $locale, $groups_cache; // todo: change where we used this modified function.
	$groups_array = array();
	if($guests) array_push($groups_array, array(nGUEST, $locale['user0']));
	if($members) array_push($groups_array, array(nMEMBER, $locale['user1']));
	if($moderators) array_push($groups_array, array(nMODERATOR, $locale['userf1']));
	if($admins) array_push($groups_array, array(nADMIN, $locale['user2']));
	if($superadmins) array_push($groups_array, array(nSUPERADMIN, $locale['user3']));
	if($only_guests) array_push($groups_array, array(nONLYGUEST, $locale['user4']));
	
	if(is_array($array)) array_push($groups_array, $array);

	if ($show_groups AND !$groups_cache) { cache_groups(); }
	if ($show_groups AND is_array($groups_cache) && count($groups_cache)) {
		foreach ($groups_cache as $group) {
			array_push($groups_array, array($group['group_id'], $group['group_name']));
		}
	}
	return $groups_array;
}

// Get the name of the access level or user group // Pimped for "guests only"
function getgroupname($group_id, $return_desc = false) {
	global $locale, $groups_cache;
	if ($group_id == nGUEST) { return $locale['user0'];
	} elseif ($group_id == nMEMBER) { return $locale['user1']; exit;
	} elseif ($group_id == nMODERATOR) { return $locale['userf1']; exit;
	} elseif ($group_id == nADMIN) { return $locale['user2']; exit;
	} elseif ($group_id == nSUPERADMIN) { return $locale['user3']; exit;
	} elseif ($group_id == nONLYGUEST) { return $locale['user4']; exit;
	} else {
		if (!$groups_cache) { cache_groups(); }
		if (is_array($groups_cache) && count($groups_cache)) {
			foreach ($groups_cache as $group) {
				if ($group_id == $group['group_id']) { return ($return_desc ? ($group['group_description'] ? $group['group_description'] : '-') : $group['group_name']); exit; }
			}
		}
	}
	return "N/A";
}
#$group_access_debug = '';
// Getting the access levels used when asking the database for data; Pimped
function groupaccess($field, $data = false, $debug = '') { #global $group_access_debug;
if (iSUPERADMIN) { return "$field != '".nONLYGUEST."'"; }
	if ($data === false) {
		if (iGUEST) { return "($field = '".nGUEST."' OR $field = '".nONLYGUEST."')";
		} elseif (iADMIN) { $res = "($field='".nGUEST."' OR $field='".nMEMBER."' OR $field='".nMODERATOR."' OR $field='".nADMIN."'";
		} elseif (iMODERATOR) { $res = "($field='".nGUEST."' OR $field='".nMEMBER."' OR $field='".nMODERATOR."'";
		} elseif (iMEMBER) { $res = "($field='".nGUEST."' OR $field='".nMEMBER."'";
		}
		if (iUSER_GROUPS != "" && !iSUPERADMIN) { $res .= " OR $field='".str_replace(".", "' OR $field='", 	iUSER_GROUPS)."'"; }
		$res .= ")"; #$group_access_debug .= "<br>y.".$debug.".y".$res."<br>";
		return $res;
	} else {
		$res = "";
		$retrieval = explode('|', $data);		#echo "[<pre>".var_dump($retrieval)."</pre>]";
		foreach ($retrieval as $retrievals) { #echo "[<pre>".var_dump($retrievals)."</pre>]";
			if (iADMIN) {
				$user_groups = iUSER_GROUPS. "." .nGUEST. "." .nMEMBER. "." .nMODERATOR. "." .nADMIN;
				$groups = explode('.', $user_groups);
				foreach ($groups as $group_access) {
					if (in_array($group_access, explode('.', $retrievals))) {
						if ($res) $res .= " OR ";
						if (!$res) $res .= "(";
						$res .= $field."='".$retrievals."'";
					} 
				}
			}
			elseif (iMODERATOR) {
				$user_groups = iUSER_GROUPS. "." .nGUEST. "." .nMEMBER. "." .nMODERATOR;
				$groups = explode('.', $user_groups);
				foreach ($groups as $group_access) {
					if (in_array($group_access, explode('.', $retrievals))) {
						if ($res) $res .= " OR ";
						if (!$res) $res .= "(";
						$res .= $field."='".$retrievals."'";
					}
				}
			}
			elseif (iMEMBER) {						#echo $retrievals;
				$user_groups = iUSER_GROUPS. "." .nGUEST. "." .nMEMBER;
				$groups = explode('.', $user_groups);
				foreach ($groups as $group_access) {
					if (in_array($group_access, explode('.', $retrievals))) {
						if ($res) $res .= " OR ";
						if (!$res) $res .= "(";
						$res .= $field."='".$retrievals."'";
					}
				}
			}
			else {
				$group_access = nGUEST;
				if (in_array($group_access, explode('.', $retrievals))) {
					if ($res) $res .= " OR ";
					if (!$res) $res .= "(";
					$res .= $field."='".$retrievals."'";
				} 
			}

		}
		if ($res != "") $res .= ")";
		if (!$res) $res = $field."='".nGUEST."'"; #$group_access_debug .= "<br>x.".$debug.".x".$res."<br>";
		return $res;
	}
}

// User profile link
function profile_link($user_id, $user_name, $user_status, $class = 'profile-link', $title = '', $style = '', $content = '') { // Pimped function
	global $locale, $settings, $pif_global;
	if($user_id == 0 || $user_id == "0") return "System";

	$title = ($title ? " title='".$title."'" : '');
	$class = ($class ? " class='".$class."'" : '');
	$style = ($style ? " style='".$style."'" : '');
	$content = ($content == '' ? $user_name : $content);
	
	if ((in_array($user_status, $pif_global['visible_members']) || checkrights("M")) && (iMEMBER || $settings['hide_userprofiles'] == "0")) { // User-Status 0,3,5
		$link = "<a href='".BASEDIR.
		make_url("profile.php?lookup=".$user_id, SEO_PROFILE_A.SEO_PROFILE_B1.$user_id.SEO_PROFILE_B2, $user_name, SEO_PROFILE_C).
		"'".$class.$title.$style.">".$content."</a>";
	} elseif ($user_status == "5" || $user_status == "6") {
		$link = $locale['useranonym'];
	} else {
		$link = $content;
	}
	return $link;
}

// Group profile link
function group_link($group_id, $group_name, $class = "profile-link", $title = '') { // Pimped: new function
	global $settings;

	$title = ($title ? " title='".$title."'" : '');
	$class = ($class ? " class='".$class."'" : "");
	
	if (iMEMBER || $settings['hide_groupprofiles'] == "0") {
		$link = "<a href='".BASEDIR.
		make_url("profile.php?group_id=".$group_id, SEO_GROUP_A.SEO_GROUP_B1.$group_id.SEO_GROUP_B2, $group_name, SEO_GROUP_C).
		"'".$class.$title.">".$group_name."</a>";
	} else {
		$link = $group_name;
	}
	return $link;
}

// Level of Guest and Member
define("iGUEST", $userdata['user_level'] == nGUEST ? 1 : 0);
define("iMEMBER", $userdata['user_level'] >= nMEMBER ? 1 : 0);
// User Level
define("iUSER", $userdata['user_level']);
// Admin Rights
define("iUSER_RIGHTS", $userdata['user_rights']);
// User Group definitions
define("iUSER_GROUPS", substr($userdata['user_groups'], 1));
// Admin Group
$userdata['user_group_rights'] = "";
if(iMEMBER && iUSER_GROUPS != "") {
	if(!$groups_cache) { cache_groups(); }
	if (is_array($groups_cache) && count($groups_cache)) {
		$user_group = explode(".", iUSER_GROUPS);
		if (is_array($user_group) && count($user_group)) {
			foreach ($groups_cache as $group) {
				if (in_array($group['group_id'], $user_group) && $group['group_rights'] != "") {
					$add = explode(".", $group['group_rights']);
					foreach($add as $adds) {
						if(!in_array($adds, explode(".", $userdata['user_group_rights']))) {
							if (!defined("iGROUP_ADMIN")) define("iGROUP_ADMIN", true);
							$userdata['user_group_rights'] = $userdata['user_group_rights'].($userdata['user_group_rights'] == "" ? "" : ".").$adds;
						}
					}
				}
			}
		}
	} unset($user_group, $add, $adds);
}
if (!defined("iGROUP_ADMIN")) define("iGROUP_ADMIN", false);
// Admin Group Rights
define("iGROUP_RIGHTS", $userdata['user_group_rights']);
// Moderator or Admin Level
define("iMODERATOR", (
($userdata['user_level'] == nMODERATOR) || ($userdata['user_level'] == nSUPERADMIN) || 
($userdata['user_level'] == nADMIN && in_array("FMD", explode(".", iUSER_RIGHTS))) || (iGROUP_ADMIN == true && in_array("FMD", explode(".", iGROUP_RIGHTS))) ) ? 1 : 0); // Pimped
define("iADMIN", ($userdata['user_level'] >= nADMIN || iGROUP_ADMIN == true) ? 1 : 0);
define("iSUPERADMIN", $userdata['user_level'] == nSUPERADMIN ? 1 : 0);
// Administration Access check
if (iMODERATOR || iADMIN) {
	define("iAUTH", substr(md5(DB_PREFIX.substr($userdata['user_password'], 16, 32).USER_IP.$userdata['user_name']), 5, 20));
	$aidlink = "?aid=".iAUTH;
}

// Update Online Users & Visitor Counter
$count = dbcount("(online_user)",DB_ONLINE,"online_user=".($userdata['user_level'] != 0 ? "'".$userdata['user_id']."'" : "'0' AND online_ip='".USER_IP."'"));
if ($count > 0) {
	$result = unbdbquery("UPDATE ".DB_ONLINE." SET online_lastactive='".time()."'
	WHERE online_user=".($userdata['user_level'] != 0 ? "'".$userdata['user_id']."'" : "'0' AND online_ip='".USER_IP."'")."");
} else {
	$result = unbdbquery("INSERT INTO ".DB_ONLINE." (online_user, online_ip, online_lastactive) VALUES
	('".($userdata['user_level'] != 0 ? $userdata['user_id'] : "0")."', '".USER_IP."', '".time()."')");
	if (!isset($_COOKIE[COOKIE_PREFIX.'visited'])) {
		$result = unbdbquery("UPDATE ".DB_SETTINGS." SET settings_value=settings_value+1 WHERE settings_name='counter'");
		setcookie(COOKIE_PREFIX."visited", "yes", time() + 31536000, "/", "", "0");
	}
}
$result = unbdbquery("DELETE FROM ".DB_ONLINE." WHERE online_lastactive<".(time()-120)."");

if (!require_once INCLUDES."system_images.php") die($die1."system_images.php not found!".$die2);
if (!require_once INCLUDES."pif_global_cache.php") die($die1."pif_global_cache.php not found!".$die2);
if (!require_once INCLUDES."pif_log_system.php") die($die1."pif_log_system.php not found!".$die2);

## Pimped functions =>

function navigation_cache() {
global $navigation_cache;

	$result = dbquery(
		"SELECT link_name, link_url, link_seo_url, link_window, link_position FROM ".DB_SITE_LINKS."
		WHERE ".groupaccess('link_visibility')." ".(!(bool)IF_MULTI_LANGUAGE ? '':" AND (link_language='all' OR link_language='".LANGUAGE."')")." 
		ORDER BY link_order ASC");
	if (dbrows($result)) {
		$navigation_cache = array();
		while ($data = dbarray($result)) {
			$navigation_cache[] = $data;
		}
	} else {
		$navigation_cache = '';
	}
}

// Create a selection list of possible languages for administration
function make_admin_language_opts($selected_language = "") {
global $settings;
$language_allowed = explode(",", $settings['locale_content']);
	$res = "<option value='all'>all</option>\n";
	for ($i = 0; $i < count($language_allowed); $i++) {
		$sel = ($selected_language == $language_allowed[$i] ? " selected='selected'" : "");
		$res .= "<option value='".$language_allowed[$i]."'$sel>".$language_allowed[$i]."</option>\n";
	}
return $res;
}

// fix for utf-8 in JavaScript
function escape_javascript($text) {
	$search = array("&Auml;", "&Ouml;", "&Uuml;", "&auml;", "&ouml;", "&uuml;", "&szlig");
	$replace = array("%C4", "%D6", "%DC", "%E4", "%F6", "%FC", "%DF");
return 'unescape(\''.str_replace($search, $replace, $text).'\')';	
}

// Round Number
function round_num($num){
	$rounded = number_format(round($num, 2));
	if($rounded == 0 && $num > 0){ $rounded = "&lt;1"; }
	return $rounded;
}

// A User List function
function user_list($guests=false, $members=false, $link_class=""){
	global $locale;
	$string = "";
	if($guests){
		if(is_array($guests))
			$guests = count($guests);
		if(!isnum($guests))
			$guests = 0;
		if($guests == 1) {
			$string .= $guests."  ".$locale['wih101'];
		} elseif($guests > 1) {
			$string .= $guests."  ".$locale['wih102'];
		}
	}
	if($members){
		if(is_array($members)){
			if(isset($members[0]['user_name']) && isset($members[0]['user_id'])){
				$new_members = array();
				foreach($members as $member) {
					$user_status = (isset($member['user_status']) && isnum($member['user_status'])) ? $member['user_status'] : '0';
					$new_members[] = profile_link($member['user_id'], $member['user_name'], $user_status, $link_class, $member['user_name'], (isset($member['user_level']) && $member['user_level'] > nMEMBER ? "font-weight: bold;" : "")); // Pimped: profile_link added
					}
				$members = $new_members;
			}
			if(!empty($string))
				$string .= ", ";
			$string .= implode(", ", $members);
		}
	}
	return $string;
}

?>