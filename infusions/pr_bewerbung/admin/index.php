<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2009 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin/index.php
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
require_once "../../../maincore.php";
require_once THEMES."templates/admin_header.php";

if (!defined("iAUTH") || $_GET['aid'] != iAUTH || !iADMIN) redirect("../../index.php");

//Locale includieren
if (file_exists(INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php")) {
	include INFUSIONS."pr_bewerbung/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."pr_bewerbung/locale/German.php";
}

$pr_bew_admin = TRUE;

// Version der Infusion
require_once INFUSIONS."pr_bewerbung/includes/version.inc.php";

$status="";

// Check if old Version was installed
$optionab = dbquery("SELECT * FROM ".DB_PREFIX."prb_config");
$option = dbarray($optionab);
$rows = @mysql_num_rows($optionab);

if ($optionab && $option['pr_version'] != $version && $rows != "0"){
opentable("Update");
	echo "Es ist ein Update verf&uuml;gbar \n <br /><a href='".INFUSIONS."pr_bewerbung/update.php'> Zum Update </a>";
closetable();

	require_once THEMES."templates/footer.php";

die();
}elseif(file_exists(INFUSIONS."pr_bewerbung/update.php")){
	opentable("Warnung");
		echo "<font color='red'>update.php wurde gefunden. Bitte umgehen l&ouml;schen!</font><br />";
	closetable();
}
			
// Form Actions
	if(iSUPERADMIN){
		if(isset($_GET['form_del']) && isNum($_GET['form_del'])){
			$result0 = dbquery("SELECT pr_name FROM ".DB_PREFIX."form_fields WHERE pr_id='".$_GET['form_del']."'");
			$value0 = dbarray($result0);
			$value = $value0['pr_name'];
			$result1 = dbquery("ALTER TABLE `".DB_PREFIX."bewerbung` DROP `pr_".$value."` ");
			$result2 = dbquery("DELETE FROM ".DB_PREFIX."form_fields WHERE pr_id='".$_GET['form_del']."'");
			if($result1 && $result2){
				$status = "<center><font color='green'>Erfolgreich gel&ouml;scht!</font></center><br />";
			}else{
				$status = "<center><font color='red'>L&ouml;schen fehlgeschlagen!</font></center><br />";
			}
		}elseif(isset($_GET['form_up']) && isNum($_GET['form_up'])){
			// Not included yet
			$status = "ToDo";
		}elseif(isset($_GET['form_down']) && isNum($_GET['form_down'])){
			// Not included yet
			$status = "ToDo";
		}
	}	

// No script Meldung
echo "<noscript><center><b>Ihr Browser unterstützt kein Javascript oder Sie haben dieses abgeschaltet<br />
		Die volle Funktionalität dieses Adminbereichs ist aber nur mit Javascript möglich!</b></center></noscript>";

// Navigation includieran
require_once INFUSIONS."pr_bewerbung/admin/navi.inc.php";

// Makes Actions

// Save Setup 
if (isset($_POST['save_op']) && iSUPERADMIN){
		$result = dbquery("UPDATE ".DB_PREFIX."formulars SET pr_activ='".$_POST['aktiv']."', pr_pnactiv='".$_POST['pnactiv']."', pr_pnto='".$_POST['pnto']."', pr_forumtype='".$_POST['forumtype']."', pr_forumid='".$_POST['forumid']."', pr_formname='".$_POST['fname']."', pr_headtext='".pr_chars(addslashes($_POST['head']))."' WHERE pr_id='1'");
		if($result){
			$status = "<center><font color='green'>Setup wurde erfolgreich gespeichert!</font></center><br /> ";
		}else{
			$status = "<center><font color='red'>Setup konnte nicht gespeichert werden!</font></center><br /> ";
		}
}

	// BEW Options
	if(isset($_GET['board']) && isset($_GET['id']) && iADMIN){
		include_once INFUSIONS."pr_bewerbung/admin/forum_post.inc.php";
	}
	if (isset($_GET['del_ssa']) && iSUPERADMIN) {
		$result = dbquery("DELETE FROM ".PR_DB_BEWERBUNG." WHERE pr_id='".$_GET['del_ssa']."'");
		if ($result){
			$status = "<center><font color='green'>L&ouml;schen erfolgreich</font></center>";
		}else{
			$status = "<center><font color='red'>L&ouml;schen fehlgeschlagen</font></center>";
		}
	}
	if (isset($_GET['del']) && iADMIN) {
		$result = dbquery("UPDATE ".PR_DB_BEWERBUNG." SET pr_status='4', pr_by='".$userdata['user_name']."' WHERE pr_id='".$_GET['del']."'");
		if ($result){
			$status = "<center><font color='green'>L&ouml;schen erfolgreich</font></center>";
		}else{
			$status = "<center><font color='red'>L&ouml;schen fehlgeschlagen</font></center>";
		}
	}
	if (isset($_GET['bea']) && iADMIN) {
		$result = dbquery("UPDATE ".PR_DB_BEWERBUNG." SET pr_status='2' WHERE pr_id='".$_GET['bea']."'");
		if ($result){
			$status = "<center><font color='green'>Erfolgreich verschoben!</font></center>";
		}else{
			$status = "<center><font color='red'>Verschieben fehlgeschlagen!</font></center>";
		}
	}
	if (isset($_GET['ein']) && iADMIN) {
		$result = dbquery("UPDATE ".PR_DB_BEWERBUNG." SET pr_status='3' WHERE pr_id='".$_GET['ein']."'");
		if ($result){
			$status = "<center><font color='green'>Erfolgreich verschoben!</font></center>";
		}else{
			$status = "<center><font color='red'>Verschieben fehlgeschlagen!</font></center>";
		}
	}

//Beginn Content
echo "<div id='eins' style='width:100%; height:100%;'>";

opentable("Bewerbung v".$version." RCv6 &raquo; &Uuml;bersicht");

			echo $status."<br /><br />";
			
			count_bew();

// Do NOT remove! / Nicht enfernen!
// Verstöße werden strafrechtlich verfolgt!
render_copy(true);
closetable();

echo "</div>";


require_once THEMES."templates/footer.php";
?>