<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/includes/warning_info.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter, pirdani, emblinux
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../../maincore.php";
require_once TEMPLATES."window_header.php";
require_once LOCALE.LOCALESET."warning.php";

include_once INCLUDES."warning.inc.php";

opentable($locale['WARN300']);

echo "<table width='100%' cellpadding='2' cellspacing='0' border='0' align='center'>
	<tr class='tbl2'>
		<td align='center' valign='top' colspan='3'><b>".$locale['WARN326']."</b></td>
	</tr>
	<tr>
		<td colspan='3'>".$locale['WARN301']."</td>
	</tr>
	<tr>
		<td align='center' valign='top' width='100'>".show_warning_symbols(0)."</td>
		<td align='left' valign='top' width='100'>".$locale['WARN302']."</td>
		<td align='left' valign='top'>".$locale['WARN311']."</td>
	</tr>
	<tr>
		<td align='center' valign='top'>".show_warning_symbols(15)."</td>
		<td align='left' valign='top'>".$locale['WARN303']."</td>
		<td align='left' valign='top'>".$locale['WARN312']."</td>
	</tr>
	<tr>
		<td align='center' valign='top'>".show_warning_symbols(30)."</td>
		<td align='left' valign='top'>".$locale['WARN304']."</td>
		<td align='left' valign='top'>".$locale['WARN313']."</td>
	</tr>
	<tr>
		<td align='center' valign='top'>".show_warning_symbols(45)."</td>
		<td align='left' valign='top'>".$locale['WARN305']."</td>
		<td align='left' valign='top'>".$locale['WARN314']."</td>
	</tr>
	<tr>
		<td align='center' valign='top'>".show_warning_symbols(60)."</td>
		<td align='left' valign='top'>".$locale['WARN306']."</td>
		<td align='left' valign='top'>".$locale['WARN315']."</td>
	</tr>
	<tr>
		<td align='center' valign='top'>".show_warning_symbols(75)."</td>
		<td align='left' valign='top'>".$locale['WARN307']."</td>
		<td align='left' valign='top'>".$locale['WARN316']."</td>
	</tr>
	<tr>
		<td align='center' valign='top'>".show_warning_symbols(90)."</td>
		<td align='left' valign='top'>".$locale['WARN308']."</td>
		<td align='left' valign='top'>".$locale['WARN317']."</td>
	</tr>
	<tr>
		<td align='center' valign='top'>".show_warning_symbols(99)."</td>
		<td align='left' valign='top'>".$locale['WARN309']."</td>
		<td align='left' valign='top'>".$locale['WARN318']."</td>
	</tr>
	<tr>
		<td align='center' valign='top' >".show_warning_symbols(100)."</td>
		<td align='left' valign='top'>".$locale['WARN310']."</td>
		<td align='left' valign='top'>".$locale['WARN319']."</td>
	</tr>";
	$warning_catalog = dbquery("SELECT warn_point, warn_length, warn_subject FROM ".DB_WARNING_CATALOG." ORDER BY warn_point, warn_length");
	if(dbrows($warning_catalog)) {
	echo "
	<tr>
		<td colspan='3'>&nbsp;</td>
	</tr>
	<tr class='tbl2'>
		<td align='center' valign='top' colspan='3'><b>".$locale['WARN320']."</b></td>
	</tr>
	<tr>
		<td align='center' valign='top' ><b>".$locale['WARN321']."</b></td>
		<td align='center' valign='top'><b>".$locale['WARN322']."</b></td>
		<td align='left' valign='top'><b>".$locale['WARN323']."</b></td>
	</tr>";
	while($data = dbarray($warning_catalog)) {
		echo "
			<tr>
				<td align='center' valign='top'>".$data['warn_point']."</td>
				<td align='left' valign='top'>".$data['warn_length']." ".$locale['WARN324']."</td>
				<td align='left' valign='top'>".$data['warn_subject']."</td>
			</tr>";
	}
	echo "	<tr>
		<td colspan='3'>&nbsp;</td>
	</tr>
	<tr>
		<td align='center' valign='top' colspan='3'>".$locale['WARN325']."</td>
	</tr>";
	}
echo "
	<tr>
		<td colspan='3'>&nbsp;</td>
	</tr>
	<tr class='tbl2'>
		<td align='center' valign='top' colspan='3'><b>".$locale['WARN327']."</b></td>
	</tr>
	<tr>
		<td align='left' valign='top' colspan='3'>".$locale['WARN328']."</td>
	</tr>
</table>";
echo "<br />";

closetable();

require_once TEMPLATES."window_footer.php";
?>