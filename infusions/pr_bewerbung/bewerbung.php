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
require_once "../../maincore.php";
require_once THEMES."templates/header.php";

require_once INFUSIONS."pr_bewerbung/includes/functions.inc.php";

// Datenbankabfrage
$result = dbquery("SELECT * FROM ".DB_PREFIX."formulars");
$option = dbarray($result);

/* ----------------------------- SETUP ------------------------------------- */

$disable_captcha = false; // Use this only if you get problems with the captcha; to disable captcha change to true

/* ----------------------------------------------------------------------- */

//Locale includieren
if (file_exists(INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php")) {
	include INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."pr_bewerbung/locale/German.php";
}

add_to_title(" - ".$option['pr_formname']);

$pn_activ = $option['pr_pnactiv'];
$pn_to = $option['pr_pnto']; // The Id of the User who would get the PM

// ------------ SEND POST ------------------- \\
if (isset($_POST['absenden'])) {

	include_once INCLUDES."securimage/securimage.php";
	$error = "";

	$result = dbquery("SELECT * FROM ".DB_PREFIX."form_fields WHERE pr_toform = '1' AND pr_pflicht='1'");

	while ($data = dbarray($result)){
		if($_POST[$data['pr_name']] == ""){
			$error .="<li>".$locale['pr_b080'].": ".$data['pr_desc']." </li>";
		}
	}
		if($disable_captcha != true){
			$securimage = new Securimage();
			if (!isset($_POST['captcha_code']) || $securimage->check($_POST['captcha_code']) == false) {
				$error .= "<li>".$locale['pr_b081']."</li>";
			}
		}

	if ($error != "") {
		opentable($locale['pr_e01']);
		echo "<center>".$locale['pr_en1']."</center><br /><br />$error";
		echo '<br /><center><input class="button" type="button" value="'.$locale['pr_b006'].'" onClick="history.back()"></center>';
	}else {

		$resultnames = "";
		$resultvalues = "";
		$ip = USER_IP;
		$time = time();
		
		$result2 = dbquery("SELECT * FROM ".DB_PREFIX."form_fields WHERE pr_toform = '1'");

		while ($data = dbarray($result2)){
			
			$save = pr_save($_POST[$data['pr_name']]);
			$resultnames .= ", pr_".$data['pr_name'];
			$resultvalues .= ", '".$save."'";

		}

			// Send PM
			if($pn_activ == "1"){
				if($pn_to != "-1" && $pn_to != "-2" && $pn_to > 0){
					pm_send($pn_to, $locale['pr_b085'], $locale['pr_b086'].pr_save($_POST['bname']), 0, $locale['pr_b086'].pr_save($_POST['bname']));
				}elseif($pn_to == "-1"){
					$pn_result = dbquery("SELECT user_id FROM ".DB_USERS." WHERE user_level='102' OR user_level='103'");
						while($data = dbarray($pn_result)){
							pm_send($data['user_id'], $locale['pr_b085'], $locale['pr_b086'].pr_save($_POST['bname']), 0, $locale['pr_b086'].pr_save($_POST['bname']));
						}
				}elseif($pn_to == "-2"){
					$pn_result = dbquery("SELECT user_id FROM ".DB_USERS." WHERE user_level='103'");
						while($data = dbarray($pn_result)){
							pm_send($data['user_id'], $locale['pr_b085'], $locale['pr_b086'].pr_save($_POST['bname']), 0, $locale['pr_b086'].pr_save($_POST['bname']));
						}
				}else{
					// falsche ID
				}
			}
			
			// Send eMail
				// Coming Soon
			

		// Save Inputs
		$result = dbquery("INSERT INTO ".DB_PREFIX."bewerbung (pr_date, pr_ip".$resultnames.") VALUES('".$time."', '".$ip."'".$resultvalues.")"); 
	
		opentable($locale['pr_b002']);
			if ($result){
				echo "<center>".$locale['pr_b002a']."<br>";
			}else{
				echo "<center>".$locale['pr_b002b']."<br>";
			}
				echo "</center>";
	}

}else {
// ------------ MAIN CONTENT ------------------- \\

include_once PR_BEWERBUNG."includes/values.inc.php";

opentable($option['pr_formname']);

if ($option['pr_activ'] == "1" ||  iADMIN){

	// ------------ HEAD ------------------- \\

	if ($option['pr_activ'] != "1" ){
		echo "<center><b><font color='red'>".$locale['pr_b004a']."</font></b></center><br /><br />\n";
	}

	if ($option['pr_headtext'] != ""){
	
		echo "<div class='quote'>";
		$head = eval("?>".stripslashes($option['pr_headtext'])."<?php ");
		echo $head;

		if (!iMEMBER){
			echo '<br /><br /><center>'.$locale['pr_b005'].' <a href="'.BASEDIR.'register.php">'.$locale['pr_b005a'].'</a></center><br />';
		}

		echo "</div><br />\n<br />\n";
	}

	// ------------ FORM ------------------- \\

	echo '<form name="bewerbung" method="post" onreset="return confirmReset(this)" action="'.FUSION_SELF.(FUSION_QUERY ? '?'.FUSION_QUERY : '').'">
	<table  border="0" width="100%" align="center">';
		
	$result = dbquery("SELECT * FROM ".DB_PREFIX."form_fields WHERE pr_toform='1'"); 

		while ($data = dbarray($result)){
			echo "<tr>
				<td>".$data['pr_desc'].":";
					if ($data['pr_pflicht'] == "1"){ echo "<span style='color:#ff0000'>*</span>";}
				echo "</td>
				<td>\n";
					$form = eval("?>".stripslashes($data['pr_form'])."<?php ");
					echo $form;
				echo "\n</td>
			</tr>\n";
		}
	if($disable_captcha != true){
	echo "<tr>\n";
	echo "<td width='100' class='tbl'>Captcha Code:</td>\n";
	echo "<td class='tbl'>";
	echo "<img id='captcha' src='".INCLUDES."securimage/securimage_show.php' alt='' align='left' />\n";
 	echo "<a href='".INCLUDES."securimage/securimage_play.php'><img src='".INCLUDES."securimage/images/audio_icon.gif' alt='' align='top' class='tbl-border' style='margin-bottom:1px' /></a><br />\n";
	echo "<a href='#' onclick=\"document.getElementById('captcha').src = '".INCLUDES."securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."securimage/images/refresh.gif' alt='' align='bottom' class='tbl-border' /></a>\n";
	echo "</td>\n</tr>\n<tr>";
	echo "<td class='tbl'>-> ".$locale['pr_b031']."</td>\n";
	echo "<td class='tbl'><input type='text' name='captcha_code' class='textbox' style='width:100px' /></td>\n";
	echo "</tr>\n";
	}
	echo "</table><br><center>".$locale['pr_b007']."</center><br>";
	echo '<p align="center"><input type="submit" name="absenden" value="'.$locale['pr_b028'].'" class="button"> <input type="reset" class="button" value="'.$locale['pr_b028a'].'" onclick="return confirm(\'Formular wirklich leeren?\');"><br></p>
	</form>';
	if (checkrights("PRB")){
		echo '<center><form name="admingo" method="post" action="'.INFUSIONS.'pr_bewerbung/admin/index.php'.$aidlink.'">
		<input type="submit" name="absenden" value="'.$locale['pr_b028b'].'" class="button">
		</form></center>';
	}

// ------------ INACTIV ------------------- \\
}else{

	echo $locale['pr_b004'];
	
}
			
}

/*------------------------------------------------------------------------------------------------+
| - Es ist NICHT erlaubt das Copyright zu entfernen
| - Jeder Verstoß wird zur Anzeige gebracht
| -------------------------------------------------------------------------------------------------
| - Es ist möglich jeden Regelverstoß zu finden!
| - Sollten Sie das Copyright entfernen wollen so schreiben sie mir eine Mail an admin@prugnator.de
+------------------------------------------------------------------------------------------------*/

echo "<div align='right'>Code &copy; by <a href='http://www.prugnator.de'>PrugnatoR</a></div>";

/*-----------------------------------------------------------------------------------------------*/

closetable();


require_once THEMES."templates/footer.php";
?>