<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
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

//Locale includieren
if (file_exists(INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php")) {
	include INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."pr_bewerbung/locale/German.php";
}

// Infusion general information
$inf_title = "Bewerbungsskript";
$inf_description = "Bewerbungsscript Infusion mit freiwählbaren Formularaufbau";
$inf_version = "2.00";
$inf_developer = "PrugnatoR";
$inf_email = "admin@prugnator.de";
$inf_weburl = "http://www.prugnator.de";

$inf_folder = "pr_bewerbung"; // The folder in which the infusion resides.

// Delete any items not required below.
$inf_newtable[1] = DB_PREFIX."bewerbung (
pr_id 		INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
pr_date 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_einst 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_by 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_bname	VARCHAR(50) NOT NULL DEFAULT '-',
pr_als 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_name 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_vname 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_gday 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_adress 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_plz 		CHAR(5) NOT NULL DEFAULT '-',
pr_ort 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_tel 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_email 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_icq 		VARCHAR(30) NOT NULL DEFAULT '-',
pr_msn 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_aim 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_skype 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_hp 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_job 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_sex 		VARCHAR(30) NOT NULL DEFAULT '-',
pr_erf 		CHAR(5) NOT NULL DEFAULT '-',
pr_erf_ref 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_connect 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_anz 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_style 	VARCHAR(100) NOT NULL DEFAULT '-',
pr_why 		VARCHAR(200) NOT NULL DEFAULT '-',
pr_stime 	VARCHAR(100) NOT NULL DEFAULT '-',
pr_soft 	VARCHAR(50) NOT NULL DEFAULT '-',
pr_ip 		VARCHAR(50) NOT NULL DEFAULT '-',
pr_comment 	VARCHAR(250) NOT NULL,
pr_status 	TINYINT(1) NOT NULL DEFAULT '1',
PRIMARY KEY (pr_id)
) TYPE=MyISAM;";

$inf_newtable[2] = DB_PREFIX."formulars (
pr_id 			INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
pr_activ		TINYINT(1) NOT NULL DEFAULT '1',
pr_pnactiv  		TINYINT(1) NOT NULL DEFAULT '0',
pr_pnto	  		INT(11) NOT NULL,
pr_forumtype		TINYINT(1) NOT NULL DEFAULT '1',
pr_forumid		INT(11) NOT NULL DEFAULT '1',
pr_formname		VARCHAR(100) NOT NULL,
pr_headtext		TEXT NOT NULL,
pr_access		VARCHAR(100) NOT NULL DEFAULT 'iGUEST',
PRIMARY KEY (pr_id)
) TYPE=MyISAM;";

$inf_newtable[3] = DB_PREFIX."form_fields (
pr_id 			INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
pr_name			VARCHAR(100) NOT NULL,
pr_desc			VARCHAR(100) NOT NULL,
pr_value		VARCHAR(100) NOT NULL,
pr_type			VARCHAR(100) NOT NULL,
pr_pflicht		TINYINT(1) NOT NULL DEFAULT '0',
pr_toform  		INT(11) NOT NULL DEFAULT '1',
pr_order	  	INT(11) NOT NULL,
pr_form			TEXT NOT NULL,
PRIMARY KEY (pr_id)
) TYPE=MyISAM;";

$inf_newtable[4] = DB_PREFIX."prb_config (
pr_version		VARCHAR(25) NOT NULL DEFAULT '2.0'
) TYPE=MyISAM;";

$inf_droptable[1] = DB_PREFIX."bewerbung";
$inf_droptable[2] = DB_PREFIX."formulars";
$inf_droptable[3] = DB_PREFIX."form_fields";
$inf_droptable[4] = DB_PREFIX."prb_config";

$inf_insertdbrow[1] = DB_PREFIX."formulars (pr_formname,pr_headtext) VALUES('".$locale['pr_b001']."', '".$locale['pr_b003']."')";
$inf_insertdbrow[2] = DB_PREFIX."prb_config (pr_version) VALUES('2.00')";

