<?php
// Member Management Options
$locale['400'] = "Mitglieder-Verwaltung";
$locale['401'] = "Mitglied";
$locale['402'] = "Neues Mitglied hinzuf&uuml;gen";
$locale['403'] = "Usertyp";
$locale['404'] = "Optionen";
$locale['405'] = "Ansehen";
$locale['406'] = "Bearbeiten";
$locale['407'] = "Aktivieren";
$locale['408'] = "Entsperren";
$locale['409'] = "Sperren";
$locale['410'] = "L&ouml;schen";
$locale['411'] = "Es gibt keine %s Mitglieder";
$locale['412'] = " die anfangen mit ";
$locale['413'] = " &uuml;bereinstimmen ";
$locale['414'] = "Alle anzeigen";
$locale['415'] = "Suche nach einem Mitglied:";
$locale['416'] = "Suchen";
$locale['417'] = "Aktion ausw&auml;hlen";
$locale['418'] = "Abbrechen";
$locale['419'] = "Zur&uuml;cksetzen";
// Ban/Unban/Delete Member
$locale['420'] = "Bann gespeichert";
$locale['421'] = "Bann aufgehoben";
$locale['422'] = "Mitglied gel&ouml;scht";
$locale['423'] = "Bist du sicher, dass du dieses Mitglied l&ouml;schen willst?";
$locale['424'] = "Mitglied aktiviert";
// Edit Member Details
$locale['430'] = "Mitglied bearbeiten";
$locale['431'] = "Die &Auml;nderungen der Mitgliederdetails wurden gespeichert";
$locale['432'] = "Zur&uuml;ck zum Mitglieder Admin";
$locale['433'] = "Zur&uuml;ck zum Admin Index";
$locale['434'] = "Die Mitgliederdetails konnten nicht gespeichert werden:";
// Extra Edit Member Details form options
$locale['440'] = "&Auml;nderungen speichern";
// Update Profile Errors
$locale['450'] = "Der Prim&auml;re Administrator kann nicht bearbeitet werden!";
$locale['451'] = "Du musst einen Usernamen und E-Mail Adresse angeben.";
$locale['452'] = "Der Username enth&auml;lt ung&uuml;ltige Zeichen.";
$locale['453'] = "Der Benutzername ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." wird leider schon verwendet.";
$locale['454'] = "Ung&uuml;ltige E-Mail Adresse.";
$locale['455'] = "Die E-Mail Adresse ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." wird leider schon verwendet.";
$locale['456'] = "Die Passw&ouml;rter stimmen nicht &uuml;berein.";
$locale['457'] = "Falsches Passwort. Verwende bitte nur alphanumerische Zeichen.<br />
Das Passwort muss mindestens 6 Zeichen lang sein.";
$locale['458'] = "<strong>Warnung:</strong> Unerwartete Scriptausf&uuml;hrung.";
// View Member Profile
$locale['470'] = "Mitgliederprofil";
$locale['472'] = "Statistiken";
$locale['473'] = "Benutzergruppen";
// Add Member Errors
$locale['480'] = "Mitglied hinzuf&uuml;gen";
$locale['481'] = "Der Mitgliederaccount wurde angelegt.";
$locale['482'] = "Der Mitgliederaccount konnte nicht angelegt werden.";
// Suspension Log 
$locale['510s'] = "Sperrprotokoll f&uuml;r ";
$locale['511s'] = "Es gibt keine aufgezeichneten tempor&auml;ren Sperren f&uuml;r dieses Mitglied im Sperrprotokoll";
$locale['512s'] = "Fr&uuml;here tempor&auml;re Sperren von ";
$locale['513'] = "Nr."; // as in number
$locale['514'] = "Datum";
$locale['515'] = "Grund";
$locale['516'] = "Sperre von Administrator";
$locale['517'] = "System Action";
$locale['518'] = "Zur&uuml;ck zum Benutzerprofil";
$locale['519'] = "Sperrprotokoll f&uuml;r diesen Benutzer ";
$locale['520'] = "Aufgehoben: ";
$locale['521'] = "IP: ";
$locale['522'] = "Not yet reinstated";
// User Management Errors
$locale['540'] = "Fehler";
$locale['541'] = "Fehler: Du musst einen Grund fuuml;r die tempor&auml;re Sperre angeben!";
$locale['542'] = "Fehler: Du musst einen Grund fuuml;r die Sicherheitssperre angeben!";
// User Management Admin
$locale['550'] = "Sperre User: ";
$locale['551'] = "Dauer in Tagen";
$locale['552'] = "Grund:";
$locale['553'] = "Sperren";
$locale['554'] = "Es liegen keine tempor&auml;ren Sperren f&uuml;r dieses Mitglied im Sperrprotokoll vor.";
$locale['555'] = "Wenn du willst das dieser Benutzer gesperrt werden soll, dann klicke auf 'Sperren'";
$locale['556'] = "Tempor&auml;re Sperre aufheben von Benutzer: ";
$locale['557'] = "Tempor&auml;re Sperre aufheben";
$locale['558'] = "Sperre des Benutzers aufheben: ";
$locale['559'] = "Sperre aufheben";
$locale['560'] = "Sicherheitssperre des Benutzers aufheben: ";
$locale['561'] = "Sicherheitssperre aufheben";
$locale['562'] = "Benutzer sperren: ";
$locale['563'] = "Benutzer aus Sicherheitsgr&uuml;nden sperren: ";
$locale['585a'] = "Bitte gib eine Begr&uuml;ndung an, warum du diesen Benutzer Sperren oder Entsperren willst: ";
$locale['566'] = "Sperre aufgehoben";

