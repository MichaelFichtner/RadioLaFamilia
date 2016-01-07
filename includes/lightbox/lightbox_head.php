<?php
/*---------------------------------------------------------------------------+
| Pimped Fusion Content Management System
| http://pimped-fusion.net
+----------------------------------------------------------------------------+
| based on PHP-Fusion CMS v7.01, Copyright (C) 2002 - 2009 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------------------------------+
| Filename: lightbox_head.php
| Version: Pimped Fusion v0.05.00, PHP-Fusion 7.01.00b
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/

/*---------------------------------------------------------------------------+
| Author: olelau
| web:http://phpfusion-freak.dk
+---------------------------------------------------------------------------*/

add_to_head("<link rel=\"stylesheet\" type=\"text/css\" href=\"".INCLUDES."lightbox/jquery.fancybox.css\" media=\"screen\" />
<script type=\"text/javascript\" src=\"".INCLUDES."lightbox/jquery.easing.1.3.js\"></script>
<script type=\"text/javascript\" src=\"".INCLUDES."lightbox/jquery.fancybox-1.2.1.pack.js\"></script>
<script type='text/javascript'>$(document).ready(function() { $(\"a.fancybox\").fancybox({'overlayShow': true })});; </script>");
?>

