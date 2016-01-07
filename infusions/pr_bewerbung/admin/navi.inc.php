<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin/navi.inc.php.php
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

opentable("Navigation");

echo "<script type='text/javascript'>
<!--
var waitText = '<div align=\"center\" style=\"padding-top: 81px; padding-bottom: 81px; font-weight: bold;\">Loading <img src=\"".INFUSIONS."pr_bewerbung/admin/images/loading.gif\" width=\"16\" height=\"16\" alt=\"Loading\"></ div>';
//-->
</script>";

require_once INFUSIONS."pr_bewerbung/includes/functions.inc.php";

?>
     	<center>
	<input type="button" onclick="switch_site('<?php echo INFUSIONS."pr_bewerbung/admin/new.php".$aidlink ?>');" class="button" value="Neue Bewerbungen" />
	<input type="button" onclick="switch_site('<?php echo INFUSIONS."pr_bewerbung/admin/bearbeitung.php".$aidlink ?>');" class="button" value="In Bearbeitung" />
	<input type="button" onclick="switch_site('<?php echo INFUSIONS."pr_bewerbung/admin/eingestellt.php".$aidlink ?>');" class="button" value="Eingestellte" />
	<br /><br />
	<?php
		if (iSUPERADMIN){
	?>
			<input type="button" onclick="switch_site('<?php echo INFUSIONS."pr_bewerbung/admin/setup.php".$aidlink ?>');" class="button" value="Setup" />
			<input type="button" onclick="switch_site('<?php echo INFUSIONS."pr_bewerbung/admin/deleted.php".$aidlink ?>');" class="button" value="Gel&ouml;schte" />
	<?php
		}
	?>	
	</center>


<?php

closetable();

?>