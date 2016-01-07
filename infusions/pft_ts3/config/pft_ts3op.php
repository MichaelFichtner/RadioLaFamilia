<?php

$new='';
$sinfo='';
$plist = '';
$clist='';

if($info['cache'] >= '1'){
    $filename = "ts_cache/cache_time.txt";
    $handle = fopen($filename, "r");
    $timecode = fread($handle, filesize($filename));
    $time = $timecode + ($info['cache']*60);
    if($time <= time() OR !file_exists("ts_cache/cache_server.html") OR !file_exists("ts_cache/cache_user.html") OR !file_exists("ts_cache/cache_banner.html") OR !file_exists("ts_cache/cache_tree.html")){
        $handle = fopen($filename, "w");
        fseek($handle, 0);
        fwrite($handle, time());
        $new = '1';
    }else{
        $new = '0';
    }
    fclose($handle);
}else{
    $new = '1';
}



if($new == '1'){
    $plist = '';
    $clist = '';
    $sinfo = '';

    $fp = @fsockopen($ip, $t_port, $errno, $errstr, 2);
    if($fp){

        $cmd = "serveridgetbyport virtualserver_port=".$port."\n";
        if(!($sid = sendCmd($fp, $cmd))){
            $error[] = 'No Server ID';
        }else{
            $sid = splitInfo($sid);
            $sid = $sid['server_id'];
        }

        $cmd = "use sid=".$sid."\n";
        if(!($select = sendCmd($fp, $cmd))){
            $error[] = 'Wrong Server ID';
        }

        $cmd = "serverinfo\n";
        if(!($sinfo = sendCmd($fp, $cmd))){
            $error[] = 'No Serverstatus';
        }else{
            $sinfo = splitInfo($sinfo);
        }

        $cmd = "channellist -topic -flags -voice -limits\n";
        if(!($clist_t = sendCmd($fp, $cmd))){
            $error[] = 'No Channellist';
        }else{
            $clist_t = splitInfo2($clist_t);
            foreach ($clist_t as $var) {
                $clist[] = splitInfo($var);
            }

        }
        $cmd = "clientlist -uid -away -voice -groups\n";
        if(!($plist_t = sendCmd($fp, $cmd))){
            $error[] = 'No Playerlist';
        }else{
            $plist_t = splitInfo2($plist_t);
            foreach ($plist_t as $var) {
                if(strpos($var, 'client_type=0') !== FALSE) {
                    $plist[] = splitInfo($var);
                }
            }
            if($plist != ''){
                foreach ($plist as $key => $var) {
                    $temp = '';
                    if(strpos($var['client_servergroups'], ',') !== FALSE){
                        $temp = explode(',', $var['client_servergroups']);
                    }else{
                        $temp[0] = $var['client_servergroups'];
                    }
                    $t = '0';
                    foreach ($temp as $t_var) {
                        if($t_var == '6'){
                            $t = '1';
                        }
                    }
                    if($t == '1'){
                        $plist[$key]['s_admin'] = '1';
                    }else{
                        $plist[$key]['s_admin'] = '0';
                    }
                }
                usort($plist, "cmp2");
                usort($plist, "cmp1");
            }

        }
        if(isset($_POST['tmcl']) && $_POST['tmcl'] != ''){
            $cmd = "use sid=".$sid."\nlogin serveradmin ".$info['password']." \nsendtextmessage targetmode=1 target=".$_POST['client']." msg=TS\sViewer:\s".rep2($_POST['tmcl'])." \n";
            if(!($tms = sendCmd($fp, $cmd))){
                $error_tm[] = 'Can\'t Send';
            }
        }
        $cmd = "quit\n";
        fputs($fp, $cmd);
        fclose($fp);
    }else{
        $error[] = 'Can not connect to the server';
    }
}


?>