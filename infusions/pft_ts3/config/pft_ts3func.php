<?php
function sendCmd($fp, $cmd){
    $msg = '';
    fputs($fp, $cmd);
    while(strpos($msg, 'msg=') === false){
        $msg .= fread($fp, 8096);
    }
    if(!strpos($msg, 'msg=ok')){
        return false;
    }else{
        return $msg;
    }
}
function splitInfo($info){
    $info = trim(str_replace('error id=0 msg=ok', '', $info));
    $info = explode(' ', $info);
    foreach ($info as $var) {
        if(strpos($var, '=')=== FALSE){
            $return[$var] = '';
        }else{
            $a = str_replace('TS3', '', $var);
            $b = trim(substr($a, 0, (strpos($a, '='))));
            $return[$b] = substr($var, (strpos($var, '=')+1));
        }
    }
    return $return;
}
function splitInfo2($info){
    $info = trim(str_replace('error id=0 msg=ok', '', $info));
    $info = explode('|', $info);
    return $info;
}

function tree($id,$platzhalter,$clist,$error,$sinfo,$plist,$info,$new){
    $return = '';
    if($new == '1'){
        if(!isset($error[0])){
            foreach ($clist as $key => $var) {
                if($var['pid'] == $id){
                    $return .= $platzhalter;
                    $return .= '&nbsp;<img src="tsimages/channel.png" alt="'.rep($var['channel_topic']).'" title="'.rep($var['channel_topic']).'" />&nbsp;&nbsp;';
                    $return .= '<b>'.cutc($var['channel_name'],$info).'</b>&nbsp;&nbsp;';
                    if($var['channel_flag_default'] == 1){
                        $return .= '<div class="tsca" style="float:right;"><img src="tsimages/home.png" alt="'.rep($var['channel_topic']).'" title="'.rep($var['channel_topic']).'" /></div>';
                    }

                    if($var['channel_flag_password'] == 1){
                        $return .= '<div class="tsca" style="float:right;"><img src="tsimages/schloss.png" alt="'.rep($var['channel_topic']).'" title="'.rep($var['channel_topic']).'" /></div>';
                    }

                    $return .= '<div style="clear:both"></div>';
                    if($var['total_clients'] >= '1'){
                        if($plist != ''){
                            foreach($plist as $u_key => $u_var){
                                if($u_var['cid'] == $var['cid']){
                                    $p_img = '<i>>(Online)</i>';
                                    if($u_var['client_input_muted'] == '1'){
                                        $p_img = '<i>>(No Mic)</i>';
                                    }
                                    if($u_var['client_output_muted'] == '1'){
                                        $p_img = '<i>>(No Sound)</i>';
                                    }
                                    if($u_var['client_away'] == '1'){
                                        $p_img = '<i>>(Away)</i>';
                                    }
                                    $g_img = '';
                                    $g_temp = '';
                                    if(strpos($u_var['client_servergroups'], ',') !== FALSE){
                                        $g_temp = explode(',', $u_var['client_servergroups']);
                                    }else{
                                        $g_temp[0] = $u_var['client_servergroups'];
                                    }
                                    foreach ($g_temp as $sg_var) {
                                        if(isset($info['sgroup'][$sg_var]['p'])){
                                            $g_img .= '<i>(S)</i>';
                                        }
                                    }
                                    if(isset($info['cgroup'][$u_var['client_channel_group_id']]['p'])){
                                        if(isset($info['cgroup'][$u_var['client_channel_group_id']]['p'])){
                                    $g_img .= '<i>(C)</i>';
                                        }
                                    }
                                    $return .= $platzhalter.'&nbsp;&nbsp;'.$p_img.'&nbsp;&nbsp;>>&nbsp;'.cutn($u_var['client_nickname'],$info).'&nbsp;'.$g_img.'<div style="clear:both"></div>';
                                }
                            }
                        }
                    }
                    $return .= tree($var['cid'],$platzhalter.'<div class="tsleer">&nbsp;</div>',$clist,$error,$sinfo,$plist,$info, $new);
                }
            }
        }else{
            foreach ($error as $var) {
                $return .= $var.'<br />';
            }
        }
        if($info['cache'] == '1'){
            $filename = "ts_cache/cache_tree.html";
            $handle = fopen($filename, "w");
            fseek($handle, 0);
            fwrite($handle, $return);
            fclose($handle);
        }
    }else{
        $filename = "ts_cache/cache_tree.html";
        $handle = fopen($filename, "r");
        $return = fread($handle, filesize($filename));
        fclose($handle);
    }
    return $return;
}

