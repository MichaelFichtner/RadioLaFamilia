<?php 
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (c)2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin/form_creator/saver.php
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
require_once THEMES."templates/admin_header.php"; 

require_once INFUSIONS."pr_bewerbung/includes/form_creator.inc.php";

$num="new_".num_generate(10);

//Locale includieren
if (file_exists(INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php")) {
	include INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."pr_bewerbung/locale/German.php";
}

if (!iSUPERADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { die("Access denied"); } 


$result = dbquery("UPDATE ".DB_PREFIX."users SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'"); 

opentable("Status"); 

	if($_POST['type'] == "itext"){ 
		$name = "pr_".$num;
		$form = "<input type=\"text\" name=\"".$num."\" class=\"textbox\" value=\"".$_POST['value']."\" size=\"24\" />\n"; 
		$result1 = dbquery("INSERT INTO ".DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('".$num."', '".$_POST['desc']."','".$_POST['value']."' , '".$_POST['pflicht']."', '1', '".$form."', 'itext')");  
		$result2 = dbquery("ALTER TABLE `".DB_PREFIX."bewerbung` ADD `".$name."` VARCHAR( 200 ) NOT NULL"); 
	}elseif($_POST['type'] == "text_field"){ 
		$name = "pr_".$num;
		$form = "<textarea type=\"text\" name=\"".$num."\" rows=\"5\" cols=\"54\" class=\"textbox\">".$_POST['value']."</textarea>\n";
		$result1 = dbquery("INSERT INTO ".DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('".$num."', '".$_POST['desc']."','".$_POST['value']."' , '".$_POST['pflicht']."', '1', '".$form."', 'text_field')");  
		$result2 = dbquery("ALTER TABLE `".DB_PREFIX."bewerbung` ADD `".$name."` TEXT NOT NULL"); 
	}elseif($_POST['type'] == "check"){ 
		$name = "pr_".$num;
		$form = "<input type=\"checkbox\" id=\"".$num."\" name=\"".$num."\" value=\"1\" />\n";
		if($_POST['value'] != ""){
			$form .= "<label for=\"".$num."\">".$_POST['value']."</label>";
		}
		$result1 = dbquery("INSERT INTO ".DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('".$num."', '".$_POST['desc']."','".$_POST['value']."' , '".$_POST['pflicht']."', '1', '".$form."', 'check')");  
		$result2 = dbquery("ALTER TABLE `".DB_PREFIX."bewerbung` ADD `".$name."` VARCHAR( 200 ) NOT NULL"); 
	}elseif($_POST['type'] == "select"){ 
		$name = "pr_".$num;
		$form = "<select name=\"".$num."\" class=\"textbox\">\n<option value=\"\">".$locale['pr_b041']."</option>\n";
		$value = "";
		for($i=1; $i<=$_POST['values']; $i++){
			$form .= "<option value=\"".$_POST['value'.$i]."\">".$_POST['value'.$i]."</option>\n";
			if($i == 1){
				$value .= $_POST['value'.$i];
			}else{
				$value .= " ,".$_POST['value'.$i];
			}
		}
		$form .= "</select>\n";
		$result1 = dbquery("INSERT INTO ".DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('".$num."', '".$_POST['desc']."','".$value."' , '".$_POST['pflicht']."', '1', '".$form."', 'select')");  
		$result2 = dbquery("ALTER TABLE `".DB_PREFIX."bewerbung` ADD `".$name."` VARCHAR( 200 ) NOT NULL"); 
	}elseif($_POST['type'] == "radio"){ 
		$name = "pr_".$num;
		$form = "<input type=\"hidden\" name=\"".$num."\" value=\"\" />\n";
		$value = "";
		for($i=1; $i<=$_POST['values']; $i++){
			$form .= "<input type=\"radio\" name=\"".$num."\" value=\"".$_POST['value'.$i]."\" /> ".$_POST['value'.$i]." \n";
			if($i == 1){
				$value .= $_POST['value'.$i];
			}else{
				$value .= " ,".$_POST['value'.$i];
			}
		}
		$result1 = dbquery("INSERT INTO ".DB_PREFIX."form_fields (pr_name, pr_desc, pr_value, pr_pflicht, pr_toform, pr_form, pr_type) VALUES('".$num."', '".$_POST['desc']."','".$value."' , '".$_POST['pflicht']."', '1', '".$form."', 'radio')");  
		$result2 = dbquery("ALTER TABLE `".DB_PREFIX."bewerbung` ADD `".$name."` VARCHAR( 200 ) NOT NULL"); 
	}
	
	if($result1 && $result2){
		echo "Neues Formularfeld erstellt <br />Erstellt unter dem Namen: ".$num;
	}else{
		echo "Fehler beim Erstelllen des Formularfelds";
	}
	
// Do NOT remove! / Nicht enfernen!
// Verstöße werden strafrechtlich verfolgt! 
echo "<br /><div align='right'>Code &copy; by <a href='http://www.prugnator.de' target='_blank'>PrugnatoR</a></div>"; 
closetable(); 

echo "</body></html>"


?>