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
if (!defined("IN_FUSION")) { die("Access Denied"); }

include INFUSIONS."gr_radiostatus_panel/infusion_db.php";
if (file_exists(INFUSIONS."gr_radiostatus_panel/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_radiostatus_panel/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_radiostatus_panel/locale/German/index.php";
}

openside($locale['grrs_54']);
if(function_exists('fsockopen')) {
	if (!defined("RADIOSTATUS")) {
		define("RADIOSTATUS", INFUSIONS."gr_radiostatus_panel/");
	}
echo "<script type='text/javascript'>
function GetXmlHttpObject() {
	var xmlHttp = null;
	try {
		xmlHttp = new XMLHttpRequest();
	} catch (e) {
		try {
			xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
		} catch (e) {
			xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
		}
	}
	return xmlHttp;
}

function updateSHOUTcast() {
	sc_xmlHttp = GetXmlHttpObject();
	if (sc_xmlHttp == null) {
		alert ('Your browser does not support AJAX!');
		return;
	}
	sc_xmlHttp.open('GET', '".RADIOSTATUS."gr_radiostatus_inc.php', true);
	sc_xmlHttp.onreadystatechange = ChangedSHOUTcast;
	sc_xmlHttp.send(null);
	setTimeout('updateSHOUTcast()',20000);
}

function ChangedSHOUTcast() {
	switch (sc_xmlHttp.readyState ) {
		case 3:
			document.getElementById('radiostatus').innerHTML = 'Bitte Warten';
			document.getElementById('radiostatus').style.display='block';
			break;
		case 4:
			if (sc_xmlHttp.status != 200) {
				alert('Der Request wurde abgeschlossen, ist aber nicht OK\\nFehler:'+request.status);
			} else {
				document.getElementById('radiostatus').innerHTML = sc_xmlHttp.responseText;
				document.getElementById('radiostatus').style.display='block';
			}
			break;
		default:
			break;
	}
}
updateSHOUTcast();

function rsqt(id) {
	rswmpWindow = window.open('http://radio-la-familia.de/rlf-player/index.html','quicktime','width=420,height=150');
	}

function rswmp(id) {
	rswmpWindow = window.open('".RADIOSTATUS."gr_player.php?p=wmp&id='+id,'wmp','width=360,height=180');
	}
function rsreal(id) {
	rsrealWindow = window.open('".RADIOSTATUS."gr_player.php?p=real&id='+id,'real','width=420,height=160');
}

function msg(msg) {
	alert(msg);
}
function gb(id) {
	gbWindow = window.open('".RADIOSTATUS."gr_grussbox.php?id='+id,'grussbox','width=700,height=450');
}
</script>\n";
echo "<div id='radiostatus'></div>\n";
} else {
	echo "<div align='center'>".$locale['grrs_35'].(iSUPERADMIN ? "<br />".$locale['grrs_36'] : "")."</div>";
}

echo "<div class='small2' align='right' ><a style='color:#282828;font-size: 9px;' href='http://www.granade.eu/scripte/radiostatus.html' target='_blank'>Radiostatus &copy;</a></div>\n";
closeside();
?>