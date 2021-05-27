<?php
if($_SESSION['reportTerm'] == 'mid'){
  $colspan = 6;
} else {
  $colspan = 7;
}

 ?>
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
        <div class="page-title">Report Card</div>
      </div>
    </div>
  </nav>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-4">
                <h4 class="card-title"><i class="material-icons-outlined card-icon">accessibility_new</i>Report Card</h4>
                <p class="card-category"><?php echo $_SESSION['SemesterName'].' - '.$_SESSION['termStatus']?></p>
              </div>
            </div>
          </div>

          <div class="card-body">
            <select id="reportcardTerm" class="form-control" style="width:10%">
            </select>
            <div class="btn-group">
                <button type="button" class="btn btn-secondary" onclick="redirectToReportCard('M')">MidTerm</button>
                <button type="button" class="btn btn-secondary" onclick="redirectToReportCard('F')">Final</button>
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


  <script>

    $(document).ready(function () {
      GetReportCardSemester();
      pagerNumResize();
      minimizeSidebar();
    });
  </script>
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
