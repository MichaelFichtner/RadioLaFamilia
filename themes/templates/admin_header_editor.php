<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: themes/templates/admin_header_editor.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| based on PHP-Fusion CMS v7.01 by Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

define("ADMIN_PANEL", true);

require_once INCLUDES."functions_admin_include.php";
include_once LOCALE.LOCALESET."admin/admin_global.php";
require_once INCLUDES."output_handling_include.php";
require_once THEME."theme.php";

if ($settings['maintenance'] == "1" && !iADMIN) { redirect(BASEDIR."maintenance.php"); }
if (iMEMBER) {
	$result = dbquery("UPDATE ".DB_USERS." SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".(int)$userdata['user_id']."'");
}
if (!isset($_GET['pagenum']) || !isnum($_GET['pagenum'])) $_GET['pagenum'] = 1;

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>".$settings['sitename']." ".$settings['sitetitle']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='Language' content='".$locale['xml_lang']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<meta name='generator' content='Pimped-Fusion - Open Source Content Management System - v".$settings['version_pimp']."' />\n";
echo "<link rel='stylesheet' href='".load_css_file()."' type='text/css' media='screen' />\n";
if (file_exists(IMAGES."favicon.ico")) { echo "<link rel='shortcut icon' href='".IMAGES."favicon.ico' type='image/x-icon' />\n"; }
if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "<script type='text/javascript' src='".INCLUDES_JS."jscript.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES_JS."jquery.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES_JS."admin-msg.js'></script>\n";
require_once INCLUDES."header_includes.php";

if ($settings['wysiwyg_enabled'] == "ckeditor") {

echo "<script type='text/javascript' src='".INCLUDES_JS."ckeditor/ckeditor.js'></script>";

}

if ($settings['wysiwyg_enabled'] == "tinymce") {
	echo "<style type='text/css'>.mceIframeContainer iframe{width:100%!important;}</style>\n";
	echo "<script language='javascript' type='text/javascript' src='".INCLUDES_JS."tiny_mce/tiny_mce.js'></script>\n
<script type='text/javascript'>
function advanced() {
	tinyMCE.init({
		mode:'textareas',
		theme:'advanced',
		skin: 'o2k7',
		skin_variant: 'silver',
		pagebreak_separator : '<--PAGEBREAK-->',
		language:'".$locale['tinymce']."',
		plugins: 'safari,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
		theme_advanced_buttons1: 'newdocument,styleprops,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect',
		theme_advanced_buttons2: 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor',
		theme_advanced_buttons3: 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl,|,print,fullscreen',
		theme_advanced_buttons4: 'insertlayer,moveforward,movebackward,absolute,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,blockquote,pagebreak',
		theme_advanced_toolbar_location:'top',
		theme_advanced_toolbar_align: 'left',
		theme_advanced_statusbar_location : 'bottom',
		theme_advanced_resizing : true,
		invalid_elements:'script,object,applet,iframe',
		content_css:'".THEME."styles.css',
		external_image_list_url:'".IMAGES."imagelist.js',
	});
}

function simple() {
	tinyMCE.init({
	mode:'textareas',
	theme:'simple',
	language:'".$locale['tinymce']."'
	});
}

function toggleEditor(id) {
	if (!tinyMCE.get(id))
		tinyMCE.execCommand('mceAddControl', false, id);
	else
		tinyMCE.execCommand('mceRemoveControl', false, id);
}
</script>\n";
}

echo "</head>\n<body>\n";

require_once TEMPLATES."panels.php";

ob_start();
?>