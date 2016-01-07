<?php
$locale['400'] = "Registrierung";
$locale['401'] = "Account aktivieren";
// Registration Errors
$locale['402'] = "Bitte gib einen g&uuml;ltigen Benutzernamen, ein Passwort und eine E-Mail Adresse an.";
$locale['403'] = "Der Benutzername enth&auml;lt ung&uuml;ltige Zeichen.";
$locale['404'] = "Die Passw&ouml;rter stimmen nicht &uuml;berein.";
$locale['405'] = "Ung&uuml;ltiges Passwort. Es sind nur alphanumerische Zeichen erlaubt.<br />
Das Passwort muss mindestens 6 Zeichen lang sein.";
$locale['406'] = "Diese E-Mail Adresse ist nicht g&uuml;ltig.";
$locale['407'] = "Der Benutzername ".(isset($_POST['username']) ? $_POST['username'] : "")." wird bereits verwendet.";
$locale['408'] = "Die E-Mail Adresse ".(isset($_POST['email']) ? $_POST['email'] : "")." wird bereits verwendet.";
$locale['409'] = "F&uuml;r diese E-Mail Adresse gibt es bereits ein inaktives Nutzerkonto.";
$locale['410'] = "Ung&uuml;ltier Sicherheitscode.";
$locale['411'] = "Diese E-Mail Adresse oder die Domain der E-Mail Adresse ist gesperrt.";
# Pimped ->
$locale['412'] = "Sicherheitsfrage nicht beantwortet";
$locale['413'] = "falsche Antwort auf die Sicherheitsfrage";
$locale['414'] = "falsche Sicherheits-ID &uuml;bergeben. Kontaktiere den Administrator der Seite";
# <- Pimped
// Email Message
$locale['449'] = "Herzlich willkommen auf ".$settings['sitename'];
$locale['450'] = "Hallo ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Herzlich Willkommen auf ".$settings['sitename'].". Hier sind Deine Logindaten:\n
Benutzername: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Kennwort: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n\nDas Kennwort kann von niemandem, auch nicht von einem Administrator in Klarschrift im System gelesen werden.\n\n
Bitte aktiviere Deinen Account mit folgendem Link:\n";
// Registration Success/Fail
$locale['451'] = "Registrierung abgeschlossen";
$locale['452'] = "Du kannst dich nun anmelden.";
$locale['453'] = "Dein Benutzerkonto wird in K&uuml;rze von einem Administrator aktiviert.";
$locale['454'] = "Die Registrierung ist fast vollst&auml;ndig. Du erh&auml;lst in K&uuml;rze eine E-Mail mit Deinen Anmeldedaten und einem Link, mit dem du dein Benutzerkonto innerhalb von 24 Stunden aktivieren musst. Erst danach kannst du dich anmelden.";
$locale['455'] = "Dein Benutzerkonto wurde &uuml;berpr&uuml;ft.";
$locale['456'] = "Registrierung fehlgeschlagen";
$locale['457'] = "Beim Senden der E-Mail ist ein Fehler aufgetreten, bitte nimm Kontakt zu einem <a href='mailto:".$settings['siteemail']."'><b>Administrator</b></a> auf.";
$locale['458'] = "Deine Registrierung ist aus folgendem Grund fehlgeschlagen:";
$locale['459'] = "Bitte versuch es sp&auml;ter noch einmal";
// Register Form
$locale['500'] = "Bitte trage alle erforderlichen Daten ein. ";
$locale['501'] = "Eine Aktivierungs-Mail wird an die angegebene E-Mail Adresse gesendet. ";
$locale['502'] = "Die markierten Felder (<span style='color:#ff0000;'>*</span>) m&uuml;ssen ausgef&uuml;llt werden.
Achte bei deinem Benutzernamen und deinem Kennwort auf Gro&szlig;- und Kleinschreibung.";
$locale['503'] = "Weitere Informationen kannst du sp&auml;ter deinem Benutzerprofil hinzuf&uuml;gen.";
$locale['504'] = "Sicherheitscode:";
$locale['505'] = "Bitte gib den Sicherheitscode hier ein:";
$locale['506'] = "Registrieren";
$locale['507'] = "Die Registrierung ist derzeit deaktiviert.";
$locale['508'] = "Einverst&auml;ndniserkl&auml;rung";
$locale['509'] = "Ich habe die <a href='".BASEDIR."print.php?type=T' target='_blank'>Einverst&auml;ndniserkl&auml;rung</a> gelesen und akzeptiere diese.";
$locale['510'] = "Sicherheitsfrage:"; # Pimped
$locale['511'] = "Recaptcha Sicherheitscode:"; # Pimped
// Validation Errors
$locale['550'] = "Bitte w&auml;hle einen Benutzernamen.";
$locale['551'] = "Bitte trage ein g&uuml;ltiges Kennwort ein.";
$locale['552'] = "Bitte gib eine g&uuml;ltige E-Mail Adresse an.";
$locale['553'] = "Der Recaptcha Sicherheitscode wurde nicht richtig eingegeben. Bitte versuch es nochmal."; # Pimped
$locale['554'] = "Recaptcha ist aktiviert, aber es gab keine Eingabe! Bitte versuch es nochmal."; # Pimped
?>