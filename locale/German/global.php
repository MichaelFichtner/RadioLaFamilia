<?php
/*
German Language Fileset
for Pimped-Fusion 0.09.00
http://www.pimped-fusion.net
*/
 
// Locale Settings
setlocale(LC_TIME, "de_DE.UTF8", "de_DE@euro", "de", "DE"); // Linux Server (Windows may differ)
$locale['charset'] = "UTF-8"; # change it to the old setting, if you have problems
// $locale['charset'] = "iso-8859-1";
$locale['xml_lang'] = "de";
$locale['tinymce'] = "de";
$locale['phpmailer'] = "de";
 
// Full & Short Months
$locale['months'] = "&nbsp|Januar|Februar|M&auml;rz|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember";
$locale['shortmonths'] = "&nbsp|Jan|Feb|M&auml;r|Apr|Mai|Jun|Jul|Aug|Sept|Okt|Nov|Dez";
 
// Global
define('L_YES', "ja");
define('L_NO', "nein");
define('L_SAVE', "Speichern");
define('L_SELECTPLEASE', "- Bitte ausw&auml;hlen -");

// Standard User Levels
define('L_PUBLIC', "&Ouml;ffentlich");
	$locale['user0'] = "&Ouml;ffentlich";
define('L_MEMBER', "Mitglied");
	$locale['user1'] = "Mitglied";
define('L_ADMINISTRATOR', "Administrator");
	$locale['user2'] = "Administrator";
define('L_SUPERADMIN', "Super Administrator");
	$locale['user3'] = "Super Administrator";
define('L_ONLYGUESTS', "nur G&auml;ste");
	$locale['user4'] = "nur G&auml;ste";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Anonymous User
