<?php

require_once( 'lib/woocommerce-api.php' );
require_once( 'my.php' );
write_log("Запуск программы.");
send_email("начало обновления.");
$options = array(
	'debug'           => true,
	'return_as_array' => true,
	'validate_url'    => false,
	'timeout'         => 300,
	'ssl_verify'      => false,
);

try {

	$client = new WC_API_Client( 'http://test.mnogosveta.su', 'ck_2bf7c714e0f782da43d3887c1a173f5a438471a5', 'cs_e0688af1e32568279ca0f3a7dc9dfa5dced00b8a', $options );
write_log("Авторизировали API.");


for($from_this = 0;$from_this < 20;$from_this++){
    
write_log("----------парсим $from_this пачку товаров.");

	$arrary_of_products = $client->products->get('',array( 'fields' => 'id,permalink','filter[limit]' => 500,'filter[offset]' => $from_this*500  ));
	if(!empty($arrary_of_products["products"])){
	    
	write_log("получили список продуктов.");
	for($i=0;$i<count($arrary_of_products["products"]);$i++){

	    write_log("------------------------ Начало парсинга продукта с индексом: $i ------------------------");
        contiune_parsing();
	    $product_id = $arrary_of_products["products"][$i]["id"];
	    $product_url = $arrary_of_products["products"][$i]["permalink"];
	    write_log("получили товар: $product_url");
	    
        $data = get_data($product_url);
        write_log("** $data **");
        $client->products->update( $product_id, $data );
	}
}else{
    write_error_log("в $from_this пачек товаров нет позиций");
    write_log("в $from_this пачек товаров нет позиций");}

}

write_log("!!!!!!!!!!парсинг закончен!!!!!!!!.");
send_email("обновление закончено. ");
	



} catch ( WC_API_Client_Exception $e ) {

	echo $e->getMessage() . PHP_EOL;
	echo $e->getCode() . PHP_EOL;

	if ( $e instanceof WC_API_Client_HTTP_Exception ) {

		print_r( $e->get_request() );
		print_r( $e->get_response() );
	}
}
