<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: themes/templates/window_footer.php
| Version: Pimped Fusion v0.08.00
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

require_once INCLUDES."footer_includes.php";
echo "Powered by <a href='http://www.pimped-fusion.net' title='Pimped-Fusion - Open Source Content Management System' target='_blank'>Pimped-Fusion</a> copyright &copy; 2009 - ".date("Y")."<br />\n";
echo "</body>\n</html>\n";

$output = ob_get_contents();
ob_end_clean();
echo handle_output($output);

if (ob_get_length () !== FALSE){
	ob_end_flush();
}

if ($settings['login_method'] == "sessions") {
	session_write_close();
}

mysql_close($db_connect);
?>