<?php
if (isset($_POST['ts3save'])) {

		$error = "";
        $ip	=  stripinput($_POST['ip']);
        $port =  stripinput($_POST['port']);
        $telnet =  stripinput($_POST['telnet']);
		$banner =  stripinput($_POST['banner']);
		$legend =  stripinput($_POST['legend']);
		$useron =  stripinput($_POST['useron']);
		$stats =  stripinput($_POST['stats']);
		
		if ($ip == "") {
		$error .= "· <span class='alt'>Die Server IP fehlt!</span><br />\n";
		}
		if ($port == "") {
		$error .= "· <span class='alt'>Der Server Port fehlt!</span><br />\n";
		}
		if ($telnet == "") {
		$error .= "· <span class='alt'>Der Telnetport fehlt!</span><br />\n";
		}	
		if ($error != "") {
			opentable("Fehler");
			echo "<u>Folgender Fehler trat auf:</u><br /><br />\n$error\n\n";
			closetable();
			
		} else {
		$result = dbquery("UPDATE ".DB_TS3C." SET ip = '".$ip."', port = '".$port."', telnet = '".$telnet."', banner = '".$banner."', legend = '".$legend."', useron = '".$useron."', stats = '".$stats."'  WHERE ts3c_id");

	redirect(FUSION_SELF.$aidlink."&section=config");	
	}
}  

opentable("Grundeinstellungen");	
	echo "<form action='".FUSION_SELF.$aidlink."&section=config' method='POST'>";
	echo "<table align='center' cellspacing='0' cellpadding='0' width='100%'>\n";
	
	echo "<tr>\n<td align='left' width='100px'>Server IP:</td><td align='left' width='250px'><input type='text' name='ip' class='textbox' value='---' style='width:200px'></td><td align='left'><i>z.B. 42.234.123.12</i></td>\n</tr>\n";

	echo "<tr>\n<td align='left' width='100px'>&nbsp;</td><td align='left' width='250px'>&nbsp;</td><td align='left'>&nbsp;</td>\n</tr>\n";
	
	echo "<tr>\n<td align='left' width='100px'>Server Port:</td><td align='left' width='250px'><input type='text' name='port' class='textbox' value='---' style='width:200px'></td><td align='left'><i>z.B. 9987</i></td>\n</tr>\n";
	
	echo "<tr>\n<td align='left' width='100px'>&nbsp;</td><td align='left' width='250px'>&nbsp;</td><td align='left'>&nbsp;</td>\n</tr>\n";
	
	echo "<tr>\n<td align='left' width='100px'>Telnet Port:</td><td align='left' width='250px'><input type='text' name='telnet' class='textbox' value='---' style='width:200px'></td><td align='left'><i>z.B. 11001</i></td>\n</tr>\n";
	
	echo "<tr>\n<td align='left' width='100px'>&nbsp;</td><td align='left' width='250px'>&nbsp;</td><td align='left'>&nbsp;</td>\n</tr>\n";
		
	echo "<tr>\n<td align='left' width='100px'>Zeige Banner:</td><td align='left' width='250px'>
		<label><select name='banner' id='banner' style='width: 200px'>
			<option value='1'>An</option>
			<option value='0'>Aus</option>
			</select></label>
	</td><td align='left'><i>&nbsp;</i></td>\n</tr>\n";
	
	echo "<tr>\n<td align='left' width='100px'>&nbsp;</td><td align='left' width='250px'>&nbsp;</td><td align='left'>&nbsp;</td>\n</tr>\n";
	
	echo "<tr>\n<td align='left' width='100px'>Zeige Legende:</td><td align='left' width='250px'>
		<label><select name='legend' id='legend' style='width: 200px'>
			<option value='1'>An</option>
			<option value='0'>Aus</option>
			</select></label>
	</td><td align='left'><i>&nbsp;</i></td>\n</tr>\n";
	
	echo "<tr>\n<td align='left' width='100px'>&nbsp;</td><td align='left' width='250px'>&nbsp;</td><td align='left'>&nbsp;</td>\n</tr>\n";	
	
	echo "<tr>\n<td align='left' width='100px'>Zeige User:</td><td align='left' width='250px'>
		<label><select name='useron' id='useron' style='width: 200px'>
			<option value='1'>An</option>
			<option value='0'>Aus</option>
			</select></label>
	</td><td align='left'><i>&nbsp;</i></td>\n</tr>\n";
	
	echo "<tr>\n<td align='left' width='100px'>&nbsp;</td><td align='left' width='250px'>&nbsp;</td><td align='left'>&nbsp;</td>\n</tr>\n";	
		
	echo "<tr>\n<td align='left' width='100px'>Zeige Statistik:</td><td align='left' width='250px'>
		<label><select name='stats' id='stats' style='width: 200px'>
			<option value='1'>An</option>
			<option value='0'>Aus</option>
			</select></label>
	</td><td align='left'><i>&nbsp;</i></td>\n</tr>\n";
	
	echo "<tr>\n<td colspan='3' align='center' width='570px'>&nbsp;</td>\n</tr>\n";
	
	echo "<tr>\n<td colspan='3' align='center' width='570px'><input type='submit' name='ts3save' value='Speichern' class='button'></td>\n</tr>\n</table>\n</form>";
 
closetable();

opentable("Aktuelle Einstellung");

$result = dbquery("SELECT * FROM ".DB_TS3C."");
	while($data = dbarray($result)){
	
	echo "<table align='center' cellspacing='1' cellpadding='1' width='100%'>\n<tr>\n";
	echo "<td class='tbl2' width='10%' align='left'><b>IP</b></td>\n";
	echo "<td class='tbl2' width='10%' align='left'><b>Port</b></td>\n";
	echo "<td class='tbl2' width='25%' align='left'><b>Telnet</b></td>\n";
	echo "<td class='tbl2' width='25%' align='left'><b>Banner</b></td>\n";
	echo "<td class='tbl2' width='25%' align='left'><b>Statistik</b></td>\n";
	echo "<td class='tbl2' width='25%' align='left'><b>Legenge</b></td>\n";
	echo "<td class='tbl2' width='25%' align='left'><b>User</b></td>\n";	;
	echo "</tr>\n";	
	
	echo "<tr>\n";
	echo "<td class='tbl' align='left'>".$data['ip']."</td>\n";		
	echo "<td class='tbl' align='left'>".$data['port']."</td>\n";		
	echo "<td class='tbl' align='left'>".$data['telnet']."</td>\n";		
echo "<td class='tbl' align='left'>"; if ($data['banner'] == "0") { echo "Aus"; } else { echo "An"; } echo "</td>\n";;	
echo "<td class='tbl' align='left'>"; if ($data['stats'] == "0") { echo "Aus"; } else { echo "An"; } echo "</td>\n";	
echo "<td class='tbl' align='left'>"; if ($data['legend'] == "0") { echo "Aus"; } else { echo "An"; } echo "</td>\n";	
echo "<td class='tbl' align='left'>"; if ($data['useron'] == "0") { echo "Aus"; } else { echo "An"; } echo "</td>\n";	
	echo "</tr>";
	echo "</table>";
	}
closetable();
?>		