function rep($var){
    $search[] = chr(194);
    $replace[] = '';
    $search[] = chr(183);
    $replace[] = '&#183;';
    $search[] = chr(180);
    $replace[] = '&#180;';
    $search[] = chr(175);
    $replace[] = '&#175;';
    $search[] = '\/';
    $replace[] = '/';
    $search[] = '\s';
    $replace[] = ' ';
    $search[] = '\p';
    $replace[] = '|';
    $search[] = '[URL]';
    $replace[] = '';
    $search[] = '[/URL]';
    $replace[] = '';
    $search[] = '[b]';
    $replace[] = '';
    $search[] = '[/b]';
    $replace[] = '';

    return str_replace($search, $replace, $var);
}

function rep2($var){
    $rep[] = '\/';
    $search[] = '/';
    $rep[] = '\s';
    $search[] = ' ';
    $rep[] = '\p';
    $search[] = '|';

    return str_replace($search, $rep, $var);
}
function cutc($var,$info){
    $var = rep($var);
    if($info['cutchannel'] >= '1'){
        $count = strlen($var);
        if($count > $info['cutchannel']){
            $pos = $info['cutchannel']-3;
            $var = substr($var, 0, $pos).'...';

        }
    }
    return $var;
}

function cutn($var,$info){
    $var = rep($var);
    if($info['cutname'] >= '1'){
        $count = strlen($var);
        if($count > $info['cutname']){
            $pos = $info['cutname']-3;
            $var = substr($var, 0, $pos).'...';
        }
    }
    return $var;
}

function legend($info, $new){
    $return = '';
    if($info['legend'] == '1'){
        $return .= '<p>&nbsp;</p><div id="legend" ><h3>Legende:</h3>';
        foreach ($info['sgroup'] as $var) {
            $return .= '<i>'.$var['p'].'</i>&nbsp;=>&nbsp;';
            $return .= '&nbsp;'.$var['n'].'&nbsp;';
            $return .= '<div style="clear:both"></div>';
        }
        foreach ($info['cgroup'] as $var) {
            $return .= '<i>'.$var['p'].'</i>&nbsp;=>&nbsp;';
            $return .= '&nbsp;'.$var['n'].'&nbsp;';
            $return .= '<div style="clear:both"></div>';
        }
        $return .= '</div>';
    }
    return $return;
}

function useron($info, $sinfo, $new){
    $return = '';
    if($new == '1'){
        if($info['useron'] == 1 && isset($sinfo['virtualserver_clientsonline'])){
            $return .= 'User online: '.($sinfo['virtualserver_clientsonline']-$sinfo['virtualserver_queryclientsonline']).'/'.$sinfo['virtualserver_maxclients'].'';
        }
        if($info['cache'] == '1'){
            $filename = "ts_cache/cache_user.html";
            $handle = fopen($filename, "w");
            fseek($handle, 0);
            fwrite($handle, $return);
            fclose($handle);
        }
    }else{
        $filename = "ts_cache/cache_user.html";
        $handle = fopen($filename, "r");
        $return = fread($handle, filesize($filename));
        fclose($handle);
    }
    return $return;
}

function banner($info, $sinfo, $new){
    $return = '';
    if($new == '1'){
        if($info['banner'] == 1 && isset($sinfo['virtualserver_hostbanner_gfx_url']) && $sinfo['virtualserver_hostbanner_gfx_url'] != ''){
            $return .= '<img id="tsbanner" src="'.rep($sinfo['virtualserver_hostbanner_gfx_url']).'" alt="TS Banner" />';
        }
        if($info['cache'] == '1'){
            $filename = "ts_cache/cache_banner.html";
            $handle = fopen($filename, "w");
            fseek($handle, 0);
            fwrite($handle, $return);
            fclose($handle);
        }
    }else{
        $filename = "ts_cache/cache_banner.html";
        $handle = fopen($filename, "r");
        $return = fread($handle, filesize($filename));
        fclose($handle);
    }
    return $return;
}

