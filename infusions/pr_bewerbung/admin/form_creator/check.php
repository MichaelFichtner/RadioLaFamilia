<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin/form_creator/itext.php
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

require_once "../../../../maincore.php";

define("ADMIN_PANEL", true);

require_once INCLUDES."output_handling_include.php";
require_once INCLUDES."header_includes.php";
require_once THEME."theme.php";

function num_generate($length) {
$chars_for_pw  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$chars_for_pw .= "abcdefghijklmnopqrstuvwxyz";
$chars_for_pw .= "0123456789";
$char_control  = "";
        srand((double) microtime() * 1000000);
        for($i = 0;$i < 50;$i++) {
            $number = rand(2, strlen($chars_for_pw)-2);
            $char_control .= $chars_for_pw[$number];
        }
        $char_control = substr($char_control, 0, $length);
        return $char_control;
}
$num=num_generate(10);

if (!iSUPERADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { die("Access denied"); }

// CSS-Class from zWar by zezoar
?>
<style type="text/css">
<!--
.infopopup {background-color:#FFF4CC; position:absolute; display:none; padding:2px; padding-left:5px; padding-right:5px; border:1px solid #BBBBBB; font-family:Verdana, Arial, Times New Roman; width:300px; margin-top:10px; text-align:center; color:#000000;}
-->
</style>
<?php

echo "<form name='check' method='post' action='form_creator/saver.php".$aidlink."'>";

//echo "<font style='font-style:italic;color:red;'>Der Feldname taucht nur in der Datenbank auf, dieser wird automatisch vom Sytem erzeugt und kann von Ihnen nicht beeinflusst werden!</font><br /><br />";

echo "<table width='100%'>
	<tr>
		<td>Beschreibung: <img src='".INFUSIONS."pr_bewerbung/admin/images/qmark.png' onmouseover=\"return overlay(this, 'name_info', 'bottomleft');\" onmouseout=\"return overlayclose('name_info');\" style='vertical-align:middle;'/>
		<div id='name_info' class='infopopup'>Dies ist die elementare Angabe, sie beschreibt dem User was er mit diesem Feld best&auml;tigt.</div>
		</td>
		<td><input type='text' name='desc' size='24' class='textbox'></td>
	</tr>
	<tr>
		<td>Value: <i>(Leer lassen, wenn nicht ben&ouml;tigt)</i></td>
		<td><input type='text' name='value' size='24' class='textbox'></td>
	</tr>
	<tr>
		<td>Pflichtfeld?: </td>
		<td><select name='pflicht' class='textbox'>
			<option value='1'>Ja</option>
			<option value='0'>Nein</option>
		</td>
	</tr>
</table>";

echo "<center><input type='hidden' name='type' value='check'><input type='submit' value='Speichern' name='save_it' class='button'></center>";

echo "</form>";

?>