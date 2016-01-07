<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Radio-Theme Red2violet
| Author: Kevin Kersten
| Webseite: www.pursoundradio.de
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
require_once INCLUDES."theme_functions_include.php";
// theme settings
define ("THEME_WIDTH", "1024");
define("THEME_BULLET", "&middot;");


function render_page($license=false) {
global $settings, $main_style, $userdata, $locale, $aidlink, $p_data;

echo "<table cellpadding='0' cellspacing='0' width='".THEME_WIDTH."' align='center'><tr><td>";

//Header
echo "<div class='headneu'>";
//echo "<table cellpadding='0' cellspacing='0' width='100%' style='border: 0px; margin: 0px auto'><tr><td width='50%' height='163' align='right' valign='top'></td></tr></table>";
echo "<div id='logo'>".showbanners()."</div>";
echo "</div>";

//sublinks css Dropdown-Menu
include INCLUDES.'include_theme_navigation.php';

//Content

		
//Panelanordnung LEFT MID RIGHT
echo "<table cellpadding='0' cellspacing='0' width='".THEME_WIDTH."' class='main-bg'>
<tr>";
	if (LEFT) { echo "<td class='side-border-left' valign='top'>".LEFT."</td>"; }
	echo "<td class='main1-bg' valign='top'>".U_CENTER.CONTENT.L_CENTER."</td>";
	if (RIGHT) { echo "<td class='side-border-right' valign='top'>".RIGHT."</td>"; }
echo "</tr>
</table>";

//footer
echo "<div class='sidefooter'><strong><a href='".BASEDIR."impressum.php'>Impressum</a> | <a href='".BASEDIR."contact.php'>Kontakt</a></strong></div>";
echo "<div id='footer'>

<table cellpadding='0' cellspacing='0' width='".THEME_WIDTH."' class='footerback'>
<tr>
	<td class='copyleft'>".showcopyright()."</td>
	<td class='copyright'>
		Based on Red2violet theme &copy; 2011 by <a href='http://www.puresoundradio.de/' target='_blank'>Wondergekko</a><br />
		Theme &copy; 2013 by <a href='http://www.radio-la-familia.de/' target='blank'>RLF</a><br />
		</td>
</tr>
</table>
</div>";
echo "</td></tr></table>\n";

}

function render_news($subject, $news, $info) {

	//echo "<div class='capmain'>$subject</div>\n";
	echo "<table cellpadding='0' cellspacing='0' width='100%' align='center'>
	<tr>
	<td class='tabletoplinks'></td>
	<td class='tabletop'>".$subject."</td>
	<td class='tabletoprechts'></td>
	</tr></table>";
	// ende meins
	echo "<div class='main-body floatfix'>".$news."</div>\n";
	echo "<div class='news-footer'>\n";
	echo newsposter($info,"&middot;").newsopts($info,"&middot;").itemoptions("N",$info['news_id']);
	echo "</div>\n";
}

function render_article($subject, $article, $info) {
	echo "<div class='border tablebreak'>";
	// echo "<div class='capmain'>$subject</div>\n";
	echo "<table cellpadding='0' cellspacing='0' width='100%' align='center'>
	<tr>
	<td class='tabletoplinks'></td>
	<td class='tabletop'>".$subject."</td>
	<td class='tabletoprechts'></td>
	</tr></table>";
	// ende meins
	echo "<div class='main-body floatfix'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div>\n";
	echo "<div class='news-footer'>\n";
	echo articleposter($info,"&middot;").articleopts($info,"&middot;").itemoptions("A",$info['article_id']);
	echo "</div>\n";
	echo "</div>";
}

function opentable($title) {
echo "<div  class='tablerand'>
<table cellpadding='0' cellspacing='0' width='100%' align='center'>
<tr>
	<td class='tabletoplinks'></td>
	<td class='tabletop'>".$title."</td>
	<td class='tabletoprechts'></td>
</tr></table>
<table cellpadding='0' cellspacing='0' width='100%' align='center'><tr>
	<td class='tableseitlinks'></td>
	<td class='tbody'>\n";
}

function closetable() {
echo "</td>
	<td class='tableseitrechts'></td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%' class='spacer'><tr>
	<td class='tablebutlinks'></td>
	<td class='tablebut'>&nbsp;</td>
	<td class='tablebutrechts'></td>
</tr>
</table></div>\n";
}

function openside($title, $collapse = false, $state = "on") {
global $panel_collapse; $panel_collapse = $collapse;
echo "<table cellpadding='0' cellspacing='0' width='100%' class='sidetop'>
<tr>
	<td class='paneltext'>$title</td>\n";
	if ($collapse == true) {
		$boxname = str_replace(" ", "", $title);
		echo "<td>".panelbutton($state, $boxname)."</td>\n";
	}
echo "</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
	<td class='sidemid'>\n";
	if ($collapse == true) { echo panelstate($state, $boxname); }
}

function closeside($collapse = false) {
global $panel_collapse;
if ($panel_collapse == true) { echo "</div>\n"; }
echo "</td></tr></table>
<table cellpadding='0' cellspacing='0' width='100%' class='spacer'><tr>
<td class='sidebut'>&nbsp;</td>
</tr></table>\n";
}

function opentable2($title) {
echo "<div  class='tablerand'>
<table cellpadding='0' cellspacing='0' width='100%' align='center'>
<tr>
	<td class='tabletoplinks'></td>
	<td class='tabletop'>".$title."</td>
	<td class='tabletoprechts'></td>
</tr></table>
<table cellpadding='0' cellspacing='0' width='100%' align='center'><tr>
	<td class='tableseitlinks'></td>
	<td class='tbody'>\n";
}

function closetable2() {
echo "</td>
	<td class='tableseitrechts'></td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%' class='spacer'><tr>
	<td class='tablebutlinks'></td>
	<td class='tablebut'>&nbsp;</td>
	<td class='tablebutrechts'></td>
</tr>
</table></div>\n";
}

?>