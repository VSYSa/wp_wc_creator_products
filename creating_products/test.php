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

$mem_start = memory_get_usage();
define('WP_MEMORY_LIMIT', '1024M');
ignore_user_abort(true);
set_time_limit(0);

define("api_host", "https://mnogosveta.su/");
define("api_key_ck", "ck_73951e6714a5f64af41448ed70965ad43cf05efe");
define("api_key_cs", "cs_eb7b510b0042b842f9730fd8806fba20e03c4475");
// рекурсивный сбор ссылок с сайта, определение кто товар и загрузка инф
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

function update_product_list()
{
    mysql_query('UPDATE `settings` SET `value` = 2 WHERE `title` = "status_updating"');
    mysql_query('TRUNCATE TABLE `products_list`');
    mysql_query('ALTER TABLE `products_list` AUTO_INCREMENT=0');

    $options = array(
        'debug' => false,
        'return_as_array' => true,
        'validate_url' => false,
        'timeout' => 300,
        'ssl_verify' => false,
    );

    try {
        echo 1;
        $client = new WC_API_Client(api_host, api_key_ck, api_key_cs, $options);
        echo 2;
        echo $quantiti_products = $client->products->get_count()['count'];
        echo 3;


    } catch (WC_API_Client_Exception $e) {

        echo $e->getMessage() . PHP_EOL;
        echo $e->getCode() . PHP_EOL;

        if ($e instanceof WC_API_Client_HTTP_Exception) {

            print_r($e->get_request());
            print_r($e->get_response());
        }
    }
}
update_product_list()













?>

