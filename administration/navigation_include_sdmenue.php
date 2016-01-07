<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: navigation_include_sdmenue.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: Proggi, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

$admin_nav_color = $settings['adminmenue_color'];
if(class_exists('Switcher')){
	$colour = $colour_switcher->selected;
	if(is_dir(INCLUDES_JS."admin_nav/".$colour)){
		$admin_nav_color = $colour;
	}
}

add_to_head("
<link rel='stylesheet' href='".INCLUDES_JS."admin_nav/".$admin_nav_color."/admin_nav.css' type='text/css' media='screen' />
<script type='text/javascript' src='".INCLUDES_JS."admin_nav/admin_nav.js'></script>
");

echo '<script type="text/javascript">
// <![CDATA[var pkUccMenu = new SDMenu("pk_ucc_menu"); // ID of the menu element
var pkUccMenu;
window.onload = function() {
pkUccMenu = new SDMenu("pk_ucc_menu");
pkUccMenu.init();
//pkUccMenu.collapseAll();
pkUccMenu.speed			= 2;		// Menu sliding speed (1 - 5 recomended)
pkUccMenu.remember		= true;		// Store menu states (expanded or collapsed) in cookie and restore later
pkUccMenu.oneSmOnly		= true;		// One expanded submenu at a time
if(pkUccMenu.oneSmOnly == true) {
	var regex = new RegExp("sdmenu_" + encodeURIComponent(pkUccMenu.menu.id) + "=([01]+)");
	var match = regex.exec(document.cookie);
	if (match) {
		var states = match[1].split("");
		for (var i = 0; i < states.length; i++)
			pkUccMenu.submenus[i].className = (states[i] == 0 ? "collapsed" : "");
	} else {
		var firstSubmenu = pkUccMenu.submenus[0];
		pkUccMenu.expandMenu(firstSubmenu);
	}
}
//pkUccMenu.init();
};
// ]]>
</script>';

#openside($locale['global_001']);

echo '<div style="text-align: center;">';
echo '<a href="javascript:pkUccMenu.expandAll();" onclick="this.blur();">';
echo '<img src="'.INCLUDES_JS.'admin_nav/'.$admin_nav_color.'/minus.png" width="9" height="9" alt="'.$locale['sdme101'].'" title="'.$locale['sdme101'].'" border="0" style="margin-bottom: 1px;" /></a>&nbsp;';
echo '<a href="javascript:pkUccMenu.collapseAll();" onclick="this.blur();">';
echo '<img src="'.INCLUDES_JS.'admin_nav/'.$admin_nav_color.'/plus.png" width="9" height="9" alt="'.$locale['sdme102'].'" title="'.$locale['sdme102'].'" border="0" style="margin-bottom: 1px;" /></a>';
echo '</div>'."\n";
echo '<div class="pkUccTopTitle"><span>'.$locale['global_001'].'</span></div>'."\n";
echo "<div class='normalLink'><a class='toWebsite' href='./../index.php'>".$locale['sdme100']."</a></div>\n";
echo '<div class="normalLink"><a class="adminHome" href="'.ADMIN.'index.php'.$aidlink.'">'.$locale['ac00'].'</a></div>'."\n";

echo '<div id="pk_ucc_menu" class="sdmenu">'."\n";

$result = dbquery("SELECT admin_title, admin_page, admin_rights, admin_link FROM ".DB_ADMIN." ORDER BY admin_page DESC, admin_title ASC");
	$rows = dbrows($result);
	while ($data = dbarray($result)) {
		if ($data['admin_link'] != "reserved" && checkrights($data['admin_rights']) && array_key_exists("admin_".$data['admin_rights'], $locale) && ($_GET['pagenum'] == 1 || $_GET['pagenum'] == 2 || $_GET['pagenum'] == 3 || $_GET['pagenum'] == 4)) {
			$pages[$data['admin_page']] .= "<a href='".ADMIN.$data['admin_link'].$aidlink."'>".preg_replace("/&(?!(#\d+|\w+);)/", "&amp;", $locale["admin_".$data['admin_rights']])."</a>\n";
		} elseif ($data['admin_link'] != "reserved" && checkrights($data['admin_rights'])) {
			$pages[$data['admin_page']] .= "<a href='".ADMIN.$data['admin_link'].$aidlink."'>".preg_replace("/&(?!(#\d+|\w+);)/", "&amp;", $data['admin_title'])."</a>\n";
		}
	}
	$content = false;
	for ($i = 1; $i < 6; $i++) {
		$page = $pages[$i];
		if ($page) {
			$admin_pages = true;
			echo "<div>\n";
			echo "<span>".$locale['ac0'.$i]."</span>\n";
			echo $page."</div>\n";
			$content = true;
		}
	}
echo "</div>";
echo "<div class='normalLink'><a class='logoutBottom' href='".BASEDIR."setuser.php?logout=yes'>".$locale['global_124']."</a></div>\n";
echo "<div class='pkUccBottom'></div>";
echo "<br />";
#closeside();

?>