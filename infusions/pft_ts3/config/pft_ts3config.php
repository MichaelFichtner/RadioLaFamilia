<?php
// -------------------------------------------------------
// PHP-Fusion Content Management System
// Copyright (C) 2002 - 2008 Nick Jones
// http://www.php-fusion.co.uk/
// -------------------------------------------------------
// Project: 	TS3 Viewer v1.0 für PHPFusion v7.x
// Author: 		HappyF
// Releasedate: Feb. 2010
// Version: 	1.0
// Web:			www.PHPFusion-Tools.de
// -------------------------------------------------------
// This program is released as free software under the
// Affero GPL license. You can redistribute it and/or
// modify it under the terms of this license which you
// can read by viewing the included agpl.txt or online
// at www.gnu.org/licenses/agpl.html. Removal of this
// copyright header is strictly prohibited without
// written permission from the original author(s).
// -------------------------------------------------------
include INFUSIONS."pft_ts3/infusion_db.php";

$result = dbquery("SELECT * FROM ".DB_TS3C."");
while($data = dbarray($result)){


    $ip = $data['ip'];
    $port = $data['port'];
    $t_port = $data['telnet'];
    $info['banner'] = $data['banner'];
    $info['legend'] = $data['legend'];
    $info['useron'] = $data['useron'];
    $info['stats'] = $data['stats'];
}
    $info['cutname'] = '0';
    $info['cutchannel'] = '0';
    $info['cache'] = '0';
    $info['serverinfo']['virtualserver_platform']['show'] = '1'; //Show on wich OS TS3 run
    $info['serverinfo']['virtualserver_platform']['label'] = '(TS3 OS)'; 
    $info['serverinfo']['virtualserver_version']['show'] = '0'; //Show the TS3 server version
    $info['serverinfo']['virtualserver_version']['label'] = '(TS3 Version)'; 
    $info['serverinfo']['virtualserver_channelsonline']['show'] = '1'; //Show the number of channels
    $info['serverinfo']['virtualserver_channelsonline']['label'] = '(R&auml;ume)'; 
    $info['serverinfo']['virtualserver_uptime']['show'] = '1'; //Show the server uptime since the last restart
    $info['serverinfo']['virtualserver_uptime']['label'] = '(Online seit)';
    $info['serverinfo']['virtualserver_created']['show'] = '0'; //Show when the server was installed
    $info['serverinfo']['virtualserver_created']['label'] = '(Serverstart)';
    $info['password'] = '';
    $info['tm_client'] = '0';
    $info['tm_width'] = '20';
    $info['tm_leng'] = '100';
    $info['showlink'] = '0';
    $info['sgroup'][6]['n'] = 'Serveradmin';
    $info['sgroup'][6]['p'] = '(S)';
    $info['cgroup'][5]['n'] = 'Channeladmin';
    $info['cgroup'][5]['p'] = '(C)';
    $info['cgroup'][6]['n'] = 'Channel Operator';
    $info['cgroup'][6]['p'] = '(O)';
	
	
?>