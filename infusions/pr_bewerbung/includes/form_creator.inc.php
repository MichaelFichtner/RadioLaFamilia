<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: includes/form_creator.inc.php
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

function form_daten() {
  if (isset($_POST)) {
    foreach ($_POST as $key => $element) {
      echo "<input type=\"hidden\" name=\"$key\" value=\"$element\">\n";
    }
  }else {
    foreach ($_GET as $key => $element) {
      echo "<input type=\"hidden\" name=\"$key\" value=\"$element\">\n";
    }
  }
}

function num_generate($length) {
$chars_for_pw  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$chars_for_pw .= "abcdefghijklmnopqrstuvwxyz";
$chars_for_pw .= "0123456789";
$char_control  = "";
        srand((double) microtime() * 1000000);
        for($i = 0;$i < 50;$i++) {
            $number = rand(2, strlen($chars_for_pw)-2);
            $char_control .= $chars_for_pw[$number];
        }
        $char_control = substr($char_control, 0, $length);
        return $char_control;
}
$num=num_generate(10);

// OnChange Function
?>
 <script type="text/javascript">
         <!--    
             function switch_form(goto){

	if(goto != ""){
		document.getElementById("inputfull").innerHTML = waitText;;
	}
		            
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
                  req.open("GET", goto, true);

                //Beim abschliessen des request wird diese Funktion ausgeführt
                req.onreadystatechange = function(){            
                    switch(req.readyState) {
                            case 4:
                            if(req.status!=200) {
                                alert("Fehler:"+req.status); 
                            }else{    
                                //alert(req.responseText);
                                //schreibe die antwort in den div container mit der id content 
                                document.getElementById('inputfull').innerHTML = req.responseText;
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