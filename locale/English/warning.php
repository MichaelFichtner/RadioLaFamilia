<?php

// infusion.php
#$locale['PDW100'] = "System of Admonishment";

// global
$locale['WARN200'] = "Warnings:";
$locale['WARN201'] = "Point";
$locale['WARN202'] = "Points";

// warning.inc.php
$locale['WARN210'] = "You have been warned";
$locale['WARN211'] = "Posting:";
$locale['WARN212'] = "Reason:";
$locale['WARN213'] = "Explanatio:";
$locale['WARN214'] = "The admonishment remains registered until ";
$locale['WARN215'] = "d.m.Y"; //date format
$locale['WARN216'] = " .
To avoid exclusion of the forum, please respect the forum rules in the future.";
$locale['WARN217'] = "User has reached the limit of admonishments";
$locale['WARN218'] = "Link to User!";
$locale['WARN219'] = "Post:";
$locale['WARN220'] = "Posting no more existent";

// warning_info.php
$locale['WARN300'] = "Information about the Admonishment system";
$locale['WARN301'] = "The Admonishment system allows conclusions of the behavior of a user. <br />
The forum moderators may deliver a warning in case the board rules are not respected.";
$locale['WARN302'] = "0 points";
$locale['WARN303'] = "up to 15 points";
$locale['WARN304'] = "up to 30 points";
$locale['WARN305'] = "up to 45 points";
$locale['WARN306'] = "up to 60 points";
$locale['WARN307'] = "up to 75 points";
$locale['WARN308'] = "up to 90 points";
$locale['WARN309'] = "up to 99 points";
$locale['WARN310'] = "as of 100 points";
$locale['WARN311'] = "";
$locale['WARN312'] = "";
$locale['WARN313'] = "";
$locale['WARN314'] = "";
$locale['WARN315'] = "";
$locale['WARN316'] = "";
$locale['WARN317'] = "";
$locale['WARN318'] = "";
$locale['WARN319'] = "User locked out";
$locale['WARN320'] = "Points distribution";
$locale['WARN321'] = "points";
$locale['WARN322'] = "duration";
$locale['WARN323'] = "Reason";
$locale['WARN324'] = "days"; // also used in 1.php
$locale['WARN325'] = "<strong>Important</strong><br />
Each moderator may increase or reduce the number of points given by his own judgement, in order to handle i.e. sequential violations more effective.";
$locale['WARN326'] = "General";
$locale['WARN327'] = "Appeal";
$locale['WARN328'] = "If you do not agree and don't want to accept an admonishment, you may appeal against the admonishment. Another moderator will then take over the case.";

//warning.php
$locale['WARN400'] = "Error";
$locale['WARN401'] = "An error occured during the registration of a new warning.";
$locale['WARN402'] = "Edit warning";
$locale['WARN403'] = "Change";
$locale['WARN404'] = "Register warning";
$locale['WARN405'] = "Add";
$locale['WARN406'] = "<strong>Reason:</strong>";
$locale['WARN407'] = "<strong>Explanation:</strong><br /><span class='small'>Input mandatory! Fill form meaningful please!</span>";
$locale['WARN408'] = "<strong>Points:</strong>";
$locale['WARN409'] = "<span class='small'>(Leave empty for standard points)</span>";
$locale['WARN410'] = "Admonishments";
$locale['WARN411'] = "edit";
$locale['WARN412'] = "delete";
$locale['WARN413'] = "Point";
$locale['WARN414'] = "Points";
$locale['WARN415'] = "Valid until";
$locale['WARN416'] = "d.m.Y"; //date format
$locale['WARN417'] = "Total:";
$locale['WARN418'] = "Admonishments";
$locale['WARN419'] = "No warnings registered for this user.";
$locale['WARN420'] = "Archive - Expired admonishments";
$locale['WARN421'] = "Expired on";
$locale['WARN422'] = "You warn this forum post:";
$locale['WARN423'] = "You don't want to warn this forum post, but the user in general? Go "; // 1
$locale['WARN424'] = "Here"; // 2
$locale['WARN425'] = ""; // 3
$locale['WARN426'] = "Kind:";

// admin.php
define('L_WARN_ADMIN_TITLE', "Administration - System of Admonishment");
define('L_WARN_ADMIN_REASON', "Reason for warning");
define('L_WARN_ADMIN_SET', "Settings");
define('L_WARN_ADMIN_STAT', "Statistic");
define('L_WARN_ADMIN_CLEAN', "Clean up");

