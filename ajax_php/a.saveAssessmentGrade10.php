<?php
if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
  @extract($_POST);
  if(isset($AssessmentFormID)) {
    include_once '../config/dbconnect.php';
    require_once '../config/studentPortalClass.php';
    $c = new studentPortalClass();
    $mob = $c->SaveAssessmentGrade10($AssessmentFormID,$AssessmentID, $cRate, $communication, $tRate, $thinking, $pRate, $personal, $personal1);
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