$locale['useranonym'] = "Anonymous User";
// Navigation
$locale['global_001'] = "Navigation";
$locale['global_002'] = "Keine Seiten Links definiert\n";
// Users Online
$locale['global_010'] = "Benutzer Online";
$locale['global_011'] = "G&auml;ste Online";
$locale['global_012'] = "Mitglieder Online";
$locale['global_013'] = "Keine Mitglieder Online";
$locale['global_014'] = "Registrierte Mitglieder";
$locale['global_015'] = "Unaktivierte Mitglieder";
$locale['global_016'] = "Neustes Mitglied";
// Forum Side panel
$locale['global_020'] = "Forum Themen";
$locale['global_021'] = "Neueste Themen";
$locale['global_022'] = "Hei&szlig;este Themen";
$locale['global_023'] = "Keine Themen vorhanden";
// Articles Side panel
$locale['global_030'] = "Neueste Artikel";
$locale['global_031'] = "Keine Artikel vorhanden";
// Welcome panel
$locale['global_035'] = "Willkommen";
// Latest Active Forum Threads panel
$locale['global_040'] = "Letztes aktives Forum Thema";
$locale['global_041'] = "Meine letzten Themen";
$locale['global_042'] = "Meine letzten Beitr&auml;ge";
$locale['global_043'] = "Neue Beitr&auml;ge";
$locale['global_044'] = "Thema";
$locale['global_045'] = "Aufrufe";
$locale['global_046'] = "Antworten";
$locale['global_047'] = "Letzter Beitrag";
$locale['global_048'] = "Forum";
$locale['global_049'] = "Geschrieben am";
$locale['global_050'] = "Autor";
$locale['global_051'] = "Umfrage";
$locale['global_052'] = "Moved";
$locale['global_053'] = "Du hast noch keine Themen im Forum gestartet.";
$locale['global_054'] = "Du hast noch keine Beitr&auml;ge im Forum geschrieben.";
$locale['global_055'] = "Es gibt %u neue Beitr&auml;ge seit deinem letzten Besuch.";
$locale['global_056'] = "Meine Abonnements";
$locale['global_057'] = "Optionen";
$locale['global_058'] = "Stop";
$locale['global_059'] = "Du verfolgst derzeit keine Themen.";
$locale['global_060'] = "Dieses Thema nicht mehr verfolgen?";
$locale['global_061'] = "Sprache"; // Pimped
$locale['global_062'] = "Zeige mehr Threads"; // Pimped
$locale['global_063'] = "Zeige weniger Threads"; // Pimped
$locale['global_064'] = "Einstellungen"; // Pimped
$locale['global_065'] = "Zeige nur Threads mit der Sprache:"; // Pimped
$locale['global_066'] = "Speichere Einstellungen"; // Pimped
$locale['global_067'] = " [erledigt] "; // Pimped
// News & Articles
$locale['global_070'] = "Geschrieben von ";
$locale['global_071'] = "am ";
$locale['global_072'] = "Mehr lesen";
$locale['global_073'] = " Kommentare";
$locale['global_073b'] = " Kommentar";
$locale['global_074'] = " gelesen";
$locale['global_075'] = "Drucken";
$locale['global_076'] = "Bearbeiten";
$locale['global_077'] = "News";
$locale['global_078'] = "Es wurden noch keine News ver&ouml;ffentlicht";
$locale['global_079'] = "In ";
$locale['global_080'] = "Keine Kategorie";
// Page Navigation
$locale['global_090'] = "Zur&uuml;ck";
$locale['global_091'] = "Vor";
$locale['global_092'] = "Seite ";
$locale['global_093'] = " von ";
// Guest User Menu
$locale['global_100'] = "Login";
$locale['global_101'] = "Benutzername";
$locale['global_102'] = "Passwort";
$locale['global_103'] = "Login merken";
$locale['global_104'] = "Login";
$locale['global_105'] = "Noch kein Mitglied?<br /><a href='".BASEDIR.make_url("register.php", "register", "", ".html")."' class='side'>Registriere</a> dich jetzt."; // Pimped: make_url
$locale['global_106'] = "Passwort vergessen?<br />Jetzt ein <a href='".make_url("lostpassword.php", "lostpassword", "", ".html")."' class='side'>neues Passwort</a> zuschicken lassen."; // Pimped: make_url
$locale['global_107'] = "Registrieren";
$locale['global_108'] = "Passwort vergessen";
// Member User Menu
$locale['global_120'] = "Profil bearbeiten";
$locale['global_121'] = "Private Nachrichten";
$locale['global_122'] = "Mitgliederliste";
$locale['global_123'] = "Administration";
$locale['global_124'] = "Logout";
$locale['global_125'] = "Du hast %u neue ";
$locale['global_126'] = "Nachricht";
$locale['global_127'] = "Nachrichten";
$locale['global_128'] = "Gemeldete Posts";
// Poll
$locale['global_130'] = "Mitglieder Umfrage";
$locale['global_131'] = "Abstimmen";
$locale['global_132'] = "Nur Mitglieder k&ouml;nnen an der Umfrage teilnehmen.";
$locale['global_133'] = "Stimme";
$locale['global_134'] = "Stimmen";
$locale['global_135'] = "Stimmen: ";
$locale['global_136'] = "Gestartet: ";
$locale['global_137'] = "Beendet: ";
$locale['global_138'] = "Umfragen Archiv";
$locale['global_139'] = "W&auml;hle eine Umfrage aus der Liste:";
$locale['global_140'] = "Anzeigen";
$locale['global_141'] = "Umfrage ansehen";
$locale['global_142'] = "Keine Umfrage vorhanden";
// Shoutbox
$locale['global_150'] = "Shoutbox";
$locale['global_151'] = "Name:";
$locale['global_152'] = "Mitteilung:";
$locale['global_153'] = "Shout";
$locale['global_154'] = "G&auml;sten ist das schreiben von Mitteilungen nicht erlaubt.<br /><br />";
$locale['global_155'] = "Shoutbox Archiv";
$locale['global_156'] = "Keine Nachrichten vorhanden.";
$locale['global_157'] = "L&ouml;schen";
$locale['global_158'] = "Sicherheitscode:";
$locale['global_159'] = "Sicherheitscode eingeben:";
// Footer Counter
$locale['global_170'] = "eindeutiger Besuch";
$locale['global_171'] = "eindeutige Besuche";
$locale['global_172'] = "Seitenaufbau: %s Sekunden";
$locale['global_173'] = "Queries";
// Admin Navigation
$locale['global_180'] = "Administration";
$locale['global_181'] = "Zur&uuml;ck zur Seite";
$locale['global_182'] = "<strong>Achtung:</strong> Administrator-Passwort wurde nicht oder falsch eingegeben.";
// Miscellaneous
$locale['global_190'] = "Wartungsmodus aktiviert";
$locale['global_191'] = "Deine IP-Adresse ist auf unserer Blacklist.";
$locale['global_192'] = "Logout als ";
$locale['global_193'] = "Login als ";
$locale['global_194'] = "Dieser Benutzer Account ist zur Zeit gesperrt.";
$locale['global_195'] = "Dieser Benutzer Account ist noch nicht aktiviert worden.";
$locale['global_196'] = "Ung&uuml;ltiger Benutzername oder Passwort.";
$locale['global_197'] = "Einen Augenblick, du wirst weitergeleitet...<br /><br />
[ <a href='".REDIRECT_TO."'>oder klicke hier um sofort weitergeleitet zu werden.</a> ]"; // Pimped
$locale['global_198'] = "<strong>Warnung:</strong> setup.php ist noch vorhanden, umgehend l&ouml;schen.";
$locale['global_198b'] = "<strong>Warnung:</strong> update.php ist noch vorhanden, umgehend l&ouml;schen."; // Pimped: added
$locale['global_199'] = "<strong>Warnung:</strong> Du hast kein Admin Passwort eingestellt, gehe auf <a href='".BASEDIR."edit_profile.php'>Profil bearbeiten</a> um eins zu setzen.";
//Titles
$locale['global_200'] = " - ";
$locale['global_201'] = ": ";
$locale['global_202'] = $locale['global_200']."Suche";
$locale['global_203'] = $locale['global_200']."FAQ";
$locale['global_204'] = $locale['global_200']."Forum";
//Themes
$locale['global_210'] = "Direkt zum Inhalt";
// No themes found
$locale['global_300'] = "Kein Theme gefunden";
$locale['global_301'] = "Entschuldigung, aber diese Seite kann nicht angezeigt werden. Aus bestimmten Gr&uuml;nden, kann kein Theme gefunden werden. Wenn du der Haupt Administrator bist, verwende deinen FTP Client, um ein anderes Theme, welches f&uuml;r <em>Pimped-Fusion</em> designed ist und lade es in den <em>themes/</em> Ordner. Nach dem Upload, &uuml;berpr&uuml;fe in den <em>Haupteinstellungen</em>, ob das ausgew&auml;lte Theme korrekt in das Verzeichnis <em>themes/</em> geladen wurde. Bitte beachte, dass der hochgeladene Theme Ordner den gleichen Namen hat (inclusive Gross- Kleinschreibung, was f&uuml;r Unix Server wichtig ist) wie das Theme in den <em>Haupteinstellungen</em>.<br /><br />Wenn du ein normales Mitglied dieser Seite bist, kontaktiere bitte den Haupt Administrator &uuml;ber diese ".hide_email($settings['siteemail'])." E-Mail und berichte &uuml;ber diesen Umstand.";
$locale['global_302'] = "Das gew&auml;hlte Theme in den Haupteinstellungen existiert nicht oder ist unvollst&auml;ndig!";
// User Management
// Member status
$locale['global_400'] = "suspendiert"; #überarbeiten?
$locale['global_401'] = "gebannt";
$locale['global_402'] = "deaktiviert";
$locale['global_403'] = "Account stillgelegt";
$locale['global_404'] = "Account anonymisiert";
$locale['global_405'] = "anonymisierter User";
$locale['global_406'] = "Dieser Account wurde aus folgendem Grund gebannt:";
$locale['global_407'] = "Dieser Account wurde tempor&auml;r gesperrt bis ";
$locale['global_408'] = " aus folgendem Grund:";
$locale['global_409'] = "Dieser Account wurde aus Sicherheitsgr&uuml;nden gesperrt.";
$locale['global_410'] = "Der Grund daf&uuml;r ist: ";
$locale['global_411'] = "Die Mitgliedschaft dieses Accounts wurde beendet.";
$locale['global_412'] = "Dieser Account wurde anonymisiert, wahrscheinlich aufgrund von Inaktivit&auml;t.<br />
Wende dich an den Administrator f&uuml;r weitere Informationen.<br />
Der Account kann leider nicht wiederhergestellt werden.<br />";
// Banning due to flooding
$locale['global_440'] = "Automatisierte Sperre durch eine Spamflood-Kontrolle";
$locale['global_441'] = "Dein Account auf ".$settings['sitename']."wurde gesperrt";
$locale['global_442'] = "Hallo [USER_NAME],\n
Unser System hat festgestellt, dass Dein Account auf ".$settings['sitename']." zu viele Anfragen bzw. Beitr&auml;ge in zu kurzer Zeit verfasst hat.\n
Diese Beitr&auml;ge wurden von folgender IP verfasst: ".USER_IP.". Die Sperre wurde automatisiert durchgef&uuml;hrt, um das Versenden von Spam-Nachrichten von sogenannten Bots zu verhindern.\n
Bitte kontaktiere den Administrator der Seite durch ".$settings['siteemail'].", um Deinen Account wieder freizuschalten.\n\n
Mit freundlichen Gr&uuml;ßen\n
das Sicherheitssystem
".$settings['siteusername'];
// Lifting of suspension
$locale['global_450'] = "Tempor&auml;re Sperre automatisch vom System aufgehoben";
$locale['global_451'] = "Tempor&auml;re Sperre auf ".$settings['sitename']." aufgehoben";
$locale['global_452'] = "Hallo USER_NAME,\n
Die tempor&auml;re Sperre Deines Accounts auf ".$settings['siteurl']." wurde aufgehoben. Hier sind Deine Login-Details:\n
Username: USER_NAME\n
Passwort: aus Sicherheitsgr&uuml;nden versteckt\n
Falls Du Dein Passwort vergessen hast, kannst Du es durch folgenden Link resetten: LOST_PASSWORD\n\n
Regards,\n
".$settings['siteusername'];
$locale['global_453'] = "Hallo USER_NAME,\n
Die temporäre Sperre Deines Accounts auf ".$settings['siteurl']." wurde aufgehoben.\n\n
Viele Gr&uuml;sse,\n
".$settings['siteusername'];
$locale['global_454'] = "Account reaktiviert auf ".$settings['sitename'];
$locale['global_455'] = "Hallo USER_NAME,\n
Bei Deinem letzten Login wurde Dein Account auf ".$settings['siteurl']." reaktiviert und ist nun nicht mehr als inaktiv markiert.\n\n
Viele Gr&uuml;sse,\n
".$settings['siteusername'];

