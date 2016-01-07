<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: spoiler2_bbcode_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Author: Valerio Vendrame (lelebart)
| Co-Author: slaughter (some minor modifications)
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if(!function_exists("spoiler2_bbcode_addtohead")) {
	function spoiler2_bbcode_addtohead() {
		return "<script type='text/javascript'>
//<![CDATA[
$(document).ready(function() {
	$('.spoiler-body').hide();
	$('.spoiler-button-hide').hide();
	$('.spoiler-head').show().css('cursor', 'pointer').click(function(){
		$('span',this).toggle();
		$(this).parents('.spoiler-main').next('.spoiler-body').slideToggle('fast');
	});
});
//]]>
</script>";
	}
	add_to_head(spoiler2_bbcode_addtohead());
}

$text = preg_replace("#\[spoiler2\](.*?)\[/spoiler2\]#si", "<div class='code_bbcode'><div class='spoiler-main tbl-border tbl2' style='width:400px'><strong>".$locale['bb_spoiler2_text']."</strong> <span class='spoiler-head' style='visibilty:hidden;display:none;'>[<span class='spoiler-toggle'>".$locale['bb_spoiler2_show']."</span><span class='spoiler-toggle spoiler-button-hide'>".$locale['bb_spoiler2_hide']."</span>]</span></div><div class='spoiler-body tbl-border tbl1' style='width:400px;overflow:auto;'>\\1</div></div>", $text);
?>