<?php
if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
  @extract($_POST);
  if(isset($studentActivityId) && isset($title) && isset($category) && isset($location) && isset($sDate) && isset($eDate) && isset($hours) && isset($vlwe) && isset($approver) && isset($comment1) && isset($comment2) && isset($comment3)) {
    include_once '../config/dbconnect.php';
    require_once '../config/studentPortalClass.php';
    $c = new studentPortalClass();
    $supervisor = "";
    $mob = $c->UpdateActivityRecords($studentActivityId,$title,$category,$location,$sDate,$eDate,$hours,$vlwe,$approver,$approverFullName,$supervisor,$comment1,$comment2,$comment3);
  		if ($mob != false) {
  			echo json_encode($mob);
  		} else {
  			echo json_encode(array('result' => '0'));
  		}
  } else {
    echo json_encode(array('result' => '0'));
  }
} else {
	echo 0;
	exit;
}
?>
