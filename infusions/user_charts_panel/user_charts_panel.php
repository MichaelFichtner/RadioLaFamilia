<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: new_infusion_panel.php
| CVS Version: 1.00
| Author: INSERT NAME HERE
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

include INFUSIONS."user_charts/infusion_db.php";

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."user_charts_panel/locale/".$settings['locale'].".php")) {
    // Load the locale file matching the current site locale setting.
    include INFUSIONS."user_charts_panel/locale/".$settings['locale'].".php";
} else {
    // Load the infusion's default locale file.
    include INFUSIONS."user_charts_panel/locale/English.php";
}

echo '<link rel="stylesheet" href="<?php echo INFUSIONS ?>user_charts/css/my.css">';

openside("User Charts");

$res = dbquery("SELECT s.*,(SELECT COUNT(chart_id) + 1 FROM " . DB_CHARTS . " WHERE chart_punkte > s.chart_punkte) AS chart_platz FROM " . DB_CHARTS . " s ORDER BY chart_punkte DESC LIMIT 3;");

echo "<table class='table_panel' border='1' style='margin: 0 auto'>";
while ($row = dbarray($res)){
    echo "<tr><td>";
    echo "<img class='table_img' src='".$row['chart_cover']."' alt='bild'>";
    echo    "</td><td><table><tr><td>";
    echo $row["chart_interpret"];
    echo "</td></tr><tr><td>";
    echo $row["chart_song"];
    echo "</td></tr></table></td></tr>";
};
echo "</table><br><br>";



echo "<button>User Charts</button>";

closeside();
?>