// Safe Redirect
$locale['global_500'] = "Du wirst weitergeleitet zu %s, bitte warten. Falls du nicht weitergeleitet wirst, klicke hier.";

// Recaptcha
$locale['recap101'] = "Recaptcha Validation Code:";
$locale['recap102'] = "The reCAPTCHA wasn't entered correctly.";
$locale['recap103'] = "Recaptcha is enabled, but there was no input!";

// Failed Login Attempts
$locale['flogins_100'] = "Jemand hat versucht, sich auf deinem Account einzuloggen";
$locale['flogins_101'] = "Jemand hat versucht, sich auf deinem Account einzuloggen.\nEs wurden %s gescheitere Login-Versuche aufgezeichnet.\n";
$locale['flogins_102'] = "Der Login-Versuch fand am %s statt.";
$locale['flogins_103'] = "Der erste Login-Versuch fand am %s statt und der letzte am %s";

// Language Switcher
$locale['langswitch_100'] = "Sprachen";

// Share this
$locale['share_001'] = "URL:";
$locale['share_002'] = "BB-Code:";
$locale['share_003'] = "HTML:";
$locale['share_004'] = "AddThis:";
$locale['share_005'] = "Share this thread";
$locale['share_006'] = "Share this news";
$locale['share_007'] = "Share this article";
?>