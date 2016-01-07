<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: encrypt_password_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

// Encrpyt Passwords in database
function encrypt_pw($string) {
	return encrypt_pw_part2(encrypt_pw_part1($string));
}

// Encrpyt Passwords: This string will be saved in the user's cookie
function encrypt_pw_part1($string) {
	return md5($string);
}

// Encrpyt Passwords: This string will be saved in the database
// You can use Salts in this function
function encrypt_pw_part2($string) {
	if(defined("PIF_SALT")) { $salt = PIF_SALT; } else { $salt = ''; }
	return md5($string.$salt);
}


?>