<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (c) 2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: bewerbung.php
| pr_Bewerbungsscript v2.00
| Author: PrugnatoR
| URL: http://www.prugnator.de
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

// ACHTUNG!!! Diese Datei wird momentan nicht genutzt !!!
// ATTENTION!!! This files isn't used at the moment !!!


if (!defined("PR_DB_BEWERBUNG")) {
	define("PR_DB_BEWERBUNG", DB_PREFIX."bewerbung");
}
if (!defined("PR_DB_BCONFIG")) {
	define("PR_DB_BCONFIG", DB_PREFIX."prb_config");
}
if (!defined("PR_DB_FORMULARS")) {
	define("PR_DB_FORMULARS", DB_PREFIX."formulars");
}
if (!defined("PR_DB_FORM_FIELDS")) {
	define("PR_DB_FORM_FIELDS", DB_PREFIX."form_fields");
}
?>