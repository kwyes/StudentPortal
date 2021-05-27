<?php
if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
  @extract($_POST);
  if(isset($course) && isset($description) && isset($email) && isset($firstName) && isset($lastName) && isset($phone) && isset($position) && isset($topic)) {
    include_once '../config/dbconnect.php';
    require_once '../config/studentPortalClass.php';
    $category = '';
    if($others) {
      $category = $others;
    } else {
      $category = $capCategory;
    }
    $c = new studentPortalClass();
    $mob = $c->AddCareerLifePathway($course,$description,$email,$firstName,$lastName,$phone,$position,$topic,$category);
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
