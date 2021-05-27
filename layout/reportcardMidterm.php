<?php
  $colspan = 6;
  // $_SESSION['studentId'] = 201900145;
  // $_SESSION['SemesterID'] = 76;
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
        <div class="page-title">Report Card Midterm</div>
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
            <table width="720" border="0" cellspacing="0" cellpadding="0" style="font-size:10pt;font-family: Arial, Helvetica, sans-serif;">
              <tr>
                <td width="180">
                  <img src="https://admin.bodwell.edu/bhs/studentimages/bhs<?=$_SESSION['studentId']?>.jpg" width=100 height=150 alt="" border="0"></td>
                <td align="center" valign="top" width="340"><b>
                    <div style="font-size:11pt;">
                      Bodwell High School and Bodwell Academy
                    </div>
                    <br>
                    <i>Strength In Diversity</i>
                  </b><br><br>
                  955 Harbourside Drive<br>
                  North Vancouver, BC V7P 3S4<br>
                  Tel: (604)998-1000 Fax: (604)924-5058<br>
                  http://www.bodwell.edu<br>
                  email: office@bodwell.edu<br>
                </td>
                <td align="center" width="180">
                  <img src="https://admin.bodwell.edu/bhs/images/bhslogo2.jpg" width=110 height=116 alt="" border="0">
                </td>
              </tr>
              <tr>
                <td colspan="2"><b><?php echo $_SESSION['studentLastName'].', '.$_SESSION['studentFirstName']; ?></b>
                  <br>
                  <table border="0" cellspacing="0" cellpadding="0" style="font-size:10pt;font-family: Arial, Helvetica, sans-serif;">
                    <tr>
                      <td>Student #:</td>
                      <td><?=$_SESSION['studentId']?></td>
                    </tr>
                    <tr>
                      <td align="right">PEN #:</td>
                      <td>
                        <?=$_SESSION['PEN']?>
                      </td>
                    </tr>
                  </table>
                </td>
                <td align="center" valign="top">Principal: Mr. Stephen Goobie</td>
              </tr>
              <tr>
                <td colspan="3">
                  <hr size="0.1" width="100%" color="Black">
                </td>
              </tr>
            </table>

            <table width="720" border="0" cellspacing="0" cellpadding="0" style="font-size:10pt;font-family: Arial, Helvetica, sans-serif;">
              <!-- <cfif left(attended, 3) is "AEP">
                <cfset attended=replace(attended, "AEP" , "Academic & English Preparation (AEP)" )>
              </cfif> -->
              <tr>
                <td>Program Attended: <?=str_replace("AEP","Academic & English Preparation (AEP)",$_SESSION['CurrentGrade'])?></td>
                <td align="right">
                  <span class="termInfo">

                  </span>
                </td>
              </tr>
              <tr>
                <td>Mentor Teacher: <?=$_SESSION['Mentor']?></td>
                <td align="right"><b>
                    Mid-term Report Card
                  </b></td>
              </tr>
            </table>


              <table width="720" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt;font-family: Arial, Helvetica, sans-serif;">
                <tr>
                  <td colspan="<?=$colspan?>">
                    <hr size="0.1" width="100%" color="Black">
                  </td>
                </tr>
              </table>

                <div class="aepBasicInfo_1">

                </div>
                <div class="sateBasicInfo_1">

                </div>
                <table class="courseTbl" width="720" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt;font-family: Arial, Helvetica, sans-serif;">
                  <tbody>
                    <tr align="center" style="font-weight:bold;">
                      <td align="left" width="250">Course Name</td>
                      <td width="220">&nbsp;</td>
                      <td>Total</td>
                      <td>Total</td>
                      <td>Mid-term</td>
                      <td>Letter</td>
                    </tr>
                    <tr align="center" style="font-weight:bold;">
                      <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Teacher's Name</td>
                      <td>&nbsp;</td>
                      <td>Absences</td>
                      <td>Lates</td>
                      <td>Mark</td>
                      <td>Grade</td>
                    </tr>
                    <tr>
                      <td colspan="<?=$colspan?>">
                        <hr size="0.1" width="100%" color="Black">
                      </td>
                    </tr>

                  </tbody>

                </table>


                <div class="overallDiv">

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
      // toggleSpinner();
      chkReportCardEligible('Tuition', 0);
      GetReportCard('Mid',"<?=$colspan?>", "<?=$_POST['sId']?>");
      pagerNumResize();
      minimizeSidebar();





    });
  </script>
  <style media="screen">
    table th, table td {
      padding:0 !important;
    }
  </style>
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
