<meta charset="utf-8">

<?php
/**
 * Created by PhpStorm.
 * User: vlad-
 * Date: 04.08.2017
 * Time: 0:47
 */
$mem_start = memory_get_usage();
ini_set("display_errors",1);
error_reporting(E_ALL);
require_once( 'api/woocommerce-api.php' );
function db(){
    $db_host="localhost";
    $db_username="root";
    $db_password="toor";
    $db_id = mysql_connect($db_host, $db_username, $db_password)
    or die('Не удалось соединиться: ' . mysql_error());
    mysql_select_db('creating_products')
    or die('Не удалось выбрать базу данных');
    mysql_set_charset("utf8");
}
db();
















?>

