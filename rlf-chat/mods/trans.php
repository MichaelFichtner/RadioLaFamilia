<?php

include_once 'song.php';

header("Content-Type: text/html; charset=utf-8");

// XML auslesen für Titel anzeige

header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
$server_rlf = "94.199.242.33"; //hier deinen server 
$port_rlf = 7777; //hier den port eintragen

$server_rbs = "94.199.242.33"; //hier deinen server 
$port_rbs = 6166; //hier den port eintragen  

$song_rbs = '';
$song_rlf = '';

    $fp_rlf = @fsockopen($server_rlf, $port_rlf, $errno, $errstr, 30);
	$fp_rbs = @fsockopen($server_rbs, $port_rbs, $errno, $errstr, 30);
 
    if ($fp_rlf) {
        fputs($fp_rlf, "GET /7.html HTTP/1.0\r\nUser-Agent: XML Getter (Mozilla Compatible)\r\n\r\n");
        while(!feof($fp_rlf))
            $page .= fgets($fp_rlf, 1000);
        fclose($fp_rlf);
        $page = ereg_replace(".*<body>", "", $page);
        $page = ereg_replace("</body>.*", "", $page); //","
		
		//var_dump($page);
		
        $numbers = explode(",", $page);
		
		//var_dump($numbers);
		
        $shoutcast_currentlisteners = $numbers[0]; 
        $connected = $numbers[1]; 
        $shoutcast_peaklisteners = $numbers[2]; 
        $shoutcast_maxlisteners = $numbers[3]; 
        $shoutcast_reportedlisteners = $numbers[4]; 
        $shoutcast_bitrate = $numbers[5]; 
        $shoutcast_cursong_rlf = song::songR($numbers);//$numbers[6]; 
        $shoutcast_curbwidth = $shoutcast_bitrate * $shoutcast_currentlisteners; 
        $shoutcast_peakbwidth = $shoutcast_bitrate * $shoutcast_peaklisteners; 
    }
	
	if ($fp_rbs) {
        fputs($fp_rbs, "GET /7.html HTTP/1.0\r\nUser-Agent: XML Getter (Mozilla Compatible)\r\n\r\n");
        while(!feof($fp_rbs))
            $page .= fgets($fp_rbs, 1000);
        fclose($fp_rbs);
        $page = ereg_replace(".*<body>", "", $page);
        $page = ereg_replace("</body>.*", "", $page);
        $numbers = explode(",", $page);
        $shoutcast_currentlisteners = $numbers[0]; 
        $connected = $numbers[1]; 
        $shoutcast_peaklisteners = $numbers[2]; 
        $shoutcast_maxlisteners = $numbers[3]; 
        $shoutcast_reportedlisteners = $numbers[4]; 
        $shoutcast_bitrate = $numbers[5]; 
        $shoutcast_cursong_rbs = song::songR($numbers);//$numbers[6]; 
        $shoutcast_curbwidth = $shoutcast_bitrate * $shoutcast_currentlisteners; 
        $shoutcast_peakbwidth = $shoutcast_bitrate * $shoutcast_peaklisteners; 
    }

// Class zum auslesen des Modi's über api sc_trans 2
	
class transRlf
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

class transRbs
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


if ($connected == 1) {

	echo "<br /><br />";
	// echo "<marquee direction='left' behavior='scroll' scrollamount='8' bgcolor=#990099><font color=#FFFF00>".htmlspecialchars($shoutcast_cursong)."</font></marquee>";
    $song_rlf = $shoutcast_cursong_rlf . '<br>';
	
	$song_rbs =  $shoutcast_cursong_rbs;
}    
    else {
        echo 'Unser Radio ist zur Zeit offline!';
}



// Modibild ausgabe RLF

$transRlf = new transRlf();
   $transRlf->host = "94.199.242.33";  //Transserver Host
   $transRlf->port = "9874";  //Transserver Port
   $transRlf->adminpass = "3!trZ2EcDYzEPzn"; //Transserver Admin Passwort
   $transRlf->adminuser = "sc_trans_admin"; //Transserver Admin Username
   
    $bild = $transRlf->between("<name>","</name>",$transRlf->getInfoTrans("op=getstatus&seq=45"));
    echo ucfirst($bild)." on Air at RLF !";
	// echo $bild;
	$DJ = $bild.".png"; //"./pic/".
	
	
	echo "<br /><br />";
	$DJ_PIC = "<img src='http://www.radio-la-familia.de/images/avatars/".$DJ."' alt='".$bild."' />";
	echo $DJ_PIC ;
	echo '<br>';
	echo  '<p>' . $song_rlf . '</p><hr>';

// Modibild ausgabe RBS

/*$transRbs = new transRbs();
   $transRbs->host = "94.199.242.33";  //Transserver Host
   $transRbs->port = "4444";  //Transserver Port
   $transRbs->adminpass = "6x6=36id$7%er8q"; //Transserver Admin Passwort
   $transRbs->adminuser = "sc_trans_admin"; //Transserver Admin Username
   
    $bild = $transRbs->between("<name>","</name>",$transRbs->getInfoTrans("op=getstatus&seq=45"));
    echo ucfirst($bild)." on Air at RBS !";
	// echo $bild;
	$DJ = $bild.".png"; //"./pic/".
	
	
	echo "<br /><br />";
	$DJ_PIC = "<img src='http://www.radio-black-sun.de/images/avatars/".$DJ."' height='100' alt='".$bild."' />";
	echo $DJ_PIC;
		echo '<br>';
	echo '<p>' . $song_rbs . '</p><hr>';*/
	/// Titel-Anzeige ######
	

	
	
	?>