<?php
function update_status($what,$str){
    $myFile = "/$what/stop_parsing.txt";
    $f = fopen($myFile, 'a');

    fwrite($fp, $str);
    fclose($fp);}

    if($_GET['q']==1){update_status(quantiti,true);}
    elseif($_GET['q']==0){update_status(quantiti,false);}
    
    if($_GET['p']==1){update_status(prise,true);}
    elseif($_GET['p']==0){update_status(prise,false);}
    
    if($_GET['a']==1){update_status(all,true);}
    elseif($_GET['a']==0){update_status(all,false);}

?>