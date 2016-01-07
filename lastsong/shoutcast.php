 
<?php
include "shoutcast.class.php";
echo "Hallo";
  
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
$shoutcast->host = "94.199.242.33";
$shoutcast->port = "7777";
$shoutcast->passwd = "Oled98$34!A";


if ($shoutcast->openstats()) {
    // We got the XML, gogogo!..
    if ($shoutcast->GetStreamStatus()) {
        echo "<b>".$shoutcast->GetServerTitle()."</b> (".$shoutcast->GetCurrentListenersCount()." of ".$shoutcast->GetMaxListenersCount()." listeners, peak: ".$shoutcast->GetPeakListenersCount().")<p>nn";
        echo "<table border=0 cellpadding=0 cellspacing=0>n";
        echo "<tr><td width='180'><b>Server Genre: </b></td><td>'.$shoutcast->GetServerGenre().'</td></tr>n";
        echo "<tr><td><b>Server URL: </b></td><td><a href=".$shoutcast->GetServerURL().">".$shoutcast->GetServerURL()."</a></td></tr>n";
        echo "<tr><td><b>Server Title: </b></td><td>".$shoutcast->GetServerTitle()."</td></tr><tr><td colspan=2>&nbsp;</td></tr>n";
        echo "<tr><td><b>Current Song: </b></td><td>".$shoutcast->GetCurrentSongTitle()."</td></tr>n";
        echo "<tr><td><b>BitRate: </b></td><td>".$shoutcast->GetBitRate()."</td></tr><tr><td colspan=2>&nbsp;</td></tr>n";
        echo "<tr><td><b>Average listen time: </b></td><td>".ConvertSeconds($shoutcast->GetAverageListenTime())."</td></tr><tr><td colspan=2>&nbsp;</td></tr>n";
        echo "<tr><td><b>IRC: </b></td><td>".$shoutcast->GetIRC()."</td></tr>n";
        echo "<tr><td><b>AIM: </b></td><td>".$shoutcast->GetAIM()."</td></tr>n";
        echo "<tr><td><b>ICQ: </b></td><td>".$shoutcast->GetICQ()."</td></tr><tr><td colspan=2>&nbsp;</td></tr>n";
        echo "<tr><td><b>WebHits Count: </b></td><td>".$shoutcast->GetWebHitsCount()."</td></tr>n";
        echo "<tr><td><b>StreamHits Count: </b></td><td>".$shoutcast->GetStreamHitsCount()."</td></tr>n";
        echo "</table><p>";
        echo "<b>Song history;</b>n";
        $history = $shoutcast->GetSongHistory();
        if (is_array($history)) {
            for ($i=0;$i<sizeof($history);$i++) {
                echo "&#91;".$history&#91;$i&#93;&#91;"playedat"&#93;."&#93; - ".$history&#91;$i&#93;&#91;"title"&#93;."n";
            }
        } else {
            echo "No song history available..";
        }
        echo "<p>";
 
        echo "<b>Listeners;</b>n";
        $listeners = $shoutcast->GetListeners();
        if (is_array($listeners)) {
            for ($i=0;$i<sizeof($listeners);$i++) {
                echo "&#91;".$listeners&#91;$i&#93;&#91;"uid"&#93;."&#93; - ".$listeners&#91;$i&#93;&#91;"hostname"&#93;." using ".$listeners&#91;$i&#93;&#91;"useragent"&#93;.", connected for ".ConvertSeconds($listeners&#91;$i&#93;&#91;"connecttime"&#93;)."n";
            }
        } else {
            echo "Noone listens right now..";
        }
    } else {
        echo "Server is up, but no stream available..";
    }
} else {
    // Ohhh, damnit..
    echo $shoutcast->geterror();
}
?>
