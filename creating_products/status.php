<?php
/**
 * Created by PhpStorm.
 * User: vlad-
 * Date: 03.08.2017
 * Time: 21:33
 */

error_reporting (E_ALL & ~ E_DEPRECATED & ~ E_NOTICE);
error_reporting (0);
function send($a){
    print_r(json_encode($a));
}
function db(){
    $db_host="localhost";
    $db_username="root";
    $db_password="toor";
    $db_id = mysql_connect($db_host, $db_username, $db_password)
    or die('Не удалось соединиться: ' . mysql_error());
    mysql_select_db('creating_products')
    or die('Не удалось выбрать базу данных');
}
db();


if($_POST['what_to_do']==='get_all_information'){
    $request = array(
        'quantity_urls'   => mysql_fetch_array(mysql_query("SELECT COUNT(1) FROM `url_list` WHERE 1"))[0],
        'quantity_parsed_urls'   => mysql_fetch_array(mysql_query('SELECT COUNT(1) FROM `url_list` WHERE `status_updating`=2'))[0],
        'quantity_urls_to_parsing'   => mysql_fetch_array(mysql_query('SELECT COUNT(1) FROM `url_list` WHERE `status_updating`=1'))[0],
        'quantity_found_products'   => mysql_fetch_array(mysql_query('SELECT COUNT(1) FROM `found_products` WHERE 1'))[0],
        'continue_creating'   => mysql_fetch_array(mysql_query('SELECT `value` FROM `settings` WHERE `title`="continue_creating"'))[0],
        'status_updating'   => mysql_fetch_array(mysql_query('SELECT `value` FROM `settings` WHERE `title`="status_updating"'))[0],
        'next_url_to_updating' => mysql_fetch_row(mysql_query('SELECT `url` FROM `url_list` WHERE `status_updating`=1 ORDER BY `date_of_uploading` ASC LIMIT 1'))[0],
        'goods_uploaded' => mysql_fetch_row(mysql_query('SELECT COUNT(1) FROM `found_products` WHERE `product_status`=4'))[0],
        'quantity_downloaded_from_our_PL' => mysql_fetch_row(mysql_query('SELECT COUNT(1) FROM `products_list` WHERE 1'))[0],
        'time_of_start_updating'   => mysql_fetch_array(mysql_query('SELECT `value` FROM `settings` WHERE `title`="time_of_start_updating"'))[0],
        'time_of_end_updating'   => mysql_fetch_array(mysql_query('SELECT `value` FROM `settings` WHERE `title`="end_of_updating"'))[0],
        'quantiti_products_in_our_shop'   => mysql_fetch_array(mysql_query('SELECT `value` FROM `settings` WHERE `title`="quantiti_products_in_our_shop"'))[0],
        'memory_usage'   => mysql_fetch_row(mysql_query('SELECT `value` FROM `settings` WHERE `title`="memory_usage"'))[0],
        'last_updated'   => mysql_fetch_row(mysql_query('SELECT `value` FROM `settings` WHERE `title`="last_updated"'))[0]
    );
    send($request);
}
elseif($_POST['what_to_do']==='pause_creating'){
    mysql_query("UPDATE `settings` SET `value`=2 WHERE `title`='continue_creating'");
    echo 'ready';
}
elseif($_POST['what_to_do']==='continue_creating'){
    mysql_query("UPDATE `settings` SET `value`=1 WHERE `title`='continue_creating'");
    echo 'ready';
}
elseif($_POST['what_to_do']==='stop_creating') {
    mysql_query("UPDATE `settings` SET `value`=0 WHERE `title`='continue_creating'");
    mysql_query("UPDATE `settings` SET `value` =10 WHERE `title` = 'status_updating'");
    echo 'ready';
}
elseif($_POST['what_to_do']==='clear_all') {
    mysql_query('TRUNCATE TABLE `found_products`');
    mysql_query('TRUNCATE TABLE `products_list`');
    mysql_query('TRUNCATE TABLE `url_list`');

    echo 'ready';
}else{
    echo 111;
}