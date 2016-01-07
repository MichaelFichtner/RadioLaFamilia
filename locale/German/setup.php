<?php
$locale['title'] = "Pimped-Fusion v%s Setup";
$locale['sub-title'] = "Pimped-Fusion v%s Setup";
 
$locale['charset'] = "UTF-8"; # change it to the old setting, if you have problems // $locale['charset'] = "iso-8859-1";
$locale['001'] = "Schritt 1: Sprache ausw&auml;hlen";
$locale['002a'] = "Schritt 2: Server Check";
$locale['002'] = "Schritt 3: Schreibrechte werden &uuml;berpr&uuml;ft";
$locale['003'] = "Schritt 4: Datenbank Einstellungen";
$locale['004'] = "Schritt 5: Konfiguration und Datenbank erstellen";
$locale['005'] = "Schritt 6: Superadmin Benutzer anlegen";
$locale['006'] = "Schritt 7: Installation abgeschlossen";
$locale['006a'] = "Delete Setup Files"; // Pimped
$locale['006b'] = "Developer Tool"; // Pimped
$locale['007'] = "Weiter";
$locale['008'] = "Zur&uuml;ck";
$locale['009'] = "Fertig";
// Step 1
$locale['010'] = "Bitte w&auml;hle deine Sprache aus:";
$locale['011'] = "Informationen über Pimped-Fusion und mehr Sprachpakete kannst du auf <a href='http://www.pimped-fusion.net'>www.pimped-fusion.net/</a> herunterladen.";
// Step 2
$locale['step200'] = "&Uuml;berpr&uuml;fe Server Versionen/Einstellungen";
$locale['step201'] = "Deine Version";
$locale['step202'] = "Empfohlen";
$locale['step203'] = "PHP-Version";
$locale['step204'] = "MySQL-Version";
$locale['step205'] = "Zend-Version";
$locale['step206'] = "Safe Mod";
$locale['step207'] = "AN";
$locale['step208'] = "AUS";
$locale['step209'] = "Register Globals";
$locale['step210'] = "Magic Quotes";
$locale['step211'] = "Short Open Tag";
$locale['step212'] = "fsockopen";
$locale['step213'] = "aktiviert";
$locale['step214'] = "deaktiviert";
$locale['step215'] = "GD library";
$locale['step216'] = "Memory Limit";
$locale['step217'] = "File Uploads";
$locale['step218'] = "Upload Max Filesize";
$locale['step219'] = "Timezone";
$locale['step220'] = "oder h&ouml;her";
$locale['step221'] = "Max File Uploads";
$locale['step222'] = "Move your Mouse over the Versions/Settings to get some information about them.";
$locale['step223'] = "unkown";

$locale['step280'] = "The PHP-Version of your Server";
$locale['step281'] = "The local MySQL-Version of your server. But you might also use any external MySQL-Server.";
$locale['step282'] = "This is only shown for your information.";
$locale['step283'] = "This Setting of PHP is out of use and deprecated. It should be OFF.";
$locale['step284'] = "Register Globals should be OFF, due to security reasons. This Setting of PHP is out of use and deprecated.";
$locale['step285'] = "This Setting of PHP is out of use and deprecated. It should be OFF.";
$locale['step286'] = "This is only shown for your information. Pimped-Fusion works with Short Open Tag ON and OFF.";
$locale['step287'] = "This is needed to check if there are any newer versions of Pimped-Fusion available. If this feature is disabled it is also ok, and you can continue the installation of Pimped-Fusion.";
$locale['step288'] = "GD library is needed for your Photo Albums. If you do not need those, you can continue the installation process.";
$locale['step289'] = "Memory Limit should be 16 at least, recommended 32 (MB). This sets the maximum amount of memory that a script is allowed to allocate.";
$locale['step290'] = "Whether or not to allow HTTP file uploads. This must be enabled for all Uploads; for example: Forum Attachments, Downloads, Photo Albums";
$locale['step291'] = "This is the maximum size of an uploaded file. Should be 2 at least, recommended 8 MB";
$locale['step292'] = "The maximum number of files allowed to be uploaded simultaneously.";
$locale['step293'] = "This is only shown for your information.";

