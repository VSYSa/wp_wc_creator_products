<?php


$image = '/upload/iblock/be3/2573_1w_svetil_nik_nastennyy.jpg';
$keywords = preg_split("/\//", $image);
print_r($keywords );
echo 'localhost/upload/electra/'.(array_pop($keywords));





/*
ini_set("memory_limit","1024M");
$url='http://www.electra.ru/private_office/export/files/electra_photos.tar.gz?login=potolok.plus2013&pwd=89137758184';

file_put_contents('images.tar.gz', file_get_contents($url));

// декомпрессия из gz
$p = new PharData('images.tar.gz');
$p->decompress(); // создание files.tar
  
// распаковка из tar
$phar = new PharData('images.tar');
$phar->extractTo('images');






$ftp_server    = '91.227.68.183';
$ftp_user_name    = 'image';
$ftp_user_pass    = '9S6j1S3j';
$file = 'index.php';
$fp = fopen($file, 'r');
$ftp = ftp_connect($ftp_server);
$login_result = ftp_login($ftp, $ftp_user_name, $ftp_user_pass);

// Начало загрузки вместо FTP_AUTORESUME можно указать ftp_size($ftp,"test.remote") для докачки с места обрыва
$ret = ftp_nb_put($ftp, "test.remote", "test.local", FTP_BINARY, FTP_AUTORESUME);
while ($ret == FTP_MOREDATA) {

    // производим какие-то действия ...
    echo ".";

    // продолжение загрузки ...
    $ret = ftp_nb_continue($ftp);
}
if ($ret != FTP_FINISHED) {
    echo "При загрузке файла произошла ошибка...";
    exit(1);
}
*/

?>
