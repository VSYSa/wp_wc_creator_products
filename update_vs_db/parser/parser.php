<?php
require_once 'simple_html_dom.php';



function pars($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec ($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($code == 404) {
        return (array('error' => 'Page not found'));
    }
    curl_close ($ch);

    if(preg_match ( '/magia-sveta.ru/' ,  $url )) {
        return(pars_magia_sveta($url));

    }elseif(preg_match ( '/shop.electra.ru/' ,  $url )){
        return(pars_electra($url));
    }elseif(preg_match ( '/antares-svet.ru/' ,  $url )){

        return(pars_antares_svet($url));
    }elseif(preg_match ( '/electra.ru/' ,  $url )){

            return(pars_electra($url));
    }
}


function pars_magia_sveta($url){
    $str = 0;
        $html = file_get_html($url);
        if($html->innertext!='' and !isset($html->find('div.product-price a',0)->attr['data-window-id'])  and count($html->find('a.available-tab-open'))){    //если сайт не пустой и нашел тег а с классом available-tab-open

            $strp = $html->find('span.price',0);    //находим первое значение у тега а с классом available-tab-open
            $strp =  preg_replace('|<span class="old-price">(.*?)</span>|sei', '', $strp) ;
            $strp = preg_replace("/[^0-9]/", '', $strp);     //отчищаем от слов
            $strq = $html->find('a.available-tab-open',0);    //находим первое значение у тега а с классом available-tab-open
            $strq = preg_replace("/[^0-9]/", '', $strq);
             //выводим строку
        }else{
            if(count($html->find('a.available-tab-open'))){
                return (array('error' => 'No deliveries'));
            }
            if(!isset($html->find('div.product-price a',0)->attr['data-window-id'])){
                return (array('error' => 'product has not price'));
            }
                return (array('error' => 'Page donot load'));
        }
        $html->clear();
        unset($html);
        $str = array( 'stock_quantity' => $strq, 'regular_price' => $strp);
        return ($str);
    
}
function pars_electra($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url ); // отправляем на
    curl_setopt($ch, CURLOPT_HEADER, 1); // пустые заголовки
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер

    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4

    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // следовать за редиректами
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTREDIR, 3);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); // сохранять куки в файл
    curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.txt');
    //curl_setopt($ch, CURLOPT_POST, 1); // использовать данные в post
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
      'USER_LOGIN'=>'potolok.plus2013',
      'USER_PASSWORD'=>'89137758184',
      'backurl'=>"$url",
      'AUTH_FORM'=>'Y',
      'TYPE'=>'AUTH',
      'TYPE_NX'=>'AUTH',
      'Login'=>'Войти',
    ));
    $result = curl_exec ($ch);

    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($code == 301 || $code == 302) {
        $newurl = preg_replace('/electra.ru/', 'www.electra.ru', $url);
        curl_close ($ch);
        return pars_electra($newurl);
    }
    $html = str_get_html($result);
    if($html->innertext!=''  and count($html->find('div.nx-basket-byer'))){
        curl_close($ch);
        $out = $html -> find('div.nx-basket-byer');
        $out = $out[data-cart];
        preg_match('|{(.*?)}|sei', $out, $arr) ;
        $data = json_decode($arr[0], true);
        $quantiti = $data[ost];
        $prise = $data[price]*2;
    }else{
        return (array('error' => 'Page donot load'));
    };
    $str = array('stock_quantity' => $quantiti, 'regular_price' => $prise);
    return ($str);
}


function pars_antares_svet($url){
     $html = file_get_html($url);
    $price = $html->find('div.price', 0);   //смотрим сколько стоит товар
    $price = preg_replace("/[^0-9]/", '', $price);   
    $quantity1=0;
    $quantity2=0;
    if(count($html->find('span.quant', 0))){
        $quantity1 = $html->find('span.quant', 0)->innertext;   //смотрим сколько товара на ватутина 99
        $quantity1 = preg_replace("/[^0-9]/", '', $quantity1);
    }
    if(count($html->find('span.quant', 1))){
        $quantity2 = $html->find('span.quant', 1)->innertext;//смотрим сколько товара на гоголя 32/1
        $quantity2 = preg_replace("/[^0-9]/", '', $quantity2);
    }
    $quantity=$quantity1+$quantity2;
   
   
    $str = array( 'stock_quantity' => $quantity, 'regular_price' => $price);
    return ($str);
}


?>