<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: language_switcher_panel.php
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

$url = FUSION_REQUEST;

$language_files = makefilelist(LOCALE, ".|..", true, "folders");

if (isset($_POST['switchuser_language']) && in_array($_POST['switchuser_language'], $language_files)) {
	setcookie("user_language", $_POST['switchuser_language'], time() + 3600*24*30, "/", "", "0");
	redirect($url);
}


if(isset($_COOKIE['user_language']) && $_COOKIE['user_language'] != '' && preg_match("/^[0-9a-zA-Z_]+$/", $_COOKIE['user_language'])) {
	$selected = $_COOKIE['user_language'];
} else {
	$selected = $settings['locale'];
}

openside($locale['langswitch_100']);
echo "<div style='text-align:center;'>
<form name='language_switcher' method='post' action='".$url."'>
<select name='switchuser_language' class='textbox' style='width:100px;' onchange=\"javascript:document.language_switcher.submit();\">
".makefileopts($language_files, $selected)."
</select>
</form>
</div>";

closeside();
?>