<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: photos.php
| Version: Pimped Fusion v0.09.00
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
require_once "../maincore.php";
require_once TEMPLATES."admin_header.php";
require_once INCLUDES."photo_functions_include.php";
require_once INCLUDES."bbcode_include.php";
include LOCALE.LOCALESET."admin/photos.php";

if (!checkrights("PH") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }
if (!isset($_GET['album_id']) && !isnum($_GET['album_id'])) { redirect("photoalbums.php".$aidlink); }

if (function_exists('gd_info')) {

	define("SAFEMODE", @ini_get("safe_mode") ? true : false);
	define("PHOTODIR", PHOTOS.(!SAFEMODE ? "album_".$_GET['album_id']."/" : ""));

	if (isset($_GET['status']) && !isset($message)) {
		if ($_GET['status'] == "sn") {
			$message = $locale['410'];
		} elseif ($_GET['status'] == "su") {
			$message = $locale['411'];
		} elseif ($_GET['status'] == "se") {
			$message = $locale['414']."<br />\n<span class='small'>";
			if ($_GET['error'] == 1) { $message .= $locale['415']."</span>"; }
			elseif ($_GET['error'] == 2) { $message .= sprintf($locale['416'], parsebytesize($settings['photo_max_b']))."</span>"; }
			elseif ($_GET['error'] == 3) { $message .= $locale['417']."</span>"; }
			elseif ($_GET['error'] == 4) { $message .= sprintf($locale['418'], $settings['photo_max_w'], $settings['photo_max_h'])."</span>"; }
			elseif ($_GET['error'] == 5) { $message .= "No Photo selected / Photo couldn't be uploaded</span>"; }
		} elseif ($_GET['status'] == "delt") {
			$message = $locale['412'];
		} elseif ($_GET['status'] == "del") {
			$message = $locale['413'];
		} elseif ($_GET['status'] == "mov") {
			$message = $locale['419'];
		}
		if (isset($message)) { echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
	}

	if (isset($_POST['cancel'])) {
		redirect(FUSION_SELF.$aidlink."&album_id=".$_GET['album_id']);
	} elseif (isset($_POST['move_photo']) && (isset($_POST['move_album_id']) && isnum($_POST['move_album_id'])) && (isset($_GET['photo_id']) && isnum($_GET['photo_id']))) {
		$result = dbquery("SELECT MAX(photo_order)+1 as last_order FROM ".DB_PHOTOS." WHERE album_id='".$_POST['move_album_id']."' GROUP BY album_id");
		if (dbrows($result)) {
			$data = dbarray($result);
			$last_order = $data['last_order'];
		} else {
			$last_order = 1;
		}
		if (!SAFEMODE) {
			$result2 = dbquery("SELECT photo_filename, photo_thumb1, photo_thumb2 FROM ".DB_PHOTOS." WHERE photo_id='".(int)$_GET['photo_id']."'");
			if (dbrows($result2)) {
				$data2 = dbarray($result2);
				$old_image = $data2['photo_filename'];
				$old_thumb1 = $data2['photo_thumb1'];
				$old_thumb2 = $data2['photo_thumb2'];
				$new_image = image_exists(PHOTOS."album_".$_POST['move_album_id']."/", $old_image);
				$file_name = explode(".", $new_image);
				$new_thumb1 = $file_name[0]."_t1.".$file_name[1];
				$new_thumb2 = $file_name[0]."_t2.".$file_name[1];
				unset($file_name);
				if ($data2['photo_filename']) { @rename (PHOTODIR.$old_image, PHOTOS."album_".$_POST['move_album_id']."/".$new_image); }
				if ($data2['photo_thumb1']) { @rename (PHOTODIR.$old_thumb1, PHOTOS."album_".$_POST['move_album_id']."/".$new_thumb1); }
				if ($data2['photo_thumb2']) { @rename (PHOTODIR.$old_thumb2, PHOTOS."album_".$_POST['move_album_id']."/".$new_thumb2); }
				if ($old_image != $new_image) {
					$result3 = dbquery("UPDATE ".DB_PHOTOS." SET album_id='".$_POST['move_album_id']."', photo_order='".$last_order."', photo_filename='".$new_image."', photo_thumb1='".$new_thumb1."', photo_thumb2='".$new_thumb2."' WHERE photo_id='".$_GET['photo_id']."'");
				} else {
					$result3 = dbquery("UPDATE ".DB_PHOTOS." SET album_id='".$_POST['move_album_id']."', photo_order='".$last_order."' WHERE photo_id='".$_GET['photo_id']."'");
				}
			} else {
				redirect(FUSION_SELF.$aidlink."&album_id=".$_GET['album_id']);
			}
		} else {
			$result3 = dbquery("UPDATE ".DB_PHOTOS." SET album_id='".$_POST['move_album_id']."', photo_order='".$last_order."' WHERE photo_id='".$_GET['photo_id']."'");
		}
		$k = 1;
		$result2 = dbquery("SELECT photo_id FROM ".DB_PHOTOS." WHERE album_id='".$_GET['album_id']."' ORDER BY photo_order");
		if (dbrows($result2)) {
			while ($data2 = dbarray($result2)) {
				$result3 = dbquery("UPDATE ".DB_PHOTOS." SET photo_order='".$k."' WHERE photo_id='".$data2['photo_id']."'");
				$k++;
			}
		}
		redirect (FUSION_SELF.$aidlink."&album_id=".$_POST['move_album_id']."&amp;status=mov");
	} elseif (isset($_POST['move_sel_photos']) && (isset($_POST['move_album_id']) && isnum($_POST['move_album_id']))) {
		$result = dbquery("SELECT MAX(photo_order)+1 as last_order FROM ".DB_PHOTOS." WHERE album_id='".(int)$_POST['move_album_id']."' GROUP BY album_id");
		if (dbrows($result)) {
			$data = dbarray($result);
			$last_order = $data['last_order'];
		} else {
			$last_order = 1;
		}
		$check_count = 0; $photo_ids = "";
		if (is_array($_POST['sel_photo']) && count($_POST['sel_photo']) > 0) {
			foreach ($_POST['sel_photo'] as $this_photo) {
				if (isnum($this_photo)) { $photo_ids .= ($photo_ids ? "," : "").$this_photo; }
				$check_count++;
			}
		}
		if ($check_count > 0) {
			$result = dbquery("SELECT photo_id, photo_filename, photo_thumb1, photo_thumb2
			FROM ".DB_PHOTOS." WHERE album_id='".$_GET['album_id']."' AND photo_id IN (".$photo_ids.") ORDER BY photo_order");
			$rows = dbrows($result);
			if ($rows) {
				$i = 0;
				while ($data = dbarray($result)) {
					if (!SAFEMODE) {
						$old_image = $data['photo_filename'];
						$old_thumb1 = $data['photo_thumb1'];
						$old_thumb2 = $data['photo_thumb2'];
						$new_image = image_exists(PHOTOS."album_".$_POST['move_album_id']."/", $old_image);
						$file_name = explode(".", $new_image);
						$new_thumb1 = $file_name[0]."_t1.".$file_name[1];
						$new_thumb2 = $file_name[0]."_t2.".$file_name[1];
						unset($file_name);
						if ($data['photo_filename']) { @rename (PHOTODIR.$old_image, PHOTOS."album_".$_POST['move_album_id']."/".$new_image); }
						if ($data['photo_thumb1']) { @rename (PHOTODIR.$old_thumb1, PHOTOS."album_".$_POST['move_album_id']."/".$new_thumb1); }
						if ($data['photo_thumb2']) { @rename (PHOTODIR.$old_thumb2, PHOTOS."album_".$_POST['move_album_id']."/".$new_thumb2); }
						if ($old_image != $new_image) {
							$result2 = dbquery("UPDATE ".DB_PHOTOS." SET album_id='".$_POST['move_album_id']."', photo_order='".$last_order."', photo_filename='".$new_image."', photo_thumb1='".$new_thumb1."', photo_thumb2='".$new_thumb2."' WHERE photo_id='".$data['photo_id']."'");
						} else {
							$result2 = dbquery("UPDATE ".DB_PHOTOS." SET album_id='".$_POST['move_album_id']."', photo_order='".$last_order."' WHERE photo_id='".$data['photo_id']."'");
						}
					} else {
						$result2 = dbquery("UPDATE ".DB_PHOTOS." SET album_id='".$_POST['move_album_id']."', photo_order='".$last_order."' WHERE photo_id='".$data['photo_id']."'");
					}
					$last_order++;
				}
				$k = 1;
				$result2 = dbquery("SELECT photo_id FROM ".DB_PHOTOS." WHERE album_id='".$_GET['album_id']."' ORDER BY photo_order");
				if (dbrows($result2)) {
					while ($data2 = dbarray($result2)) {
						$result3 = dbquery("UPDATE ".DB_PHOTOS." SET photo_order='".$k."' WHERE photo_id='".$data2['photo_id']."'");
						$k++;
					}
				}
				redirect (FUSION_SELF.$aidlink."&album_id=".$_POST['move_album_id']."&amp;status=mov");
			} else {
				redirect (FUSION_SELF.$aidlink."&album_id=".$_GET['album_id']);
			}
		} else {
			redirect (FUSION_SELF.$aidlink."&album_id=".$_GET['album_id']);
		}
	} elseif (isset($_POST['move_all_photos']) && (isset($_POST['move_album_id']) && isnum($_POST['move_album_id']))) {
		$result = dbquery("SELECT MAX(photo_order)+1 as last_order FROM ".DB_PHOTOS." WHERE album_id='".$_POST['move_album_id']."' GROUP BY album_id");
		if (dbrows($result)) {
			$data = dbarray($result);
			$last_order = $data['last_order'];
		} else {
			$last_order = 1;
		}
		$result = dbquery("SELECT photo_id, photo_filename, photo_thumb1, photo_thumb2
		FROM ".DB_PHOTOS." WHERE album_id='".$_GET['album_id']."' ORDER BY photo_order");
		$rows = dbrows($result);
		if ($rows) {
			while ($data = dbarray($result)) {
				if (!SAFEMODE) {
					$old_image = $data['photo_filename'];
					$old_thumb1 = $data['photo_thumb1'];
					$old_thumb2 = $data['photo_thumb2'];
					$new_image = image_exists(PHOTOS."album_".$_POST['move_album_id']."/", $old_image);
					$file_name = explode(".", $new_image);
					$new_thumb1 = $file_name[0]."_t1.".$file_name[1];
					$new_thumb2 = $file_name[0]."_t2.".$file_name[1];
					unset($file_name);
					if ($data['photo_filename']) @rename (PHOTODIR.$old_image, PHOTOS."album_".$_POST['move_album_id']."/".$new_image);
					if ($data['photo_thumb1']) @rename (PHOTODIR.$old_thumb1, PHOTOS."album_".$_POST['move_album_id']."/".$new_thumb1);
					if ($data['photo_thumb2']) @rename (PHOTODIR.$old_thumb2, PHOTOS."album_".$_POST['move_album_id']."/".$new_thumb2);
					if ($old_image != $new_image) {
						$result2 = dbquery("UPDATE ".DB_PHOTOS." SET album_id='".$_POST['move_album_id']."', photo_order='".$last_order."', photo_filename='".$new_image."', photo_thumb1='".$new_thumb1."', photo_thumb2='".$new_thumb2."' WHERE photo_id='".$data['photo_id']."'");
					} else {
						$result2 = dbquery("UPDATE ".DB_PHOTOS." SET album_id='".$_POST['move_album_id']."', photo_order='".$last_order."' WHERE photo_id='".$data['photo_id']."'");
					}
				} else {
					$result2 = dbquery("UPDATE ".DB_PHOTOS." SET album_id='".$_POST['move_album_id']."', photo_order='".$last_order."' WHERE photo_id='".$data['photo_id']."'");
				}
				$last_order++;
			}
			redirect (FUSION_SELF.$aidlink."&album_id=".$_POST['move_album_id']."&amp;status=mov");
		} else {
			redirect (FUSION_SELF.$aidlink."&album_id=".$_GET['album_id']);
		}
	} elseif ((isset($_GET['action']) && $_GET['action'] == "deletepic") && (isset($_GET['photo_id']) && isnum($_GET['photo_id']))) {
		$data = dbarray(dbquery("SELECT photo_filename, photo_thumb1, photo_thumb2 FROM ".DB_PHOTOS." WHERE photo_id='".$_GET['photo_id']."'"));
		$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_filename='', photo_thumb1='', photo_thumb2='' WHERE photo_id='".$_GET['photo_id']."'");
		@unlink(PHOTODIR.$data['photo_filename']);
		@unlink(PHOTODIR.$data['photo_thumb1']);
		if ($data['photo_thumb2']) { @unlink(PHOTODIR.$data['photo_thumb2']); }
		redirect(FUSION_SELF.$aidlink."&status=delt&album_id=".$_GET['album_id']."");
	} elseif ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['photo_id']) && isnum($_GET['photo_id']))) {
		$data = dbarray(dbquery("SELECT album_id, photo_filename, photo_thumb1, photo_thumb2, photo_order FROM ".DB_PHOTOS." WHERE photo_id='".$_GET['photo_id']."'"));
		$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_order=(photo_order-1) WHERE photo_order>'".$data['photo_order']."' AND album_id='".$_GET['album_id']."'");
		$result = dbquery("DELETE FROM ".DB_PHOTOS." WHERE photo_id='".$_GET['photo_id']."'");
		$result = dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_item_id='".$_GET['photo_id']."' and comment_type='P'");
		$result = dbquery("DELETE FROM ".DB_RATINGS." WHERE rating_item_id='".$_GET['photo_id']."' and rating_type='P'");
		if ($data['photo_filename']) { @unlink(PHOTODIR.$data['photo_filename']); }
		if ($data['photo_thumb1']) { @unlink(PHOTODIR.$data['photo_thumb1']); }
		if ($data['photo_thumb2']) { @unlink(PHOTODIR.$data['photo_thumb2']); }
		redirect(FUSION_SELF.$aidlink."&status=del&album_id=".$_GET['album_id']."");
	} elseif ((isset($_GET['action']) && $_GET['action']=="mup") && (isset($_GET['photo_id']) && isnum($_GET['photo_id']))) {
		$data = dbarray(dbquery("SELECT photo_id FROM ".DB_PHOTOS." WHERE album_id='".$_GET['album_id']."' AND photo_order='".intval($_GET['order'])."'"));
		$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_order=photo_order+1 WHERE photo_id='".$data['photo_id']."'");
		$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_order=photo_order-1 WHERE photo_id='".$_GET['photo_id']."'");
		$rowstart = $_GET['order'] > $settings['thumbs_per_page'] ? ((ceil($_GET['order'] / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
		redirect(FUSION_SELF.$aidlink."&album_id=".$_GET['album_id']."&rowstart=$rowstart");
	} elseif ((isset($_GET['action']) && $_GET['action']=="mdown") && (isset($_GET['photo_id']) && isnum($_GET['photo_id']))) {
		$data = dbarray(dbquery("SELECT photo_id FROM ".DB_PHOTOS." WHERE album_id='".$_GET['album_id']."' AND photo_order='".intval($_GET['order'])."'"));
		$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_order=photo_order-1 WHERE photo_id='".$data['photo_id']."'");
		$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_order=photo_order+1 WHERE photo_id='".$_GET['photo_id']."'");
		$rowstart = $_GET['order'] > $settings['thumbs_per_page'] ? ((ceil($_GET['order'] / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
		redirect(FUSION_SELF.$aidlink."&album_id=".$_GET['album_id']."&rowstart=$rowstart");
		//Pimped subcategories begin ->
	}  elseif ((isset($_GET['action']) && $_GET['action'] == "up") && (isset($_GET['album_id']) && isnum($_GET['album_id']))) {
		$parent = (isset($_GET['parent']) && isnum($_GET['parent']) ? " AND album_parent='".$_GET['parent']."'" : "");
		$data = dbarray(dbquery("SELECT album_id FROM ".DB_PHOTO_ALBUMS." WHERE album_order='".intval($_GET['order'])."'".$parent));
		$result = dbquery("UPDATE ".DB_PHOTO_ALBUMS." SET album_order=album_order+1 WHERE album_id='".$data['album_id']."'");
		$result = dbquery("UPDATE ".DB_PHOTO_ALBUMS." SET album_order=album_order-1 WHERE album_id='".$_GET['album_id']."'");
		$rowstart = $_GET['order'] > $settings['thumbs_per_page'] ? ((ceil($_GET['order'] / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
		redirect(FUSION_SELF.$aidlink."&album_id=".$_GET['parent']."&rowstart=$rowstart");
	} elseif ((isset($_GET['action']) && $_GET['action'] == "down") && (isset($_GET['album_id']) && isnum($_GET['album_id']))) {
	    $parent = (isset($_GET['parent']) && isnum($_GET['parent']) ? " AND album_parent='".$_GET['parent']."'" : "");
		$data = dbarray(dbquery("SELECT album_id FROM ".DB_PHOTO_ALBUMS." WHERE album_order='".intval($_GET['order'])."'".$parent));
		$result = dbquery("UPDATE ".DB_PHOTO_ALBUMS." SET album_order=album_order-1 WHERE album_id='".$data['album_id']."'");
		$result = dbquery("UPDATE ".DB_PHOTO_ALBUMS." SET album_order=album_order+1 WHERE album_id='".$_GET['album_id']."'");
		$rowstart = $_GET['order'] > $settings['thumbs_per_page'] ? ((ceil($_GET['order'] / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
		redirect(FUSION_SELF.$aidlink."&album_id=".$_GET['parent']."&rowstart=$rowstart");
		//Pimped subcategories end <-
	} elseif (isset($_POST['save_photo'])) {
		$error = "";
		$photo_title = stripinput($_POST['photo_title']);
		$photo_description = stripinput($_POST['photo_description']);
		$photo_order = isnum($_POST['photo_order']) ? $_POST['photo_order'] : "";
		$photo_comments = isset($_POST['photo_comments']) ? "1" : "0";
		$photo_ratings = isset($_POST['photo_ratings']) ? "1" : "0";
		
		$photo_files = array(); $photo_thumb1_files = array(); $photo_thumb2_files = array();
		
		if (isset($_FILES['photo_pic_file'])) {
			$_FILES['photo_pic_file'] = array_reverse($_FILES['photo_pic_file']);
			for ($a = 0 ; $a < count($_FILES['photo_pic_file']['tmp_name']); $a++) {
				
				$photo_pic['name'] = $_FILES['photo_pic_file']['name'][$a];
				$photo_pic['error'] = $_FILES['photo_pic_file']['error'][$a];
				$photo_pic['tmp_name'] = $_FILES['photo_pic_file']['tmp_name'][$a];
				$photo_pic['size'] = $_FILES['photo_pic_file']['size'][$a];
				
				$photo_file = ""; $photo_thumb1 = ""; $photo_thumb2 = "";
				
				if (is_uploaded_file($photo_pic['tmp_name'])) {
					$photo_types = array(".gif",".jpg",".jpeg",".png");
					$photo_name = str_replace(" ", "_", strtolower(substr($photo_pic['name'], 0, strrpos($photo_pic['name'], "."))));
					$photo_ext = strtolower(strrchr($photo_pic['name'],"."));
					$photo_dest = PHOTODIR;
					if (!preg_match("/^[-0-9A-Z_\.\[\]]+$/i", $photo_name)) {
						$error = 1;
					} elseif ($photo_pic['size'] > $settings['photo_max_b']){
						$error = 2;
					} elseif (!in_array($photo_ext, $photo_types)) {
						$error = 3;
					} else {
						$photo_file = image_exists($photo_dest, $photo_name.$photo_ext);
						move_uploaded_file($photo_pic['tmp_name'], $photo_dest.$photo_file);
						chmod($photo_dest.$photo_file, 0644);
						$imagefile = @getimagesize($photo_dest.$photo_file);
						if ($imagefile[0] > $settings['photo_max_w'] || $imagefile[1] > $settings['photo_max_h']) {
							$error = 4;
							unlink($photo_dest.$photo_file);
						} else {
							$photo_thumb1 = image_exists($photo_dest, $photo_name."_t1".$photo_ext);
							createthumbnail($imagefile[2], $photo_dest.$photo_file, $photo_dest.$photo_thumb1, $settings['thumb_w'], $settings['thumb_h']);
							if ($imagefile[0] > $settings['photo_w'] || $imagefile[1] > $settings['photo_h']) {
								$photo_thumb2 = image_exists($photo_dest, $photo_name."_t2".$photo_ext);
								createthumbnail($imagefile[2],$photo_dest.$photo_file,$photo_dest.$photo_thumb2, $settings['photo_w'],$settings['photo_h']);
							}
						}
						if($photo_file) $photo_files[] = $photo_file;
						if($photo_thumb1) $photo_thumb1_files[] = $photo_thumb1;
						if($photo_thumb2) $photo_thumb2_files[] = $photo_thumb2;
					}
				}
			}
		}
		
		if (!$error) {
			if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['photo_id']) && isnum($_GET['photo_id']))) {
				$old_photo_order = dbresult(dbquery("SELECT photo_order FROM ".DB_PHOTOS." WHERE photo_id='".$_GET['photo_id']."'"),0);
				if ($photo_order > $old_photo_order) {
					$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_order=(photo_order-1) WHERE photo_order>'$old_photo_order' AND photo_order<='$photo_order' AND album_id='".$_GET['album_id']."'");
				} elseif ($photo_order < $old_photo_order) {
					$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_order=(photo_order+1) WHERE photo_order<'$old_photo_order' AND photo_order>='$photo_order' AND album_id='".$_GET['album_id']."'");
				}
				$update_photos = $photo_file ? "photo_filename='$photo_file', photo_thumb1='$photo_thumb1', photo_thumb2='$photo_thumb2', " : "";
				$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_title='$photo_title', photo_description='$photo_description', ".$update_photos."photo_datestamp='".time()."', photo_order='$photo_order', photo_allow_comments='$photo_comments', photo_allow_ratings='$photo_ratings' WHERE photo_id='".$_GET['photo_id']."'");
				$rowstart = $photo_order > $settings['thumbs_per_page'] ? ((ceil($photo_order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
				redirect(FUSION_SELF.$aidlink."&status=su&album_id=".$_GET['album_id']."&rowstart=$rowstart");
			} else {
				$result = false;
				
				// here Photo-Upload allow even if there is no Photo Uploaded?
				
				for ($a = 0 ; $a < count($photo_files); $a++) {
					
					if (!$photo_order) {
						$photo_order = dbresult(dbquery("SELECT MAX(photo_order) FROM ".DB_PHOTOS." WHERE album_id='".$_GET['album_id']."'"), 0) + 1;
					}
					$result = dbquery("UPDATE ".DB_PHOTOS." SET photo_order=(photo_order+1) WHERE photo_order>='$photo_order' AND album_id='".$_GET['album_id']."'");
					$result = dbquery("INSERT INTO ".DB_PHOTOS." (album_id, photo_title, photo_description, photo_filename, photo_thumb1, photo_thumb2, photo_datestamp, photo_user, photo_views, photo_order, photo_allow_comments, photo_allow_ratings) VALUES ('".$_GET['album_id']."', '$photo_title', '$photo_description', '".$photo_files[$a]."', '".$photo_thumb1_files[$a]."', '".$photo_thumb2_files[$a]."', '".time()."', '".$userdata['user_id']."', '0', '$photo_order', '$photo_comments', '$photo_ratings')");
					
				}
				
				$rowstart = $photo_order > $settings['thumbs_per_page'] ? ((ceil($photo_order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
				if($result) {
					redirect(FUSION_SELF.$aidlink."&status=sn&album_id=".$_GET['album_id']."&rowstart=$rowstart");
				} else {
					redirect(FUSION_SELF.$aidlink."&status=se&error=5&album_id=".$_GET['album_id']."&rowstart=$rowstart");
				}
			}
		}
		if ($error) {
			redirect(FUSION_SELF.$aidlink."&status=se&error=$error&album_id=".$_GET['album_id']);
		}
	}else{
		$data3 = dbarray(dbquery("SELECT album_title FROM ".DB_PHOTO_ALBUMS." WHERE album_id='".$_GET['album_id']."'"));
		$album_title = $data3['album_title'];
		if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['photo_id']) && isnum($_GET['photo_id']))) {
			$result = dbquery("SELECT photo_title, photo_description, photo_filename, photo_thumb1, photo_thumb2,
			photo_order, photo_allow_comments, photo_allow_ratings
			FROM ".DB_PHOTOS." WHERE photo_id='".$_GET['photo_id']."'");
			if (dbrows($result)) {
				$data = dbarray($result);
				$photo_id = $_GET['photo_id'];
				$photo_title = $data['photo_title'];
				$photo_description = $data['photo_description'];
				$photo_filename = $data['photo_filename'];
				$photo_thumb1 = $data['photo_thumb1'];
				$photo_thumb2 = $data['photo_thumb2'];
				$photo_order = $data['photo_order'];
				$photo_comments = $data['photo_allow_comments'] == "1" ? " checked='checked'" : "";
				$photo_ratings = $data['photo_allow_ratings'] == "1" ? " checked='checked'" : "";
				$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;album_id=".$_GET['album_id']."&amp;photo_id=".$_GET['photo_id'];
				add_to_title($locale['global_200'].$locale['401'].$locale['global_201'].$photo_title);
				opentable($album_title.": ".$locale['401']." - ($photo_id - $photo_title)");
			} else {
				redirect(FUSION_SELF.$aidlink);
			}
		} else {
			$photo_title = "";
			$photo_description = "";
			$photo_filename = "";
			$photo_thumb1 = "";
			$photo_thumb2 = "";
			$photo_order = "";
			$photo_comments = " checked='checked'";
			$photo_ratings = " checked='checked'";
			$formaction = FUSION_SELF.$aidlink."&amp;album_id=".$_GET['album_id']."";
			opentable($album_title.": ".$locale['400']);
		}
		echo "<form name='inputform' method='post' action='".$formaction."' enctype='multipart/form-data'>\n";
		echo "<table cellspacing='0' cellpadding='0' class='center'>\n<tr>\n";
		if (isset($_GET['action']) && $_GET['action'] == "edit") {
			$result2 = dbquery("SELECT album_id, album_title FROM ".DB_PHOTO_ALBUMS." WHERE album_id!='".$_GET['album_id']."'");
			if (dbrows($result2)) {
				echo "<td colspan='2' class='tbl' style='text-align:center'>\n";
				echo $locale['430'].": <select class='textbox' name='move_album_id'>\n";
				echo "<option value=''>-- ".$locale['473']." --</option>\n";
				while ($data2 = dbarray($result2)) {
					echo "<option value='".$data2['album_id']."'>".$data2['album_title']."</option>\n";
				}
				echo "</select>\n";
				echo "<input class='button' type='submit' name='move_photo' value='".$locale['431']."' />";
				echo "</td>\n</tr>\n<tr>\n";
			}
		}
		echo "<td class='tbl'>".$locale['432']."</td>\n";
		echo "<td class='tbl'><input type='text' name='photo_title' value='".$photo_title."' maxlength='100' class='textbox' style='width:330px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td valign='top' class='tbl'>".$locale['433']."</td>\n";
		echo "<td class='tbl'><textarea name='photo_description' cols='60' rows='5' class='textbox' style='width:330px;'>".$photo_description."</textarea><br />\n";
		echo display_bbcodes("300px", "photo_description", "inputform", "b|i|u|center|small|url|mail|img|quote")."</td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>".$locale['434']."</td>\n";
		echo "<td class='tbl'><input type='text' name='photo_order' value='".$photo_order."' maxlength='5' class='textbox' style='width:40px;' /></td>\n";
		echo "</tr>\n";
		if ((isset($_GET['action']) && $_GET['action'] == "edit") && ($photo_thumb1 && file_exists(PHOTODIR.$photo_thumb1))) {
			echo "<tr>\n<td valign='top' class='tbl'>".$locale['435']."</td>\n";
			echo "<td class='tbl'><img src='".PHOTODIR.$photo_thumb1."' border='1' alt='".$photo_thumb1."' /></td>\n";
			echo "</tr>\n";
		}
		echo "<tr>\n<td valign='top' class='tbl'>".$locale['436'];
		if ((isset($_GET['action']) && $_GET['action'] == "edit") && ($photo_thumb2 && file_exists(PHOTODIR.$photo_thumb2))) {
			echo "<br /><br />\n<a class='small' href='".FUSION_SELF.$aidlink."&amp;action=deletepic&amp;album_id=".$_GET['album_id']."&amp;photo_id=".$_GET['photo_id']."'>".$locale['455']."</a></td>\n";
			echo "<td class='tbl'><img src='".PHOTODIR.$photo_thumb2."' border='1' alt='".$photo_thumb2."' />";
		} elseif ((isset($_GET['action']) && $_GET['action'] == "edit") && ($photo_filename && file_exists(PHOTODIR.$photo_filename))) {
			echo "<br /><br />\n<a class='small' href='".FUSION_SELF.$aidlink."&amp;action=deletepic&amp;album_id=".$_GET['album_id']."&amp;photo_id=".$_GET['photo_id']."'>".$locale['455']."</a></td>\n";
			echo "<td class='tbl'><img src='".PHOTODIR.$photo_filename."' border='1' alt='".$photo_filename."' />";
		} elseif (isset($_GET['action']) && $_GET['action'] == "edit") {
			echo "</td>\n<td class='tbl'>";
			echo "<input type='file' name='photo_pic_file[]' class='textbox' style='width:250px;' />\n";
		} else {
			echo "</td>\n<td class='tbl'>";
			add_to_head("<script src='".INCLUDES_JS."multiupload.js' type='text/javascript'></script>");
			$photo_types = array(".gif",".jpg",".jpeg",".png");
			$insert_type = ''; $x = true;
			foreach($photo_types as $type) {
				if(substr($type, 0, 1) == '.') { $type = substr($type, 1); }
				$insert_type .= ($x == false ? '|' : '').$type;
				$x = false;
			}
			echo "<input type='file' name='photo_pic_file[]' class='multi' accept='".$insert_type."'  maxlength='50' style='width:200px;' />\n";
		
		}
		echo "</td>\n</tr>\n<tr>\n";
		echo "<td colspan='2' align='center' class='tbl'><br />\n";
		echo "<label><input type='checkbox' name='photo_comments' value='yes'".$photo_comments." /> ".$locale['437']."</label><br />\n";
		echo "<label><input type='checkbox' name='photo_ratings' value='yes'".$photo_ratings." /> ".$locale['438']."<br /><br /></label>\n";
		echo "<input type='submit' name='save_photo' value='".$locale['439']."' class='button' />\n";
		if (isset($_GET['action']) && $_GET['action'] == "edit") {
			echo "<input type='submit' name='cancel' value='".$locale['440']."' class='button' />\n";
		}
		echo "</td></tr>\n</table></form>\n";
		closetable();
	}
	// Subcategories begin ->
	$rows = dbcount("(album_id)", DB_PHOTO_ALBUMS, "album_parent='".$_GET['album_id']."'");
	if ($rows) {
	include LOCALE.LOCALESET."admin/photoalbums.php";
	opentable($locale['402']);
		if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
		$_GET['rowstart'] = $_GET['page'] > 0 ? ($_GET['page']-1) * $settings['thumbs_per_page']: "0";
			$result = dbquery(
			"SELECT ta.album_id, ta.album_title, ta.album_thumb, ta.album_order, ta.album_datestamp, ta.album_parent, ta.album_access,
			tu.user_id, tu.user_name, tu.user_status FROM ".DB_PHOTO_ALBUMS." ta
			LEFT JOIN ".DB_USERS." tu ON ta.album_user=tu.user_id
			WHERE album_parent='".$_GET['album_id']."'
			ORDER BY album_order LIMIT ".$_GET['rowstart'].",".$settings['thumbs_per_page']
		);
		$counter = 0; $k = ($_GET['rowstart'] == 0 ? 1 : $_GET['rowstart'] + 1);
		echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
		if ($rows > $settings['thumbs_per_page']) {
			echo "<div align='center' styl='margin-top:5px;'>\n".pagination(true,$_GET['rowstart'], $settings['thumbs_per_page'], $rows, 3, FUSION_SELF.$aidlink."&amp;")."\n</div>\n"; }
		while ($data = dbarray($result)) {
			$up = ""; $down = "";
			$parent = ($data['album_parent'] == 0 ? "" : "&amp;parent=".$data['album_parent']);
			if ($rows != 1){
				$order = $data['album_order'] - 1;
				$order = $data['album_order'] + 1;
				if ($k == 1){
					$down = " &middot;\n<a href='".FUSION_SELF.$aidlink."&amp;page=".$_GET['rowstart']."&amp;action=down&amp;order=$order&amp;album_id=".$data['album_id']."$parent><img src='".get_image("right")."' alt='".$locale['467']."' title='".$locale['468']."' style='border:0px;vertical-align:middle' /></a>\n";
				}elseif ($k < $rows){
					$up = "<a href='".FUSION_SELF.$aidlink."&amp;page=".$_GET['rowstart']."&amp;action=up&amp;order=$order&amp;album_id=".$data['album_id']."$parent'><img src='".get_image("left")."' alt='".$locale['467']."' title='".$locale['466']."' style='border:0px;vertical-align:middle' /></a> &middot;\n";
					$down = " &middot;\n<a href='".FUSION_SELF.$aidlink."&amp;page=".$_GET['rowstart']."&amp;action=down&amp;order=$order&amp;album_id=".$data['album_id']."$parent><img src='".get_image("right")."' alt='".$locale['467']."' title='".$locale['468']."' style='border:0px;vertical-align:middle' /></a>\n";
				} else {
					$up = "<a href='".FUSION_SELF.$aidlink."&amp;page=".$_GET['rowstart']."&amp;action=up&amp;order=$order&amp;album_id=".$data['album_id']."$parent'><img src='".get_image("left")."' alt='".$locale['467']."' title='".$locale['466']."' style='border:0px;vertical-align:middle' /></a> &middot;\n";
				}
			}
			if ($counter != 0 && ($counter % $settings['thumbs_per_row'] == 0)) { echo "</tr>\n<tr>\n"; }
			echo "<td align='center' valign='top' class='tbl'>\n";
			echo "<strong>".$data['album_title']."</strong><br /><br />\n<a href='photos.php".$aidlink."&amp;album_id=".$data['album_id']."'>";
			if ($data['album_thumb'] && file_exists(PHOTOS.$data['album_thumb'])){
				echo "<img src='".PHOTOS.rawurlencode($data['album_thumb'])."' alt='".$locale['460']."' style='border:0px' />";
			} else {
				echo $locale['461'];
			}
			echo "</a><br /><br />\n<span class='small'>".$up;
			echo "<a href='photoalbums.php".$aidlink."&amp;action=edit&amp;album_id=".$data['album_id']."$parent'>".$locale['468']."</a> &middot;\n";
			echo "<a href='photoalbums.php".$aidlink."&amp;action=delete&amp;album_id=".$data['album_id']."$parent' onclick=\"return PhotosWarning\">".$locale['469']."</a> ".$down;
			echo "<br /><br />\n".$locale['462'].showdate("shortdate", $data['album_datestamp'])."<br />\n";
			echo $locale['463'].profile_link($data['user_id'], $data['user_name'], $data['user_status'])."<br />\n";
			echo $locale['464'].getgroupname($data['album_access'])."<br />\n";
			echo $locale['465'].dbcount("(photo_id)", DB_PHOTOS, "album_id='".$data['album_id']."'")."</span><br />\n";
			echo "</td>\n";
			$counter++; $k++;
		}
		echo "</tr>\n<tr>\n<td align='center' colspan='".$settings['thumbs_per_row']."' class='tbl2'><a href='photoalbums.php".$aidlink."&amp;action=refresh'>".$locale['470']."</a></td>\n</tr>\n</table>\n";
		if ($rows > $settings['thumbs_per_page']) {
			echo "<div align='center' style='margin-top:5px;'>\n".pagination(true,$_GET['rowstart'], $settings['thumbs_per_page'], $rows, 3, FUSION_SELF.$aidlink."&amp;")."\n</div>\n"; }
	closetable();
	} 
	echo "<script type='text/javascript'>\n"."function PhotosWarning(value) {\n";
	echo "return confirm ('".$locale['500']."');\n}\n</script>";
    // Subcategories end <-
	opentable($album_title.": ".$locale['402']);
	$rows = dbcount("(photo_id)", DB_PHOTOS, "album_id='".$_GET['album_id']."'");
	if ($rows) {
		if (!isset($_GET['rowstart']) || isset($_GET['rowstart']) && !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
		$result = dbquery(
			"SELECT tp.photo_id, tp.photo_title, tp.photo_thumb1, tp.photo_datestamp, tp.photo_views, tp.photo_order,
			tu.user_id, tu.user_name, tu.user_status FROM ".DB_PHOTOS." tp
			LEFT JOIN ".DB_USERS." tu ON tp.photo_user=tu.user_id
			WHERE album_id='".$_GET['album_id']."' ORDER BY photo_order
			LIMIT ".$_GET['rowstart'].",".$settings['thumbs_per_page']
		);
		$counter = 0; $k = ($_GET['rowstart'] == 0 ? 1 : $_GET['rowstart'] + 1);
		echo "<form name='move_form' method='post' action='".FUSION_SELF.$aidlink."&amp;album_id=".$_GET['album_id']."'>\n";
		echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
		if ($rows > $settings['thumbs_per_page']) {
			echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], $settings['thumbs_per_page'], $rows, 3, FUSION_SELF.$aidlink."&amp;album_id=".$_GET['album_id']."&amp;")."\n</div>\n";
		}
		while ($data = dbarray($result)) {
			$up = ""; $down = "";
			if ($rows != 1){
				$orderu = $data['photo_order'] - 1;
				$orderd = $data['photo_order'] + 1;
				if ($k == 1) {
					$down = " &middot;\n<a href='".FUSION_SELF.$aidlink."&amp;album_id=".$_GET['album_id']."&amp;rowstart=".$_GET['rowstart']."&amp;action=mdown&amp;order=$orderd&amp;photo_id=".$data['photo_id']."'><img src='".get_image("right")."' alt='".$locale['453']."' title='".$locale['453']."' style='border:0px;vertical-align:middle' /></a>\n";
				} elseif ($k < $rows){
					$up = "<a href='".FUSION_SELF.$aidlink."&amp;album_id=".$_GET['album_id']."&amp;rowstart=".$_GET['rowstart']."&amp;action=mup&amp;order=$orderu&amp;photo_id=".$data['photo_id']."'><img src='".get_image("left")."' alt='".$locale['452']."' title='".$locale['452']."' style='border:0px;vertical-align:middle' /></a> &middot;\n";
					$down = " &middot;\n<a href='".FUSION_SELF.$aidlink."&amp;album_id=".$_GET['album_id']."&amp;rowstart=".$_GET['rowstart']."&amp;action=mdown&amp;order=$orderd&amp;photo_id=".$data['photo_id']."'><img src='".get_image("right")."' alt='".$locale['453']."' title='".$locale['453']."' style='border:0px;vertical-align:middle' /></a>\n";
				} else {
					$up = "<a href='".FUSION_SELF.$aidlink."&amp;album_id=".$_GET['album_id']."&amp;rowstart=".$_GET['rowstart']."&amp;action=mup&amp;order=$orderu&amp;photo_id=".$data['photo_id']."'><img src='".get_image("left")."' alt='".$locale['452']."' title='".$locale['452']."' style='border:0px;vertical-align:middle' /></a> &middot;\n";
				}
			}
			if ($counter != 0 && ($counter % $settings['thumbs_per_row'] == 0)) { echo "</tr>\n<tr>\n"; }
			echo "<td align='center' valign='top' class='tbl'>\n";
			echo "<label><input type='checkbox' name='sel_photo[]' value='".$data['photo_id']."' />&nbsp;<strong>".$data['photo_order']." ".$data['photo_title']."</strong></label><br /><br />\n";
			if ($data['photo_thumb1'] && file_exists(PHOTODIR.$data['photo_thumb1'])){
				echo "<img src='".PHOTODIR.$data['photo_thumb1']."' alt='".$locale['451']."' style='border:0px' />";
			} else {
				echo $locale['450'];
			}
			echo "<br /><br />\n<span class='small'>".$up;
			echo "<a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;album_id=".$_GET['album_id']."&amp;photo_id=".$data['photo_id']."'>".$locale['454']."</a> &middot;\n";
			echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;album_id=".$_GET['album_id']."&amp;photo_id=".$data['photo_id']."'>".$locale['455']."</a> ".$down;
			echo "<br /><br />\n".$locale['456'].showdate("shortdate", $data['photo_datestamp'])."<br />\n";
			echo $locale['457'].profile_link($data['user_id'], $data['user_name'], $data['user_status'])."<br />\n";
			echo $locale['458'].$data['photo_views']."<br />\n";
			echo $locale['459'].dbcount("(comment_id)", DB_COMMENTS, "comment_type='P' AND comment_item_id='".$data['photo_id']."'")."</span><br />\n";
			echo "</td>\n";
			$counter++; $k++;
		}
		$result = dbquery("SELECT album_id, album_title FROM ".DB_PHOTO_ALBUMS." WHERE album_id!='".$_GET['album_id']."'");
		echo "</tr>\n<tr>\n";
		echo "<td align='center' colspan='".$settings['thumbs_per_row']."' class='tbl2'>\n";
		if (dbrows($result)) {
			echo "<a href='#' onclick=\"javascript:setChecked('move_form','sel_photo[]',1);return false;\">".$locale['470']."</a> ::\n";
			echo "<a href='#' onclick=\"javascript:setChecked('move_form','sel_photo[]',0);return false;\">".$locale['471']."</a> ::\n";
			echo $locale['472'].": <select class='textbox' name='move_album_id'>\n";
			echo "<option value=''>-- ".$locale['473']." --</option>\n";
			while ($data = dbarray($result)) {
				echo "<option value='".$data['album_id']."'>".$data['album_title']."</option>\n";
			}
			echo "</select><br />\n<input class='button' type='submit' name='move_sel_photos' value='".$locale['474']."' onclick=\"javascript:return ConfirmMove(0);\" />\n";
			echo "<input class='button' type='submit' name='move_all_photos' value='".$locale['475']."' onclick=\"javascript:return ConfirmMove(1);\" />\n";
		}
		echo "<input class='button' type='button' value='".$locale['476']."' onclick=\"location.href='photoalbums.php".$aidlink."';\" />\n";
		echo "</td>\n</tr>\n</table>\n</form>\n";	
		if (dbrows($result)) {
			echo "<script type='text/javascript'>\n"."function setChecked(frmName,chkName,val) {\n";
			echo "dml=document.forms[frmName];\n"."len=dml.elements.length;"."\n"."for(i=0;i < len;i++) {\n";
			echo "if(dml.elements[i].name == chkName) {"."\n"."dml.elements[i].checked = val;\n";
			echo "}\n}\n}\n"."function ConfirmMove(moveType) {\n";
			echo "if(moveType==0) {"."\n"."return confirm('".$locale['481']."')\n";
			echo "}else{\n"."return confirm('".$locale['482']."')\n";
			echo "}\n}\n</script>\n";
		}
		if ($rows > $settings['thumbs_per_page']) {
			echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], $settings['thumbs_per_page'], $rows, 3, FUSION_SELF.$aidlink."&amp;album_id=".$_GET['album_id']."&amp;")."\n</div>\n";
		}
	}else{
		echo "<div style='text-align:center'>".$locale['480']."</div>\n";
	}
	closetable();
} else {
	opentable($locale['403']);
	echo "<div class='admin-message'>".$locale['420']."</div>\n";
	closetable();
}

require_once TEMPLATES."footer.php";
?>