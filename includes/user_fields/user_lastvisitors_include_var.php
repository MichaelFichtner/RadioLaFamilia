<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: lastvisitors_include.php
| Version: Pimped Fusion v0.07.00
+----------------------------------------------------------------------------+
| Authors: slaughter, Christoph Schreck, Smokeman
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

$user_field_name = $locale['uf_lastvis_01'].":";
$user_field_desc = $locale['uf_lastvis_02'];
$user_field_dbname = "user_lastvisitors";
$user_field_group = 2;
$user_field_dbinfo = "VARCHAR(200) NOT NULL DEFAULT ''";
?>