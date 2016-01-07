<?php
/**
 * Created by PhpStorm.
 * User: preacher
 * Date: 15.10.15
 * Time: 00:53
 */

require_once "../../../maincore.php";
include INFUSIONS."user_charts/infusion_db.php";

require_once INFUSIONS."user_charts/lib/SearchCover.php";

$resultNew = dbquery("SELECT * FROM ".DB_NEUEINTRAG);
$resultRaus = dbquery("SELECT * FROM ".DB_CHARTS." WHERE chart_woche = 8 ");
$rowsRaus = dbrows($resultRaus);
$rowsNew = dbrows($resultNew);

//$test = 0;

echo "<div style='height: 100%; color: black;'>";
echo "<h4 style='text-align: center'> Wochen auswertung </h4>";

if($rowsNew >= $rowsRaus) {
    echo "OK";

    echo "<form action=" .$aidlink . " method='post'>

    <input type='submit' name='auswertung' value='Auswerten JETZT !'>

    </form>";
}else{
    echo "Bitte erst neue Songs einpflegen ! ";
}

echo "</div>";
