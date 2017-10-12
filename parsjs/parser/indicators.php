<?php


function write_log($str){
    $date = date("d-m");
    $time = date("H:i:s");
    $fp = fopen("logs/$date.txt", 'a');
    fwrite($fp, $time);
    fwrite($fp, $str. PHP_EOL);
    fclose($fp);
}
function send_email($message){
    $to      = 'vlad-sys-1998@yandex.ru';
    $subject = 'Обновление цены и количества товаров';
    $headers = 'From: updates-on-mnogosveta.su' . "\r\n" .
        'Reply-To: webmaster@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);


}
function write_error_log($str){
    $date = date("d-m");
    $time = date("H:i:s");
    $fp = fopen("logs/error/$date.txt", 'a');
    fwrite($fp, $time);
    fwrite($fp, $str. PHP_EOL);
    fclose($fp);
}


$request = $_POST;
$do = $request['what_to_do'];
if($do==='write_log'){
    write_log($request['text']);
}elseif($do==='send_email'){

}




?>
