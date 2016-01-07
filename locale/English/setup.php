<?php
$locale['title'] = "Pimped-Fusion v%s Setup";
$locale['sub-title'] = "Pimped-Fusion v%s Setup";

$locale['charset'] = "UTF-8"; # change it to the old setting, if you have problems // $locale['charset'] = "iso-8859-1";
$locale['001'] = "Step 1: Locale";
$locale['002a'] = "Step 2: Server Check";
$locale['002'] = "Step 3: File and Folder Permissions Test";
$locale['003'] = "Step 4: Database Settings";
$locale['004'] = "Step 5: Config / Database Setup";
$locale['005'] = "Step 6: Primary Admin Details";
$locale['006'] = "Step 7: Final Settings";
$locale['006a'] = "Delete Setup Files"; // Pimped
$locale['006b'] = "Developer Tool"; // Pimped
$locale['007'] = "Next";
$locale['008'] = "Back";
$locale['009'] = "Finish";
// Step 1
$locale['010'] = "Please select the required locale (language):";
$locale['011'] = "Get more information and download more Language Packs from <a href='http://www.pimped-fusion.net'>www.pimped-fusion.net</a>";
// Step 2
$locale['step200'] = "Check Server Versions/Settings";
$locale['step201'] = "Your Version";
$locale['step202'] = "Recommended";
$locale['step203'] = "PHP-Version";
$locale['step204'] = "MySQL-Version";
$locale['step205'] = "Zend-Version";
$locale['step206'] = "Safe Mod";
$locale['step207'] = "ON";
$locale['step208'] = "OFF";
$locale['step209'] = "Register Globals";
$locale['step210'] = "Magic Quotes";
$locale['step211'] = "Short Open Tag";
$locale['step212'] = "fsockopen";
$locale['step213'] = "enabled";
$locale['step214'] = "disabled";
$locale['step215'] = "GD library";
$locale['step216'] = "Memory Limit";
$locale['step217'] = "File Uploads";
$locale['step218'] = "Upload Max Filesize";
$locale['step219'] = "Timezone";
$locale['step220'] = "or above";
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
$locale['020'] = "In order for setup to continue, the following files/folders must be writable:";
$locale['021'] = "Write permissions check passed, click Next to continue.";
$locale['022'] = "Write permissions check failed, please CHMOD files/folders marked Failed.";
$locale['023'] = "Passed";
$locale['024'] = "Failed";
#Pimped ->
$locale['025'] = "Error:";
$locale['026'] = "The specified FTP host is not reachable! Please check your entries or set the permissions manually via FTP client.";
$locale['027'] = "The specified login details have been rejected! Please check your entries or set the permissions manually via FTP client.";
$locale['028'] = "Your Web server does not supported any of the functions <i>ftp_connect()</i>, <i>ftp_login()</i> or <i>ftp_site()</i>!
However, these are necessary to carry out an automatic granting of rights of the files. Please set the necessary rights manually by means of FTP client and refresh the page.";
$locale['029'] = "reload site";
$locale['029a'] = "automatic right assignment";
$locale['029b'] = "It is possible by entering the FTP data to set automatically the rights, what is faster than adding all the necessary privileges to every file and every folder manually.";
$locale['029c'] = "Note";
$locale['029d'] = "As path must be filled in the FTP path to the root directory!";
$locale['029e'] = "example:";
$locale['029f'] = "FTP-Host";
$locale['029g'] = "FTP-Path";
$locale['029h'] = "FTP-User Name";
$locale['029i'] = "FTP-Password";
$locale['029j'] = "automatic right assignment!";
// Step 4 - Access criteria
$locale['030'] = "Please enter your MySQL database access settings.";
$locale['031'] = "Database Hostname:";
$locale['032'] = "Database Username:";
$locale['033'] = "Database Password:";
$locale['034'] = "Database Name:";
$locale['035'] = "Table Prefix:";
$locale['036'] = "Cookie Prefix:";
// Step 5 - Database Setup
$locale['040'] = "Database connection established.";
$locale['041'] = "Config file successfully written.";
$locale['042'] = "Database tables created.";
$locale['043'] = "Error:";
$locale['044'] = "Unable to connect with MySQL.";
$locale['045'] = "Please ensure your MySQL username and password are correct.";
$locale['046'] = "Unable to write config file.";
$locale['047'] = "Please ensure config.php is writable.";
$locale['048'] = "Unable to create database tables.";
$locale['049'] = "Please specify your database name.";
$locale['050'] = "Unable to connect with MySQL database.";
$locale['051'] = "The spesified MySQL database does not exist.";
$locale['052'] = "Table prefix error.";
$locale['053'] = "The specified table prefix is already in use.";
$locale['054'] = "Could not write or delete MySQL tables.";
$locale['055'] = "Please make sure your MySQL user has read, write and delete permission for the selected database.";
$locale['056'] = "Empty fields.";
$locale['057'] = "Please make sure you have filled out all the MySQL connection fields.";
// Step 6 - Super Admin login
$locale['060'] = "Primary Super Admin login details<br /><br />(Note: Your user password and admin password must be different)";
$locale['061'] = "Username:";
$locale['062'] = "Login Password:";
$locale['063'] = "Repeat Login password:";
$locale['064'] = "Admin Password:";
$locale['065'] = "Repeat Admin password:";
$locale['066'] = "Email address:";
$locale['067'] = "Please use alpha numeric characters only. Password must be a minimum of 6 characters long.";
// Step 7 - User details validation
$locale['070'] = "User name contains invalid characters.";
$locale['070b'] = "User name field can not be left empty.";
$locale['071'] = "Your two login passwords do not match.";
$locale['072'] = "Invalid login password, please use alpha numeric characters only.<br />Password must be a minimum of 6 characters long.";
$locale['072b'] = "Login password fields can not be left empty";
$locale['073'] = "Your two admin passwords do not match.";
$locale['074'] = "Your user password and admin password must be different.";
$locale['075'] = "Invalid admin password, please use alpha numeric characters only.<br />Password must be a minimum of 6 characters long.";
$locale['075b'] = "Admin password fields can not be left empty.";
$locale['076'] = "Your email address does not appear to be valid.";
$locale['076b'] = "Email field can not be left empty.";
$locale['077'] = "Your user settings are not correct:";
// Step 7 - Admin Sections
$locale['092a'] = "Forum Post Ratings"; // Pimped # all the other variables are defined in /locale/admin/main.php
// Step 7 - Navigation Links
$locale['130'] = "Home";
$locale['131'] = "Articles";
$locale['132'] = "Downloads";
$locale['133'] = "FAQ";
$locale['134'] = "Discussion Forum";
$locale['135'] = "Contact Me";
$locale['136'] = "News Categories";
$locale['137'] = "Web Links";
$locale['138'] = "Photo Gallery";
$locale['139'] = "Search";
$locale['140'] = "Submit Link";
$locale['141'] = "Submit News";
$locale['142'] = "Submit Article";
$locale['143'] = "Submit Photo";
// Stage 7 - Panels
$locale['160'] = "Navigation";
$locale['161'] = "Online Users";
$locale['162'] = "Forum Threads";
$locale['163'] = "Latest Articles";
$locale['164'] = "Welcome Message";
$locale['165'] = "Forum Threads List";
$locale['166'] = "Old User Info"; // Pimped
$locale['166a'] = "New Enhanced User Info"; // Pimped
$locale['167'] = "Members Poll";
$locale['168'] = "Shoutbox";
$locale['169'] = "Last Seen Users"; // Pimped
$locale['170'] = "Alternative CSS-Navigation"; // Pimped
// Stage 7 - News Categories
$locale['180'] = "Bugs";
$locale['181'] = "Downloads";
$locale['182'] = "Games";
$locale['183'] = "Graphics";
$locale['184'] = "Hardware";
$locale['185'] = "Journal";
$locale['186'] = "Members";
$locale['187'] = "Mods";
$locale['188'] = "Movies";
$locale['189'] = "Network";
$locale['190'] = "News";
$locale['191'] = "PHP-Fusion";
$locale['192'] = "Security";
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
$locale['220'] = "Contact Information";
$locale['221'] = "Miscellaneous Information";
$locale['222'] = "Options";
$locale['223'] = "Statistics";
// Welcome message
$locale['230'] = "Welcome to your site";
// Final message
### Pimped
$locale['240'] = "Setup is complete, Pimped-Fusion is now ready for use.<br />
Click <a href='../index.php'>here</a> to go to your Pimped-Fusion powered site.<br /><br />
<strong>Security Note:</strong><br />
You should delete setup.php (and update.php if you uploaded this file too) from
your server and chmod your config.php back to 644 for security purposes.<br />
<br />
<strong>Note for developers and testers:</strong><br />
You have now installed a clean installation of Pimped-Fusion. If you like to fill your installation with some sample content (Users, Articles, Forum Threads) <a href='setup.php?step=9".(isset($_POST['localeset']) ? "&amp;localset=".$_POST['localeset'] : "")."'>click here</a>.<br />
<br />
<br />
Click on the next step to delete the setup.php and update.php<br /><br />
Thank you for choosing Pimped-Fusion.";
$locale['241'] = "Deleting setup.php and update.php... <br />
Wait now, you will be redirected to your main page in 10 seconds.<br />
Thank you for choosing Pimped-Fusion.";
$locale['242'] = "Delete setup.php";
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
$locale['250'] = "What is the capital of Germany?";
$locale['250r']= "Berlin";
$locale['251'] = "What is the capital of England?";
$locale['251r']= "London";
$locale['252'] = "Are you a human being?";
$locale['252r']= "Yes";
$locale['253'] = "Are you a Spam Bot?";
$locale['253r']= "No";
$locale['254'] = "What is 25+4?";
$locale['254r']= "29";
$locale['255'] = "What is 13+7?";
$locale['255r'] = "20";
?>