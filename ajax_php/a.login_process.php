<?php
if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
	@extract($_POST);
	if(isset($log) && isset($pwd)) {
		include_once '../config/dbconnect.php';
		require_once '../config/studentPortalClass.php';
		$c = new studentPortalClass();
		$userID = addslashes($log);
		$userPW = addslashes($pwd);
		$chk = addslashes($chk);
		$staffId = addslashes($staffId);
		$mob = $c->login($userID, $userPW, 1, $staffId);
		if ($mob != false) {
			// echo json_encode($mob);
      header("Location: https://student.bodwell.edu");

		} else {
			echo json_encode(array('result' => '0'));
		}
	}else {
		echo json_encode(array('result' => '-2'));
	}
} else {
	echo 0;
	exit;
}


?>