// Step 3
$locale['020'] = "Um die Installation durchzuf&uuml;hren, m&uuml;ssen die folgenden Dateien und Ordner beschreibbar (CHMOD 777) sein:";
$locale['021'] = "Datei- und Ordnerberechtigungen sind in Ordnung, die Installation kann fortgef&uuml;hrt werden.";
$locale['022'] = "Datei- und Ordnerberechtigungen sind nicht in Ordnung. Bitte &uuml;berpr&uuml;fe die CHMODs und starte die Installation anschließend erneut.";
$locale['023'] = "in Ordnung";
$locale['024'] = "Fehlerhaft";
#Pimped ->
$locale['025'] = "Fehler:";
$locale['026'] = "Der angegebene FTP-Host ist nicht erreichbar! Bitte &uuml;berpr&uuml;fe deine Eingaben oder setze die Dateirechte manuell per FTP-Client.";
$locale['027'] = "Die angegebenen Login-Daten wurden zur&uuml;ckgewiesen! Bitte &uuml;berpr&uuml;fen deine Eingaben oder setze die Dateirechte manuell per FTP-Client.";
$locale['028'] = "Dein Webserver unterst&uuml;tz eine der Funktionen <i>ftp_connect()</i>, <i>ftp_login()</i> oder <i>ftp_site()</i> nicht!
Diese sind jedoch notwendig, um eine automatische Rechtevergabe der Dateien durchzuf&uuml;hren. Bitte setze die notwendigen Dateirechte manuell mittels FTP-Client und aktualisiere die Seite.";
$locale['029'] = "Seite neu laden";
$locale['029a'] = "Automatische Rechtevergabe";
$locale['029b'] = "Es besteht die M&ouml;glichkeit durch die Eingabe der FTP-Daten die Rechte automatisch setzen zu lassen, was um einiges schneller geht als jeder Datei bzw. jedem Ordner manuell die notwendigen Rechte zu verleihen.";
$locale['029c'] = "Hinweis";
$locale['029d'] = "Als Pfad muss der FTP-Pfad zum Stammverzeichnis eingetragen werden!";
$locale['029e'] = "Beispiel:";
$locale['029f'] = "FTP-Host";
$locale['029g'] = "FTP-Pfad";
$locale['029h'] = "FTP-Benutzername";
$locale['029i'] = "FTP-Passwort";
$locale['029j'] = "Automatische Rechtevergabe!";
// Step 4 - Access criteria
$locale['030'] = "Gib hier bitte die Zugangsdaten deiner MySQL-Datenbank ein:<br /><br />(Du musst bei jeder Installation ein anderes Tabellenpr&auml;fix nutzen)";
$locale['031'] = "Datenbank Hostname:";
$locale['032'] = "Datenbank-Benutzer:";
$locale['033'] = "Datenbank-Passwort:";
$locale['034'] = "Name der Datenbank:";
$locale['035'] = "Tabellenpr&auml;fix:";
$locale['036'] = "Cookiepr&auml;fix:"; // Pimped 
// Step 5 - Database Setup
$locale['040'] = "Verbindung zur Datenbank hergestellt.";
$locale['041'] = "Konfigurationsdatei erfolgreich abgespeichert.";
$locale['042'] = "Die Datenbanktabellen wurden erstellt.";
$locale['043'] = "Fehler:";
$locale['044'] = "Verbindung zur MySQL-Datenbank konnte nicht hergestellt werden.";
$locale['045'] = "Bitte &uuml;berpr&uuml;fe die Zugangsdaten (Username/Passwort) deiner MySQL-Datenbank.";
$locale['046'] = "Konfigurationsdatei konnte nicht erstellt werden.";
$locale['047'] = "Bitte &uuml;berpr&uuml;fe ob die config.php beschreibbar ist.";
$locale['048'] = "Datenbanktabellen konnten nicht erstellt werden.";
$locale['049'] = "Bitte gib den Namen der Datenbank ein.";
$locale['050'] = "Kann keine Verbindung zur MySQL-Datenbank herstellen.";
$locale['051'] = "Die angegebene MySQL-Datenbank existiert nicht.";
$locale['052'] = "Fehler mit dem Tabellen Prefix.";
$locale['053'] = "Das angegebene Tabellen Prefix wird bereits benutzt.";
$locale['054'] = "Kann MySQL Tabellen nicht schreiben oder l&ouml;schen.";
$locale['055'] = "Stelle bitte sicher, dass der Benutzer entsprechende Rechte besitzt, um Tabellen zu lesen, zu schreiben und zu l&ouml;schen.";
$locale['056'] = "Leere Felder.";
$locale['057'] = "Bitte stelle sicher, dass alle Felder ausgef&uuml;llt sind f&uuml;r eine MySQL-Verbindung.";
// Step 6 - Super Admin login
$locale['060'] = "Gib die Zugangsdaten f&uuml;r den Superadmin-Account ein:<br /><br />(Notiz: die Login- und Administrationspassw&ouml;rter m&uuml;ssen unterschiedlich sein)";
$locale['061'] = "Benutzername:";
$locale['062'] = "Login Passwort:";
$locale['063'] = "Login Passwort (wiederholen):";
$locale['064'] = "Admin Passwort:";
$locale['065'] = "Admin Passwort (wiederholen):";
$locale['066'] = "E-Mail-Adresse:";
$locale['067'] = "Bitte benutze nur alphanumerische Zeichen. Das Passwort muss mindestens 6 Zeichen lang sein";
// Step 7 - User details validation
$locale['070'] = "Benutzername enth&auml;lt ung&uuml;ltige Zeichen.";
$locale['070b'] = "User name field can not be left empty.";
$locale['071'] = "Deine Loginpassw&ouml;rter sind nicht identisch.";
$locale['072'] = "Ung&uuml;ltiges Loginpasswort. Bitte benutze nur alphanumerische Zeichen.<br />Außerdem muss das Passwort mindestens 6 Zeichen lang sein";
$locale['072b'] = "Passwort darf nicht leer sein";
$locale['073'] = "Deine Administrationspassw&ouml;rter sind nicht identisch.";
$locale['074'] = "Die Login- und Administrationspassw&ouml;rter m&uuml;ssen unterschiedlich sein.";
$locale['075'] = "Ung&uuml;ltiges Administratorpasswort. Bitte benutze nur alphanumerische Zeichen.<br />Außerdem muss das Passwort mindestens 6 Zeichen lang sein";
$locale['075b'] = "Admin Passwort darf nicht leer sein.";
$locale['076'] = "Du hast keine g&uuml;ltige E-Mail-Adresse angegeben.";
$locale['076b'] = "E-Mail darf nicht leer sein.";
$locale['077'] = "Bei den Zugangsdaten ist ein Fehler aufgetreten:";
// Step 7 - Admin Sections
$locale['092a'] = "Forum Post Ratings"; // Pimped # all the other variables are defined in /locale/admin/main.php
// Step 7 - Navigation Links
$locale['130'] = "Startseite";
$locale['131'] = "Artikel";
$locale['132'] = "Downloads";
$locale['133'] = "FAQ";
$locale['134'] = "Forum";
$locale['135'] = "Kontakt";
$locale['136'] = "News Kategorien";
$locale['137'] = "Weblinks";
$locale['138'] = "Foto Galerie";
$locale['139'] = "Suche";
$locale['140'] = "Link einsenden";
$locale['141'] = "News einsenden";
$locale['142'] = "Artikel einsenden";
$locale['143'] = "Foto einsenden";
// Stage 7 - Panels
$locale['160'] = "Navigation";
$locale['161'] = "Benutzer Online";
$locale['162'] = "Foren Threads";
$locale['163'] = "Letzte Artikel";
$locale['164'] = "Begr&uuml;ßungstext";
$locale['165'] = "Forum Thread Liste";
$locale['166'] = "Alte Benutzer Info"; // Pimped
$locale['166a'] = "Neue Erweitere Benutzer Info"; // Pimped
$locale['167'] = "Mitglieder Umfrage";
$locale['168'] = "Shoutbox";
$locale['169'] = "Letzte User"; // Pimped
$locale['170'] = "Alternative CSS-Navigation"; // Pimped
// Stage 7 - News Categories
$locale['180'] = "Fehler";
$locale['181'] = "Downloads";
$locale['182'] = "Spiele";
$locale['183'] = "Grafik";
$locale['184'] = "Hardware";
$locale['185'] = "Journal";
$locale['186'] = "Mitglieder";
$locale['187'] = "Mods";
$locale['188'] = "Filme";
$locale['189'] = "Netzwerk";
$locale['190'] = "News";
$locale['191'] = "PHP-Fusion";
$locale['192'] = "Sicherheit";
$locale['193'] = "Software";
$locale['194'] = "Themes";
$locale['195'] = "Windows";
// Stage 7 - Sample Forum Ranks
$locale['200'] = "Super Admin";
$locale['201'] = "Admin";
$locale['202'] = "Moderator";
$locale['203'] = "Newbie";
$locale['204'] = "Junior Member";
$locale['205'] = "Member";
$locale['206'] = "Senior Member";
$locale['207'] = "Veteran Member";
$locale['208'] = "Fusioneer";
// Stage 7 - User Field Categories
$locale['220'] = "Kontakt Information";
$locale['221'] = "Sonstige Informationen";
$locale['222'] = "Optionen";
$locale['223'] = "Statistiken";
// Welcome message
$locale['230'] = "Willkommen auf deiner Seite";
// Final message
### Pimped
$locale['240'] = "Die Installation ist abgeschlossen, du kannst Pimped-Fusion nun benutzen.<br />
Klicke <a href='../index.php'>hier</a>, um auf deine Pimped-Fusion Seite zu gelangen.<br /><br />
<strong>Sicherheits-Hinweis:</strong><br />
Du musst die setup.php von deinem Webserver l&ouml;schen (und die update.php falls du diese Datei auch hochgeladen hast) und die Schreibrechte der Datei config.php aus Sicherheitsgr&uuml;nden zur&uuml;ck auf 644 &auml;ndern.<br />
<br />
<strong>Note for developers and testers:</strong><br />
You have now installed a clean installation of Pimped-Fusion. If you like to fill your installation with some sample content (Users, Articles, Forum Threads) <a href='setup.php?step=9".(isset($_POST['localeset']) ? "&amp;localset=".$_POST['localeset'] : "")."'>click here</a>.<br />
<br />
<br />
Gehe weiter zum n&auml;chsten Schritt, um die Dateien setup.php und update.php zu l&ouml;schen<br /><br />
Vielen Dank, dass du dich f&uuml;r Pimped-Fusion entschieden hast.";
$locale['241'] = "L&ouml;sche setup.php und update.php... <br />
Warte, in 10 Sekunden wirst du zur Startseite deiner neuen Website weitergeleitet<br />
Vielen Dank, dass du dich f&uuml;r Pimped-Fusion entschieden hast.";
$locale['242'] = "L&ouml;sche setup.php";
$locale['243'] = "Fill your database with some sample content. For example News, Users, Articles, Forum categories, Forum Threads etc.<br />
<br />
This may be interesting for developers or theme coders<br /><br />";
$locale['244'] = "Insert Sample Data";
$locale['245'] = "<br />
Go and delete your setup-files<br />";
$locale['246'] = "Delete Setup Files";
$locale['247'] = "insert sucessfull";
// Security questions
### Pimped
$locale['250'] = "Hauptstadt von Deutschland?";
$locale['250r']= "Berlin";
$locale['251'] = "Hauptstadt von England";
$locale['251r']= "London";
$locale['252'] = "Bist du ein Mensch?";
$locale['252r']= "Ja";
$locale['253'] = "Bist du ein Spambot?";
$locale['253r']= "Nein";
$locale['254'] = "Das Ergebnis von 25 + 4 ist?";
$locale['254r']= "29";
$locale['255'] = "Das Ergebnis von 13 + 7 ist?";
$locale['255r'] = "20";
?>