$inf_insertdbrow[3] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('bname', '".$locale['pr_b010']."', '1', '1', '<input type=\"text\" name=\"bname\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[4] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('als', '".$locale['pr_b011']."', 'Moderator ,Gast-Mod ,Techniker', '1', '1', '<select name=\"als\" class=\"textbox\">\n<option value=\"\">".$locale['pr_b041']."</option>\n<option>Moderator</option>\n<option>Gast-Mod</option>\n<option>Techniker</option>\n</select>', 'select')";
$inf_insertdbrow[5] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('name', '".$locale['pr_b012']."', '1', '1', '<input type=\"text\" name=\"name\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[6] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('vname', '".$locale['pr_b013']."', '1', '1', '<input type=\"text\" name=\"vname\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[7] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form) VALUES('gday', '".$locale['pr_b014']."', 'DD.MM.YYYY', '1', '1', '<input type=\"text\" value=\"DD.MM.YYYY\" name=\"gday\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[8] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('adress', '".$locale['pr_b015']."', '0', '1', '<input type=\"text\" name=\"adress\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[9] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('plz', '".$locale['pr_b016']."', '0', '1', '<input type=\"text\" name=\"plz\" size=\"5\" class=\"textbox\">')";
$inf_insertdbrow[10] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('ort', '".$locale['pr_b016a']." ', '0', '1', '<input type=\"text\" name=\"ort\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[11] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('tel', '".$locale['pr_b017']."', '0', '1', '<input type=\"text\" name=\"tel\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[12] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('email', '".$locale['pr_b018']."', '1', '1', '<input type=\"text\" name=\"email\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[13] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('icq', '".$locale['pr_b019']."', '0', '1', '<input type=\"text\" name=\"icq\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[14] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('msn', '".$locale['pr_b019a']."', '0', '1', '<input type=\"text\" name=\"msn\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[15] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('aim', '".$locale['pr_b019b']."', '0', '1', '<input type=\"text\" name=\"aim\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[16] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('skype', '".$locale['pr_b019c']."', '0', '1', '<input type=\"text\" name=\"skype\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[17] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('hp', '".$locale['pr_b020']."', '0', '1', '<input type=\"text\" name=\"hp\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[18] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('job', '".$locale['pr_b021']."', '".$locale['pr_b035']." ,".$locale['pr_b036']." ,".$locale['pr_b037']." ,".$locale['pr_b038']." ,".$locale['pr_b038a']."', '0', '1', '<select name=\"job\" class=\"textbox\">\n<option value=\"\">".$locale['pr_b041']."</option>\n<option>".$locale['pr_b035']."</option>\n<option>".$locale['pr_b036']."</option>\n<option>".$locale['pr_b037']."</option>\n<option>".$locale['pr_b038']."</option>\n<option>".$locale['pr_b038a']."</option>\n</select>', 'select')";
$inf_insertdbrow[19] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('sex', '".$locale['pr_b022']."', '".$locale['pr_b039']." ,".$locale['pr_b039a']." ', '0', '1', '<select name=\"sex\" class=\"textbox\">\n<option value=\"\">".$locale['pr_b041']."</option>\n<option>".$locale['pr_b039']."</option>\n<option>".$locale['pr_b039a']."</option>\n</select>', 'select')";
$inf_insertdbrow[20] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('erf', '".$locale['pr_b023']."', '".$locale['pr_b040']." ,".$locale['pr_b040a']." ', '1', '1', '<select name=\"erf\" class=\"textbox\">\n<option value=\"\">".$locale['pr_b041']."</option>\n<option>".$locale['pr_b040']."</option>\n<option>".$locale['pr_b040a']."</option>\n</select>', 'select')";
$inf_insertdbrow[21] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('erf_ref', '".$locale['pr_b023a']."', '0', '1', '<input type=\"text\" name=\"erf_ref\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[22] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form) VALUES('anz', '".$locale['pr_b024']."','".$locale['pr_b024a']."' , '1', '1', '<input type=\"text\" name=\"anz\" value=\"".$locale['pr_b024a']."\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[23] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('connect', '".$locale['pr_b025']."', '1', '1', '<input type=\"text\" name=\"connect\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[24] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('style', '".$locale['pr_b026']."', '0', '1', '<input type=\"text\" name=\"style\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[25] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('stime', '".$locale['pr_b029']."', '0', '1', '<input type=\"text\" name=\"stime\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[26] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form) VALUES('soft', '".$locale['pr_b030']."', '0', '1', '<input type=\"text\" name=\"soft\" size=\"24\" class=\"textbox\">')";
$inf_insertdbrow[27] = DB_PREFIX."form_fields (pr_name, pr_desc, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('why', '".$locale['pr_b027']."', '1', '1', '<textarea type=\"text\" name=\"why\" rows=\"5\" cols=\"54\" class=\"textbox\"></textarea>', 'text_field')";


$inf_adminpanel[1] = array(
	"title" => "pr_Bewerbung",
	"image" => "image.gif",
	"panel" => "admin/index.php",
	"rights" => "PRB"
);

$inf_sitelink[1] = array(
	"title" => "Bewerbung",
	"url" => "bewerbung.php",
	"visibility" => "0"
);
?>