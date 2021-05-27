<?php
if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
  @extract($_POST);
  if(isset($AssessmentFormID)) {
    include_once '../config/dbconnect.php';
    require_once '../config/studentPortalClass.php';
    $c = new studentPortalClass();
    $mob = $c->SaveAssessmentGrade8($AssessmentFormID,$AssessmentID, $cRate, $tRate, $pRate,
     $Communication1, $Communication2, $Communication3, $Communication4, $Communication5, $Communication6, $Communication7, $Communication8, $Communication9
   , $Communication10, $Communication11, $Communication12
  , $Personal1, $Personal2, $Personal3, $Personal4, $Personal5, $Personal6, $Personal7, $Personal8, $Personal9, $Personal10, $Personal11, $Personal12
  , $Thinking1, $Thinking2, $Thinking3, $Thinking4, $Thinking5, $Thinking6, $Thinking7, $Thinking8, $Thinking9, $Thinking10, $Thinking11, $Thinking12);
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
