<?php

require_once 'simple_html_dom.php';

function send_email($message){
    $to      = 'vlad-sys-1998@yandex.ru';
$subject = 'Обновление цены товаров';
$headers = 'From: updates-on-mnogosveta.su' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
    
    
}
function write_log($str){
    $date = date("d-m");
    $time = date("H:i:s");
    $fp = fopen("logs/$date.txt", 'a');
    fwrite($fp, $time);
    fwrite($fp, $str. PHP_EOL);
    fclose($fp);
}
function write_error_log($str){
    $date = date("d-m");
    $time = date("H:i:s");
    $fp = fopen("logs/error/$date.txt", 'a');
    fwrite($fp, $time);
    fwrite($fp, $str. PHP_EOL);
    fclose($fp);
}
function contiune_parsing(){
    $fp = fopen("stop_parsing.txt", 'r');
$str = fgets($fp);
    fclose($fp);
if($str != "true"){return true;}else{
    write_log("принудительное окончание обновления");
	send_email("принудительное окончание обновления.");
	exit;}
}
function pars_magia_sveta($url){
    $str = 0;
        $html = file_get_html($url);
        if($html->innertext!='' and count($html->find('span.price'))){    //если сайт не пустой и нашел тег а с классом available-tab-open
            $str = $html->find('span.price',0);    //находим первое значение у тега а с классом available-tab-open
            $str =  preg_replace('|<span class="old-price">(.*?)</span>|sei', '', $str) ;
            $str = preg_replace("/[^0-9]/", '', $str);     //отчищаем от слов
             //выводим строку
        }else{return ("error");}
        return $str;
        $html->clear();
        unset($html);
    
}
function pars_electra($url){
    write_log("готовимся к авторизации");
    $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url ); // отправляем на
curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут4
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); // сохранять куки в файл
curl_setopt($ch, CURLOPT_COOKIEFILE,  dirname(__FILE__).'/cookie.txt');
curl_setopt($ch, CURLOPT_POST, 1); // использовать данные в post
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
  'USER_LOGIN'=>'potolok.plus2013',
  'USER_PASSWORD'=>'89137758184',
  'backurl'=>'/catalog/Lyustry/1390999/',
  'AUTH_FORM'=>'Y',
  'TYPE'=>'AUTH',
  'TYPE_NX'=>'AUTH',
  'Login'=>'Войти',
));
write_log("авторизировались");
$html = str_get_html(curl_exec($ch));
if($html->innertext!='' and count($html->find('div.nx-basket-byer'))){
curl_close($ch);
$out = $html -> find('div.nx-basket-byer');
$out = $out[data-cart];
preg_match('|{(.*?)}|sei', $out, $arr) ;

$prise = json_decode($arr[0], true);
write_log("распарсили и получили $quantiti");
}else{return ("error");}
return $prise[price]*2;

}
function pars_antares_svet($url){
    $html = file_get_html($url);
    
        write_log("скачали страницу товара антареса");
        $str = 0;
        if($html->innertext!='' and (count($html->find('span.roubles')) or count($html->find('div.gogolya')))){    //если сайт не пустой и нашел тег а с классом available-tab-open
        
             $str = $html->find('span.roubles', 0);   //смотрим сколько товара на ватутина 99
             $str = preg_replace("/[^0-9]/", '', $str);   //отчищаем от слов
             write_log("получили $str");
            
        }else{return ("error");}
        return $str; //выводим строку
        $html->clear();
        unset($html);
    
}

function startparsing($url){
    if(preg_match ( '/magia-sveta.ru/' ,  $url )) {
    write_log("сайт магия света");
    return pars_magia_sveta($url);
    

}elseif(preg_match ( '/electra.ru/' ,  $url )){
    return pars_electra($url);
}
elseif(preg_match ( '/antares-svet.ru/' ,  $url )){
    write_log("сайт антарес свет");
    return pars_antares_svet($url);
}}

function parsing_us($url_us){
    write_log("Запуск парсинга своей страницы");
    $str = 0;
    $html = file_get_html($url_us);
        if($html->innertext!='' and count($html->find('div.Qsource'))){    //если сайт не пустой и нашел тег а с классом available-tab-open
            $str = strip_tags($html->find('div.Qsource',0));    //находим первое значение у тега а с классом Qsource
           // $str = preg_replace("/[^0-9]/", '', $str);    отчищаем от слов
        write_log("получили $str");
             //выводим строку
             return $str;
        }else{return ("error");}
        
        $html->clear();
        unset($html);
    
}

function get_prise($product_url){
    write_log("начали парсинг");
    
    $pars_url  = parsing_us($product_url);
    if($pars_url =="error"){
        write_error_log("не смогли распарсить свою страницу $product_url");
        return 0;}
    $prise = startparsing($pars_url);
    if($prise =="error"){
        write_error_log("распарсили свою страницу $product_url, но не смогли распарсить страницу товара $pars_url");
        return 0;}
    write_log("цена товара = <$prise руб.>");
    return $prise;
}

?>