<?php
// -------------------------------------------------------
// PHP-Fusion Content Management System
// Copyright (C) 2002 - 2008 Nick Jones
// http://www.php-fusion.co.uk/
// -------------------------------------------------------
// Project: 	TS3 Viewer v1.0 für PHPFusion v7.x
// Author: 		HappyF
// Releasedate: Feb. 2010
// Version: 	1.0
// Web:			www.PHPFusion-Tools.de
// -------------------------------------------------------
// This program is released as free software under the
// Affero GPL license. You can redistribute it and/or
// modify it under the terms of this license which you
// can read by viewing the included agpl.txt or online
// at www.gnu.org/licenses/agpl.html. Removal of this
// copyright header is strictly prohibited without
// written permission from the original author(s).
// -------------------------------------------------------

error_reporting(E_ALL);
$error = array();

require_once "../../maincore.php";
require_once THEMES."templates/header.php";

include_once INFUSIONS."pft_ts3/config/pft_ts3config.php";
include_once INFUSIONS."pft_ts3/config/pft_ts3func.php";
include_once INFUSIONS."pft_ts3/config/pft_ts3op.php";

opentable("Teamspeak 3 Viewer by PHPFusion-Tools.de");
echo "<table align='left' width='96%' cellspacing='0' cellpadding='0'>
		<tr>
		<td>";
?>
        <?php if(!isset($error[0])){ echo banner($info, $sinfo, $new);} ?>
		<table align="left"><td><tr>
        <h3><a href="ts3server://<?=$ip?>?port=<?=$port?>">>> Connect!</a></h3>
		</td></tr></table>

        <?php if(!isset($error[0])){echo useron($info, $sinfo, $new);} ?>

        <?php if(!isset($error[0])){ echo ts_server($sinfo, $info, $new); } ?>

        <?php echo tree('0', '', $clist, $error, $sinfo, $plist, $info, $new);
        if(!isset($error[0])){
            echo legend($info, $new);
            echo stats($info, $sinfo, $new);
            echo tm_client($info,$plist, $new);
            echo showlink($info, $new);
        }
        ?>
<?php
echo "</td></tr></table>";
closetable();

require_once THEMES."templates/footer.php";
?>