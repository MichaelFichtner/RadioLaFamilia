<?php
$locale['email_create_subject'] = "Account erstellt auf ";
$locale['email_create_message'] = "Hallo [USER_NAME],\n
für Dich wurde ein Account auf ".$settings['sitename']." erstellt.\n
Du kannst dich nun mit folgendem Benutzernamen und Passwort einloggen.\n
Username: [USER_NAME]\n
Passwort: [PASSWORD]\n\n
Gr&uuml;sse,\n
".$settings['siteusername'];

$locale['email_activate_subject'] = "Account aktiviert auf ";
$locale['email_activate_message'] = "Hallo [USER_NAME],\n
Dein Account auf ".$settings['sitename']." wurde aktiviert.\n
Du kannst dich nun mit deinem Benutzernamen und Passwort einloggen.\n\n
Gr&uuml;sse,
".$settings['siteusername'];

$locale['email_deactivate_subject'] = "Account Re-Aktivierung ben&ouml;tigt auf ".$settings['sitename'];
$locale['email_deactivate_message'] = "Hallo [USER_NAME],\n
Du warst das letzte Mal vor ".$settings['deactivation_period']." Tagen auf ".$settings['sitename']." eingeloggt. Dein Account wurde daher auf inaktiv gesetzt, all deine Daten zum Account sind noch vorhanden, eingeschloßen dem auf der Seite eingereichten Inhalt.\n
Um Deinen Benutzernamen zu reaktivieren klicke einfach auf den folgenden Link:\n
".$settings['siteurl']."reactivate.php?user_id=[USER_ID]&code=[CODE]\n\n
Mit freundlichen Gr&uuml;ssen,\n
".$settings['siteusername'];

$locale['email_ban_subject'] = "Dein Account auf ".$settings['sitename']." wurde gesperrt";
$locale['email_ban_message'] = "Hallo [USER_NAME],\n
Dein Account auf ".$settings['sitename']." wurde gesperrt durch ".$userdata['user_name']." aus folgendem Grund:\n
[REASON].\n
Wenn du mehr Informationen über diese Sperre haben möchtest, kontaktiere bitte den Administrator der Seite: ".$settings['siteemail'].".\n\n
".$settings['siteusername'];

$locale['email_secban_subject'] = "Dein Account auf ".$settings['sitename']." wurde gesperrt";
$locale['email_secban_message'] = "Hello [USER_NAME],\n
Dein Account auf ".$settings['sitename']." wurde gesperrt durch ".$userdata['user_name'].", weil im Zusammenhang mit Dir oder Deinem Account Aktivitäten verzeichnet wurden, die eine Bedrohung der Sicherheit der Website darstellen könnten.\n
Wenn du mehr Informationen über diese Sperre haben möchtest, kontaktiere bitte den Administrator der Seite: ".$settings['siteemail'].".\n\n
".$settings['siteusername'];

$locale['email_suspend_subject'] = "Dein Account auf ".$settings['sitename']." wurde temporär gesperrt";
$locale['email_suspend_message'] = "Hello [USER_NAME],\n
Dein Account auf  ".$settings['sitename']." wurde temporär gesperrt durch ".$userdata['user_name']." bis [DATE] (Serverzeit) aus folgendem Grund:\n
[REASON].\n
Wenn du mehr Informationen über diese Sperre haben möchtest, kontaktiere bitte den Administrator der Seite: ".$settings['siteemail'].".\n\n
".$settings['siteusername'];
?>