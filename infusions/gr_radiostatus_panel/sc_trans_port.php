<?php

 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, 'http://sc_trans_admin:3!trZ2EcDYzEPzn@94.199.242.33:9874/api');
 curl_setopt ($ch, CURLOPT_POST, 1);
 curl_setopt ($ch, CURLOPT_POSTFIELDS, 'op=getstatus&seq=45');
 // curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
 curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
 $xml = curl_exec($ch);
 
 
 function ExtractString($str, $start, $end)
 {
        $str_low = strtolower($str);
        $pos_start = strpos($str_low, $start);
        $pos_end = strpos($str_low, $end);
        if ( ($pos_start !== false) && ($pos_end !== false) )
            {
                $pos1 = $pos_start + strlen($start);
                $pos2 = $pos_end - $pos1;
                return substr($str, $pos1, $pos2);
            }
 }
 
 $match = ExtractString($xml, '<name>', '</name>');
 
 // echo $match;
 
 curl_close ($ch);

?>