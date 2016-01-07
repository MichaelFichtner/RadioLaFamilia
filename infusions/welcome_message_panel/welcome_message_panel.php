<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: welcome_message_panel.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

// $settings['welome_panel_dis']
// 0 = Show for all
// 1 = Show for Members
// 2 = Show for Guests

if(($settings['welome_panel_dis'] == "0") || ($settings['welome_panel_dis'] == "1" && iMEMBER) || ($settings['welome_panel_dis'] == "2" && !iMEMBER)) {
	if (!defined("PIF_THEME") || PIF_THEME == false) {
		opentable($locale['global_035']);
	} else {
		opentable($locale['global_035'], $settings['siteintro_collapse'], $settings['siteintro_collapse_state']);
	}
		ob_start();
		eval("?>".stripslashes($settings['siteintro'])."<?php ");
		$content = ob_get_contents();
		ob_end_clean();
		echo $content;
	closetable();
}

?>