<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/update_profile_include.php
| Version: Pimped Fusion v0.06.00
+----------------------------------------------------------------------------+
| based on PHP-Fusion CMS v7.01 by Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if (!iMEMBER || !isset($_POST['user_hash']) || $_POST['user_hash'] != $user_data['user_password']) { redirect("index.php"); }

$error = ""; $db_values = ""; $set_avatar = "";

if($settings['allowtochange_username']) {
	$user_name = trim(preg_replace("/ +/i", " ", $_POST['user_name']));
} else {
	$user_name = $userdata['user_name'];
}
$user_email = trim(stripinput($_POST['user_email']));
$user_new_password = trim(stripinput($_POST['user_new_password']));
$user_new_password2 = trim(stripinput($_POST['user_new_password2']));

if (iADMIN) {
	$user_new_admin_password = trim(stripinput($_POST['user_new_admin_password']));
	$user_new_admin_password2 = trim(stripinput($_POST['user_new_admin_password2']));
} else {
	$user_new_admin_password = "";
}

if ($user_name == "" || $user_email == "") {
	$error .= $locale['430']."<br />\n";
} else {
	if (preg_check("/^[-0-9A-Z_@\s]+$/i", $user_name)) {
		if ($user_name != $user_data['user_name']) {
			$result = dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_name='".$user_name."' AND user_id<>'".$userdata['user_id']."'");
			if (dbrows($result)) {
				$error .= $locale['432']."<br />\n";
			}
		}
	} else {
		$error .= $locale['431']."<br />\n";
	}
	if (preg_match("/^[-0-9A-Z._%+ÄÖÜäöü]{1,50}@([-0-9A-Z.ÄÖÜäöü]+\.){1,50}([A-Z]){2,6}$/i", $user_email)) {
		if ($user_email != $user_data['user_email']) {
			if ((isset($_POST['user_password'])) && encrypt_pw($_POST['user_password']) == $user_data['user_password']) {
				$result = dbquery("SELECT user_email FROM ".DB_USERS." WHERE user_email='".$user_email."'");
				if (dbrows($result)) {
					$error .= $locale['434']."<br />\n";
				}
			} else {
				$error .= $locale['437']."<br />\n";
			}
		}
	} else {
		$error .= $locale['433']."<br />\n";
	}
}

if ($user_new_password) {
	if ((isset($_POST['user_password'])) && encrypt_pw($_POST['user_password']) == $user_data['user_password']) {
		if ($user_new_password2 != $user_new_password) {
			$error .= $locale['435']."<br />";
		} else {
			if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $user_new_password)) {
				$error .= $locale['436']."<br />\n";
			}
			if ((encrypt_pw($user_new_password) == encrypt_pw($user_new_admin_password)) || (encrypt_pw($user_new_password) == $user_data['user_admin_password'])) {
				$error .= $locale['439']."<br><br>\n";
			}
		}
	} else {
		$error .= $locale['437']."<br />\n";
	}
}

if (iADMIN && $user_new_admin_password) {
	if ($user_data['user_admin_password']) {
		if ((!isset($_POST['user_admin_password'])) || encrypt_pw($_POST['user_admin_password']) != $user_data['user_admin_password']) {
			$error .= $locale['441']."<br />\n";
		}
	}
	if (!$error) {
		if ($user_new_admin_password2 != $user_new_admin_password) {
			$error .= $locale['438']."<br />";
		} else {
			if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $user_new_admin_password)) {
				$error .= $locale['440']."<br />\n";
			}
			if ((encrypt_pw($user_new_admin_password) == encrypt_pw($user_new_password)) || (encrypt_pw($user_new_admin_password) == $user_data['user_password'])) {
				$error .= $locale['439']."<br><br>\n";
			}
		}
	}
}

$user_hide_email = isnum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";

