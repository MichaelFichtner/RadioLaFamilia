<?php
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
$server = "94.199.242.33"; //hier deinen server 
$port = 7777; //hier den port eintragen 

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

if ($connected == 1) {
    echo 'Jetzt im Radio: ' . htmlspecialchars($shoutcast_reportedlisteners);
	var_dump($page);
}    
    else {
        echo 'Unser Radio ist zur Zeit offline!';
}
## shoutcast by Pr3mu off ##
?>