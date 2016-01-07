<?php
/**
 * Created by PhpStorm.
 * User: preacher
 * Date: 29.09.15
 * Time: 21:43
 */

//TODO-Michy annpassen noch Prototyp

require_once "../../../maincore.php";
include INFUSIONS."user_charts/infusion_db.php";

$songId = $_POST['songId'];
$points = $_POST['points'];
$user = $_POST['user'];
$time = time();
$datenbank = $_POST['db'];

$_update_vote = 'UPDATE '. $datenbank .' SET chart_vote = chart_vote + '.$points.' WHERE chart_id = '.$songId.';';

$_user_sperre = 'INSERT INTO '. DB_TIMECHECK .' (userid, songid, votetime) VALUE ('.$user.', '.$songId.', '.$time.');';

$res_sperre = $_user_sperre;

$res = $_update_vote;

$sperre = dbquery($res_sperre);
$was = dbquery($res);

if ($sperre && $was){
    echo $was;
}else{
    false;
}