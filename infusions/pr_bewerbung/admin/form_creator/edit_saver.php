<?php 
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright ɠ2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin/form_creator/edit_saver.php
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
require_once INFUSIONS."pr_bewerbung/includes/version.inc.php"; 

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
		$form = "<input type=\"text\" name=\"".$_POST['name']."\" class=\"textbox\" value=\"".$_POST['value']."\" size=\"24\" />\n"; 
		$result1 = dbquery("UPDATE ".DB_PREFIX."form_fields SET pr_desc='".$_POST['desc']."', pr_value='".$_POST['value']."', pr_pflicht='".$_POST['pflicht']."', pr_form='".$form."' WHERE pr_id='".$_POST['id']."'");  
	}elseif($_POST['type'] == "text_field"){ 
		$form = "<textarea type=\"text\" name=\"".$_POST['name']."\" rows=\"5\" cols=\"54\" class=\"textbox\">".$_POST['value']."</textarea>\n";
		$result1 = dbquery("UPDATE ".DB_PREFIX."form_fields SET pr_desc='".$_POST['desc']."', pr_value='".$_POST['value']."', pr_pflicht='".$_POST['pflicht']."', pr_form='".$form."' WHERE pr_id='".$_POST['id']."'");  
	}elseif($_POST['type'] == "check"){ 
		$form = "<input type=\"checkbox\" id=\"".$_POST['name']."\" name=\"".$_POST['name']."\" value=\"1\" />\n";
		if($_POST['value'] != ""){
			$form .= "<label for=\"".$_POST['name']."\">".$_POST['value']."</label>";
		}
		$result1 = dbquery("UPDATE ".DB_PREFIX."form_fields SET pr_desc='".$_POST['desc']."', pr_value='".$_POST['value']."', pr_pflicht='".$_POST['pflicht']."', pr_form='".$form."' WHERE pr_id='".$_POST['id']."'");  
	}elseif($_POST['type'] == "select"){ 
		$form = "<select name=\"".$_POST['name']."\" class=\"textbox\">\n<option value=\"\">".$locale['pr_b041']."</option>\n";
		$value = "";
		$values = explode(",", $_POST['value']);
		$n = count($values)-1;
		for($i=0; $i<=$n; $i++){
			$form .= "<option value=\"".$values[$i]."\">".$values[$i]."</option>\n";
			if($i == 1){
				$value .= $values[$i];
			}else{
				$value .= " ,".$values[$i];
			}
		}
		$form .= "</select>\n";
		$result1 = dbquery("UPDATE ".DB_PREFIX."form_fields SET pr_desc='".$_POST['desc']."', pr_value='".$_POST['value']."', pr_pflicht='".$_POST['pflicht']."', pr_form='".$form."' WHERE pr_id='".$_POST['id']."'");  

	}elseif($_POST['type'] == "radio"){ 
		$form = "<input type=\"hidden\" name=\"".$_POST['name']."\" value=\"\" />\n";
		$value = "";
		$values = explode(",", $_POST['value']);
		$n = count($values)-1;
		for($i=0; $i<=$n; $i++){
			$form .= "<input type=\"radio\" name=\"".$_POST['name']."\" value=\"".$values[$i]."\" /> ".$values[$i]." \n";
			if($i == 1){
				$value .= $values[$i];
			}else{
				$value .= " ,".$values[$i];
			}
		}
		$result1 = dbquery("UPDATE ".DB_PREFIX."form_fields SET pr_desc='".$_POST['desc']."', pr_value='".$_POST['value']."', pr_pflicht='".$_POST['pflicht']."', pr_form='".$form."' WHERE pr_id='".$_POST['id']."'");  
 
	}
	
	if($result1){
		echo "Formularfeld bearbeitet";
	}else{
		echo "Fehler beim Bearbeiten des Formularfelds";
	}
	
// Do NOT remove! / Nicht enfernen!
// Verstöße werden strafrechtlich verfolgt! 
render_copy(); 
closetable(); 

echo "</body></html>";


?>