<?php
if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
  @extract($_POST);
  if(isset($activityName) && isset($activityCategory) && isset($activityLocation) && isset($activitySDate) && isset($activityEDate) && isset($activityHours) && isset($activityVLWE) && isset($activityApprover) && isset($activityComment1) && isset($activityComment2) && isset($activityComment3)) {
    include_once '../config/dbconnect.php';
    require_once '../config/studentPortalClass.php';
    $c = new studentPortalClass();
    $mob = $c->AddActivityRecords($activityName,$activityCategory,$activityLocation,$activitySDate,$activityEDate,$activityHours,$activityVLWE,$activityApprover,$activityApproverFullName,$activitySupervisor,$activityComment1,$activityComment2,$activityComment3);
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