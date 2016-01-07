<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (c)2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: functions.inc.php
| pr_Bewerbungsscript v2.00
| Author: PrugnatoR
| URL: http://www.prugnator.de
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

if (!defined("IN_FUSION")) { die("Access Denied"); }

include_once INFUSIONS."pr_bewerbung/includes/version.inc.php";
		
include_once INFUSIONS."pr_bewerbung/includes/panel_func.inc.php";

// Define Constant
define("PR_BEWERBUNG", INFUSIONS."pr_bewerbung/");
define("PR_BEWERBUNG_ADMIN", INFUSIONS."pr_bewerbung/admin");
define("PR_BEWERBUNG_IMG", INFUSIONS."pr_bewerbung/admin/images");

// Variablenfestlegung
$test="test"; // for switch_site				

// Spamschutz
$number1 = rand(1,15);
$number2 = rand(1,15);

// pr AntiSpam v2.5 (not anymore used yet)
function makeAntiSpam(){
	global $number1, $number2;
	if($number1<$number2){
	$ergebnis = $number1 + $number2;
	$operator = "+";
	}else{
	$num = rand(1,2);
	if ($num == "1"){
	$ergebnis = $number1 - $number2;
	$operator = "-";
	}else{
	$ergebnis = $number1 + $number2;
	$operator = "+";
	}
	}
	echo '<tr>
		<td>'.$number1.$operator.$number2.' =<span style="color:#ff0000">*</span> </td>
		<td><input type="text" name="spam" size="24" class="textbox">
		<input type="hidden" name="erg" value="'.$ergebnis.'"></td>
	</tr>';
}

