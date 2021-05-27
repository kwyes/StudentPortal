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
        <div class="page-title">Dashboard</div>
      </div>
    </div>
  </nav>
  <div class="content">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <div class="card-text">
              <h4 class="card-title"><i class="material-icons-outlined card-icon">date_range</i><span
                  class="dashboard-semesterName"></span></h4>
              <p class="card-category"><span class="dashboard-termstatus"></span></p>
            </div>
          </div>
          <div class="card-body">
            <div class="progress progress-striped active progress-custom">
              <div class="progress-bar">
                <span class="sr-only"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header ">
            <div class="card-text">
              <h4 class="card-title"><i class="material-icons-outlined card-icon">assignment_ind</i><span
                  class="dashboard-fullName"></span>
              </h4>
            </div>
          </div>
          <div class="card-body">
            <div class="text-center">
              <img draggable="false" class="dashboard-userPic student-img" src="" onerror="this.src='assets/img/student.png'" alt="">
            </div>
            <table class="table table-hover" id="student-inf">
              <tbody>
                <tr>
                  <td>Student ID</td>
                  <td class="dashboard-studentId"></td>
                </tr>
                <tr>
                  <td>PEN</td>
                  <td class="dashboard-pen"></td>
                </tr>
                <tr>
                  <td>Current Grade </td>
                  <td class="dashboard-grade"></td>
                </tr>
                <tr>
                  <td>Counsellor</td>
                  <td class="dashboard-Counsellor"></td>
                </tr>
                <tr>
                  <td>Living Location</td>
                  <td class="dashboard-location"></td>
                </tr>
                <tr>
                  <td>House</td>
                  <td class="dashboard-house"></td>
                </tr>
                <tr>
                  <td>Hall</td>
                  <td class="dashboard-hall"></td>
                </tr>
                <tr>
                  <td>Room No</td>
                  <td class="dashboard-room"></td>
                </tr>
                <tr>
                  <td>Youth Advisor 1</td>
                  <td class="dashboard-advisor1"></td>
                </tr>
                <tr>
                  <td>Youth Advisor 2</td>
                  <td class="dashboard-advisor2"></td>
                </tr>
                <tr>
                  <td>Mentor Teacher</td>
                  <td class="dashboard-mentor"></td>
                </tr>
                <tr>
                  <td>Number of Terms</td>
                  <td class="dashboard-terms"></td>
                </tr>
                <tr>
                  <td>Number of AEP Terms</td>
                  <td class="dashboard-aepTerms"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="card-footer ">
          </div>
        </div>
        <!-- <div class="card">
          <div class="card-header">
            <div class="card-text">
              <h4 class="card-title"><i class="nc-icon nc-calendar-60 card-icon"></i>Term Status</h4>
              <p class="card-category"><span class="dashboard-CurrentTerm"></span> - <span
                  class="dashboard-termstatus"></span></p>
            </div>
          </div>
          <div class="card-body">
            <table class="table table-hover" id="term-status">
              <thead class="text-warning text-center">
                <tr>
                  <th>Start Date</th>
                  <th>Mid Cutoff</th>
                  <th>End Date</th>
                </tr>
              </thead>
              <tbody class="text-center">
                <tr>
                  <td class="dashboard-startDate"></td>
                  <td class="dashboard-midCutoff"></td>
                  <td class="dashboard-endDate"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div> -->
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <div class="card-text">
              <h4 class="card-title"><i class="material-icons-outlined card-icon">class</i>My Courses & Grades</h4>
              <p class="card-category">
                <span class="dashboard-CurrentTerm"></span> - <span
                  class="dashboard-termstatus"></span>
                </p>
              <div class="form-check">
                <label class="form-check-label">
                  <input class="form-check-input checkbox-credit" type="checkbox" value="" checked>
                  Show all (including credits and non-credits)
                  <span class="form-check-sign">
                    <span class="check"></span>
                  </span>
                  <p class="discretionarymarks-info"></p>
                </label>
              </div>
            </div>
          </div>
          <div class="card-body scroll">
            <div class="table-responsive">
              <table id="dashboard-mycourses" class="table table-hover" width="100%">
                <thead class="text-warning text-center">
                  <tr>
                    <th>Course</th>
                    <th>Percent Grade</th>
                    <th>Letter Grade</th>
                    <th>Credit</th>
                    <th>Teacher</th>
                    <th>Room</th>
                    <th>Late</th>
                    <th>Absence</th>
                  </tr>
                </thead>
                <tbody class="text-center">

                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <div class="card-text">
              <div class="row">
                <div class="col-md-6">
                  <h4 class="card-title"><i class="material-icons-outlined card-icon">accessibility_new</i>My
                    Participation
                    Hours</h4>
                  <p class="card-category"><span class="dashboard-CurrentTerm"></span> - <span
                      class="dashboard-termstatus"></span></p>
                </div>
                <div class="col-md-6 text-right">
                  <button class="btn btn-primary submitHour-btn" data-toggle="modal">Self-Submit Hours</button>
                </div>
              </div>

            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="dashboard-myparticipation" width="100%">
                <thead class="text-warning text-center">
                  <tr>
                    <th></th>
                    <th>Category</th>
                    <th>Current Term</br>(<span class="dashboard-CurrentTerm"></span>)</th>
                    <th>Accumulated</br>(Since <span class="dashboard-EnrollmetTermName"></span>)</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <tr>
                    <td><i class="material-icons-outlined">directions_bike</i></td>
                    <td class="text-left">Physical, Outdoor & Recreation Education</td>
                    <td class="dashboard-PORE-C"></td>
                    <td class="dashboard-PORE-A"></td>
                  </tr>
                  <tr>
                    <td><i class="material-icons-outlined">school</i></td>
                    <td class="text-left">Academic, Interest & Skill Development</td>
                    <td class="dashboard-AISD-C"></td>
                    <td class="dashboard-AISD-A"></td>
                  </tr>
                  <tr>
                    <td><i class="material-icons-outlined">language</i></td>
                    <td class="text-left">Citizenship, Interaction & Leadership Experience</td>
                    <td class="dashboard-CILE-C"></td>
                    <td class="dashboard-CILE-A"></td>
                  </tr>
                  <tr>
                    <td><i class="material-icons-outlined">palette</i></td>
                    <td class="text-left">Arts, Culture & Local Exploration</td>
                    <td class="dashboard-ACLE-C"></td>
                    <td class="dashboard-ACLE-A"></td>
                  </tr>
                  <tr class="font-weight-bold">
                    <td></td>
                    <td class="text-left">Total Participation Hours</td>
                    <td class="dashboard-TOTAL-C"></td>
                    <td class="dashboard-TOTAL-A"></td>
                  </tr>
                  <tr class="font-weight-bold">
                    <td></td>
                    <td class="text-left">Total Volunteer & Work Experience</td>
                    <td class="dashboard-VLWE-C"></td>
                    <td class="dashboard-VLWE-A"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card" id="display-career-card">
          <div class="card-header">
            <div class="card-text">
              <div class="row">
                <div class="col-md-6">
                  <h4 class="card-title"><i class="material-icons-outlined card-icon">flag</i>Career Life Pathway</h4>
                  <p class="card-category"><span class="dashboard-CurrentTerm"></span> - <span
                      class="dashboard-termstatus"></span></p>
                  <input type="hidden" id="hidden-courseId">
                </div>
                <div class="col-md-6 text-right">
                  <button class="btn btn-primary submitCapstone-btn" data-toggle="modal">Submit Capstone</button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="dashboard-Career" width="100%">
                <thead class="text-warning text-center">
                  <tr>
                    <th>COURSE</th>
                    <th>TEACHER</th>
                    <th>CAPSTONE TOPIC</th>
                    <th>CAREER GUIDE</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  <tr>
                    <td>CLE<br />
                      <span class="span-CLE-coursename" style="font-size: 10px">Career Life Education</span>
                    </td>
                    <td class="dashboard-CLE-teacher italic-grey">Not Submitted</td>
                    <td class="dashboard-CLE-topic italic-grey">Not Submitted</td>
                    <td class="dashboard-CLE-guide italic-grey">Not Submitted</td>
                    <td class="dashboard-CLE-date italic-grey">Not Submitted</td>
                    <td class="dashboard-CLE-status italic-grey">Not Submitted</td>
                  </tr>
                  <tr>
                    <td>CLC/Capstone<br /><span class="span-CLC-coursename" style="font-size: 10px">Career Life
                        Connections</span></td>
                    <td class="dashboard-CLC-teacher italic-grey">Not Submitted</td>
                    <td class="dashboard-CLC-topic italic-grey">Not Submitted</td>
                    <td class="dashboard-CLC-guide italic-grey">Not Submitted</td>
                    <td class="dashboard-CLC-date italic-grey">Not Submitted</td>
                    <td class="dashboard-CLC-status italic-grey">Not Submitted</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <script src="js/modalControl.js?ver=0.2" charset="utf-8"></script>
  <?php
    include_once('careerLifePathWaySelfSubmitModal.html');
    include_once('selfSubmitModal.html');
  ?>


  <script type="text/javascript">
    $(document).ready(function () {
      isMinimizeSidebar = false;
      ajaxtoCurrentTerm();
      minimizeSidebar();
    });
  </script>
