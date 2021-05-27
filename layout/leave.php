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
        <div class="page-title">Leave Request</div>
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
                <h4 class="card-title"><i class="material-icons-outlined card-icon">mail</i>Leave Request List</h4>
                <p class="card-category"><?php echo $_SESSION['SemesterName'].' - '.$_SESSION['termStatus']?></p>
              </div>
              <div class="col-md-8 participation-buttons">
                <div class="row">
                  <div class="col-md-12">
                    <!-- <button class="btn btn-primary browseAct-btn" data-toggle="modal">Browse
                      School Activities</button> -->
                    <button class="btn btn-primary request-leave-btn" data-toggle="modal">Request</button>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <input type="hidden" class="sId" name="" value="<?=$_SESSION['studentId']?>">
          <div class="card-body">
            <table id="requestLeaveTable" class="table table-hover display nowrap" cellspacing="0" width="100%">
              <thead class="text-warning text-center">
                <tr>
                  <th class="">Leave Type</th>
                  <th class="">Start Date</th>
                  <th>End Date</th>
                  <th class="">Reason</th>
                  <th>Approver</th>
                  <th><span rel="tooltip" data-html="true" data-original-title="<div class='text-left font12'>
                      <!-- <label><i class='material-icons-outlined mg-r-10'>date_range</i>
                        Registered</label> -->
                        <div class='row'><div class='col-md-2 mg-tb-auto'><i class='material-icons-outlined mg-r-10'>hourglass_empty</i></div><div class='col-md-10 mg-tb-auto'><label>Pending Approval</label></div></div>
                        <div class='row'><div class='col-md-2 mg-tb-auto'><i class='material-icons-outlined mg-r-10'>done_outline</i></div><div class='col-md-10 mg-tb-auto'><label>Hours Approved</label></div></div>
                        <div class='row'><div class='col-md-2 mg-tb-auto'><i class='material-icons-outlined mg-r-10'>link_off</i></div><div class='col-md-10 mg-tb-auto'><label>Rejected</label></div></div>
                    </div>">Status</span></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <!-- end content-->
      </div>
      <!--  end card  -->
    </div>
    <!-- end col-md-12 -->
  </div>
  <!-- end row -->
<?php
  include_once('layout/leaveRequestModal.html');
  include_once('layout/leaveRequestDetailModal.html');
?>

<script>
  $(document).ready(function () {
    showStudentLeaveRequest();
    pagerNumResize();
    minimizeSidebar();
  });
  // there is a ajaxfunction call in modalControl.js file
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
