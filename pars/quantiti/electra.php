<?php
require_once 'simple_html_dom.php';


$ch = curl_init();
$url = 'http://www.electra.ru/catalog/Lyustry/1390999/';
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

$html = str_get_html(curl_exec($ch));


$es = $html -> find('div.nx-basket-byer');
$es = $es[data-cart];
preg_match('|{(.*?)}|sei', $es, $arr) ;

$f = json_decode($arr[0], true);
echo $f[ost];
curl_close($ch);

?>