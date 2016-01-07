<?php
----------------------------------------------------------------------------+
----------------------------------------------------------------------------+
----------------------------------------------------------------------------+
---------------------------------------------------------------------------*/
function add_tags($type, $class = "tbl") {
	$res = '';
	if ($settings['enable_tags']) {
		$res .= "<tr>\n<td valign='top' width='100' class='".$class."'>".$locale['tag_add']."</td>\n";
	}
	return $res;
}
function edit_tags($item_id, $type, $class = "tbl") {
	global $locale, $settings;
	$res = '';
		$data = dbarray(dbquery("SELECT tag_name FROM ".DB_TAGS." WHERE tag_item_id='".(int)$item_id."' AND tag_type="._db($type).""));
		$res .= "<tr>\n<td valign='top' width='100' class='".$class."'>".$locale['tag_add']."</td>\n";
		$res .= "<td class='tbl1'>";
		$res .= "<input type='text' name='tag_name' value='".$data['tag_name']."' class='textbox' style='width:285px;' /><br />";
	}
	return $res;
}
function insert_tags($item_id, $type, $name) {
		$result = dbquery("INSERT INTO ".DB_TAGS." (tag_item_id, tag_type, tag_name) VALUES ('".(int)$item_id."', "._db($type).", "._db($name).")");
}
function update_tags($item_id, $type, $name) {
			$result = dbquery("UPDATE ".DB_TAGS." SET tag_name="._db($name)." WHERE tag_item_id='".(int)$item_id."' AND tag_type="._db($type)."");
}
function delete_tags($item_id, $type) {
	$result = dbquery("DELETE FROM ".DB_TAGS." WHERE tag_item_id='".(int)$item_id."' AND tag_type="._db($type)."");
}
function show_tags($item_id, $type) {
	global $settings, $locale;
		$result = dbquery("SELECT tag_name FROM ".DB_TAGS." WHERE tag_item_id='".(int)$item_id."' AND tag_type="._db($type));
		if (dbrows($result)) {
			$data = dbarray($result);
		}
}
?>