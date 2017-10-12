<?php

/**
 * Created by PhpStorm.
 * User: vlad-
 * Date: 27.07.2017
 * Time: 20:18
 */

require_once( 'include.php' );
db();


if($_POST['what_to_do']==='get_all_information'){
    $request = array(
        'quantiti_products'   => mysql_fetch_array(mysql_query("SELECT `value` FROM `settings` WHERE `title`='quantiti_products'"))[0],
        'quantiti_errors'   => mysql_fetch_array(mysql_query('SELECT COUNT(1) FROM `errors` WHERE 1'))[0],
        'uploaded_products'   => mysql_fetch_array(mysql_query('SELECT COUNT(1) FROM `products_list` WHERE `date_upload_product_in_list`!=0'))[0],
        'updated_products_information'   => mysql_fetch_array(mysql_query('SELECT COUNT(1) FROM `products_list` WHERE `date_update`!=0'))[0],
        'updated_products'   => mysql_fetch_array(mysql_query('SELECT COUNT(1) FROM `products_list` WHERE `date_upload`!=0'))[0],
        'last_updated'   => mysql_fetch_array(mysql_query('SELECT `id` FROM `products_list` ORDER BY date_update DESC LIMIT 1'))[0],
        'status_updating'   => mysql_fetch_array(mysql_query("SELECT `value` FROM `settings` WHERE `title`='continue_update'"))[0],
        'status_step_updating'   => mysql_fetch_array(mysql_query("SELECT `value` FROM `settings` WHERE `title`='status_step_updating'"))[0],
        'time_of_last_update'   => mysql_fetch_array(mysql_query("SELECT `value` FROM `settings` WHERE `title`='last_update'"))[0],
        'time_of_start_updating'   => mysql_fetch_array(mysql_query("SELECT `value` FROM `settings` WHERE `title`='time_of_start_updating'"))[0],
        'uploaded_products_time'   => mysql_fetch_array(mysql_query("SELECT `date_upload_product_in_list` FROM `products_list` WHERE `date_upload_product_in_list`!=0 ORDER BY `date_upload_product_in_list` DESC LIMIT 1"))[0]-mysql_fetch_array(mysql_query("SELECT `date_upload_product_in_list` FROM `products_list` WHERE `date_upload_product_in_list`!=0 ORDER BY `date_upload_product_in_list` ASC LIMIT 1"))[0],
        'updated_products_information_time'   => mysql_fetch_array(mysql_query("SELECT `date_update` FROM `products_list` WHERE `date_update`!=0 ORDER BY `date_update` DESC LIMIT 1"))[0]-mysql_fetch_array(mysql_query("SELECT `date_update` FROM `products_list` WHERE `date_update`!=0 ORDER BY `date_update` ASC LIMIT 1"))[0],
        'updated_products_time'   => mysql_fetch_array(mysql_query("SELECT `date_upload` FROM `products_list` WHERE `date_upload`!=0 ORDER BY `date_upload` DESC LIMIT 1"))[0]-mysql_fetch_array(mysql_query("SELECT `date_upload` FROM `products_list` WHERE `date_upload`!=0 ORDER BY `date_upload` ASC LIMIT 1"))[0],

        //'это время * количество продуктов / на уже обновленные продукты'


    );
    send($request);
}
elseif($_POST['what_to_do']==='get_errors'){
    send(table_in_array('SELECT `products_list`.`id`, `products_list`.`product_id`, `products_list`.`parsing_url`,    `products_list`.`product_url`, `errors`.`text` FROM    `products_list`,`errors`  WHERE    `products_list`.id = `errors`.`id_from_products_list`'));
}
elseif($_POST['what_to_do']==='pause_parsing'){
    mysql_query("UPDATE `settings` SET `value` = 2 WHERE `title` = 'continue_update'");
    send('ready');
}
elseif($_POST['what_to_do']==='continue_parsing'){
    mysql_query("UPDATE `settings` SET `value` = 1 WHERE `title` = 'continue_update'");
    send('ready');
}
elseif($_POST['what_to_do']==='stop_parsing'){
    mysql_query("UPDATE `settings` SET `value` = 0 WHERE `title` = 'continue_update'");
    mysql_query("UPDATE `settings` SET `value` = 0 WHERE `title` = 'status_step_updating'");
    send('ready');
}elseif($_POST['what_to_do']==='add_to_remove_product'){
    mysql_query('INSERT INTO `updateproducts`.`to_remove` (`product_id`,`id_from_products_list`, `text`) VALUES ( '+$_POST['product_id']+',(SELECT `id_from_products_list` FROM `errors` WHERE `product_id`='+$_POST['product_id']+'),(SELECT `text` FROM `errors` WHERE `product_id`='+$_POST['product_id']+')');
    mysql_query('DELETE FROM `errors` WHERE `product_id`='+$_POST['product_id']);
    mysql_query('UPDATE `products_list` SET `status`=404 WHERE `product_id`='+$_POST['product_id']);
    send('ready');
}elseif($_POST['what_to_do']==='remove_product'){
    remove_product($_POST['product_id']);
    mysql_query('DELETE FROM `products_list` WHERE `product_id`='+$_POST['product_id']);
    mysql_query('DELETE FROM `to_remove` WHERE `product_id`='+$_POST['product_id']);
    send('ready');
}
else{
    echo 111;
}
function remove_product($product_id){
    $options = array(
        'debug' => false,
        'return_as_array' => true,
        'validate_url' => false,
        'timeout' => 300,
        'ssl_verify' => false,
    );

    try {

        $client = new WC_API_Client($api_host, $api_key_ck, $api_key_cs, $options);

        $client->products->delete( $product_id);

    } catch (WC_API_Client_Exception $e) {

        echo $e->getMessage() . PHP_EOL;
        echo $e->getCode() . PHP_EOL;

        if ($e instanceof WC_API_Client_HTTP_Exception) {

            print_r($e->get_request());
            print_r($e->get_response());
        }
    }

}
?>