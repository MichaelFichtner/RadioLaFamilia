<?php

$MYSQL_DUMPER = true;

if (!require_once "../../maincore.php") die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><strong>maincore.php not found!</strong><br /></div>");

if (!defined("iAUTH") || !checkrights("DB") || !iSUPERADMIN) {
	die("No Access");
}


?>