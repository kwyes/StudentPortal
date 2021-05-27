<?php
require_once 'sendEmailClass.php';

$from = 'helpdesk@bodwell.edu';
$cc = '';
$subject = 'test2';
$body = 'test2';


$to = array(
    array('email' => 'chanho.lee@bodwell.edu', 'name' => "test")
);
// sendEmail($from, $to, $cc, $subject, $body, $altBody = '');
 ?>