// CSS-Class from zWar by zezoar
?>
<style type="text/css">
<!--
.infopopup {background-color:#FFF4CC; position:absolute; display:none; padding:2px; padding-left:5px; padding-right:5px; border:1px solid #BBBBBB; font-family:Verdana, Arial, Times New Roman; width:300px; margin-top:10px; text-align:center; color:#000000;}
-->
</style>
<?php


// Make Admintable
	function table_actions($id, $type){
		global $aidlink,$locale;
		$menu = "";
		if ($type == "2"){
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;del=".$id."'><img src='".INFUSIONS."pr_bewerbung/admin/images/delete_cross.gif' border='0' alt='' /> ".$locale['pr_b065']."</a><br /> ";
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;ein=".$id."'>".$locale['pr_b066']."</a><br /> ";
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;board&amp;id=".$id."'>".$locale['pr_b068']."</a><br /> ";
			$menu .= THEME_BULLET." <a href='#' onclick=\"popup=window.open('".INFUSIONS."pr_bewerbung/admin/comment.php".$aidlink."&amp;id=".$id."','popup','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=550,height=550,left=50,top=50'); return false;\" />".$locale['pr_b069']."</a> ";
		}elseif ($type == "3"){
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;del=".$id."'><img src='".INFUSIONS."pr_bewerbung/admin/images/delete_cross.gif' border='0' alt='' /> ".$locale['pr_b065']."</a><br /> ";
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;bea=".$id."'>".$locale['pr_b064']."</a> ";
					
		}elseif ($type == "4"){
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;del_ssa=".$id."' onclick=\"return confirm('Wirklich l&ouml;schen?');\"><img src='".INFUSIONS."pr_bewerbung/admin/images/delete_cross.gif' border='0' alt='' /> ".$locale['pr_b067']."</a><br /> ";
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;bea=".$id."'>".$locale['pr_b064']."</a> ";
					
		}else{
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;del=".$id."'><img src='".INFUSIONS."pr_bewerbung/admin/images/delete_cross.gif' border='0' alt='' /> ".$locale['pr_b065']."</a><br /> ";
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;bea=".$id."'>".$locale['pr_b064']."</a><br /> ";
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;ein=".$id."'>".$locale['pr_b066']."</a><br /> ";
			$menu .= THEME_BULLET." <a href='".INFUSIONS."pr_bewerbung/admin/index.php".$aidlink."&amp;board&amp;id=".$id."'>".$locale['pr_b068']."</a><br /> ";
			$menu .= THEME_BULLET." <a href='#' onclick=\"popup=window.open('".INFUSIONS."pr_bewerbung/admin/comment.php".$aidlink."&amp;id=".$id."','popup','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=550,height=550,left=50,top=50'); return false;\" />".$locale['pr_b069']."</a> ";
		
		}
		
		return $menu;
	}

	function make_table($id, $type = "1") {
			global $data,$locale;
				echo "<!-- Form - $id -->\n
				<center>";
				if (isset($data['pr_bname']) && isset($data['pr_als'])){
					echo "<b><u>".$data['pr_bname'].$locale['pr_b060'].$data['pr_als'].$locale['pr_b060a']."</u></b>";
				}
				echo "<table border='1' cellspacing='1' cellpadding='4' align='center'>";
				$times = $data['pr_date'];
				$datum = date('d.m.Y - H:i', $times);
				echo "
				<tr>
					<td width='100'>".$locale['pr_b053']." </td>
					<td width='300'>&nbsp;".nl2br($data['pr_comment'])."</td>
				</tr>
				<tr>
					<td width='100'>".$locale['pr_b050']." </td>
					<td width='300'>&nbsp;".$datum."</td>
				</tr>
				<tr>
					<td width='100'>".$locale['pr_b051']." </td>
					<td width='300'>&nbsp;".$data['pr_ip']."</td>
				</tr>";
				if ($type == "4"){
					echo "<tr>
						<td width='100'>".$locale['pr_b052']." </td>
						<td width='300'>&nbsp;".$data['pr_by']."</td>
					</tr>";
				}
				
				$result2 = dbquery("SELECT * FROM ".DB_PREFIX."form_fields WHERE pr_toform = '1'");
				
				while ($data2 = dbarray($result2)){
						$value_norm = $data['pr_'.$data2['pr_name']];
						$value = pr_chars(htmlspecialchars($value_norm,ENT_QUOTES));
						echo "<tr>
							<td width='100'>".$data2['pr_desc']." </td>
							<td width='300'>&nbsp;".preg_replace("/&#039;/","", $value)."</td>
						</tr>";
						
				}
				
				echo "<tr>
					<td width='100'>".$locale['pr_b059']." </td>
					<td width='300'>".table_actions($data['pr_id'], $type)."</td>
				</tr></table><br /></center><hr />";	
	}


// Function for sending PM's

	function pm_send($pmtoid, $subject, $pmfromid, $pmfromname, $message){
		$pmtoid = stripinput($pmtoid);
		$subject = stripinput($subject);
		$pmfromid = stripinput($pmfromid);
		$pmfromname = stripinput($pmfromname);
		$message = descript($message);
		dbquery("INSERT INTO ".DB_MESSAGES." (message_id, message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES ('', '".$pmtoid."', '".$pmfromid."', '".$subject."', '".$message."', '0', '0', '".time()."', '0')");
	}
	
// Change some specialchars to HTML

	function pr_chars($text){
		$text = str_replace ("�", "&auml;", $text);
		$text = str_replace ("�", "&Auml;", $text);
		$text = str_replace ("�", "&ouml;", $text);
		$text = str_replace ("�", "&Ouml;", $text);
		$text = str_replace ("�", "&uuml;", $text);
		$text = str_replace ("�", "&Uuml;", $text);
		$text = str_replace ("�", "&szlig;", $text);
		return $text;
	}


/* ++++++++++++++++++++++++
// pr_Protect Script v1.51
// ------------------------
// Author: 		Christian "PrugnatoR" Weber
// (c) 2007-2010 by Christian Weber
// Main Code by Mathias Kannegiesser
// ------------------------
// EMail: 				admin@prugnator.de
// Web: 				http://www.prugnator.de
// ++++++++++++++++++++++++ */

// Simple Version (for easy variables)
function pr_save($wert)
{
if (!empty($wert)) {
    // ݢerfl𳳩ge Maskierungen aus der
   // 𢥲gebenen Variable entfernen
    if (get_magic_quotes_gpc()) 
	{
        $wert = stripslashes($wert);
    }
  
	//HTML- und PHP-Code entfernen.
	$wert = strip_tags($wert);
	//Sonderzeichen in
	//HTML-Schreibweise umwandeln
	$wert = htmlspecialchars($wert,ENT_QUOTES);
	//Entfernt 𢥲fl𳳩ge Zeichen
	//Anfang und Ende einer Zeichenkette
	$wert = trim($wert);

    // ݢergebenen Variablewert in Anf𨲵ngszeichen
    // setzen, sofern  keine Zahl oder ein
    // numerischer String vorliegt
    if (!is_numeric($wert)) 
	{
       		 $wert =  mysql_real_escape_string($wert);
   	 }
	
 }
    return $wert;
}

// Erweiterte Version (f𲠥infache Variaben und Arrays)
// Hinweis: Durch die Rekursion ist diese Funktion etwas langsamer!
function pr_save_ext($wert)
{
if (!empty($wert)) {
   if( is_array($wert) ) 
   {
       return array_map("save_ext", $wert);
   } 
   else 
   {
   	   // ݢerfl𳳩ge Maskierungen aus der
	   // 𢥲gebenen Variable entfernen
       if( get_magic_quotes_gpc() ) 
	   {
           $wert = stripslashes($wert);
       }

	//HTML- und PHP-Code entfernen.
	$wert = strip_tags($wert);
	//Sonderzeichen in
	//HTML-Schreibweise umwandeln
	$wert = htmlentities($wert,ENT_QUOTES);
	//Entfernt 𢥲fl𳳩ge Zeichen
	//Anfang und Ende einer Zeichenkette
	$wert = trim($wert);

     // Ubergebene Variblenwert, welche einen Leer 
    // String besitzen, werden durch ein NULL ersetzt
       if( $wert == '' ) 
	   {
           $wert = 'NULL';
       } 
	   	
 }
       return $wert;
   }
}

// ------------------------------------------------------------------------ //

// Admin switch site (AJAX)
 ?>

   <script type="text/javascript">
         <!--    
             function switch_site(test){

	document.getElementById("eins").innerHTML = waitText;;
	            
                 //erstellen des requests
                 var req = null;

	

                try{
                    req = new XMLHttpRequest(); // Mozilla, Opera und Co
                }
                catch (ms){
                    try{
                        req = new ActiveXObject("Msxml2.XMLHTTP"); // ie5
                    } 
                    catch (nonms){
                        try{
                            req = new ActiveXObject("Microsoft.XMLHTTP"); // ie6
                        } 
                        catch (failed){
                            req = null;
                        }
                    }  
                }
	
                if (req == null)
                      alert("Error creating request object!");
                  
                  //anfrage erstellen (GET, url ist localhost,
                  //request ist asynchron      
                  req.open("GET", test, true);

                //Beim abschliessen des request wird diese Funktion ausgef𨲴
                req.onreadystatechange = function(){            
                    switch(req.readyState) {
                            case 4:
                            if(req.status!=200) {
                                alert("Fehler:"+req.status); 
                            }else{    
                                //alert(req.responseText);
                                //schreibe die antwort in den div container mit der id content 
                                document.getElementById('eins').innerHTML = req.responseText;
                            }
                            break;
                    
                            default:
                                return false;
                            break;     
                        }
                    };
  
                  req.setRequestHeader("Content-Type",
                                      "application/x-www-form-urlencoded");
                req.send(null);
            }
         //-->
        </script>
