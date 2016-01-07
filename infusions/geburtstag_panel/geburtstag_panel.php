<?

/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------
| Geburtstag Panel v.1.0
| Copyright by Johnny (johnny@erazorbots.de) 
---------------------------------------------------*/

$thisdatum = "____-".date("m")."-__"; // teilweise übernommen vom birthday_panel !

if (date("m") == "01") { $monat = "im Januar"; }
elseif (date("m") == "02") { $monat = "im Februar"; }
elseif (date("m") == "03") { $monat = "im März"; }
elseif (date("m") == "04") { $monat = "im April"; }
elseif (date("m") == "05") { $monat = "im Mai"; }
elseif (date("m") == "06") { $monat = "im Juni"; }
elseif (date("m") == "07") { $monat = "im Juli"; }
elseif (date("m") == "08") { $monat = "im August"; }
elseif (date("m") == "09") { $monat = "im September"; }
elseif (date("m") == "10") { $monat = "im Oktober"; }
elseif (date("m") == "11") { $monat = "im November"; }
elseif (date("m") == "12") { $monat = "im Dezember"; }


$result = mysql_query("SELECT user_id, user_name, user_birthdate FROM ".$db_prefix."users WHERE user_birthdate like '$thisdatum' ORDER BY user_birthdate DESC");

openside ("Geburtstage {$monat}");

if (mysql_num_rows($result) == 0) {

echo "Keine Geburtstage {$monat}";

}

while ($row = mysql_fetch_array($result)) {

$datum = explode ("-", $row[user_birthdate]);

if ($datum[2] == date("d")) { $geschenk = "<img src=\"".IMAGES."geburtstag.gif\">\n"; } else { $geschenk = ""; }

$thismarktime = time() - mktime(0,0,0,$datum['1'],$datum['2'],$datum['0']);
$thismarktimee = date("Y",$thismarktime) - 1970;

if ($datum[2] > date("d")) { $thismarktimee = $thismarktimee + 1; } else { $thismarktimee = $thismarktimee; }

echo $geschenk;
echo $datum[2].".".$datum[1]."&nbsp;";
echo "<a href=\"".BASEDIR."profile.php?lookup=".$row[user_id]."\">".$row[user_name]."</a>";
echo "&nbsp;<br>";
//echo "&nbsp;(<b>".$thismarktimee."</b>)<br>";

}

closeside();



?>
