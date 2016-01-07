<?php
// Error messages
$locale['500'] = "Es ist ein Fehler aufgetreten";
$locale['501'] = "Der Reaktivierungslink ist nicht mehr g&uuml;ltig.<br /><br />
Kontaktiere einen Administrator &uuml;ber <a href='mailto:".$settings['siteemail']."'>".$settings['siteemail']."</a> um eine manuelle Reaktivierung zu veranlassen.";
$locale['502'] = "Der Reaktivierungslink ist nicht g&uuml;ltig!<br /><br />
Kontaktiere einen Administrator &uuml;ber <a href='mailto:".$settings['siteemail']."'>".$settings['siteemail']."</a> um eine manuelle Reaktivierung zu veranlassen.";
$locale['503'] = "Der Reaktivierungslink, den du gefolgt bist, konnte deinen Account nicht reaktivieren.<br />
Vielleicht wurde Dein Account bereits reaktiviert und Du kannst Dich <a href='".$settings['siteurl']."login.php'>hier einloggen</a>.<br /><br />
Wenn Du dich nicht einloggen kannst, kontaktiere einen Administrator &uuml;ber <a href='mailto:".$settings['siteemail']."'>".$settings['siteemail']."</a> um eine manuelle Reaktivierung zu veranlassen.";
// Send confirmation mail
$locale['504'] = "Account reaktiviert auf ".$settings['sitename'];
$locale['505'] = "Hallo [USER_NAME],\n
Dein Account auf ".$settings['sitename']." wurde reaktiviert. Wir hoffen dass wir Dich nun &ouml;fter auf unserer Webseite begr&uuml;ssen d&uuml;rfen.\n\n
Gruss,\n\n
".$settings['siteusername'];
$locale['506'] = "Vom User reaktiviert.";
?>