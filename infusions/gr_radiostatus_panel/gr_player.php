<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Radiostatus v1.0 for PHP-Fusion 7
| Filename: gr_radiostatus_admin.php
| Author: Ralf Thieme
| Webseite: www.granade.eu
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
require_once THEME."theme.php";

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' media='screen' />\n";
if (file_exists(IMAGES."favicon.ico")) { echo "<link rel='shortcut icon' href='".IMAGES."favicon.ico' type='image/x-icon' />\n"; }
echo "<script type='text/javascript' src='".INCLUDES."jscript.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES."jquery.js'></script>\n";
echo "</head>\n<body>\n";
if (isset($_GET['id']) && isnum($_GET['id']) && isset($_GET['p'])) {
	include INFUSIONS."gr_radiostatus_panel/infusion_db.php";
	$result = dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS." WHERE rs_id='".$_GET['id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$servertitle = $data['rs_name'];
	} else {
		redirect(FUSION_SELF);
	}
	$_GET['p'] = stripinput($_GET['p']);

	if ($_GET['p'] == 'wmp') {
		opentable($settings['sitename'].": ".$servertitle);
		echo "<div align='center'>\n";
		echo "<object id='mediaplayer' classid='clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95' codebase='http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701' width='320' height='50' standby='Loading Microsoft Windows Media Player components...' type='application/x-oleobject'>\n";
		echo "<param name='filename' value'http://".$data['rs_ip'].":".$data['rs_port']."' />\n";
		echo "<param name='transparentatstart' value'true' />\n";
		echo "<param name='autostart' value'true' />\n";
		echo "<param name='animationatstart' value'false' />\n";
		echo "<param name='showstatusbar' value'true' />\n";
		echo "<param name='showcontrols' value'true' />\n";
		echo "<param name='autosize' value'false' />\n";
		echo "<param name='displaysize' value'0' />\n";
		echo "<embed type='application/x-mplayer2' pluginspage='http://www.microsoft.com/Windows/MediaPlayer/' src='http://".$data['rs_ip'].":".$data['rs_port']."' name='mediaplayer' autostart='1' width='320' height='50' transparentatstart='0' autostart='1' animationatstart='0' showstatusbar='1' showcontrols='1' autosize='0' displaysize='0'></embed>\n";
		echo "</object>\n";
		echo "</div>\n";
		closetable();

/*
		echo "<div id='wmp'>";
		echo "</br>";
		echo "<a title='Windows Medien Player' href='http://radio-black-sun.de/streamurl.asx'><img width='50' border='0' alt='Windows Medien Player' src='images/wmp.png'></a>";
		echo "</br></br>";
		echo "<h3> Bitte nochmal auf den oberen Link klicken </h3>";
		echo "</br></br>";
		echo "</div>";
*/
		
	} elseif ($_GET['p'] == 'real') {
		opentable($settings['sitename'].": ".$servertitle);
		echo "<div align='center'>\n";
		echo "<object id='mediaplayer' classid='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95' codebase='http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701' width='320' height='50' standby='Loading Microsoft Windows Media Player components...' type='application/x-oleobject'>\n";
		echo "<param name='src' value'http://".$data['rs_ip'].":".$data['rs_port']."' />\n";
		echo "<param name='autostart' value'-1' />\n";
		echo "<param name='console' value'one' />\n";
		echo "<param name='controls' value'all' />\n";
		echo "<embed type='audio/x-pn-realaudio-plugin' src='http://".$data['rs_ip'].":".$data['rs_port']."' autostart='true' width='375' height='100' nojava='true' controls='all' console='one' ></embed>\n";
		echo "</object>\n";
		echo "</div>\n";
		closetable();
	} elseif ($_GET['p'] == 'qt') {
		opentable($settings['sitename'].": ".$servertitle);
		echo "<div align='center'>\n";
		echo "<object classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B' codebase='http://www.apple.com/qtactivex/qtplugin.cab' width='300' height='30'>\n";
		echo "<param name='src' value'http://".$data['rs_ip'].":".$data['rs_port']."/listen.pls' />\n";
		echo "<param name='autoplay' value'true' />\n";
		echo "<param name='controller' value'true' />\n";
		echo "<embed type='video/quicktime' pluginspage='http://www.apple.com/quicktime/download/' src='http://".$data['rs_ip'].":".$data['rs_port']."/listen.pls' name='mediaplayer' width='300' height='30' controller='true' autoplay='true'></embed>\n";
		echo "</object>\n";
		echo "</div>\n";
		closetable();
	} else {
		redirect(FUSION_SELF);
	}
} else {
	opentable($locale['grrs_38']);
	echo $locale['grrs_39'];
	closetable();
}
echo "</body>\n</html>\n";
mysql_close();
?>
