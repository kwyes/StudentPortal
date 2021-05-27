<?php
error_reporting(0);
session_start();
// require_once __DIR__.'/settings.php';
// global $settings;

$layoutDir = "layout/";
$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
$url = $_SERVER['REQUEST_URI'];

include_once $layoutDir."header.html";



  if(!isset($_SESSION['studentId'])) {
  	if($page == "reset") {
  		include_once $layoutDir."resetPassword.html";
  	} else {
  		include_once $layoutDir."login.php";
  	}
  } else {
    include_once $layoutDir."sidebar.php";
    switch($page) {
  		default : {
  			include_once $layoutDir."dashboard.php";
  			break;
  		}
      case "dashboard" : {
  			include_once $layoutDir."dashboard.php";
  			break;
  		}
  		case "gradebook" : {
  			include_once $layoutDir."gradebook.php";
  			break;
  		}
      case "participation" : {
  			include_once $layoutDir."participation.php";
  			break;
      }
      case "documents" : {
  			include_once $layoutDir."documents.php";
  			break;
      }
      case "resetEmail" : {
  			include_once $layoutDir."resetEmail.php";
  			break;
      }
      case "past" : {
  			include_once $layoutDir."pastTerm.php";
  			break;
  		}
  		case "login" : {
  			include_once $layoutDir."login.php";
  			break;
  		}
      case "logout" : {
  			include_once $layoutDir."logout.php";
  			break;
  		}
      case "reset" : {
  			include_once $layoutDir."resetPassword.html";
  			break;
      }
      case "aep" : {
  			include_once $layoutDir."aep.php";
  			break;
  		}
      case "assessments" : {
  			include_once $layoutDir."assessments.php";
  			break;
  		}
      case "selfAssessment" : {
  			include_once $layoutDir."selfAssessment.php";
  			break;
  		}
      case "leave" : {
  			include_once $layoutDir."leave.php";
  			break;
  		}
      case "transcriptRequest" : {
  			include_once $layoutDir."transcriptRequest.php";
  			break;
  		}
      case "reportcard" : {
  			include_once $layoutDir."reportcard.php";
  			break;
  		}
      case "reportcardMidterm" : {
  			include_once $layoutDir."reportcardMidterm.php";
  			break;
  		}
      case "reportcardFinalterm" : {
  			include_once $layoutDir."reportcardFinalterm.php";
  			break;
  		}
  	}
    include_once $layoutDir."version.html";

  }

	include_once $layoutDir."footer.html";
?>
<script type="text/javascript">
  if (document.layers) {
    //Capture the MouseDown event.
    document.captureEvents(Event.MOUSEDOWN);

    //Disable the OnMouseDown event handler.
    document.onmousedown = function () {
      return false;
    };
  } else {
    //Disable the OnMouseUp event handler.
    document.onmouseup = function (e) {
      if (e != null && e.type == "mouseup") {
        //Check the Mouse Button which is clicked.
        if (e.which == 2 || e.which == 3) {
          //If the Button is middle or right then disable.
          return false;
        }
      }
    };
  }

  //Disable the Context Menu event.
  document.oncontextmenu = function () {
    return false;
  };
</script>
