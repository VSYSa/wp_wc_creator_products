<?php
/**
 * Created by PhpStorm.
 * User: vlad-
 * Date: 29.07.2017
 * Time: 11:19
 */

ignore_user_abort(true);
set_time_limit(0);
define("api_host", "https://91.227.68.183/");
define("api_key_ck", "ck_5de465863b736727a6ddc8eb4fc57fc21dc16fec");
define("api_key_cs", "cs_1b154bf205a159e333d24be9fb975b82674daf83");

function db(){
    $db_host="localhost";
    $db_username="root";
    $db_password="toor";
    $db_id = mysql_connect($db_host, $db_username, $db_password)
    or die('Не удалось соединиться: ' . mysql_error());
    mysql_select_db('updateproducts')
    or die('Не удалось выбрать базу данных');
}
function continue_update(){
    $continue_update = mysql_fetch_array(mysql_query("SELECT `value` FROM `settings` WHERE `title`='continue_update'"))[0];
    if($continue_update==1){
        mysql_query("UPDATE `settings` SET `value` = 'progress' WHERE `title` = 'progress_status'");
        return;
    }elseif ($continue_update==0){
        mysql_query("UPDATE `settings` SET `value` = 'stop' WHERE `title` = 'progress_status'");
        exit;
    }elseif ($continue_update==2){
        mysql_query("UPDATE `settings` SET `value` = 'pause' WHERE `title` = 'progress_status'");
        sleep(1);
        continue_update();
    }
}
function url_pars($url)
{
    $html = new simple_html_dom();
    $html->load($url);
    $ready = $html->find('.Qsource', 0)->plaintext;
    return $ready;
}
function write_log($str){
    $date = date("d-m");
    $time = date("H:i:s");
    $fp = fopen("logs/$date.txt", 'a');
    fwrite($fp, $time);
    fwrite($fp, $str. PHP_EOL);
    fclose($fp);
}
function table_in_array($mysql_query){
    $rs=mysql_query($mysql_query);
    $table = array();
    $schet=0;
    while($row = mysql_fetch_assoc($rs)) {
        $strROW = array();
        foreach ($row as $key => $value){
            $strROW[$key] = $value;
        }
        $table[$schet] = $strROW;
        $schet++;
    }
    return $table;
}
function send_email($message){
    $to      = 'vlad-sys-1998@yandex.ru';
    $subject = 'Обновление цены и количества товаров vs db';
    $headers = 'From: updates-on-mnogosveta.su' . "\r\n" .
        'Reply-To: webmaster@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);
}
function send($a){
    print_r(json_encode($a));
}

?>