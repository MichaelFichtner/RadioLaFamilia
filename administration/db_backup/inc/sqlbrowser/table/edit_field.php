<?php
/**
 * This file is part of MySQLDumper released under the GNU/GPL 2 license
 * http://www.mysqldumper.net
 *
 * @package         MySQLDumper
 * @version         SVN: $rev: 1205 $
 * @author          $Author$
 * @lastmodified    $Date$
 * @filesource      $URL$
 */
echo 'bla';die();

if (!defined('MSD_VERSION')) die('No direct access.');

$checkit = (isset($_GET['checkit'])) ? base64_decode($_GET['checkit']) : '';
$repair = (isset($_GET['repair'])) ? base64_decode($_GET['repair']) : 0;
$tables = isset($_POST['table']) ? $_POST['table'] : array();
$sort_by_column = isset($_POST['sort_by_column']) ? $_POST['sort_by_column'] : 'name';
$sort_direction = isset($_POST['sort_direction']) ? $_POST['sort_direction'] : 'a';

$db = isset($_GET['db']) ? base64_decode($_GET['db']) : $config['db_actual'];
$tablename = isset($_GET['tablename']) ? base64_decode($_GET['tablename']) : '';

mysql_select_db($db, $config['dbconnection']);
$table_infos = getExtendedFieldInfo($db, $tablename);

//v($table_infos);


$tpl_sqlbrowser_table_edit_table = new MSDTemplate();
$tpl_sqlbrowser_table_edit_table->set_filenames(array(
    'tpl_sqlbrowser_table_edit_field' => 'tpl/sqlbrowser/table/edit_field.tpl'));

$tpl_sqlbrowser_table_edit_table->assign_vars(array(
    'DB' => $db, 
    'TABLE' => $tablename));

$tpl_sqlbrowser_table_edit_table->assign_block_vars('ROW', array());
