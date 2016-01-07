<?php
include("config.php");
 
function ConvertSeconds($seconds) {
    $tmpseconds = substr("00".$seconds % 60, -2);
    if ($seconds > 59) {
        if ($seconds > 3599) {
            $tmphours = substr("0".intval($seconds / 3600), -2);
            $tmpminutes = substr("0".intval($seconds / 60 - (60 * $tmphours)), -2);
 
            return ($tmphours.":".$tmpminutes.":".$tmpseconds);
        } else {
            return ("00:".substr("0".intval($seconds / 60), -2).":".$tmpseconds);
        }
    } else {
        return ("00:00:".$tmpseconds);
    }
}
 
$shoutcast = new ShoutCast();
$shoutcast->host;
$shoutcast->port;
$shoutcast->tunein;
$shoutcast->passwd;

// var_dump($shoutcast->openstats());


if ($shoutcast->openstats()) {
    if ($shoutcast->GetStreamStatus()) {
				
				echo "Status:  " .$shoutcast->GetStreamStatus() . " Open:  " .$shoutcast->openstats() ;
				
				echo "<br>";
				
////////////////////////////////////////////////////
// UMWANDLUNG VON FEHLERHAFT ANGEZEIGTEN UMLAUTEN //
////////////////////////////////////////////////////

                $aktuellertitel = $shoutcast->GetCurrentSongTitle();
				
				echo "was ankommt:  " .$shoutcast->GetCurrentSongTitle();
				
				echo "<br>";
				
                $uml = array("Ã¤", "Ã¶", "Ãƒ", "Ã¼", "Ã„", "Ã–", "Ãœ", "ÃŸ", "Â´");
                $ohne_uml = array("ae", "oe", "oe", "ue", "Ae", "Oe", "Ue", "ss", "'");
                $aktuellertitel_g = str_replace($uml, $ohne_uml, $aktuellertitel);
                
/////////////////////////////////////////////////////////////////////////////////////////
// JEDER ERSTE BUCHSTABE GROSS -- FALLs UNGEWÜNSCHT, DIE FOLGENDE ZEILE AUSKOMMENTIREN //
/////////////////////////////////////////////////////////////////////////////////////////
                $aktuellertitel_n = ucwords($aktuellertitel_g);
				
				echo "<br>";
				
				echo " nach der ausbesserung:  " .$aktuellertitel_n;
				
				echo "<br>";

///////////////////////
// MÖGLICHE AUSGABEN //
///////////////////////
?>

<!--Stream Title:            <?php echo $shoutcast->GetServerTitle(); ?><br>
Aktuelle Hörerzahl:        <?php echo $shoutcast->GetCurrentListenersCount(); ?><br>
Maximale Hörerzahl:        <?php echo $shoutcast->GetMaxListenersCount(); ?><br>
Hörer Peak:                <?php echo $shoutcast->GetPeakListenersCount(); ?><br>
Genre:                    <?php echo $shoutcast->GetServerGenre(); ?><br>-->
URL:                    <?php echo $shoutcast->GetServerURL(); ?><br>
Aktueller Titel:        <?php echo $shoutcast->GetCurrentSongTitle(); ?><br>
<!--Bitrate:                <?php echo $shoutcast->GetBitRate(); ?><br>
Durchschn.Zuhördauer:    <?php echo ConvertSeconds($shoutcast->GetAverageListenTime()); ?><br>
IRC:                    <?php echo $shoutcast->GetIRC(); ?><br>
AIM:                    <?php echo $shoutcast->GetAIM(); ?><br>
ICQ:                    <?php echo $shoutcast->GetICQ(); ?><br>
HP Counter:                <?php echo $shoutcast->GetWebHitsCount(); ?><br>
Tune-In Counter:        <?php echo $shoutcast->GetStreamHitsCount(); ?><br>-->

<?php
}else {
    echo "<b><font color='red'>Der Stream ist Offline</font></b>";
    }      
}

else { 
echo "was nun ?!" ;
} 
?>

<?//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SONG-HISTORY -- DIE MENGE DER ANGEZEIGTEN TITEL KANN NUR IN DER CONFIG DES SERVERS SELBER GEÄNDERT WERDEN! //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////?>

<br><br>
Song History:<br>
<?php    
    
$history = $shoutcast->GetSongHistory();
        if (is_array($history)) {

//////////////////////////////////////////
// UMWANDLUNG VON FEHLERHAFTEN UMLAUTEN //
//////////////////////////////////////////
            for ($i=0;$i<sizeof($history);$i++) {
                $uml = array("Ã¤", "Ã¶", "Ã¼", "Ã„", "Ã–", "Ãœ", "ÃŸ", "Â´");
                $ohne_uml = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss", "'");
                $history[$i]["title"] = str_replace($uml, $ohne_uml, $history[$i]["title"]);
                
/////////////////////////////////////////////////////////////////////////////////////////
// JEDER ERSTE BUCHSTABE GROSS -- FALLs UNGEWÜNSCHT, DIE FOLGENDE ZEILE AUSKOMMENTIREN //
/////////////////////////////////////////////////////////////////////////////////////////
$history[$i]["title"] = ucwords($history[$i]["title"]);
                
                echo "<font class='historyfont'>",
                              
$history[$i]["title"] = ucwords(strtolower($history[$i]["title"])).

                
                "</font><br>\n";
            }
        } else {
            echo "No song history available..";
        }
   
?>