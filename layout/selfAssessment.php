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
        <div class="page-title">Self Assessment</div>
      </div>
    </div>
  </nav>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><i class="material-icons-outlined card-icon">class</i>Self Assessment</h4>
            <div class="card-category">
              <p class="mg-b-0"><?php echo $_SESSION['SemesterName'].' - '.$_SESSION['termStatus']?></p>
              <!-- <p class="mg-b-0"><span class="gradebook-currentterm"></span><span class="currentTerm-status"></span></span> -->
              <span>
            </div>
          </div>
          <div class="card-body">
              <form id="form-assessment">
                <input type="hidden" name="AssessmentFormID" id="AssessmentFormID" value="">
                <input type="hidden" name="AssessmentID" id="AssessmentID" value="">
                <div id="tableAssessmentFormDIV" class="table-responsive">



                </div>
              </form>
          </div>
          <div class="card-footer btn-grp-assessment">
            <!-- <button id="save-assessment-grade10" class="btn btn-primary" type="button" name="button">Save</button> -->
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
    displaySelfAssessmentForm(<?=$_SESSION['GradeNum']?>);
    minimizeSidebar();
    var assessmentFormID = $('#AssessmentFormID').val();
    if(assessmentFormID) {
      getAssessment();
    }
    $("#save-assessment-grade10").click(function (event) {
      $(this).attr('disabled','disabled');
      saveAssessmentGrade10();
    });

    $("#save-assessment-grade8").click(function (event) {
      $(this).attr('disabled','disabled');
      saveAssessmentGrade8();
    });

  });
</script>
