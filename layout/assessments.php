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
        <div class="page-title">Assessments</div>
      </div>
    </div>
  </nav>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><i class="material-icons-outlined card-icon">assessment</i>English Progress Assessment (EPA)</h4>
            <div class="card-category">
              <p class="mg-b-0"><?php echo $_SESSION['SemesterName'].' - '.$_SESSION['termStatus']?></p>
            </div>
          </div>
          <div class="card-body">
            <div class="row row-eq-height">
              <div class="col-md-6">
                <select class="semester-assessments-list width-auto custom-select" name="" >

                </select>
              </div>
              <div class="col-md-6 text-right">
                <span class="assessments-info mg-lr-20 font-bold color-grey"></span>
              </div>
            </div>
            <table id="datatable-assessments" class="table table-hover display nowrap" cellspacing="0" width="100%">
              <thead class="text-warning text-center">
                <tr>
                  <th></th>
                  <th>Listening Band Score</th>
                  <th>Reading Band Score</th>
                  <th>Writing Band Score</th>
                  <th>Writing Task 1</th>
                  <th>Writing Task 2</th>
                  <th>Overall Average</th>
                </tr>
              </thead>
              <tbody class="text-center">

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

  <script>
    $(document).ready(function () {
      ajaxtoSemesterListForAssessments();
      $('.semester-assessments-list option:contains("<?=$_SESSION['SemesterName']?>")').prop('selected', true);
      ajaxtoGetAssessmentsScore();
      generateAssessTable(globalAssessments, $('.semester-assessments-list').val());
      $('.semester-assessments-list').on('change', function () {
        generateAssessTable(globalAssessments, this.value);
      });

    });
  </script>
