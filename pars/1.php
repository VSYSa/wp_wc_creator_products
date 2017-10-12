<?php
$str = $_POST['data'];
$str = preg_replace("/\/[a-zA-ZА-Яа-я0-9]+\//", "",$str);
echo "http://mnogosveta.su/addition/imagesforupload/$str";

?>