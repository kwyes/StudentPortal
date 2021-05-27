<div class="main-panel">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <div class="navbar-toggle">
          <button type="button" class="navbar-toggler">
            <span class="navbar-toggler-bar bar1"></span>
            <span class="navbar-toggler-bar bar2"></span>
            <span class="navbar-toggler-bar bar3"></span>
          </button>
        </div>
        <div class="page-title">Past Terms</div>
      </div>
    </div>
  </nav>
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><i class="material-icons-outlined card-icon">class</i>My Gradebook</h4>
            <div class="card-category">
              <p class="mg-b-0"><?php echo $_SESSION['SemesterName'].' - '.$_SESSION['termStatus']?></p>
              <!-- <p class="mg-b-0"><span class="gradebook-currentterm"></span><span class="currentTerm-status"></span></p> -->
              <p><span class="gradebook-classinfo"></span></p>
            </div>
          </div>
          <div class="card-body">
            <!-- <div class="table-responsive"> -->

            <!-- </div> -->
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>
      <!-- end col-md-12 -->

      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><i class="material-icons-outlined card-icon">accessibility_new</i>Participation Hours</h4>
            <div class="card-category">
              <p class="mg-b-0"><?php echo $_SESSION['SemesterName'].' - '.$_SESSION['termStatus']?></p>
              <!-- <p class="mg-b-0"><span class="gradebook-currentterm"></span><span class="currentTerm-status"></span></p> -->
              <p><span class="gradebook-classinfo"></span></p>
            </div>
          </div>
          <div class="card-body">
            <!-- <div class="table-responsive"> -->

            <!-- </div> -->
          </div>
          <!-- end content-->
        </div>
        <!--  end card  -->
      </div>


    </div>
    <!-- end row -->
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
<script>
  $(document).ready(function () {
    showGradeBySubject();
    pagerNumResize();
    var termStatus = '<?=$_SESSION["termStatus"]?>';
    $('.currentTerm-status').html(' - ' + termStatus);
  });
</script>
