<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Teamlist v2.2 for PHP-Fusion 7
| Filename: teamlist.php
| Author: Ralf Thieme (Gr@n@dE)
| HP: www.granade.eu
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "maincore.php";
require_once THEMES."templates/header.php";

include INFUSIONS."gr_teamlist/infusion_db.php";
if (file_exists(INFUSIONS."gr_teamlist/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_teamlist/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_teamlist/locale/German/index.php";
}

add_to_title($locale['global_200'].$locale['grtl102']);
opentable($locale['grtl102']);
$result = dbquery("SELECT * FROM ".DB_GR_TEAMLIST_GROUP." ORDER BY tlg_position");
if (dbrows($result) != 0) {
while ($tl_group = dbarray($result)) {
	echo "<div align='center'><br /><img border='0' src='".INFUSIONS."gr_teamlist/images/group/".$tl_group['tlg_pic']."' alt='".$tl_group['tlg_name']."' onClick='javascript:Toggle(".$tl_group['tlg_id'].")' style='cursor:pointer' /><span id='ToggleRow_".$tl_group['tlg_id']."' style='display:".($tl_group['tlg_status'] == 1 ? "block" : "none")."'>";
	$result2 = dbquery("SELECT * FROM ".DB_GR_TEAMLIST_USERS." WHERE tlu_groups='".$tl_group['tlg_id']."' ORDER BY tlu_position");
	if (dbrows($result2) != 0)  {
		if (dbrows($result2) > 1)  {
			$table_s = 1;
			echo "<table class='tbl-border' width='100%' align='center' cellpadding='0' cellspacing='0'>\n<tr>\n<td align='right' width='50%' class='tbl'>\n</td>\n<td width='50%' class='tbl'>\n</td>\n</tr>\n<tr>\n";
			$counter = 0; $columns = 2;
		} else {
			echo "<br />";
			$table_s = 0;
		}
		while ($tl_users = dbarray($result2)) {
			if ($table_s == 1) { if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
			$align = $counter % $columns ? "left" : "right";
			echo "<td align='$align' width='50%' class='tbl'>";
			}
			if ($tl_users['tlu_userid'] != 0) {
				$result3 = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$tl_users['tlu_userid']."'");
				$tl_users2 = dbarray($result3);
			}
			if ($tl_users['tlu_pic'] != "" && file_exists(INFUSIONS."gr_teamlist/images/team/".$tl_users['tlu_pic'])) {
				$user_avatar = INFUSIONS."gr_teamlist/images/team/".$tl_users['tlu_pic'];
			} elseif ($tl_users2['user_avatar'] != "" && file_exists(IMAGES."avatars/".$tl_users2['user_avatar'])) {
				$user_avatar = IMAGES."avatars/".$tl_users2['user_avatar'];
			} else {
				$user_avatar = IMAGES."avatars/nopic.gif";
			}
			echo "<table width='272' class='tbl-border' cellspacing='0' cellpadding='0'>
	    <tr>
	    	<td colspan='3' class='tbl1' align='center'><strong>";
	      if ($tl_users['tlu_status'] == 1) {
	      	echo "<img border='0' src='".INFUSIONS."gr_teamlist/images/aktiv.gif' alt='Aktiv' width='9' height='12' /> ";
	      } else {
	      	echo "<img border='0' src='".INFUSIONS."gr_teamlist/images/inaktiv.gif' alt='Inaktiv' width='9' height='12' /> ";
	      }
	      if ($tl_users['tlu_userid'] != 0) {
			// echo "<a href='".BASEDIR."profile.php?lookup=".$tl_users['tlu_userid']."'>".$tl_users2['user_name']."</a> (".$tl_users['tlu_abteil'].")</strong>";
	      	echo "<a href='".BASEDIR."profile.php?lookup=".$tl_users['tlu_userid']."'>".$tl_users2['user_name']."</a> ".$tl_users['tlu_abteil']."</strong>";
	      } else {
	      	echo $locale['grtl200']." (".$tl_users['tlu_abteil'].")</strong>";
	      }
	    echo "</td>
	    </tr>
	    <tr>
	    	<td rowspan='3' width='70' class='tbl2'><img border='0' src='".$user_avatar."' alt='".$tl_users2['user_name']."' width='70' height='70' /></td>
	      <td align='right' class='tbl1' width='30'>".$locale['grtl201']."</td>
	      <td align='left' class='tbl2' width='150'>".$tl_users['tlu_rname']."</td>
	    </tr>
	    <tr>
	    	<td align='right' class='tbl1'>".$locale['grtl202']."</td>
	    	<td align='left' class='tbl2'>";
	      if (array_key_exists("user_birthdate", $tl_users2) && $tl_users2['user_birthdate'] != "0000-00-00" && $tl_users['tlu_userid'] != 0) {
					$user_birthdate = explode("-", $tl_users2['user_birthdate']);
					echo TL_ALTER(number_format($user_birthdate['2']),number_format($user_birthdate['1']),$user_birthdate['0']);
				}	else {
					echo $locale['grtl203'];
				}
	      echo "</td>
			</tr>
			<tr>
			<td align='right' class='tbl1'>".$locale['grtl204']."</td>
			<td align='left' class='tbl2'>";
		  	if (array_key_exists("user_location", $tl_users2) && $tl_users2['user_location'] != "" && $tl_users['tlu_userid'] != 0) {
		    	echo $tl_users2['user_location'];
		    } else {
		    	echo $locale['grtl203'];
		    }
		    echo"</td>
	    </tr>
	    <tr>
	    	<td align='right' class='tbl1'>".$locale['grtl205']."</td>
	    	<td colspan='2' align='left' class='tbl2'>";
	    	if ($tl_users['tlu_userid'] != 0 && ($tl_users2['user_hide_email'] != "1" || iADMIN)) {
	    		echo hide_email($tl_users2['user_email'], "<img border='0' src='".INFUSIONS."gr_teamlist/images/email.png' alt='Email ' width='16' height='16' />")." ";
				}
				if (array_key_exists("user_aim", $tl_users2) && $tl_users['tlu_userid'] != 0 && $tl_users2['user_aim'] != "") {
	    		echo "<a href='aim:goim?screenname=".$tl_users2['user_aim']."' target='_blank' title='".$tl_users2['user_aim']."'><img border='0' src='".INFUSIONS."gr_teamlist/images/aim.png' alt='AIM ' width='16' height='16' /></a> ";
				}
				if (array_key_exists("user_icq", $tl_users2) && $tl_users['tlu_userid'] != 0 && $tl_users2['user_icq'] != "") {
	    		echo "<a href='http://icq.com/people/about_me.php?uin=".$tl_users2['user_icq']."' target='_blank' title='".$tl_users2['user_icq']."'><img border='0' src='".INFUSIONS."gr_teamlist/images/icq.png' alt='ICQ ' width='16' height='16' /></a> ";
				}
				if (array_key_exists("user_msn", $tl_users2) && $tl_users['tlu_userid'] != 0 && $tl_users2['user_msn'] != "") {
	    		echo "<a href='mailto:".$tl_users2['user_msn']."' target='_blank' title='".$tl_users2['user_msn']."'><img border='0' src='".INFUSIONS."gr_teamlist/images/msn.png' alt='MSN ' width='16' height='16' /></a> ";
				}
				if (array_key_exists("user_yahoo", $tl_users2) && $tl_users['tlu_userid'] != 0 && $tl_users2['user_yahoo'] != "") {
	    		echo "<a href='http://uk.profiles.yahoo.com/".$tl_users2['user_yahoo']."' title='".$tl_users2['user_yahoo']."'><img border='0' src='".INFUSIONS."gr_teamlist/images/yahoo.png' alt='YAHOO ' width='16' height='16' /></a> ";
				}
				if (($tl_users['tlu_userid'] == 0 || ($tl_users2['user_hide_email'] == "1" && !iADMIN)) && !IsSeT($tl_users2['user_aim']) && !IsSeT($tl_users2['user_icq']) && !IsSeT($tl_users2['user_msn']) && !IsSeT($tl_users2['user_yahoo'])) {
	    		echo $locale['grtl206'];
				}
			echo "</td>
	    </tr>
	    <tr>
	    	<td align='right' class='tbl1'>".$locale['grtl207']."</td>
	    	<td colspan='2' align='left' class='tbl2'>".($tl_users['tlu_feld1'] ? nl2br(parseubb($tl_users['tlu_feld1'])) : $locale['grtl203'])."</td>
	    </tr>
	    <tr>
	    	<td align='right' class='tbl1'>".$locale['grtl208']."</td>
	    	<td colspan='2' align='left' class='tbl2'>".($tl_users['tlu_feld2'] ? nl2br(parseubb($tl_users['tlu_feld2'])) : $locale['grtl203'])."</td>
	    </tr>
			</table>";
	    if ($table_s == 1) { 
	    	echo "</td>\n";
				$counter++;
			}
		}
		if ($table_s == 1) { echo "</tr>\n</table>\n"; }
	} else {
		echo "<br />".$locale['grtl249']."<br />";
	}
	echo "</span>\n</div>\n";
}
} else {
	echo "<div align='center'><br />".$locale['grtl209']."<br /><br /></div>";
}
echo "<div align='right'><a href='http://www.granade.eu/scripte/teamlist.html' target='_blank'>Teamlist &copy;</a></div>";
closetable();

function TL_ALTER($gebd,$gebm,$geby){
	$alter = date("Y") - $geby;
	if (mktime(0,0,0,date("m"),date("d"),date("Y")) < mktime(0,0,0,$gebm,$gebd,date("Y"))) {
		$alter--;
	}
	return $alter;
}
echo '<script type="text/javascript">
function Toggle(id) {
  spanid1 = "ToggleRow_"+id;
  val = document.getElementById(spanid1).style.display;
  if (val == "none") {
    document.getElementById(spanid1).style.display = "block";
  }
  else {
    document.getElementById(spanid1).style.display = "none";
  }
}
</script>';
require_once THEMES."templates/footer.php";   
?>