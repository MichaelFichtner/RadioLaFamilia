<?php

// test.php

// include "shoutcast.class.php";

	$host='94.199.242.33';
	$port='7777';
	$passwd='Oled98$34!A';

if(function_exists('fsockopen')) echo "fsockopen() ist aktiviert";
else echo "fsockopen() ist deaktiviert";

	
	
// echo " Test-Script ";

$fp = fsockopen("www.radio-la-familia.de", 7777, $errno, $errstr, 30);

if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    $out = "GET / HTTP/1.1\r\n";
    $out .= "Host: www.example.com\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
    while (!feof($fp)) {
        echo fgets($fp, 128);
    }
    fclose($fp);
}

            // if (stristr($this->_xml, "HTTP/1.0 200 OK") == true) {
                // // <-H> Thanks to Blaster for this fix.. trim();
                // xml = trim(substr($xml, 42));
            // } else {
                // error = "Bad login";
                // return(0);
            // }

            // $xmlparser = xml_parser_create();
            // if (!xml_parse_into_struct($xmlparser, $xml, $values, $indexes)) {
                // $this->_error = "Unparsable XML";
                // return(0);
            // }

            // xml_parser_free($xmlparser);

?>