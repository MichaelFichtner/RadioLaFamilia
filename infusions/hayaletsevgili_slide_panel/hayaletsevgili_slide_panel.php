<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 20011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: hayaletsevgili_slide_panel.php
| Version: 1.0
| Author: HaYaLeT http://www.hayaletsevgili.com
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
| Please don't delete copy link / Lütfen Link Silmeyin
+--------------------------------------------------------*/

if (!defined('IN_FUSION')) { die('Access Denied'); }

if (file_exists(INFUSIONS."hayaletsevgili_slide_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."hayaletsevgili_slide_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."hayaletsevgili_slide_panel/locale/English.php";
}


add_to_head("<link rel='stylesheet' type='text/css' href=' ".INFUSIONS."hayaletsevgili_slide_panel/css/global.css' />");
add_to_head("<script type='text/javascript' src='".INFUSIONS."hayaletsevgili_slide_panel/js/slides.min.jquery.js'></script> ");

add_to_head("<script type='text/javascript' src='".INFUSIONS."hayaletsevgili_slide_panel/js/thisslide.js'></script> ");

opentable($locale['slide001']);

echo "
	<div id='container'>
		<div id='example'>
			<img src='".INFUSIONS."hayaletsevgili_slide_panel/img/new-ribbon.png' width='112' height='112' alt='".$locale['slide007']."' id='ribbon' />
			<div id='slides'>
				<div class='slides_container'>";



 $result=dbquery(
	"SELECT * FROM ".$db_prefix."photo_albums ta ".
	"JOIN ".$db_prefix."photos USING (album_id)
	WHERE ".groupaccess('album_access')." ORDER BY photo_id DESC LIMIT 0,7");
if (dbrows($result)!= "0") {
 while($data = dbarray($result)) {


					echo "<div class='slide'>
						<a href='".BASEDIR."photogallery.php?photo_id=".$data['photo_id']."'>
<img  src='".BASEDIR."images/photoalbum/album_".$data['album_id']."/".$data['photo_filename']."' border='0' width='470' height='270' alt='".$data['photo_title']."' title='".$locale['slide004']."' /></a>
						<div class='caption' style='bottom:0'>
							<p>".$data['photo_title']."</p>
						</div>
					</div>"; }

} else {
	echo $locale['slide002'];
}
				echo "</div>
				<a href='#' class='prev'><img src='".INFUSIONS."hayaletsevgili_slide_panel/img/arrow-prev.png' width='24' height='43' alt='".$locale['slide008']."' title='".$locale['slide006']."' /></a>
				<a href='#' class='next'><img src='".INFUSIONS."hayaletsevgili_slide_panel/img/arrow-next.png' width='24' height='43' alt='".$locale['slide009']."' title='".$locale['slide005']."' /></a>
			</div>
			<img src='".INFUSIONS."hayaletsevgili_slide_panel/img/example-frame.png' width='639' height='341' alt='".$locale['slide010']."' id='frame' />
		</div>

	</div>";
	closetable();
?>