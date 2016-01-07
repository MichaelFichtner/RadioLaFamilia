<?php

// XML auslesen für Titel anzeige

header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
$server = "80.246.59.44"; //hier deinen server 
$port = 6166; //hier den port eintragen 

    $fp = @fsockopen($server, $port, $errno, $errstr, 30);
 
    if ($fp) {
        fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: XML Getter (Mozilla Compatible)\r\n\r\n");
        while(!feof($fp))
            $page .= fgets($fp, 1000);
        fclose($fp);
        $page = ereg_replace(".*<body>", "", $page);
        $page = ereg_replace("</body>.*", ",", $page);
        $numbers = explode(",", $page);
        $shoutcast_currentlisteners = $numbers[0]; 
        $connected = $numbers[1]; 
        $shoutcast_peaklisteners = $numbers[2]; 
        $shoutcast_maxlisteners = $numbers[3]; 
        $shoutcast_reportedlisteners = $numbers[4]; 
        $shoutcast_bitrate = $numbers[5]; 
        $shoutcast_cursong = $numbers[6]; 
        $shoutcast_curbwidth = $shoutcast_bitrate * $shoutcast_currentlisteners; 
        $shoutcast_peakbwidth = $shoutcast_bitrate * $shoutcast_peaklisteners; 
    }

// Class zum auslesen des Modi's über api sc_trans 2
	
class trans 
{
   var $host;
   var $port;
   var $adminpass;
   var $adminuser;
   
   
   function getInfoTrans($option)
   {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'http://'.$this->adminuser.':'.$this->adminpass.'@'.$this->host.':'.$this->port.'/api');
      curl_setopt ($ch, CURLOPT_POST, 1);
      curl_setopt ($ch, CURLOPT_POSTFIELDS, $option);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
      $xml = curl_exec($ch);
      return($xml);
      curl_close ($ch);
   }
   function between($von,$bis,$string) 
   {
      ##$von (<teil>)
      ##$bis (</teil>)
      ##$string = datei
   $a = explode($von,$string);
   $b = explode($bis,$a[1]);
   return($b[0]);
   }
}

// Modibild ausgabe

$trans = new trans();
   $trans->host = "80.246.59.44";  //Transserver Host
   $trans->port = "4444";  //Transserver Port
   $trans->adminpass = "6x6=36idLnsf"; //Transserver Admin Passwort
   $trans->adminuser = "sc_trans_admin"; //Transserver Admin Username
   
    $bild = $trans->between("<name>","</name>",$trans->getInfoTrans("op=getstatus&seq=45"));
    echo ucfirst($bild)." on Air!";
	// echo $bild;
	$DJ = $bild.".png"; //"./pic/".
	
	
	echo "<br /><br />";
	$DJ_PIC = "<img src='http://www.radio-la-familia.de/images/avatars/".$DJ."' alt='".$bild."' />";
	echo $DJ_PIC;
	
	/// Titel-Anzeige ######
	
if ($connected == 1) {

	echo "<br /><br />";
	// echo "<marquee direction='left' behavior='scroll' scrollamount='8' bgcolor=#990099><font color=#FFFF00>".htmlspecialchars($shoutcast_cursong)."</font></marquee>";
    echo htmlspecialchars($shoutcast_cursong);
}    
    else {
        echo 'Unser Radio ist zur Zeit offline!';
}
	
	
	?>