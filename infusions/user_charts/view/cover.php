<?php
/**
 * Created by PhpStorm.
 * User: preacher
 * Date: 15.10.15
 * Time: 00:53
 */
require_once "../../../maincore.php";
include INFUSIONS."user_charts/infusion_db.php";

// TODO-MICHY Fehler bei Cover suche beheben

echo "<div style='height: 100%; color: black;'>";
echo "<h4 style='text-align: center'> Cover erstellen </h4>";

$sqlcover = "SELECT chart_id, chart_interpret, chart_song, chart_cover FROM ". DB_CHARTS ." ";


$resultcover = dbquery($sqlcover);
$covertest = array();

echo "<table align='center' class='GeneratedTable'><thead><tr>";
echo "        <th> Interpret </th>";
echo "        <th> Song </th>";
echo "        <th> Cover </th></tr></thead><tbody>";
while ($cover = dbarray($resultcover)) {
    echo "    <tr align='center'>";
    echo "        <td>". $cover['chart_interpret'] ."</td>";
    echo "        <td>". $cover['chart_song'] ."</td>";
    echo "        <td><img src=\"" . $cover['chart_cover'] . "\"></td>";
    echo "    </tr>";
    array_push($covertest, array("id" => $cover['chart_id'], "interpret" => $cover['chart_interpret'], "song" => $cover['chart_song']));
}
echo "</tbody></table>";

$seri = serialize($covertest); // Daten voerbereiten für POST methode
echo "<form action=" .$aidlink . " method='post'>
<input type='submit' value='Cover Erstellen' name='coverMake' style='width: 100%; height: 50px;border-color: darkred;margin: 20px 0 0 0;'>
<input type='hidden' value='". $seri ."' name='daten'>
</form>";
echo "</div>";

