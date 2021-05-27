<?php
  $page = $_GET['page'];
 ?>
<div class="sidebar" data-color="blue" data-active-color="warning">
  <div class="logo text-center">
    <img src="assets/img/logo_small.png" alt="logo_small" class="logo-small" />
    <div class="bst-title">Bodwell Student Portal</div>
  </div>
  <div class="sidebar-wrapper">
    <div class="user">
      <div class="photo">
        <img src="assets/img/logo_xs.png" alt="logo_xs" />
      </div>
      <div class="info">
        <a data-toggle="collapse" href="#collapseExample" class="collapsed">
          <span class='bst-title-mini'>
            Student Portal
          </span>
        </a>
        <div class="clearfix"></div>

      </div>
    </div>
    <ul class="nav">
      <li class="<?php echo ($page=='dashboard' || $page == '')?"active":"";?>">
        <a href="?page=dashboard">
          <i class="material-icons-outlined">dashboard</i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="<?php echo ($page=='gradebook')?"active":"";?>">
        <a href="?page=gradebook">
          <i class="material-icons-outlined">class</i>
          <p>
            My gradebook
          </p>
        </a>

      </li>

      <?php if($_SESSION['AEP'] == 'Y') {?>
      <li class="<?php echo ($page=='aep')?"active":"";?>">
        <a href="?page=aep">
          <i class="material-icons-outlined">class</i>
          <p>
            AEP
          </p>
        </a>
      </li>
    <?php } ?>

    <li class="<?php echo ($page=='assessments')?"active":"";?>">
      <a href="?page=assessments">
        <i class="material-icons-outlined">assessment</i>
        <p>
          Assessments
        </p>
      </a>
    </li>

    <li class="<?php echo ($page=='selfAssessment')?"active":"";?>">
      <a href="?page=selfAssessment">
        <i class="material-icons-outlined">assignment</i>
        <p>
          Self Assessment
        </p>
      </a>
    </li>

      <li class="<?php echo ($page=='participation')?"active":"";?>">
        <a href="?page=participation">
          <i class="material-icons-outlined">accessibility_new</i>
          <p>
            My Participation
          </p>
        </a>
      </li>

      <li class="<?php echo ($page=='leave')?"active":"";?>">
        <a href="?page=leave">
          <i class="material-icons-outlined">mail</i>
          <p>
            Leave Request
          </p>
        </a>
      </li>

      <li class="<?php echo ($page=='reportcard')?"active":"";?>">
        <a href="?page=reportcard">
          <i class="material-icons-outlined">credit_card</i>
          <p>
            Report Card
          </p>
        </a>
      </li>

      <li class="<?php echo ($page=='transcriptRequest')?"active":"";?>">
        <a href="?page=transcriptRequest">
          <i class="material-icons-outlined">note_add</i>
          <p>
            Transcript Request
          </p>
        </a>
      </li>

      <li class="<?php echo ($page=='calendar')?"active":"";?>">
        <a href="https://bodwell.edu/calendar/" target="_blank">
          <i class="material-icons-outlined">alarm</i>
          <p>
            School Calendar
          </p>
        </a>
      </li>
      <li class="<?php echo ($page=='documents')?"active":"";?>">
        <a href="?page=documents">
          <i class="material-icons-outlined">description</i>
          <p>
            Documents
          </p>
        </a>
      </li>
      <!-- <li class="<?php echo ($page=='resetEmail')?"active":"";?>">
        <a href="?page=resetEmail">
          <i class="material-icons-outlined">email</i>
          <p class="textWrap">
          Reset Email Password
          </p>
        </a>
      </li> -->
      <!-- <li class="<?php echo ($page=='past')?"active":"";?>">
        <a href="?page=past">
          <i class="material-icons-outlined">timeline</i>
          <p>
            Past Term
          </p>
        </a>
      </li> -->
      <li>
        <a href="?page=logout">
          <i class="material-icons-outlined">power_settings_new</i>
          <p>
            Logout
          </p>
        </a>
      </li>
    </ul>
  </div>
</div>
<script>
  $(document).ready(function () {
    if ($('body').hasClass('sidebar-mini')) {
      console.log("mini")
    }
  });
</script>
