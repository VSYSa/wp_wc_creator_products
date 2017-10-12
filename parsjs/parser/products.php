<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));
function clear_request($arr){
    unset($arr['http']);
    $arr =$arr['products'];
    return $arr;
}
function send_request($arr){
    print_r(json_encode(clear_request($arr)));
    return;
}
?>
<?php

require_once( 'lib/woocommerce-api.php' );
$options = array(
	'debug'           => false,
	'return_as_array' => true,
	'validate_url'    => false,
	'timeout'         => 300,
	'ssl_verify'      => false,
);

try {

	$client = new WC_API_Client( 'http://test.mnogosveta.su', 'ck_4c73442695f4c3448b7d89febe2e98379622f6cf', 'cs_ba856bcf8cb73fc3efe1d32570d2cfd2118b36b2', $options );
    $request = $_POST;
    $do_products = $request['what_to_do'];
    if($do_products==='get'){
        $array_of_products = $client->products->get('',array( 'fields' => $request['fields'],'filter[limit]' => $request['products_limit'],'filter[offset]' => $request['products_offset']  ));
        send_request($array_of_products);
    }
    elseif($do_products==='send'){
        var_dump(json_decode($request['products'], true));
        $arr = json_decode($request['products'], true);
        for($i=0;$i < count($arr);$i++) {
            $client->products->update( $arr[$i]['id'], $arr[$i]['description'] );
        }
        echo 'uploaded';
    }
    elseif($do_products==='remove_product'){
        $client->products->delete( $request['product_id'], true );
        echo 'removed';
        }
    elseif($do_products==='getproduct'){
        $product = $client->products->get($request['product_id'],array( 'fields' => $request['fields']));
        unset($product['http']);
        $product=$product['product'];
        print_r(json_encode($product));

    }
    elseif($do_products==='getcount'){
        print_r( $client->products->get_count()['count']);
    }else{echo 111;}




} catch ( WC_API_Client_Exception $e ) {

	echo $e->getMessage() . PHP_EOL;
	echo $e->getCode() . PHP_EOL;

	if ( $e instanceof WC_API_Client_HTTP_Exception ) {

		print_r( $e->get_request() );
		print_r( $e->get_response() );
	}
}
