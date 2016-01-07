<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: new_infusion_panel.php
| Author: MarcusG
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
// Turn off all error reporting
error_reporting(0);
// Report all PHP errors (see changelog)
//error_reporting(E_ALL);
//error_reporting(E_ALL & ~E_NOTICE);

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."mg_jquery_login_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."mg_jquery_login_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."mg_jquery_login_panel/locale/German.php";
}

include INFUSIONS."mg_jquery_login_panel/functions.php";

//////////// Konfig ////////////////////////////////////////
// Positionierung des Login-Tabs
$abstand = 0; // hier den Abstand zum rechten Fenster-Rand angeben
//////////// Konfig ////////////////////////////////////////
add_to_head("
	<!-- stylesheets -->
  	<link rel='stylesheet' href='".INFUSIONS."mg_jquery_login_panel/css/jquery_login_style.css' type='text/css' media='screen' />

  	<!-- PNG FIX for IE6 -->
  	<!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
	<!--[if lte IE 6]>
		<script type='text/javascript' src='".INFUSIONS."mg_jquery_login_panel/js/pngfix/supersleight-min.js'></script>
	<![endif]-->

    <!-- jQuery - the core -->
	<script src='".INFUSIONS."mg_jquery_login_panel/js/jquery-1.4.2.min.js' type='text/javascript'></script>
	<!-- Sliding effect -->
	<script src='".INFUSIONS."mg_jquery_login_panel/js/slide.js' type='text/javascript'></script>
	<!-- Tooltip -->
	<script src='".INFUSIONS."mg_jquery_login_panel/js/tools.tooltip-1.1.3.min.js' type='text/javascript'></script>
	<!-- Benachrichtigungen -->
	<script type='text/javascript' src='".INFUSIONS."mg_jquery_login_panel/js/jquery.ui.all.js'></script>
	<script type='text/javascript' src='".INFUSIONS."mg_jquery_login_panel/js/jquery.jgrowl.js'></script>");

$strURL = $_SERVER['PHP_SELF'];

if (!isset($_COOKIE["pm_alert"])) {
	$pm_alert = 1;
} else {
	$pm_alert = $_COOKIE["pm_alert"];
}

if (file_exists(INFUSIONS."avatar_studio/avatar_studio.php")) {
	$avatarstudio = 1;
} else {
	$avatarstudio = 0;
}

if (defined("SCORESYSTEM")) {
	$scoresystem_active = 1;
} else {
	$scoresystem_active = 0;
}
if (iMEMBER && $pm_alert == 1 && !preg_match("/usercp/",$strURL) && !preg_match("/messages/",$strURL)) {
//PN_Addon
$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");

if ($msg_count) {echo'<script type="text/javascript">

$.jGrowl.defaults.position = "center"
				$.jGrowl.defaults.closer = function() {
					console.log("Closing everything!", this);
				};

$.jGrowl("<br /><a class=\"white\" href=\"'.BASEDIR.'messages.php\"><b>'.$locale['JQL_001'].'</b></a><br /><br /><a onclick=\"pm_alert_off();\" class=\"small white\" href=\"'.FUSION_SELF.'\">'.$locale['JQL_002'].'</a>", {
	header: "'.sprintf($locale["JQL_003"], $msg_count).($msg_count == 1 ? $locale["JQL_004"] : $locale["JQL_005"]).'!",
	sticky: true,
	speed: 2000,
	easing: "easeInOutElastic",
	animateOpen: {
		height: "show"
	},
	animateClose: {
		height: "hide"
	}
});
</script>';}
}
// Submissions
if (iADMIN) {
	if (checkrights("SU")){
		if (submiss_count('a') OR submiss_count('l') OR submiss_count('n') OR submiss_count('p')) {
			// Message
			echo'<script type="text/javascript">

			$.jGrowl.defaults.position = "center"
				$.jGrowl.defaults.closer = function() {
					console.log("Closing everything!", this);
				};

			$.jGrowl("<br /><a class=\"white\" href=\"'.ADMIN.'submissions.php'.$aidlink.'\"><b>'.$locale['JQL_009'].'</b></a>:<br />'.$locale['JQL_010'].': '.submiss_count("a").'<br />'.$locale['JQL_011'].': '.submiss_count("l").'<br />'.$locale['JQL_012'].': '.submiss_count("n").'<br />'.$locale['JQL_013'].': '.submiss_count("p").'", {
				theme: "green",
				header: "'.$locale['JQL_014'].'",
				sticky: true,
				speed: 2000,
				easing: "easeInOutElastic",
				animateOpen: {
					height: "show"
				},
				animateClose: {
					height: "hide"
				}
			});
		</script>';
		}
	}
// Neue Mitglieder
	if (checkrights("M")) {
		if (nmember_count()) {
			echo'<script type="text/javascript">

			$.jGrowl.defaults.position = "center"
				$.jGrowl.defaults.closer = function() {
					console.log("Closing everything!", this);
				};

			$.jGrowl("<br /><a class=\"white\" href=\"'.ADMIN.'members.php'.$aidlink.'&amp;sortby=all&amp;status=2\"><b>'.$locale['JQL_015'].': '.nmember_count().'</b></a>", {
				theme: "blue",
				header: "'.$locale['JQL_016'].'",
				sticky: true,
				speed: 2000,
				easing: "easeInOutElastic",
				animateOpen: {
					height: "show"
				},
				animateClose: {
					height: "hide"
				}
			});
		</script>';
		
		}
	}
}
echo "<div class='hide_content' id='hide_content' style='display:none;'></div>";
echo "<div id='jqltoppanel'>
	<div id='jqlpanel'>
		<div class='jqlcontent jqlclearfix'>";
		if (iMEMBER) {
		echo "<div class='jqlcenter2'>";
		echo "<table class='jqltblcenter' cellpadding='0' cellspacing='0' width='100%'>
				<tr>
					<td rowspan='3' style='width:100px;text-align:left;vertical-align:top;padding-right:15px;'>";
					if($userdata['user_avatar']){
						echo "<a href='".BASEDIR."profile.php?lookup=".$userdata['user_id']."'><img class='avatar' src='".IMAGES."avatars/".$userdata['user_avatar']."' alt='".$locale['JQL_006']."' title='".$locale['JQL_008']."' style='width:100px;height:100px;vertical-align:top;' /></a>\n";
					} else {
						echo "<a href='".BASEDIR."profile.php?lookup=".$userdata['user_id']."'><img class='avatar' src='".INFUSIONS."mg_jquery_login_panel/images/noavatar.gif' alt='".$locale['JQL_007']."' title='".$locale['JQL_008']."' style='width:100px;height:100px;vertical-align:top;' /></a>\n";
					}
				echo "</td>";
				echo "<td id='settings_button' class='jql_button'><a href='".BASEDIR."edit_profile.php'>&nbsp;</a></td>";
					$msgcount = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0' AND message_folder='0'");
					if ($msgcount) {
						echo "<td id='new_pm_button' class='jql_button'><a id='pm_info' href='".BASEDIR."messages.php' title='".sprintf($locale['JQL_003'], $msgcount).($msgcount == 1 ? $locale['JQL_004'] : $locale['JQL_005'])."!'>&nbsp;</a></td>";
					} else {
						echo "<td id='pm_button' class='jql_button'><a id='pm_info' href='".BASEDIR."messages.php'>&nbsp;</a></td>";
					}
					echo "<td id='members_button' class='jql_button'><a href='".BASEDIR."members.php'>&nbsp;</a></td>";
					if (iADMIN) {
						echo "<td id='admin_button' class='jql_button'><a id='admin' href='".ADMIN."index.php".$aidlink."'>&nbsp;</a></td>";
					}
					echo "<td id='search_button' class='jql_button'><a href='".BASEDIR."search.php'>&nbsp;</a></td>";
					echo "<td id='logout_button' class='jql_button'><a href='".BASEDIR."setuser.php?logout=yes'>&nbsp;</a></td>
				</tr>";
				if ($avatarstudio == 1 || $scoresystem_active == 1) {
					echo "<tr>";
					if ($avatarstudio == 1) {
						echo "<td id='avatarstudio_button' class='jql_button' style='text-align:center;'><a href='".INFUSIONS."avatar_studio/avatar_studio.php'>&nbsp;</a></td>";
					}
					if ($scoresystem_active == 1) {
						if (!score_ban($userdata['user_id'])) {
							echo "<td id='score_ok_button' class='jql_button' style='text-align:center;'><a id='scoresinfo' href='".SCORESYSTEM."scoresystem.php'>&nbsp;</a></td>";
						} else {
							echo "<td id='score_not_ok_button' class='jql_button' style='text-align:center;'><a id='scoresinfo' href='".SCORESYSTEM."scoresystem.php'>&nbsp;</a></td>";
						}
					}
				}
					echo "<td id='downloads_button' class='jql_button'><a href='".BASEDIR."downloads.php'>&nbsp;</a></td>";
					echo "<td id='forum_button' class='jql_button'><a href='".FORUM."index.php'>&nbsp;</a></td>";
					echo "<td id='photo_button' class='jql_button'><a href='".BASEDIR."photogallery.php'>&nbsp;</a></td>";
					if (!iADMIN) {
						echo "<td class='jql_button'></td>";
					}
					if ($avatarstudio == 0) {
						echo "<td class='jql_button'></td>";
					}
					if ($scoresystem_active == 0) {
						echo "<td class='jql_button'></td>";
					}
					echo "<td id='submit_button' class='jql_button'><a id='submititem' href='".FUSION_SELF."'>&nbsp;</a></td>";
					echo "</tr>";
			echo "<tr style='vertical-align:bottom;'>
					<td colspan='".(iADMIN ? "6" : "5")."' style='text-align:center;padding-top:20px;'><div id='alert_button' class='jql_button_alert'>
		<a id='pm_alert' onclick=\"pm_alert_".($pm_alert == 1 ? "off" : "on")."();\" href='".FUSION_SELF."'></a>
		</div></td>
				</tr>
			</table><br />";
	// Admin-Links
	if (iADMIN) {
	echo "<div id='admin_links' class='jqltooltips'>";
	if (checkrights("N")) { echo "<b class='white'>&middot;</b>&nbsp;<a href='".ADMIN."news.php".$aidlink."'>".$locale['JQL_017']."</a><br />";}
	if (checkrights("A")) { echo "<b class='white'>&middot;</b>&nbsp;<a href='".ADMIN."articles.php".$aidlink."'>".$locale['JQL_018']."</a>";}
	if (checkrights("A") || checkrights("N")) { echo "<hr />";}
		echo ((dbcount("(admin_id)", DB_ADMIN, "admin_page='1'")) ? "<b class='white'>&middot;</b>&nbsp;<a href='".ADMIN."index.php".$aidlink."&pagenum=1'>".$locale['JQL_019']."</a><br />" : "");
		echo ((dbcount("(admin_id)", DB_ADMIN, "admin_page='2'")) ? "<b class='white'>&middot;</b>&nbsp;<a href='".ADMIN."index.php".$aidlink."&pagenum=2'>".$locale['JQL_020']."</a><br />" : "");
		echo ((dbcount("(admin_id)", DB_ADMIN, "admin_page='3'")) ? "<b class='white'>&middot;</b>&nbsp;<a href='".ADMIN."index.php".$aidlink."&pagenum=3'>".$locale['JQL_021']."</a><br />" : "");
		
		if (str_replace(".", "", $settings['version']) >= 70100) {
			echo ((dbcount("(admin_id)", DB_ADMIN, "admin_page='4'")) ? "<b class='white'>&middot;</b>&nbsp;<a href='".ADMIN."index.php".$aidlink."&pagenum=4'>".$locale['JQL_022a']."</a><br />" : "");
			echo ((dbcount("(admin_id)", DB_ADMIN, "admin_page='5'")) ? "<b class='white'>&middot;</b>&nbsp;<a href='".ADMIN."index.php".$aidlink."&pagenum=5'>".$locale['JQL_022']."</a><br />" : "");
		} else {
			echo ((dbcount("(admin_id)", DB_ADMIN, "admin_page='4'")) ? "<b class='white'>&middot;</b>&nbsp;<a href='".ADMIN."index.php".$aidlink."&pagenum=4'>".$locale['JQL_022']."</a><br />" : "");
		}
echo "</div>";
	}
	// PM-Infos
	echo "<div id='pm_infos' class='jqltooltips'>";
	$msg_settings = dbarray(dbquery("SELECT * FROM ".DB_MESSAGES_OPTIONS." WHERE user_id='0'"));
	$bdata = dbarray(dbquery(
		"SELECT COUNT(IF(message_folder=0, 1, null)) inbox_total,
		COUNT(IF(message_folder=1, 1, null)) outbox_total, COUNT(IF(message_folder=2, 1, null)) archive_total
		FROM ".DB_MESSAGES." WHERE message_to='".$userdata['user_id']."' GROUP BY message_to"
	));
	$bdata['inbox_total'] = isset($bdata['inbox_total']) ? $bdata['inbox_total'] : "0";
	$bdata['outbox_total'] = isset($bdata['outbox_total']) ? $bdata['outbox_total'] : "0";
	$bdata['archive_total'] = isset($bdata['archive_total']) ? $bdata['archive_total'] : "0";
	echo "<b>".$locale['JQL_023'].":</b><br />
			<a href='".BASEDIR."messages.php?folder=inbox'>".$locale['JQL_024']."</a>: [".$bdata['inbox_total']."/".$msg_settings['pm_inbox']."]<br />
			<a href='".BASEDIR."messages.php?folder=outbox'>".$locale['JQL_025']."</a>: [".$bdata['outbox_total']."/".$msg_settings['pm_sentbox']."]<br />
			<a href='".BASEDIR."messages.php?folder=archive'>".$locale['JQL_026']."</a>: [".$bdata['archive_total']."/".$msg_settings['pm_savebox']."]";
	echo "</div>";
	// PM-Benachrichtigung
	echo "<div id='pm_alert_info' class='jqltooltips' style='text-align:center;'>";
	echo "".$locale['JQL_027']." ".($pm_alert == 1 ? "<span style='color:#26FF3B;'>".$locale['JQL_028']."</span>" : "<span style='color:red;'>".$locale['JQL_029']."</span>")."<br />
		<span style='font-size:xx-small;'>".$locale['JQL_030']." ".($pm_alert == 1 ? $locale['JQL_029'] : $locale['JQL_028'])."".$locale['JQL_031']."</span>";
	echo "</div>";
	// Scores
	if ($scoresystem_active == 1) {
	echo "<div id='scores_info' class='jqltooltips' style='text-align:left;'>";
		if (!score_ban($userdata['user_id'])) {
			$result = dbquery("SELECT * FROM ".DB_SCORE_TRANSFER." WHERE tra_user_id='".$userdata['user_id']."' AND tra_status!='5' ORDER BY tra_id DESC LIMIT 0,5");
			echo "<b>".$locale['JQL_032'].": ".score_account_stand()."</b><br />";
			if (dbrows($result)) {
				echo "".$locale['JQL_033'].":<br />";
				while ($data = dbarray($result)) {
					echo $data['tra_titel']." - <span class='".score_transfer_color($data['tra_typ'])."' style='padding: 1px 5px;'>".$data['tra_score']."</span><br />\n";
				}
			}
		} else {
			echo $locale['JQL_034'];
		}
	echo "</div>";
	}
	// Einsendungen Links
	echo "<div id='submit_links' class='jqltooltips' style='text-align:left;'>";
	echo "<b>".$locale['JQL_045'].":</b><br />";
	echo "<b class='white'>&middot;</b>&nbsp;<a href='".BASEDIR."submit.php?stype=l'>".$locale['JQL_046']."</a><br />";
	echo "<b class='white'>&middot;</b>&nbsp;<a href='".BASEDIR."submit.php?stype=n'>".$locale['JQL_047']."</a><br />";
	echo "<b class='white'>&middot;</b>&nbsp;<a href='".BASEDIR."submit.php?stype=a'>".$locale['JQL_048']."</a><br />";
	echo "<b class='white'>&middot;</b>&nbsp;<a href='".BASEDIR."submit.php?stype=p'>".$locale['JQL_049']."</a>";
	echo "</div>";
	
	echo "</div>";
		} else {
		echo "<div class='jqlcenter'>
				<!-- Login Form -->
					<h1>".$locale['JQL_035']."</h1>
					<img class='avatar' src='".INFUSIONS."mg_jquery_login_panel/images/gast.png' alt='".$locale['JQL_044']."' title='".$locale['JQL_044']."' style='float:left;height:50px; width:50px; vertical-align:middle; padding-right:30px;' />";
               $url_string = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
               echo "<form class='jqlclearfix' name='jqueryloginform' method='post' action='$url_string'>\n";
	echo "<span class='jqlgrey'>".$locale['JQL_036']."</span> <input type='text' name='user_name' class='jqlfield' />\n";
	echo "<span class='jqlgrey'>".$locale['JQL_037']."</span> <input type='password' name='user_pass' class='jqlfield' />\n";
	echo "<input type='checkbox' name='remember_me' value='y' style='vertical-align:middle;' /><span class='jqlgrey'>&nbsp;".$locale['JQL_038']."</span>\n";
	echo "<input type='submit' name='login' value='".$locale['JQL_035']."' class='jqlbt_login' />\n";
	echo "</form>\n\n
			</div>";
		echo "<div class='jqlright'>";

	if ($settings['enable_registration']) {
		echo "".$locale['JQL_039']."<br /><br />\n";
	}
	echo $locale['JQL_040']."
			</div>";
		}
	echo "</div>
	</div>";
	if ($abstand == "" || $abstand == 0) {
		$pos = "";
	} else {
		$pos = " style='right:".$abstand."px;'";
	}
echo "
	<div class='jqltab'>
		<div class='jqllogin'".$pos.">
			<div class='jqlleft'>&nbsp;</div>
			<div>".daytime()." ".(iMEMBER ? $userdata['user_name'] : $locale['JQL_044'])."!</div>
			<div class='jqlseparator'>|</div>
			<div id='toggle'>
				<a id='jqlopen' class='jqlopen'>".(iMEMBER ? $locale['JQL_041'] : $locale['JQL_042'])."</a>
				<a id='jqlclose' style='display: none;' class='jqlclose'>".$locale['JQL_043']."</a>
			</div>
			<div class='jqlright'>&nbsp;</div>
		</div>
	</div>

</div>";

?>