$locale['568'] = "Sicherheitssperre verh&auml;ngt";
$locale['569'] = "Sicherheitssperren aufgehoben";

$locale['572'] = "Mitglied gesperrt";
$locale['573'] = "Sperre aufgehoben";
$locale['574'] = "Mitglied deaktiviert";
$locale['575'] = "Mitglied reaktiviert";
$locale['576'] = "Account cancelled";
$locale['577'] = "Account cancellation undone";
$locale['578'] = "Account cancelled and anonymised";
$locale['579'] = "Account anonymisation undone";
$locale['580'] = "Deaktiviere inaktive Mitglieder";
$locale['581'] = "Du hast mehr als 50 inaktive Mitglieder und daher muss der Deaktivierungsprozess auf <strong>%d Male</strong> verteilt werden, um einen Timeout w&auml;hrend der Operation zu verhindern.";
$locale['582'] = "Reaktivieren";
$locale['583'] = "Wieder einsetzen";
$locale['584'] = "W&auml;hle neuen Status";
$locale['585'] = "Dieses Mitglied wurde urspr&uuml;nglich aus Sicherheitsgr&uuml;nden gesperrt! Bist du sicher, das du diese Mitglied jetzt entsperren willst?";

$locale['590'] = "Tempor&auml;r sperren";
$locale['591'] = "Tempor&auml;re Sperre aufheben";
$locale['592'] = "tempor&auml;r sperrst";
$locale['593'] = "tempor&auml;re Sperre aufhebst";
$locale['594'] = "Bitte gib eine Begr&uuml;ndung an, warum du ";
$locale['595'] = " den Benutzer ";
$locale['596'] = "Dauer:";

$locale['600'] = "Sicherheitssperre";
$locale['601'] = "aus Sicherheitsgr&uuml;nden sperren";
$locale['602'] = "Sicherheitssperre aufheben ";
$locale['603'] = "Sicherheitssperre aufhebst";
$locale['604'] = "Grund:";

// Deactivation System
$locale['610'] = "<strong>%d Mitglied(er)</strong> haben sich nicht eingeloggt f&uuml;r <strong>%d Tag(e)</strong> und werden als inaktiv markiert.
Durch die Deaktivierung haben diese Benutzer <strong>%d Tag(e)</strong>, bevor sie %s.<br />";
$locale['611'] = "Bitte beachte, dass einige Benutzer Inhalte eingereicht haben wie z.B. Forenbeitr&auml;ge, Kommentare, Fotos usw., diese werden gel&ouml;scht, sobald die deaktivierten Benutzer gel&ouml;scht werden.";
$locale['612'] = "Benutzer";
$locale['613'] = "Benutzer";
$locale['614'] = "Deaktivieren";
$locale['615'] = "dauerhaft gel&ouml;scht";
$locale['616'] = "anonymisieren";
$locale['617'] = "Warnung:";
$locale['618'] = "Es wird dringend empfohlen, den Deaktivierungsprozess von L&uuml;schen auf Anonymisieren zu &auml;ndern, um Datenverlust zu verhindern!";
$locale['619'] = "Um das zu tun klick <a href='".ADMIN."settings_users.php".$aidlink."'>hier</a>.";
$locale['620'] = "anonymisieren";
$locale['621'] = "utomatische Deaktivierung von inaktiven Benutzern.";
?>