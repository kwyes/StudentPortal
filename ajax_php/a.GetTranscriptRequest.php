<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
  include_once '../config/dbconnect.php';
  require_once '../config/studentPortalClass.php';
  $c = new studentPortalClass();
  $mob = $c->GetTranscriptRequest();
		if ($mob != false) {
			echo json_encode($mob);
		} else {
			echo json_encode(array('result' => '0'));
		}
} else {
	echo 0;
	exit;
}
?>
