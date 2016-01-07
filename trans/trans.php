<?php
//Trans Class
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
}
//Shoutcast Trans
$config["servers"]["trans"]["sounddata"] = ""; //Voller Pfad zu den MP3 Titeln
$config["servers"]["trans"]["transdata"] = ""; //Voller Pfad zu dem Sc_Trans
$config["servers"]["trans"]["transhost"] = "radio-la-familia.de";
$config["servers"]["trans"]["transport"] = "9874"; //Trans Port
$config["servers"]["trans"]["transuser"] = "sc_trans_admin"; //Trans Username
$config["servers"]["trans"]["transpass"] = "3!trZ2EcDYzEPzn"; //Trans Passwort
$trans = new trans();
	$trans->host = $config["servers"]["trans"]["transhost"];
	$trans->port = $config["servers"]["trans"]["transport"];
	$trans->adminpass = $config["servers"]["trans"]["transpass"];
	$trans->adminuser = $config["servers"]["trans"]["transuser"];
echo "<pre>";
echo "<h1>Get Status</h1>";
echo $trans->getInfoTrans("op=getstatus&seq=45");
echo "<h1>Get Options</h1>";
echo $trans->getInfoTrans("op=getoptions&seq=45");
echo "<h1>Get Endpoints</h1>";
echo $trans->getInfoTrans("op=getendpoints&seq=45");
echo "<h1>Logdata</h1>";
echo $trans->getInfoTrans("op=logdata&seq=45");
echo "<h1>List Playlists</h1>";
echo $trans->getInfoTrans("op=listplaylists&seq=45");
echo "<h1>List Events</h1>";
echo $trans->getInfoTrans("op=listevents&seq=45");
echo "<h1>List DJs</h1>";
echo $trans->getInfoTrans("op=listdjs&seq=45");
echo "</pre>";
?>