<?php

require_once( 'lib/woocommerce-api.php' );
require_once( 'my.php' );

$options = array(
	'debug'           => true,
	'return_as_array' => true,
	'validate_url'    => false,
	'timeout'         => 30000,
	'ssl_verify'      => false,
);

try {

	$client = new WC_API_Client( 'http://mnogosveta.su', 'ck_2bf7c714e0f782da43d3887c1a173f5a438471a5', 'cs_e0688af1e32568279ca0f3a7dc9dfa5dced00b8a', $options );
write_log("начинаем получение товаров");
	$arrary_of_products = $client->products->get('',array( 'fields' => 'id,permalink','filter[limit]' => 2,'filter[offset]' => 10 ));
if(empty($arrary_of_products -> products)){echo ("netu");}
print_r($arrary_of_products);
write_log("загрузили товары");
//print_r( $client->products->get() );
	/*
	$arrary_of_products = $client->products->get('',array( 'filter[limit]' => -1 ));
//	
	write_log("получили список товаров.");
	for($i=0;$i<count($arrary_of_products["products"]);$i++){

	    write_log("------------------------ Начало парсинга продукта с индексом: $i ------------------------");

	    $product_id = $arrary_of_products["products"][$i]["id"];
	    $product_url = $arrary_of_products["products"][$i]["permalink"];
	    write_log("получили товар: $product_url");
	    
        $quantiti = get_quantiti($product_url);

        $client->products->update( $product_id, array( 'managing_stock'   => true, 'stock_quantity' => "$quantiti" ) );
	}
write_log("!!!!!!!!!!парсинг закончен!!!!!!!!.");
send_email("обновление закончено.");

*/
	//'backorders' => true  предзаказ
	

} catch ( WC_API_Client_Exception $e ) {

	echo $e->getMessage() . PHP_EOL;
	echo $e->getCode() . PHP_EOL;

	if ( $e instanceof WC_API_Client_HTTP_Exception ) {

		print_r( $e->get_request() );
		print_r( $e->get_response() );
	}
}
