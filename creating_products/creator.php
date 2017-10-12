<?php



require_once( 'api/woocommerce-api.php' );
require_once( 'parser/simple_html_dom.php' );
require_once( 'lib.php' );
db();






if($_POST['what_to_do']==='start_creating'){
    mysql_query('UPDATE `settings` SET `value`=1 WHERE `title`="continue_creating"');
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    foreach (json_decode($_POST['updating_shops']) as $value){
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


}
elseif($_POST['what_to_do']==='start_spider'){
    mysql_query('UPDATE `settings` SET `value`=1 WHERE `title`="continue_creating"');
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    foreach (json_decode($_POST['updating_shops']) as $value){
        if (mysql_fetch_array(mysql_query('SELECT COUNT(1) FROM `url_list` WHERE `url`="'.mysql_real_escape_string($value).'"'))[0]==0) {
            mysql_query('INSERT INTO `url_list`(`url`, `status_updating`, `date_of_uploading`, `product_status`) VALUES ("' . mysql_real_escape_string($value) . '",1,' . time() . ',0)');
        }
        unset($value);
    }
    next_url();
    mysql_query('UPDATE `settings` SET `value`=10 WHERE `title`="continue_creating"');
    mysql_query('UPDATE `settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');
}
elseif($_POST['what_to_do']==='update_product_list'){
    mysql_query("UPDATE `settings` SET `value` = 1 WHERE `title` = 'continue_creating'");
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    update_product_list();
    mysql_query('UPDATE `settings` SET `value`=10 WHERE `title`="continue_creating"');
    mysql_query('UPDATE `settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');

}
elseif($_POST['what_to_do']==='check_on_valid_products'){
    mysql_query("UPDATE `settings` SET `value` = 1 WHERE `title` = 'continue_creating'");
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    check_on_valid_products();
    mysql_query('UPDATE `settings` SET `value`=10 WHERE `title`="continue_creating"');
    mysql_query('UPDATE `settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');

}
elseif($_POST['what_to_do']==='upload_products'){
    mysql_query("UPDATE `settings` SET `value` = 1 WHERE `title` = 'continue_creating'");
    mysql_query('UPDATE `settings` SET `value`='.time().' WHERE `title`="time_of_start_updating"');
    upload_products();
    mysql_query('UPDATE `settings` SET `value`=10 WHERE `title`="continue_creating"');
    mysql_query('UPDATE `settings` SET `value` = '.time().' WHERE `title` = "end_of_updating"');

}



?>
