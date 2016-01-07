
<?php
	#####################################################
	#####################################################
	#####                                           #####
	#####      PCPIN Gehenna Chatlog v1.2 BETA      #####
	#####            for PCPIN Chat v6.20           #####
	#####    code & design by Telcontar @ Gehenna   #####
	#####                                           #####
	##### (c) copyright 2008 by Telcontar @ Gehenna #####
	#####                 & Mavo460                 #####
	#####                                           #####
	#####################################################
	#####################################################
	#####                                           #####
	#####              version 1.2 BETA             #####
	#####            with help of Mavo460           #####
	#####  security script & special character fix  #####
	#####               user selection              #####
	#####          based on Mavo460's code          #####
	#####                                           #####
	#####                  Mavo460                  #####
	#####      member of PCP!N Community Board      #####
	#####                                           #####
	#####################################################
	#####################################################
	
	
	// EINSTELLUNGEN
		$MYSQL_HOST = "localhost";	// Hier die MySQL Serveradresse angeben ( Standard: localhost )
		$MYSQL_USER = "web0";		// Hier deinen MySQL-DB Benutzeraccount angeben
		$MYSQL_PW = "xe5Ld3SE";	// Hier dein MySQL-DB Benutzerpasswort angeben
		$MYSQL_DB = "usr_web0_2";	// Hier die MySQL Datenbank angeben
		$TABLE_PREFIX = "pcpin_";	// Hier den Prefix der Tabellen angeben ( Standard: pcpin_ )
		
		error_reporting(E_ALL);
	
	// MySQL DB Verbindung aufbauen
		$conn = mysql_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PW); 
		mysql_select_db($MYSQL_DB, $conn);
		
	// Prüfen ob die übertragene Session-ID zu einem User mit Admin-Rechten gehört
		if ( isset($_GET['s_id']) ) {
			$s_id = $_GET['s_id'];
		} else {
			die( "<b>Fehler: Keine gültige SessionID übergeben!</b><br />Die Version 1.2 der PCPIN Gehenna Chatlog kann nur dann funktionieren, wenn eine gültige Session ID vom Chat übergeben wird, beachte die Anleitung.");
		}
		$result = mysql_query("SELECT `is_admin` FROM `".$TABLE_PREFIX."session`, `pcpin_user` WHERE ".$TABLE_PREFIX."session._s_id = '".$s_id."' AND _s_user_id = ".$TABLE_PREFIX."user.id AND ".$TABLE_PREFIX."user.is_admin = 'y' ORDER BY id LIMIT 1");
		$row = mysql_fetch_assoc($result);
		$is_admin = htmlspecialchars($row['is_admin']);
		if( $is_admin != 'y' ) {
			die( "<b>Fehler: Keine g&uuml;ltige SessionID &uuml;bergeben!</b><br />Der Benutzer muss im Chat eingeloggt und Admin sein.");
		} else {
		
	// Chatroom ID ermitteln
		if( isset($_GET['raum']) && $_GET['raum'] > 0 ) {
			$room_query = mysql_query("
									SELECT `id`, `name`
									FROM `".$TABLE_PREFIX."room`
									WHERE `id` = '".$_GET['raum']."'
									LIMIT 1
								") or die( mysql_error() );
			$room_result = mysql_fetch_array($room_query) or die( mysql_error() );
			$chatroom_ID = $room_result["id"];
			$chatroom_name = $room_result["name"];
			$log_user = "";
		} else {
			$firstroom_query = mysql_query("
									SELECT `id`, `name`
									FROM `".$TABLE_PREFIX."room`
									WHERE `type` = 'p'
									ORDER BY `listpos` ASC
									LIMIT 1
								") or die( mysql_error() );
			$firstroom_result = mysql_fetch_array($firstroom_query) or die( mysql_error() );
			$chatroom_ID = $firstroom_result["id"];
			$chatroom_name = $firstroom_result["name"];
			$log_user = "";
		}

	// User ID´s ermitteln
		if( isset($_GET['user']) && $_GET['user'] > 0 ) {
			$user_query = mysql_query("
									SELECT `id`, `login`
									FROM `".$TABLE_PREFIX."user`
									WHERE `id` = '".$_GET['user']."'
									LIMIT 1
								") or die( mysql_error() );
			$user_result = mysql_fetch_array($user_query) or die( mysql_error() );
			$user_ID = $user_result["id"];
			$user_name = $user_result["login"];
		} else {
			$user_ID = 0;
		}
	 
	 
	// Legt fest, welche Nachrichtentypen ausgelesen werden sollen
	if( $user_ID > 0 ) {
		$log_where = "WHERE
						(
							`author_id` = '".$user_ID."'
						OR
							`target_user_id` = '".$user_ID."'
						)";
	} else {
		$log_where = "WHERE
						(
							`target_room_id` = '".$chatroom_ID."'
						AND
							`type` IN  (3001,111,115,10101,10105,10110,10111)
						)
					OR
						(
							`privacy` = '2'
						AND
							`type` IN (3001)
						)
					OR
						(
							`room_id` = '".$chatroom_ID."'
						AND
							`type` IN (10107)
						)";
	}
	 
	 
	// Anzahl der Zeilen in der Log
		$sql_rows = "SELECT
						COUNT(`message_id`)
					FROM
						`".$TABLE_PREFIX."message_log`
					".$log_where;
		$query_rows = mysql_query($sql_rows) or die(mysql_error());
		$rows_result = mysql_fetch_row($query_rows);
		$rows = $rows_result[0];
	// Zeilen pro Seite
		if(isset($_GET['zeilen']) && $_GET['zeilen'] > 0) {
			$zeilen = $_GET['zeilen'];
		} else {
			$zeilen = 100;	
		}
		
	// Anzahl der Seiten in der Log
		if (round($rows/$zeilen) < ($rows/$zeilen)) {
			$seiten = round($rows/$zeilen) +1;
		} else {
			$seiten = round($rows/$zeilen);
		}
		
	// Aktuelle Seite
		if(isset($_GET['seite']) && $_GET['seite'] > 0 && $_GET['seite'] <= $seiten) {
			$seite = $_GET['seite'];
		} else {
			$seite = $seiten;

		}
		
	// Startpunkt der Auslese
		$start = ($seite-1) * $zeilen;
		
	// Limit der Auslese
		if ($start == "-100") {$start = "0";} // falls kein Inhalt dann ist Limit 0
		$limit = "LIMIT ".$start.",".$zeilen;
		
	// logfix_umlaute() korrigiert die fehlerhafte Anzeige mancher Umlaute und Sonderzeichen
	// $falsch enthält die Sonderzeichen, wie sie falsch ausgegeben werden und
	// $richtig die Sonderzeichen, die eigentlich ausgegeben werden sollen.
	// Beide müssen unbedingt in gleicher Reihenfolge sein!
		function logfix_umlaute($body) {
			$falsch = array(	"Ã¤",	"Ã¶",	"Ã¼",	"Ã„",	"Ã–",	"Ãœ",	"ÃŸ",	"Â§",	"Â´",	"Ã²",	"Ã³",	"Ã’",	"Ã“");
			$richtig = array(	"ä",	"ö",	"ü",	"Ä",	"Ö",	"Ü",	"ß",	"§",	"´",	"ò",	"ó",	"Ò",	"Ó");
			$body = str_replace($falsch,$richtig,$body);
			return $body;
		}
	
	
		
	// Benötigte PCPIN Funktionen aus dem PCPIN Quellcode entnommen:
		function _pcpin_strlen() {
			$args=func_get_args();
			return call_user_func_array('strlen', $args);
		}
	
		function coloredToPlain($nickname='', $escape_html_chars=true) {
			$plain='';
			if ($nickname!='') {
				$parts=explode('^', $nickname);
				if (!isset($parts[1])) {
					$plain=$parts[0];
				} else {
					foreach ($parts as $part) {
						if (_pcpin_strlen($part)>6) {
							$plain.=substr($part, 6);
						} elseif (_pcpin_strlen($part)<6) {
							$plain.=$part;
						}
					}
				}
					}
			if ($escape_html_chars) {
				 $plain=htmlspecialchars($plain);
			}
			return $plain;
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PCPIN Gehenna Chatlog v1.2 BETA by Telcontar@Gehenna & Mavo460</title>
<style type="text/css">
<!--
a:link, a:visited, a:active, a:hover {
	color: #FF4400;
	text-decoration: none;
}
table {
	border: 1px solid black;
	background-color: #CCCCCC;
	color: #000000;
	font-family: Verdana;
	font-size: 12px;
}
.headline {
	font-family: Geneva;
	font-size: 28px;
	font-style: italic;
	font-weight: bold;
	text-align: center;
	background-color: #666666;
	color: #FFFFFF;
}
.title {
	font-family: Geneva;
	font-size: 20px;
	font-weight: bold;
	text-align: center;
	color: #333333;
	outline-color: #000000;
}
.author {
	font-family: Verdana;
	font-size: 9px;
	text-align: center;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #000000;
	background-color: #666666;
}
.copyright {
	font-family: Tahoma;
	font-size: 9px;
	color: #666666;
	text-align: center;
}
.form {
	font-family: Verdana;
	font-size: 12px;
}
-->
</style>
</head>

<body>
<table width="95%" border="0" cellpadding="5" cellspacing="0" align="center">
	<tr>
		<td colspan="6" class="headline">PCPIN Gehenna Chatlog </td>
	</tr>
	<tr>
		<td colspan="6" class="author">v1.2 BETA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;by Telcontar@Gehenna & Mavo460</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" enctype="application/x-www-form-urlencoded" name="log_room" target="_self">
				Raum:  <select name="raum" class="form">
				
				<?php
					$rooms_query = mysql_query("
											SELECT `id`, `name`
											FROM `".$TABLE_PREFIX."room`
											WHERE `type` = 'p'
											ORDER BY `listpos` ASC
										") or die( mysql_error() );
										
					if ($user_ID > 0) {
						echo "<option value='0' selected='selected'>Bitte auswählen</option>";
						while( $rooms_result = mysql_fetch_array($rooms_query) ) {
							echo "<option value='".$rooms_result["id"]."'>".$rooms_result["name"]."</option>";
						}
					} else {
						while( $rooms_result = mysql_fetch_array($rooms_query) ) {
							echo "<option value='".$rooms_result["id"]."'";
							if( $chatroom_ID == $rooms_result["id"] ) {
								echo " selected='selected'";
							}
							echo ">".$rooms_result["name"]."</option>";
						}
					}
				?>
				
				</select> <input type="submit" value="ok" class="form" />
				<input name="zeilen" type="hidden" value="<?php echo $zeilen; ?>" />
				<input name="s_id" type="hidden" value="<?php echo $s_id; ?>" />
			</form>
		</td>
		<td colspan="2" align="right">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" enctype="application/x-www-form-urlencoded" name="log_user" target="_self">
				Benutzer: <select name="user" class="form">
				
				<?php
					$users_query = mysql_query("
											SELECT `id`, `login`
											FROM `".$TABLE_PREFIX."user`
											WHERE `activated` = 'y'
											ORDER BY `id` ASC
										") or die( mysql_error() );
										
					if ($user_ID == 0) {
						echo "<option value='0' selected='selected'>Bitte auswählen</option>";
						while( $users_result = mysql_fetch_array($users_query) ) {
							echo "<option value='".$users_result["id"]."'>".$users_result["login"]."</option>";
						}
					} else {
						while( $users_result = mysql_fetch_array($users_query) ) {
							echo "<option value='".$users_result["id"]."'";
							if( $user_ID == $users_result["id"] ) {
								echo " selected='selected'";
							}
							echo ">".$users_result["login"]."</option>";
						}
					}
				?>
				
				</select> <input type="submit" value="ok" class="form" />
				<input name="zeilen" type="hidden" value="<?php echo $zeilen; ?>" />
				<input name="s_id" type="hidden" value="<?php echo $s_id; ?>" />
			</form> 
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" enctype="application/x-www-form-urlencoded" name="go_to" target="_self">
				<input name="raum" type="hidden" value="<?php echo $chatroom_ID; ?>" />
				<input name="user" type="hidden" value="<?php echo $user_ID; ?>" />
				Gehe zu Seite: <input name="seite" value="<?php echo $seite; ?>" type="text" size="5" class="form" /> <input type="submit" value="ok" class="form" />
				<input name="zeilen" type="hidden" value="<?php echo $zeilen; ?>" />
				<input name="s_id" type="hidden" value="<?php echo $s_id; ?>" />
			</form>
		</td>
		<td colspan="2" align="right">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" enctype="application/x-www-form-urlencoded" name="set_rows" target="_self">
				<input name="raum" type="hidden" value="<?php echo $chatroom_ID; ?>" />
				<input name="user" type="hidden" value="<?php echo $user_ID; ?>" />
				Zeilen pro Seite: <input name="zeilen" value="<?php echo $zeilen; ?>" type="text" size="5" class="form" /> <input type="submit" value="ok" class="form" />
				<input name="s_id" type="hidden" value="<?php echo $s_id; ?>" />
			</form>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6" class="copyright">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6" class="title"><?php echo $chatroom_name; ?></td>
	</tr>
	<tr>
		<td colspan="6" class="copyright">&nbsp;</td>
	</tr>
	<tr>
		<td width="100">
			<?php
				if( $seite > 1 ) {
					echo "<a href='log.php?raum=".$chatroom_ID."&amp;user=".$user_ID."&amp;seite=1&amp;zeilen=".$zeilen."&amp;s_id=".$s_id."'>&laquo; Erste Seite</a>";
				} else {
					echo "&laquo; Erste Seite";
				}
			?>
		</td>
		<td width="100">
			<?php
				if( $seite > 1 ) {
					echo "<a href='log.php?raum=".$chatroom_ID."&amp;user=".$user_ID."&amp;seite=".($seite-1)."&amp;zeilen=".$zeilen."&amp;s_id=".$s_id."'>&lt; Vorige Seite</a>";
				} else {
					echo "&lt; Vorige Seite";
				}
			?>
		</td>
		<td colspan="2" align="center">
			<?php
				echo "Seite <b>".$seite."</b> von <b>".$seiten."</b> Seiten.";
			?></td>
		<td width="100" align="right">
			<?php
				if( $seite < $seiten ) {
					echo "<a href='log.php?raum=".$chatroom_ID."&amp;user=".$user_ID."&amp;seite=".($seite+1)."&amp;zeilen=".$zeilen."&amp;s_id=".$s_id."'>&gt; N&auml;chste Seite</a>";
				} else {
					echo "&gt; N&auml;chste Seite";
				}
			?>
		</td>
		<td width="100" align="right">
			<?php
				if( $seite < $seiten ) {
					echo "<a href='log.php?raum=".$chatroom_ID."&amp;user=".$user_ID."&amp;seite=".$seiten."&amp;zeilen=".$zeilen."&amp;s_id=".$s_id."'>&raquo; Letzte Seite</a>";
				} else {
					echo "&raquo; Letzte Seite";
				}
			?>
		</td>
	</tr>
	<tr>
		<td colspan="6">
<?php
	$query = mysql_query(	"SELECT
								`type`,
								`date`,
								`author_nickname`,
								`target_user_nickname`,
								`body`,
								`privacy`
							FROM
								`".$TABLE_PREFIX."message_log`
							".$log_where."
							ORDER BY
								date ASC
							".$limit) or die( mysql_error() );
	
	$row_count = mysql_num_rows($query) or 0;
	print mysql_error();
	$i = 1;
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='3'>";
	while($row = mysql_fetch_assoc($query)) {
		switch( $row["type"] ) {
			case "3001":
				if($i == $row_count) {
					$style = "text-align: left; border-top: 1px dotted #666666;";
				} else {
					$style = "text-align: left; border-top: 1px dotted #666666; border-bottom: 1px solid black;";
				}
				
				
				$autor = coloredToPlain($row["author_nickname"]);
								
				if( $row["privacy"] == 0 && $row["target_user_nickname"] != "" ) {
					$ziel = coloredToPlain($row["target_user_nickname"]);
					$modus = "sagt zu";
				} elseif($row["privacy"] == 1 && $row["target_user_nickname"] != "" ) {
					$ziel = coloredToPlain($row["target_user_nickname"]);
					$modus = "flüstert zu";
				} elseif($row["privacy"] == 2 && $row["target_user_nickname"] != "" ) {
					$ziel = coloredToPlain($row["target_user_nickname"]);
					$modus = "per PN an";
				} else {
					$ziel = "&nbsp;";
					$modus = "&nbsp;";
				}
				echo "<tr>";
				echo "<td align='left' width='200px' bgcolor='#999999'><b> ".$autor."</b></td>";
				echo "<td align='left' width='75px' bgcolor='#999999'>".$modus."</td>";
				echo "<td align='left' width='100px' bgcolor='#999999'><b>".$ziel."</b></td>";
				echo "<td align='left' bgcolor='#999999'>".$row["date"]."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td colspan='4' style='".$style."'>".logfix_umlaute(nl2br($row["body"]))."</td>";
				echo "</tr>";
				break;
			case "111":
				if($i == $row_count) {
					$style = "text-align: left; background-color: #669966;";
				} else {
					$style = "text-align: left; border-bottom: 1px solid black; background-color: #669966;";
				}
				$row_body = explode("/", $row["body"]);
				$get_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[0]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($get_user_query) == 1 ) {
					$get_user = mysql_fetch_array($get_user_query);
				} else {
					$get_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				echo "<tr>";
				echo "<td style='".$style."' colspan='3'><b>&raquo;</b>&nbsp;".$get_user["nickname_plain"]." hat den Raum betreten.</td>";
				echo "<td style='".$style."'>".$row["date"]."</td>";
				echo "</tr>";
				break;
			case "115":
				if($i == $row_count) {
					$style = "text-align: left; background-color: #996666;";
				} else {
					$style = "text-align: left; border-bottom: 1px solid black; background-color: #996666;";
				}
				$row_body = explode("/", $row["body"]);
				$get_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[0]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($get_user_query) == 1 ) {
					$get_user = mysql_fetch_array($get_user_query);
				} else {
					$get_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				echo "<tr>";
				echo "<td style='".$style."' colspan='3'><b>&raquo;</b>&nbsp;".$get_user["nickname_plain"]." hat den Raum verlassen.</td>";
				echo "<td style='".$style."'>".$row["date"]."</td>";
				echo "</tr>";
				break;
			case "10101":
				if($i == $row_count) {
					$style = "text-align: left; background-color: #996666; border-top: 1px dotted black;";
				} else {
					$style = "text-align: left; background-color: #996666; border-top: 1px dotted black; border-bottom: 1px solid black;";
				}
				$row_body = explode("/", $row["body"]);
				$kicked_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[0]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($kicked_user_query) == 1 ) {
					$kicked_user = mysql_fetch_array($kicked_user_query);
				} else {
					$kicked_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				$kicking_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[1]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($kicking_user_query) == 1 ) {
					$kicking_user = mysql_fetch_array($kicking_user_query);
				} else {
					$kicking_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				echo "<tr>";
				echo "<td style='text-align: left; background-color: #994444;' colspan='3'><b>&raquo;</b>&nbsp;".$kicked_user["nickname_plain"]." wurde von ".$kicking_user["nickname_plain"]." gekickt.</td>";
				echo "<td style='text-align: left; background-color: #994444;'>".$row["date"]."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td style='".$style."' colspan='4'>Grund: ".logfix_umlaute($row_body[2])."</td>";
				echo "</tr>";
				break;
			case "10105": // Ban
				if($i == $row_count) {
					$style = "text-align: left; background-color: #996666; border-top: 1px dotted black;";
				} else {
					$style = "text-align: left; background-color: #996666; border-top: 1px dotted black; border-bottom: 1px solid black;";
				}
				$row_body = explode("/", $row["body"]);
				$banned_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[0]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($banned_user_query) == 1 ) {
					$banned_user = mysql_fetch_array($banned_user_query);
				} else {
					$banned_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				$banning_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[1]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($banning_user_query) == 1 ) {
					$banning_user = mysql_fetch_array($banning_user_query);
				} else {
					$banning_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				echo "<tr>";
				echo "<td style='text-align: left; background-color: #994444;' colspan='3'><b>&raquo;</b>&nbsp;".$banned_user["nickname_plain"]." wurde von ".$banning_user["nickname_plain"]." gebannt.</td>";
				echo "<td style='text-align: left; background-color: #994444;'>".$row["date"]."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td style='".$style."' colspan='4'>Grund: ".logfix_umlaute($row_body[3])."<br />Zeitspanne: ".$row_body[2]." Minuten</td>";
				echo "</tr>";
				break;
			case "10107": // Unban
				if($i == $row_count) {
					$style = "text-align: left; background-color: #996666;";
				} else {
					$style = "text-align: left; border-bottom: 1px solid black; background-color: #996666;";
				}
				$row_body = explode("/", $row["body"]);
				$unbanned_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[0]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($unbanned_user_query) == 1 ) {
					$unbanned_user = mysql_fetch_array($unbanned_user_query);
				} else {
					$unbanned_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				$unbanning_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[1]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($unbanning_user_query) == 1 ) {
					$unbanning_user = mysql_fetch_array($unbanning_user_query);
				} else {
					$unbanning_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				echo "<tr>";
				echo "<td style='".$style."' colspan='3'><b>&raquo;</b>&nbsp;".$unbanned_user["nickname_plain"]." wurde von ".$unbanning_user["nickname_plain"]." entbannt.</td>";
				echo "<td style='".$style."'>".$row["date"]."</td>";
				echo "</tr>";
				break;
			case "10110": // Mute
				if($i == $row_count) {
					$style = "text-align: left; background-color: #9999cc; border-top: 1px dotted black;";
				} else {
					$style = "text-align: left; background-color: #9999cc; border-top: 1px dotted black; border-bottom: 1px solid black;";
				}
				$row_body = explode("/", $row["body"]);
				$muted_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[0]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($muted_user_query) == 1 ) {
					$muted_user = mysql_fetch_array($muted_user_query);
				} else {
					$muted_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				$muting_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[1]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($muting_user_query) == 1 ) {
					$muting_user = mysql_fetch_array($muting_user_query);
				} else {
					$muting_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				echo "<tr>";
				echo "<td style='text-align: left; background-color: #7777cc;' colspan='3'><b>&raquo;</b>&nbsp;".$muted_user["nickname_plain"]." wurde von ".$muting_user["nickname_plain"]." stillgesetzt.</td>";
				echo "<td style='text-align: left; background-color: #7777cc;'>".$row["date"]."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td style='".$style."' colspan='4'>Grund: ".logfix_umlaute($row_body[3])."<br />Zeitspanne: ".$row_body[2]." Minuten</td>";
				echo "</tr>";
				break;
			case "10111": // Unmute
				if($i == $row_count) {
					$style = "text-align: left; background-color: #7777cc;";
				} else {
					$style = "text-align: left; border-bottom: 1px solid black; background-color: #7777cc;";
				}
				$row_body = explode("/", $row["body"]);
				$unmuteed_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[0]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($unmuteed_user_query) == 1 ) {
					$unmuteed_user = mysql_fetch_array($unmuteed_user_query);
				} else {
					$unmuteed_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				$unmuteing_user_query = mysql_query( "	SELECT `nickname_plain`
											FROM `".$TABLE_PREFIX."nickname`
											WHERE `user_id` = '".$row_body[1]."' AND `default` = 'y'
											LIMIT 1;") or die( "<font color='white'>".mysql_error()."</font>" );
				if( mysql_num_rows($unmuteing_user_query) == 1 ) {
					$unmuteing_user = mysql_fetch_array($unmuteing_user_query);
				} else {
					$unmuteing_user["nickname_plain"] = "Unbekannter Benutzer";
				}
				echo "<tr>";
				echo "<td style='".$style."' colspan='3'><b>&raquo;</b>&nbsp;".$unmuteed_user["nickname_plain"]."'s Stillsetzung wurde von ".$unmuteing_user["nickname_plain"]." aufgehoben.</td>";
				echo "<td style='".$style."'>".$row["date"]."</td>";
				echo "</tr>";
				break;
		}
		$i++;
	}
	echo "</table>";
?>
		</td>
	</tr>
	<tr>
		<td width="100">
			<?php
				if( $seite > 1 ) {
					echo "<a href='log.php?raum=".$chatroom_ID."&amp;user=".$user_ID."&amp;seite=1&amp;zeilen=".$zeilen."&amp;s_id=".$s_id."'>&laquo; Erste Seite</a>";
				} else {
					echo "&laquo; Erste Seite";
				}
			?>
		</td>
		<td width="100">
			<?php
				if( $seite > 1 ) {
					echo "<a href='log.php?raum=".$chatroom_ID."&amp;user=".$user_ID."&amp;seite=".($seite-1)."&amp;zeilen=".$zeilen."&amp;s_id=".$s_id."'>&lt; Vorige Seite</a>";
				} else {
					echo "&lt; Vorige Seite";
				}
			?>
		</td>
		<td colspan="2" align="center">
			<?php
				echo "Seite <b>".$seite."</b> von <b>".$seiten."</b> Seiten.";
			?></td>
		<td width="100" align="right">
			<?php
				if( $seite < $seiten ) {
					echo "<a href='log.php?raum=".$chatroom_ID."&amp;user=".$user_ID."&amp;seite=".($seite+1)."&amp;zeilen=".$zeilen."&amp;s_id=".$s_id."'>&gt; N&auml;chste Seite</a>";
				} else {
					echo "&gt; N&auml;chste Seite";
				}
			?>
		</td>
		<td width="100" align="right">
			<?php
				if( $seite < $seiten ) {
					echo "<a href='log.php?raum=".$chatroom_ID."&amp;user=".$user_ID."&amp;seite=".$seiten."&amp;zeilen=".$zeilen."&amp;s_id=".$s_id."'>&raquo; Letzte Seite</a>";
				} else {
					echo "&raquo; Letzte Seite";
				}
			?>
		</td>
	</tr>
	<tr>
		<td colspan="6" class="copyright">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6">
			<b><?php echo $rows; ?></b> Zeilen auf <b><?php echo $seiten; ?></b> Seiten mit <b><?php echo $zeilen; ?></b> Zeilen pro Seite.</td>
	</tr>
	<tr>
		<td colspan="6" class="copyright">&copy; Code &amp; Design by Telcontar@Gehenna 2008</td>
	</tr>
</table>
</body>
</html>
<?
	// Ende der Login-Übeprüfung
		}
?>