function showlink($info, $new){
    $return = '';
    if($info['showlink'] == 1){
        $return .= '<div class="useron" style="text-align:center;"><a href="http://www.phpfusion-tools.de">&copy;&nbsp;PHPFusion-Tools.de</a></div>';
    }
    return $return;
}
function stats($info, $sinfo, $new){
    $return = '';
    if($new == '1'){
        if($info['stats'] == 1){
            $tag = ($sinfo['virtualserver_uptime']/1000/60/60/24) %1;
            $std = ($sinfo['virtualserver_uptime']/1000/60/60)%24;
            $min = ($sinfo['virtualserver_uptime']/1000/60)%60;
            $sinfo['virtualserver_created'] = date('d M Y', $sinfo['virtualserver_created']);
            $sinfo['virtualserver_uptime'] = $tag.' Tage '.$std.' Stunden '.$min.' Minuten';
            $return .= '<div id="ts3stats"><h3>Statistiken:</h3>';
            foreach ($info['serverinfo'] as $key => $var){
                if($var['show'] == 1){
                    $return .= '<i>'.$var['label'].'</i>&nbsp;=>&nbsp;'.rep($sinfo[$key]).'<br>';
                }
            }
            $return .= '</div>';
        }
        if($info['cache'] == '1'){
            $filename = "ts_cache/cache_stats.html";
            $handle = fopen($filename, "w");
            fseek($handle, 0);
            fwrite($handle, $return);
            fclose($handle);
        }
    }else{
        $filename = "ts_cache/cache_stats.html";
        $handle = fopen($filename, "r");
        $return = fread($handle, filesize($filename));
        fclose($handle);
    }


    return $return;
}

function cmp1($a, $b){
    return strcmp($b["s_admin"], $a["s_admin"]);
}

function cmp2($a, $b){
    return strcmp($b["client_channel_group_id"], $a["client_channel_group_id"]);
}

function tm_client($info,$plist, $new){
    $return = '';
    if($new == '1'){
        if($info['tm_client'] == 1){
            $return = '<div class="send"><h3>Message to Client</h3>';
            if($info['password'] == ''){
                $return .= 'No Password set';
            }elseif($plist == ''){
                $return .= 'No User online';
            }else{
                $return .= '<form action="'.$_SERVER['REQUEST_URI'].'" method="post" accept-charset="ISO-8859-1"><select name="client" size="1">';
                foreach ($plist as $var) {
                    $return .= '<option value="'.$var['clid'].'">'.rep($var['client_nickname']).'</option>';
                }
                $return .= '</select><br />';
                $return .= '<input name="tmcl" type="text" size="'.$info['tm_width'].'" maxlength="'.$info['tm_leng'].'" /><input style="margin-top:10px;" type="submit" value=" Send "></form>';

            }
            $return .= '</div>';
        }
        if($info['cache'] == '1'){
            $filename = "ts_cache/cache_tm.html";
            $handle = fopen($filename, "w");
            fseek($handle, 0);
            fwrite($handle, $return);
            fclose($handle);
        }
    }else{
        $filename = "ts_cache/cache_tm.html";
        $handle = fopen($filename, "r");
        $return = fread($handle, filesize($filename));
        fclose($handle);
    }

    return $return;
}

function ts_server($sinfo, $info, $new){
    $return = '';
    if($new == '1'){
        if($info['useron'] == 1 && isset($sinfo['virtualserver_clientsonline'])){
            $return .= '@&nbsp;'.rep($sinfo['virtualserver_name']).'<div style="clear:both"></div><br>';
        }
        if($info['cache'] == '1'){
            $filename = "ts_cache/cache_server.html";
            $handle = fopen($filename, "w");
            fseek($handle, 0);
            fwrite($handle, $return);
            fclose($handle);
        }
    }else{
        $filename = "ts_cache/cache_server.html";
        $handle = fopen($filename, "r");
        $return = fread($handle, filesize($filename));
        fclose($handle);
    }

    return $return;
}



?>