// Msg
define('L_WARN_MSG_REASON_DEL', "Warning Reason deleted");
define('L_WARN_MSG_REASON_DEL_ERROR', "Warning Reason could not be deleted");
define('L_WARN_MSG_MISSING', "Reason, duration or points missing!");
define('L_WARN_MSG_REASON_ADDED', "Reason added");
define('L_WARN_MSG_REASON_EDITED', "Reason edited");

// Reasons
define('L_WARN_ADMIN_1_TITAD', "Create reason for warning");
define('L_WARN_ADMIN_1_TITED', "Edit reason of warning");
define('L_WARN_ADMIN_1_REASON', "Reason:");
define('L_WARN_ADMIN_1_TYPE', "Type of warning:");
define('L_WARN_ADMIN_1_FORUM', "Forum");
define('L_WARN_ADMIN_1_GENERAL', "Other");
define('L_WARN_ADMIN_1_DURATION', "Duration:");
define('L_WARN_ADMIN_1_POINTS', "Points:");
define('L_WARN_ADMIN_1_DAYS', "days");
define('L_WARN_ADMIN_1_DAYS2', "(afterwards the warning will be cleared)");
define('L_WARN_ADMIN_1_ADD', "Add");
define('L_WARN_ADMIN_1_EDIT', "Edit");
define('L_WARN_ADMIN_1_TITRE', "Reason for warnings");
define('L_WARN_ADMIN_1_OPTION', "Option");
define('L_WARN_ADMIN_1_O_EDIT', "edit");
define('L_WARN_ADMIN_1_O_DELETE', "delete");
define('L_WARN_ADMIN_1_NOREASONS', "There are no warning reasons yet!");
// Settings
define('L_WARN_ADMIN_2_TITLE', "Settings");
define('L_WARN_ADMIN_2_ACTIV', "Activate the Warning System:");
define('L_WARN_ADMIN_2_VISIBLE', "Visibility of warnings?");
define('L_WARN_ADMIN_2_SHOUTS', "Show Warnings in Shoutbox?");
define('L_WARN_ADMIN_2_COMMENTS', "Show Warnings in Comments?");
define('L_WARN_ADMIN_2_USERGROUP', "Which usergroup may initiate warnings?");
define('L_WARN_ADMIN_2_SENDPM', "Send a PM to the cautioned user?");
define('L_WARN_ADMIN_2_SENDER', "Who shall be the originator of the PM?");
define('L_WARN_ADMIN_2_SENDER_SELECT', "- the actual admonisher -");
define('L_WARN_ADMIN_2_RECEIVE_MSG', "Who shall receive the reference message if the user has reached the warning-limit?");

// Statistics
define('L_WARN_ADMIN_3_WARN_LOG', "Admonishment Log");
define('L_WARN_ADMIN_3_MEMBER', "Member");
define('L_WARN_ADMIN_3_REASON', "Reason / Post");
define('L_WARN_ADMIN_3_POINTS', "Points");
define('L_WARN_ADMIN_3_MODERATOR', "Moderator");
define('L_WARN_ADMIN_3_DATE', "Date");
define('L_WARN_ADMIN_3_DATE_FORM', "d.m.Y");
define('L_WARN_ADMIN_3_STATS', "Statistic");
define('L_WARN_ADMIN_3_NUM', "Number of warnings");
define('L_WARN_ADMIN_3_TOTAL', "Total:");
define('L_WARN_ADMIN_3_REASONS', "Reasons");

// Clean-up
define('L_WARN_ADMIN_4_CLEANUP', "Clean-up");
define('L_WARN_ADMIN_4_DELETE', "Delete ...");
define('L_WARN_ADMIN_4_EXP2Y', "expired admonishments older than 2 years.");
define('L_WARN_ADMIN_4_EXP1Y', "expired admonishments older than 1 year.");
define('L_WARN_ADMIN_4_ALLEX', "all expired admonishments.");
define('L_WARN_ADMIN_4_ALLWA', "all admonishments.");
define('L_WARN_ADMIN_4_ALLTRUN', "all admonishments, restart counter (TRUNCATE).");
define('L_WARN_ADMIN_4_ALLREATRUN', "all warning reasons, restart counter (TRUNCATE).");
define('L_WARN_ADMIN_4_IKNOW', "I acknowledge the deletion and I am conscious of the consequences.");
define('L_WARN_ADMIN_4_DELETE1', "Delete!!!");

?>