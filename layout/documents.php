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
        <div class="page-title">Documents</div>
      </div>
    </div>
  </nav>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><i class="material-icons-outlined card-icon">class</i>School Documents</h4>
            <div class="card-category">
              <p class="mg-b-0"><?php echo $_SESSION['SemesterName'].' - '.$_SESSION['termStatus']?></p>
              <!-- <p class="mg-b-0"><span class="gradebook-currentterm"></span><span class="currentTerm-status"></span></p> -->
              <p><span class="gradebook-classinfo"></span></p>
            </div>
          </div>
          <div class="card-body">
            <div class="places-sweet-alerts">
              <div class="row">
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-content text-center">
                      <h5>Bodwell Student Handbook – Spring 2021</h5>
                      <a href="assets/file/Bodwell Handbook - Spring 2021.pdf" target="_blank">
                        <button class="btn btn-rose btn-fill">Open</button>
                      </a>
                    </div>
                  </div>
                </div>
                <!-- <div class="col-md-3">
                  <div class="card">
                    <div class="card-content text-center">
                      <h5>Important Dates and Reminders - Summer 2019</h5>
                      <a href="assets/file/Important Dates and Reminders - Summer 2019.pdf" target="_blank">
                        <button class="btn btn-rose btn-fill">Open</button>
                      </a>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
    </div>
    <!-- end row -->
  </div>

  <script>
    $(document).ready(function () {
      showGradeBySubject();
      pagerNumResize();
      var termStatus = '<?=$_SESSION["termStatus"]?>';
      $('.currentTerm-status').html(' - ' + termStatus);
      minimizeSidebar();
    });
  </script>