if (!$error && !$user_data['user_avatar'] && !empty($_FILES['user_avatar']['name']) && is_uploaded_file($_FILES['user_avatar']['tmp_name'])) {
	require_once INCLUDES."photo_functions_include.php";
	$file_types = array(".gif",".jpg",".jpeg",".png");
	$avatar_name = str_replace(" ", "_", strtolower(substr($_FILES['user_avatar']['name'], 0, strrpos($_FILES['user_avatar']['name'], "."))));
	$avatar_ext = strtolower(strrchr($_FILES['user_avatar']['name'],"."));
	if (!preg_check("/^[-0-9A-Z_\[\]]+$/i", $avatar_name)) {
		$error .= "Avatar file name is invalid.<br />\n";
	} elseif ($_FILES['user_avatar']['size'] > $settings['avatar_filesize']){
		$error .= "Avatar file size is too big.<br />\n";
	} elseif (!in_array($avatar_ext, $file_types)) {
		$error .= "Avatar file type is invalid.<br />\n";
	} else {
		$avatar_temp = image_exists(IMAGES."avatars/", "temp".$avatar_ext);
		move_uploaded_file($_FILES['user_avatar']['tmp_name'], IMAGES."avatars/".$avatar_temp);
		chmod(IMAGES."avatars/".$avatar_temp, 0644);
		if (!verify_image(IMAGES."avatars/".$avatar_temp)) {
			@unlink(IMAGES."avatars/".$avatar_temp);
			$set_avatar = "";
		} else {
			$imagefile = getimagesize(IMAGES."avatars/".$avatar_temp);
			$avatar_file = image_exists(IMAGES."avatars/", $avatar_name.$avatar_ext);
			if ($imagefile[0] > $settings['avatar_width'] || $imagefile[1] > $settings['avatar_height']) {
				if ($settings['avatar_ratio'] == 0) {
					createthumbnail($imagefile[2], IMAGES."avatars/".$avatar_temp, IMAGES."avatars/".$avatar_file, $settings['avatar_width'], $settings['avatar_height']); 
				} else {
					createsquarethumbnail($imagefile[2], IMAGES."avatars/".$avatar_temp, IMAGES."avatars/".$avatar_file, $settings['avatar_width']);
				}
				@unlink(IMAGES."avatars/".$avatar_temp);
			} else {
				rename(IMAGES."avatars/".$avatar_temp, IMAGES."avatars/".$avatar_file);
			}
			$set_avatar = ", user_avatar='".$avatar_file."'";
		}
	}
/* Code for avatar URL input */
	} elseif (!$error && $settings['extern_avatar_upload'] && !$user_data['user_avatar'] && isset($_POST['user_avatarnet']) && $_POST['user_avatarnet'] !== "http://www.") {
		if (verify_image(stripinput($_POST['user_avatarnet']))) {
			require_once INCLUDES."photo_functions_include.php";
			$avatarname = strrchr(stripinput($_POST['user_avatarnet']), "/" );
			$avatarname = str_replace( "/", "", $avatarname);
			$avatarext = strrchr($avatarname, ".");
			$avatarname = substr($avatarname, 0, strrpos($avatarname,"."));
			if (preg_match("/^[-0-9A-Z_\[\]]+$/i", $avatarname) && preg_match("/(\.gif|\.GIF|\.jpg|\.JPG|\.jpeg|\.JPEG|\.png|\.PNG)$/", $avatarext)) {
				$avatarname = $avatarname."[".$userdata['user_id']."]".$avatarext;
				$image = stripinput($_POST['user_avatarnet']);
				copy($image, IMAGES_AVA.$avatarname);
				// Some more checks
				if (!verify_image(IMAGES_AVA.$avatarname)) {
					@unlink(IMAGES_AVA.$avatarname);
					$set_avatar = "";
				} else {
					$imagefile = getimagesize(IMAGES_AVA.$avatarname);
					$avatarname_thumb = image_exists(IMAGES_AVA, $avatarname);
					if ($imagefile[0] > $settings['avatar_width'] || $imagefile[1] > $settings['avatar_height']) {
						if ($settings['avatar_ratio'] == 0) {
							createthumbnail($imagefile[2], IMAGES_AVA.$avatarname, IMAGES_AVA.$avatarname_thumb, $settings['avatar_width'], $settings['avatar_height']); 
						} else {
							createsquarethumbnail($imagefile[2], IMAGES_AVA.$avatarname, IMAGES_AVA.$avatarname_thumb, $settings['avatar_width']);
						}
						@unlink(IMAGES_AVA.$avatarname);
						$set_avatar = ", user_avatar='".$avatarname_thumb."'";
					} else {
						$set_avatar = ", user_avatar='".$avatarname."'";
					}
				}
			}
		}
	}
/* End code for avatar URL input */

if (!$error) {
	$result = dbquery(
		"SELECT * FROM ".DB_USER_FIELDS." tuf
		INNER JOIN ".DB_USER_FIELD_CATS." tufc ON tuf.field_cat = tufc.field_cat_id
		ORDER BY field_cat_order, field_order"
	);
	if (dbrows($result)) {
		$profile_method = "validate_update"; 
		while($data = dbarray($result)) {
			if (file_exists(LOCALE.LOCALESET."user_fields/".$data['field_name'].".php")) {
				include LOCALE.LOCALESET."user_fields/".$data['field_name'].".php";
			}
			if (file_exists(INCLUDES."user_fields/".$data['field_name']."_include.php")) {
				include INCLUDES."user_fields/".$data['field_name']."_include.php";
			}
		}
	}
}

if (!$error) {	
	if (isset($_POST['del_avatar'])) {
		@unlink(IMAGES."avatars/".$user_data['user_avatar']);
		$set_avatar = ", user_avatar=''";
	}
	
	if ($user_new_password) {
		$new_pass = " user_password='".encrypt_pw($user_new_password)."', ";
		// Set new session / cookie
		if ($settings['login_method'] == "cookies") {
		header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
		setcookie(COOKIE_PREFIX."user", $user_data['user_id'].".".encrypt_pw_part1($user_new_password), time() + 3600 * 3, "/", "", "0");
	} elseif ($settings['login_method'] == "sessions") {
		$_SESSION[COOKIE_PREFIX.'user_pass'] = encrypt_pw_part1($user_new_password);
	}
	} else {
	$new_pass = " ";
	}
	if (iADMIN && $user_new_admin_password) { $new_admin_pass = " user_admin_password='".encrypt_pw($user_new_admin_password)."', "; } else { $new_admin_pass = " "; }

	$result = dbquery("UPDATE ".DB_USERS." SET user_name='$user_name',".$new_pass.$new_admin_pass."user_email='$user_email', user_hide_email='$user_hide_email'".($set_avatar ? $set_avatar : "").$db_values." WHERE user_id='".$user_data['user_id']."'");
	redirect(make_url("edit_profile.php?update_profile=ok", "edit_profile-update_profile-ok", "", ".html")); // Pimped: make_url
} else {
	echo "<div style='text-align:center'><strong>".$locale['412']."</strong><br />\n".$error."<br />\n</div>\n";
}
?>
