<?php
$token = isset($_GET['token']) ? $_GET['token'] : null;

require_once __DIR__.'/../settings.php';

switch($settings['env']) {
    case 'staging':
        $refUrl = "https://dev.bodwell.edu/student.it.edu";
        break;
    case 'production':
        $refUrl = "https://student.bodwell.edu";
        break;
    default:
        $refUrl = "https://student.bodwell.edu";
        break;
}


function redirectToInvalidToken() {
    global $settings;
    switch($settings['env']) {
        case 'staging':
            $timeoutUrl = "https://dev.bodwell.edu/student.it.edu/forgot-password-timeout";
            break;
        case 'production':
            $timeoutUrl = "https://student.bodwell.edu/forgot-password-timeout";
            break;
        default:
            $timeoutUrl = "https://student.bodwell.edu/forgot-password-timeout";
            break;
    }
    header("Location: {$timeoutUrl}");
    exit();
}

if($token) {
    global $settings;
    if($settings['pdo']['database'] == 'mysql') {
        $db = new PDO($settings['pdo']['dsn'], $settings['pdo']['user'], $settings['pdo']['pass']);
    } else {
        $db = new PDO($settings['pdo']['dsn']);
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $now = date('Y-m-d H:i:s');
    $sql = "SELECT EmailID email, DATEDIFF(second, CreateDate, '{$now}') secondsElapsed FROM tblBHSUserAuthToken WHERE Token=? AND EmailID=?";
    $st = $db->prepare($sql);
    $email = $_GET['email'];
    try {
        $st->execute(array($token, $email));
        $res = $st->fetchAll(\PDO::FETCH_ASSOC);
        if(count($res) == 1) {
            $row = $res[0];
            if($row['secondsElapsed'] > 1800) { // 600 seconds(10 minutes)
                redirectToInvalidToken();
            }
        } else {
            redirectToInvalidToken();
        }
    } catch(Exception $e) {
        header("HTTP/1.0 500 Internal Server Error");
        echo 'Internal Server Error';
        var_dump($e);
        exit();
    }
} else {
    redirectToInvalidToken();
}

 ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/logo_small.png">
  <link rel="icon" type="image/png" href="../assets/img/logo_small.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Bodwell Student Portal
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link href="../assets/css/style.css?v=0.2" rel="stylesheet" />
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../js/global.js"></script>
</head>

<body class="login-page">
  <div class="wrapper wrapper-full-page ">
    <div class="full-page">
      <div class="content">
        <div class="text-center"><img src="../assets/img/logo.png" alt="logo" class="login-logo" /></div>
        <div class="container">
          <div class="col-lg-6 col-md-6 ml-auto mr-auto">
            <form class="form" method="" action="">
              <div class="card card-login login-card-border">
                <div class="card-header ">
                  <div class="card-header ">
                    <h3 class="header text-center login-title">Bodwell Student Portal</h3>
                    <h4 class=" text-center text-reset">Create New Password</h4>
                  </div>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <input type="text" id="change-username" class="form-control" placeholder="Username (School Email)" value="<?=$_GET['email']?>">
                  </div>
                  <div class="form-group">
                    <input type="password" id="change-pw1" class="form-control" placeholder="New password">
                  </div>
                  <div class="form-group">
                    <input type="password" id="change-pw2" class="form-control" placeholder="Confirm password">
                  </div>
                </div>
                <div class="card-footer ">
                  <button type="button" class="btn btn-danger btn-round btn-block mb-3 password-confirm">Submit</button>
                  <input type="hidden" id="change-token" class="form-control" value="<?=$_GET['token']?>">
                  <input type="hidden" id="change-url" class="form-control" value="<?=$refUrl?>">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <footer class="footer footer-black  footer-white ">
        <div class="container-fluid">
          <div class="row">
            <div class="credits ml-auto">
              <span class="copyright">
                ©
                <script>
                  document.write(new Date().getFullYear())
                </script>, Bodwell High School. All rights reserved. v1.0.6
              </span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.password-confirm').click(function(event) {
        changePw();
      });
    });
  </script>
</body>
</html>
