<?php


require_once( 'api/woocommerce-api.php' );
require_once( 'parser/simple_html_dom.php' );
require_once( 'lib.php' );
db();


 mysql_query('UPDATE `settings` SET `value`=1 WHERE `title`="continue_creating"');
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    $shops=array('http://magia-sveta.ru/','http://antares-svet.ru/','http://www.electra.ru/');
    foreach ($shops as $value){
        if (mysql_fetch_array(mysql_query('SELECT COUNT(1) FROM `url_list` WHERE `url`="'.mysql_real_escape_string($value).'"'))[0]==0) {
            mysql_query('INSERT INTO `url_list`(`url`, `status_updating`, `date_of_uploading`, `product_status`) VALUES ("' . mysql_real_escape_string($value) . '",1,' . time() . ',0)');
        }
        unset($value);
    }
    next_url();
    update_product_list();
    upload_products();
    mysql_query('UPDATE `settings` SET `value`=0 WHERE `title`="continue_creating"');
    mysql_query('UPDATE `settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');


?>
