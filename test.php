<?php
// $gradenum = "AEP 10(2)";
// $gradenum = strtok($gradenum, '(');
// $int = (int) filter_var($gradenum, FILTER_SANITIZE_NUMBER_INT);
// echo $int;
function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

echo getUserIpAddr();

 ?>
