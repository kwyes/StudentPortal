<div class="main-panel">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <div class="navbar-minimize">
          <button id="minimizeSidebar" class="btn btn-icon btn-round">
            <i class="nc-icon nc-minimal-right text-center visible-on-sidebar-mini"></i>
            <i class="nc-icon nc-minimal-left text-center visible-on-sidebar-regular"></i>
          </button>
        </div>
        <div class="navbar-toggle">
          <button type="button" class="navbar-toggler">
            <span class="navbar-toggler-bar bar1"></span>
            <span class="navbar-toggler-bar bar2"></span>
            <span class="navbar-toggler-bar bar3"></span>
          </button>
        </div>
        <div class="page-title">My Gradebook - AEP</div>
      </div>
    </div>
  </nav>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><i class="material-icons-outlined card-icon">class</i>My Gradebook - Academics &
              English Preparetion (AEP)</h4>
            <div class="card-category">
              <p class="mg-b-0"><?php echo $_SESSION['SemesterName'].' - '.$_SESSION['termStatus']?></p>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 ">
                <button type="button" class="btn btn-default btn-sm mg-r-10 btn-iap" data-toggle="modal"
                  data-target="#iapMdl">IAP</button>
                <button type="button" class="btn btn-default btn-sm mg-r-10 btn-midComment" data-toggle="modal"
                  data-target="#aepCommentMdl">Mid-term Comment</button>
                <button type="button" class="btn btn-default btn-sm mg-r-10 btn-final" data-toggle="modal"
                  data-target="#aepCommentMdl">Final Comment</button>
                <a href="#aepScoresMdl" data-toggle="modal">What do scores mean?</a>
              </div>
            </div>
            <select id="aep-subject" class="select-category">
            </select>
            <table id="datatable-aep" class="table table-hover display nowrap" cellspacing="0" width="100%">
              <thead class="text-warning text-center">
                <tr>
                  <th>Skill</th>
                  <th>Standardized<br />Test 1</th>
                  <th>Progress 1</th>
                  <th>Progress 2</th>
                  <th>Mid-term</th>
                  <th>Progress 3</th>
                  <th>Progress 4</th>
                  <th>Standardized<br />Test 2</th>
                  <th>Final</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            <!-- </div> -->
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>
    <!-- end row -->
  </div>
  <script src="js/modalControl.js?ver=3.4" charset="utf-8"></script>
  <?php include_once('layout/spinner.html'); ?>
  <?php include_once('layout/aepScoresModal.html'); ?>
  <?php include_once('layout/iapModal.html'); ?>
  <?php include_once('layout/aepCommentModal.html'); ?>
  <script>
    $(document).ready(function () {
      ajaxtoAEPCourses( <?=$_SESSION['SemesterID']?> );
      ajaxtoIAPRubric( <?= $_SESSION['SemesterID']?> );
      var termStatus = '<?=$_SESSION["termStatus"]?>';
      $('.currentTerm-status').html(' - ' + termStatus);
      minimizeSidebar();
      $("#aep-subject").change(function () {
        var val = $('#aep-subject').val();
        createAEPTable(aepCourses, val);
        $(".aep-comment").html('');
        $(".aep-comment-error").html('');
      });
    });
  </script>
