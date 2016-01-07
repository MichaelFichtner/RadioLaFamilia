<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: includes/panel_func.inc.php
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

require_once INFUSIONS."pr_bewerbung/infusion_db.php";

// Counts Bewerbungen in DB

function count_bew($type = "all") {
//global $locale;
	if ($type == "all"){
		$newbew = dbcount("(pr_id)", PR_DB_BEWERBUNG, "pr_status = '1'");
		$beabew = dbcount("(pr_id)", PR_DB_BEWERBUNG, "pr_status = '2'");
		$einbew = dbcount("(pr_id)", PR_DB_BEWERBUNG, "pr_status = '3'");
		$delbew = dbcount("(pr_id)", PR_DB_BEWERBUNG, "pr_status = '4'");
		
		echo THEME_BULLET." ";
		if ($newbew == '0'){
				echo "Keine neuen Bewerbungen<br>";
			}elseif ($newbew == '1'){
				echo"<b><font color='red'>".$newbew." neue Bewerbung<br></font></b>";
			}else{
				echo"<b><font color='red'>".$newbew." neue Bewerbungen<br></font></b>";
			}
		echo "<br />".THEME_BULLET." ";
			
			if ($beabew == '1'){
				echo "".$beabew." Bewerbung ist in Bearbeitung<br>";
			}else{
				echo "".$beabew." Bewerbungen sind in Bearbeitung<br>";
			}
		echo "<br />";
			
			if (iSUPERADMIN){
			echo THEME_BULLET." ";
				if ($delbew == '1'){
					echo "".$delbew." Bewerbung wurde gel&ouml;scht";
				}else{
					echo "".$delbew." Bewerbungen wurden gel&ouml;scht";
				}
			echo "<br />";
			}
	}else{
		$count = dbcount("(pr_id)", PR_DB_BEWERBUNG, "pr_status = $type");
		return $count;
	}
}

?>