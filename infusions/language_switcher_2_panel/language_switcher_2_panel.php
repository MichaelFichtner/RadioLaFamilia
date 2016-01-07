<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: language_switcher_2_panel.php
| Version: Pimped Fusion v0.08.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/

define("LW_SHOW_IMAGES", 1);

if (isset($_GET['b2wlang'])) {
	setcookie("user_language", $_GET['b2wlang'], time() + 3600*24*30, "/", "", "0");

	if(FUSION_QUERY != "") {
		$fquery = str_replace("&amp;b2wlang=".$_GET['b2wlang'], "", FUSION_QUERY);
		$fquery = str_replace("b2wlang=".$_GET['b2wlang'], "", FUSION_QUERY);
		if($fquery != '') $fquery = "?".$fquery;
	} else {
		$fquery = '';
	}

	redirect(FUSION_SELF.$fquery);
}

openside($locale['langswitch_100']);

if (LW_SHOW_IMAGES) {
echo "<div style='text-align:center;'>
<a href='".$_SERVER['PHP_SELF'].(FUSION_QUERY ? "?".FUSION_QUERY."&amp;b2wlang=German" : "?b2wlang=German")."'><img src='".INFUSIONS."language_switcher_2_panel/images/de.gif' alt='Deutsch' style='border:0;vertical-align:middle' /></a>&nbsp;&nbsp;&nbsp;
<a href='".$_SERVER['PHP_SELF'].(FUSION_QUERY ? "?".FUSION_QUERY."&amp;b2wlang=English" : "?b2wlang=English")."'><img src='".INFUSIONS."language_switcher_2_panel/images/en.gif' alt='English' style='border:0;vertical-align:middle' /></a>
</div>";
} else {
echo "<div style='text-align:center;'>
<a href='".$_SERVER['PHP_SELF'].(FUSION_QUERY ? "?".FUSION_QUERY."&amp;b2wlang=German" : "?b2wlang=German")."'>DE</a> 
<a href='".$_SERVER['PHP_SELF'].(FUSION_QUERY ? "?".FUSION_QUERY."&amp;b2wlang=English" : "?b2wlang=English")."'>EN</a>
</div>";
}
closeside();

?>