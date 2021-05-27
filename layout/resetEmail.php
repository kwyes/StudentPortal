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
        <div class="page-title">Reset School Email Password</div>
      </div>
    </div>
  </nav>
  <div class="content">
    <p>Type your personal email (e.g. XXX@gmail.com, XXX@yahoo.com) and date of birth to reset your school email password.</p>
    <p>We will send email to that email address.</p>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <form id="form-resetEmail" class="needs-validation" novalidate>
              <div class="form-group">
                <input type="email" class="form-control personalEmail" name='pEmail' placeholder="Personal Email" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
              </div>
              <div class="form-group">
                <input type="text" onkeydown="return false" class="form-control datepicker dob" name='dob' placeholder="Date of Birth" data-date-format="YYYY-MM-DD" required>
                <div class="invalid-feedback">This field is required.</div>
              </div>
              <div class="width-100per text-center">
                <button type="button" class="btn btn-success" id="resetEmailBtn">Send Request</button>
              </div>
            </form>
          </div>
        </div>
    </div>
  </div>
  <?php
    include_once('careerLifePathWaySelfSubmitModal.html');
    include_once('selfSubmitModal.html');
  ?>


  <script type="text/javascript">
    $(document).ready(function () {
      isMinimizeSidebar = false;
      minimizeSidebar();
    });

  </script>
