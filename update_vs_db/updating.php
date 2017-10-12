<?php



require_once( 'api/woocommerce-api.php' );
require_once( 'parser/simple_html_dom.php' );
require_once( 'parser/parser.php' );
require_once( 'include.php' );
db();


if($_POST['what_to_do']==='update_product'){
   mysql_query('UPDATE `settings` SET `value`=1 WHERE `title`="continue_update"');
   mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    update_product_list();
    update_product_information();
    upload_products();
    mysql_query('UPDATE `settings` SET `value`=0 WHERE `title`="continue_update"');
    mysql_query('UPDATE `updateproducts`.`settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');


}
elseif($_POST['what_to_do']==='update_PL'){
    mysql_query("UPDATE `settings` SET `value` = 1 WHERE `title` = 'continue_update'");
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    update_product_list();
    mysql_query('UPDATE `settings` SET `value`=0 WHERE `title`="continue_update"');
    mysql_query('UPDATE `updateproducts`.`settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');

}
elseif($_POST['what_to_do']==='update_PI'){
    mysql_query("UPDATE `settings` SET `value` = 1 WHERE `title` = 'continue_update'");
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    update_product_information();
    mysql_query('UPDATE `settings` SET `value`=0 WHERE `title`="continue_update"');
    mysql_query('UPDATE `updateproducts`.`settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');

}
elseif($_POST['what_to_do']==='upload_PL'){
    mysql_query("UPDATE `settings` SET `value` = 1 WHERE `title` = 'continue_update'");
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    //mysql_query('UPDATE `settings` SET `value`=0000 WHERE `title`="time_of_start_updating"');
    upload_products();
    mysql_query('UPDATE `settings` SET `value`=0 WHERE `title`="continue_update"');
    mysql_query('UPDATE `updateproducts`.`settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');

}
elseif($_POST['what_to_do']==='remove_product'){
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    remove_product($_POST['product_id']);
    mysql_query('UPDATE `updateproducts`.`settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');

}
else{
    echo 111;
}


/**
 * Created by PhpStorm.
 * User: vlad-
 * Date: 27.07.2017
 * Time: 10:50
 */
function update_product_list()
{
    mysql_query('UPDATE `settings` SET `value`=1 WHERE `title`="status_step_updating"');

    mysql_query('TRUNCATE TABLE `errors`');
    mysql_query('ALTER TABLE `errors` AUTO_INCREMENT=0');
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

        $client = new WC_API_Client(api_host, api_key_ck, api_key_cs, $options);
        $quantiti_products = $client->products->get_count()['count'];

        mysql_query('UPDATE `updateproducts`.`settings` SET `value` = ' . $quantiti_products . ' WHERE `settings`.`id` = 2');
        for ($step = 0; $step < ceil($quantiti_products / 100); $step++) {
            $arrary_of_products = $client->products->get('', array('fields' => 'id,permalink,meta', 'filter[limit]' => 100, 'filter[offset]' => $step * 100))['products'];
            for ($num_products_in_stack = 0; $num_products_in_stack < count($arrary_of_products); $num_products_in_stack++) {
                $product_data = $arrary_of_products[$num_products_in_stack];
                mysql_query('INSERT INTO `products_list`(`product_id`, `parsing_url`, `product_url`, `date_upload_product_in_list`, `status`) VALUES (' . $product_data['id'] . ',"' . $product_data['meta']['provider_url'] . '","' . $product_data['permalink'] . '",' . time() . ',0)');
                mysql_query('UPDATE `settings` SET `value` = ' . time() . ' WHERE `title` = "last_update"');
                continue_update();
            }
        }


    } catch (WC_API_Client_Exception $e) {

        echo $e->getMessage() . PHP_EOL;
        echo $e->getCode() . PHP_EOL;

        if ($e instanceof WC_API_Client_HTTP_Exception) {

            print_r($e->get_request());
            print_r($e->get_response());
        }
    }
}
/**
 * Created by PhpStorm.
 * User: vlad-
 * Date: 27.07.2017
 * Time: 18:25
 */
function update_product_information(){
    mysql_query('UPDATE `settings` SET `value`=2 WHERE `title`="status_step_updating"');

    while(mysql_num_rows( mysql_query('SELECT `id`,`parsing_url` FROM `products_list` WHERE `status`=0  ORDER BY ID ASC LIMIT 1'))) {
        $data = mysql_fetch_object(
            mysql_query('SELECT `id`,`product_id`,`parsing_url` FROM `products_list` WHERE `status`=0  ORDER BY ID ASC LIMIT 1')
        );
        $pars_data = pars($data->parsing_url);
        if (array_key_exists('error', $pars_data)) {
            mysql_query('UPDATE `products_list` SET `product_quantiti` = 0,`product_price` = 0,`date_update` = '.time().',`status` = 500 WHERE `id` = '.$data->id);
            mysql_query('INSERT INTO `errors` (`product_id`,`id_from_products_list`, `text`) VALUES ('.$data->product_id.','.$data->id.',"'.$pars_data['error'].'")');
        }else{
            mysql_query('UPDATE `products_list` SET `product_quantiti` = ' . $pars_data['stock_quantity'] . ',`product_price` = ' . $pars_data['regular_price'] . ',`date_update` = ' . time() . ',`status` = "1"  WHERE `id` = ' . $data->id);
        }
        mysql_query('UPDATE `settings` SET `value` = ' . time() . ' WHERE `title` = "last_update"');
        continue_update();
    }
    return ready;
}

/**
 * Created by PhpStorm.
 * User: vlad-
 * Date: 27.07.2017
 * Time: 18:25
 */

function upload_products(){
    mysql_query('UPDATE `settings` SET `value`=3 WHERE `title`="status_step_updating"');

    $options = array(
        'debug' => false,
        'return_as_array' => true,
        'validate_url' => false,
        'timeout' => 300,
        'ssl_verify' => false,
    );

    try {

        $client = new WC_API_Client(api_host, api_key_ck, api_key_cs, $options);

        while(mysql_num_rows( mysql_query('SELECT `id` FROM `products_list` WHERE `date_upload`=0  ORDER BY ID ASC LIMIT 1'))){
            $data = mysql_fetch_object(
                mysql_query('SELECT `id`, `product_id`, `product_quantiti`, `product_price`,`status` FROM `products_list` WHERE `date_upload`=0 ORDER BY ID ASC LIMIT 1')
            );

            $client->products->update( $data->product_id, array('sale_price' => '','managing_stock'   => true , 'stock_quantity' => $data->product_quantiti, 'regular_price' => $data->product_price ));
            if($data->status==500 || $data->status==404){
                mysql_query('UPDATE `products_list` SET `date_upload` = ' . time() . ' WHERE `id`='.$data->id);
            }else{
                mysql_query('UPDATE `products_list` SET `status`=2, `date_upload` = ' . time() . ' WHERE `id`='.$data->id);
            }
            mysql_query('UPDATE `settings` SET `value` = ' . time() . ' WHERE `title` = "last_update"');

            continue_update();
        }

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

