<?php
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));
?>
<?php
require_once 'lib/simple_html_dom.php';
$url = $_POST['url'];


if(preg_match ( '/magia-sveta.ru/' ,  $url )) {
    print_r(pars_magia_sveta($url));

}elseif(preg_match ( '/shop.electra.ru/' ,  $url )){
        print_r(pars_electra($url));
}elseif(preg_match ( '/antares-svet.ru/' ,  $url )){

    print_r(pars_antares_svet($url));
}elseif(preg_match ( '/electra.ru/' ,  $url )){

        echo(pars_electra($url));
}



function pars_magia_sveta($url){
    $str = 0;
        $html = file_get_html($url);
        if($html->innertext!='' and count($html->find('span.price')) and count($html->find('a.available-tab-open'))){    //если сайт не пустой и нашел тег а с классом available-tab-open
            $strp = $html->find('span.price',0);    //находим первое значение у тега а с классом available-tab-open
            $strp =  preg_replace('|<span class="old-price">(.*?)</span>|sei', '', $strp) ;
            $strp = preg_replace("/[^0-9]/", '', $strp);     //отчищаем от слов
            $strq = $html->find('a.available-tab-open',0);    //находим первое значение у тега а с классом available-tab-open
            $strq = preg_replace("/[^0-9]/", '', $strq);
             //выводим строку
        }else{return (json_encode(array('error' => 'Страница не загружена')));}
        $html->clear();
        unset($html);
        $str = array('managing_stock'   => true , 'stock_quantity' => $strq, 'regular_price' => $strp);
        $str = json_encode($str);
        return ($str);
    
}
function pars_electra($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url ); // отправляем на
    curl_setopt($ch, CURLOPT_HEADER, 1); // пустые заголовки
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер

    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // следовать за редиректами
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
    if($html->innertext!='' ){
        curl_close($ch);
        $out = $html -> find('div.nx-basket-byer');
        $out = $out[data-cart];
        preg_match('|{(.*?)}|sei', $out, $arr) ;
        $data = json_decode($arr[0], true);
        $quantiti = $data[ost];
        $prise = $data[price]*2;
    }else{
        return (json_encode(array('error' => 'Страница не загружена')));
    };
    $str = array('stock_quantity' => $quantiti, 'regular_price' => $prise, 'sale_price' => '');
    $str = json_encode($str);
    return ($str);
}

function pars_antares_svet($url){
    $html = file_get_html($url);
    

        $str = 0;
        if($html->innertext!='' and (count($html->find('span.roubles')) or count($html->find('div.gogolya')))){    //если сайт не пустой и нашел тег а с классом available-tab-open
        
             $str = $html->find('span.roubles', 0);   //смотрим сколько товара на ватутина 99
             $str = preg_replace("/[^0-9]/", '', $str);   //отчищаем от слов
            
            
            $str1 = $html->find('div.vatytina', 0)->find('.td', 0);   //смотрим сколько товара на ватутина 99
             $str2 = $html->find('div.gogolya', 0)->find('.td', 0);    //смотрим сколько товара на гоголя 32/1
             $str1 = preg_replace("/[^0-9]/", '', $str1);     //отчищаем от слов
             $str2 = preg_replace("/[^0-9]/", '', $str2);   //отчищаем от слов
             $str3=$str1+$str2;

        }else{return (json_encode(array('error' => 'Страница не загружена')));}
        $html->clear();
        unset($html);
        $str = array('managing_stock'   => true , 'stock_quantity' => $str3, 'regular_price' => $str);
        $str = json_encode($str);
        return ($str);

    
}




?>