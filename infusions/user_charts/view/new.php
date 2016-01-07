<?php
/**
 * Created by PhpStorm.
 * User: preacher
 * Date: 15.10.15
 * Time: 00:53
 */

require_once "../../../maincore.php";
include INFUSIONS."user_charts/infusion_db.php";


$number = 1;
$resultNew = dbquery("SELECT * FROM ".DB_NEUEINTRAG);
$resultRaus = dbquery("SELECT * FROM ".DB_CHARTS." WHERE chart_woche = 8 ");
$rowsRaus = dbrows($resultRaus);
$rowsNew = dbrows($resultNew);

/*echo "<div style='height: 100%; background-color: #e63636'><strong>Debug-Info</strong>";
var_dump($rowsRaus);
var_dump($rowsNew);
echo "</div>";*/

echo "<div style='height: 100%'>";

echo "<h4 style='text-align: center'>Neue Song einpflegen</h4>";



echo "<form action=".$aidlink." method='post'>";
echo "<p style='text-align: center'>Diese Woche sind <strong>".$rowsRaus."</strong> Einträge nötig</p></br>";
echo "<table align='center' class='GeneratedTable'><thead><tr>";
echo "        <th> Number </th>";
echo "        <th> Interpret </th>";
echo "        <th> Song </th>";
//echo "        <th> Cover </th>";
echo "        <th> Delete </th></tr></thead><tbody>";
    while($data = dbarray($resultNew)) {
        echo "<tr>
                <td>".$number."</td>
                <td>".$data['neu_interpret']."</td>
                <td>".$data['neu_song']."</td>
                <td><input type='checkbox' name='songid[]' value='".$data['neu_id']."'></td>
              </tr>";$number++;
    }
echo "   <tr><td colspan='4'><hr color='red'></td></tr>";
if($rowsNew >= $rowsRaus) {
    echo "   <tr align='center'>
            <td colspan='3'>Alle neuen Songs eingetragen ! Bitte nicht vergessen die Cover zuerstellen !</td>
            <td><input id='neueintrag' type='submit' name='delete' value='delete'></td>
        </tr>";
}else{
    echo "   <tr>
            <td colspan='2'><input type=\"text\" name='interpret'></td>
            <td colspan='1'><input type=\"text\" name='song'></td>
            <td><input id='neueintrag' type='submit' name='neueintrag' value='Eintragen'></td>
        </tr>";
}
echo "</tbody></table></form>";
//echo $status;
echo "</div>";