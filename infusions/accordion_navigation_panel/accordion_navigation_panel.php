<?php
/*-------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+--------------------------------------------------------+
| Filename: accordion_navigation_panel.php
| Author: Wooya
| Version: Pimped Fusion v0.09.00
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

openside($locale['global_001']);
// Load Navigation Cache
if ($navigation_cache == false) { navigation_cache(); }