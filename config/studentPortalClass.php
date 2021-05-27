<?php

session_start();

require_once 'sql.php';
require_once 'sendEmailClass.php';
require_once 'image.php';

class studentPortalClass extends DBController {
    function getSql($name) {
        global $_SQL;
        return $_SQL[$name];
    }
    public function login($userID, $userPW, $chk, $staffId){
      $sql = $this->getSql('student-login');
      $sql = str_replace('{LoginID}', $userID, $sql);
      // print_r($sql);
      $stmt = $this->db->prepare($sql);
      // $stmt->bindParam(1, $userID);
      if ($stmt->execute()) {
          $response = array();
          while ($row = $stmt->fetch()) {
            $tmp = array();
            if($userPW == $row["password"]){
              $tmp["status"] = "0";
              $tmp["schoolEmail"] = $row["schoolEmail"];
              $tmp["studentId"] = $row["studentId"];
              $_SESSION['studentId'] = $row["studentId"];
              $_SESSION['studentLastName'] = $row["LastName"];
              $_SESSION['studentFirstName'] = $row["FirstName"];
              $_SESSION['studentEngName'] = $row["EnglishName"];
              $_SESSION['CurrentGrade'] = $row['CurrentGrade'];
              $_SESSION['PEN'] = $row['PEN'];
              $_SESSION['Mentor'] = $row['Mentor'];
              $gradenum = strtok($row['CurrentGrade'], '(');
              $int = (int) filter_var($gradenum, FILTER_SANITIZE_NUMBER_INT);
              $_SESSION['GradeNum'] = $int;

              $str = strstr($row['CurrentGrade'], "AEP");
              if($str == false){
                $_SESSION['AEP'] = 'N';
              } else {
                $_SESSION['AEP'] = 'Y';
              }
              $c = new studentPortalClass();
              $authlog = $c->insertUserAuthLog($userID, $row["studentId"], 'current_student', 'student_web', $chk, $staffId);
            } else {
              $tmp["status"] = "1";
            }
            array_push($response, $tmp);
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();

    }

    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function insertUserAuthLog($username, $studentid, $usercategory, $appsystem, $chk, $staffId){
        $ip = $this->getUserIpAddr();
        $date = date("Y-m-d H:i:s");
        $query = $this->getSql('insert-user-auth-log');
        $query = str_replace('{Username}', $username, $query);
        $query = str_replace('{StudentID}', $studentid, $query);
        $query = str_replace('{UserCategory}', $usercategory, $query);
        $query = str_replace('{AppSystem}', $appsystem, $query);
        $query = str_replace('{UserIPAddress}', $ip, $query);
        $query = str_replace('{InternalStaff}', $chk, $query);
        $query = str_replace('{StaffID}', $staffId, $query);
        $query = str_replace('{CreateDate}', $date, $query);
        $stmt = $this->db->prepare($query);

        if ($stmt->execute()) {
            $response = array();
            $tmp = array();
            $tmp['query'] = $query;
            array_push($response, $tmp);
           return $response;
        } else {
            return NULL;
        }
    }

    public function studentInfo(){
      $query = $this->getSql('student-info');
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(1, $_SESSION['studentId']);
      $stmt->bindParam(2, $_SESSION['SemesterID']);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $tmp = array();
            $response[] = $row;
            $response[0]['imgsrc'] = image_view1('https://asset.bodwell.edu/CvRwzmMEN7/student/bhs'.$_SESSION['studentId'].'.jpg');
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();

    }

    public function currentTerm(){
      $query = $this->getSql('find-currentsemester');
      $stmt = $this->db->prepare($query);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $tmp = array();
            $response[] = $row;
            $_SESSION['SemesterID'] = $row['SemesterID'];
            $startDate = $row['StartDate'];
            $_SESSION['StartDate'] = $startDate;
            $_SESSION['NextStartDate'] = $row['NextStartDate'];
            $midCutoffDate = $row['MidCutOffDate'];
            $endDate = $row['EndDate'];
            $today = date('Y-m-d');
            if ($today < $startDate){
              $txt = "Term Not Started";
            } elseif ($today >= $startDate && $today < $midCutoffDate) {
              $txt = "First half of term in progress";
            } elseif ($today >= $midCutoffDate && $today <= $endDate) {
              $txt = "Second half of term in progress";
            } else {
              $txt = "End of Term";
            }
            $response['text'] = $txt;
            $_SESSION['SemesterName'] = $row['SemesterName'];
            $_SESSION['termStatus'] = $txt;


          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function EnrollTerm($EnrollmentDate){
      $query = $this->getSql('find-Enrollmentsemester');
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(1, $EnrollmentDate);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $tmp = array();
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function NumberOfTerm(){
      $query = $this->getSql('num-terms-by-id');
      $stmt = $this->db->prepare($query);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $tmp = array();
            $response[] = $row;
            $_SESSION['SemesterID'] = $row['SemesterID'];
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();

    }

    public function GetMyGrades(){
        // $query = $this->getSql('course-grade-list');
        $studentid = $_SESSION['studentId'];
        $SemesterID = $_SESSION['SemesterID'];
        $sql = $this->getSql('course-grade-list');
        $sql = str_replace('{termId}', $SemesterID, $sql);
        $sql = str_replace('{studentId}', $studentid, $sql);

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
              $tmp = array();
              $response[] = $row;
            }
           return $response;
        } else {
            return NULL;
        }
        $stmt->close();
    }


    public function GetMyCourses(){
      $query = $this->getSql('course-list');
      $stmt = $this->db->prepare($query);
      $studentid = $_SESSION['studentId'];
      $SemesterID = $_SESSION['SemesterID'];
      $stmt->bindParam(1, $SemesterID);
      $stmt->bindParam(2, $studentid);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response['courses'][] = $row;
            // $response['studentCourseId'][] = $row['studentCourseId'];
            $studentCourseId[] = $row['studentCourseId'];
          }
          $c = new studentPortalClass();
          $absenselateResponse = $c->GetMyAbsenseLate($studentCourseId);
          if($absenselateResponse){
            $response_final = array_merge($response,$absenselateResponse);
          } else {
            $response_final = $response;
          }

         return $response_final;
      } else {
          return NULL;
      }
      $stmt->close();

    }

    public function GetMyAbsenseLate($studentCourseId){

      if($studentCourseId) {
          $courseList = array();
          foreach($studentCourseId as $courseId) {
              $courseList[] = "'{$courseId}'";
          }
          $query = $this->getSql('absent-list-by-courselist');
          $query = str_replace('{studentCourseList}', implode(',', $courseList), $query);
          $stmt = $this->db->prepare($query);
          if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
              $response['Absense'][] = $row;
            }
             return $response;
          } else {
              return NULL;
          }



          $stmt->close();

      } else {
          return array();
      }
    }

    public function GetPaticipationHours(){
      $query = $this->getSql('student-activity-list-v2');
      $stmt = $this->db->prepare($query);
      $studentid = $_SESSION['studentId'];
      $stmt->bindParam(1, $studentid);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = array_map('utf8_encode',$row);
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetGradeBySubject(){
      $query = $this->getSql('grade-list-by-student');
      $stmt = $this->db->prepare($query);
      $studentid = $_SESSION['studentId'];
      $SemesterID = $_SESSION['SemesterID'];
      $stmt->bindParam(1, $SemesterID);
      $stmt->bindParam(2, $studentid);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response['grade'][] = $row;
            $courseIdArr[] = $row['courseId'];
          }
          $c = new studentPortalClass();
          $courseAvg = $c->GetItemAverage($courseIdArr);
          $response_final = array_merge($response,$courseAvg);
         return $response_final;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetItemAverage($courses){
      if($courses) {
          $courseList = array();
          foreach($courses as $courseId) {
              $courseList[] = "'{$courseId}'";
          }
          $query = $this->getSql('item-average-list');
          $query = str_replace('{subjectid}', implode(',', $courseList), $query);
          $stmt = $this->db->prepare($query);

          if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
              $response['Avg'][] = $row;
            }
             return $response;
          } else {
              return NULL;
          }
          $stmt->close();

      } else {
          return array();
      }
    }

    public function GetMyParticipation(){
      $query = $this->getSql('student-activity-list-v4');
      $stmt = $this->db->prepare($query);
      $studentid = $_SESSION['studentId'];
      $SemesterID = $_SESSION['SemesterID'];
      // $StartDate = $_SESSION['StartDate'];
      $StartDate = '2018-01-02';
      $stmt->bindValue(1, $studentid);
      $stmt->bindValue(2, $StartDate);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = array_map('utf8_encode',$row);
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function AddActivityRecords($activityName,$activityCategory,$activityLocation,$activitySDate,$activityEDate,$activityHours,$activityVLWE,$activityApprover,$activityApproverFullName,$activitySupervisor,$activityComment1,$activityComment2,$activityComment3){
      $query = $this->getSql('insert-activity-record');
      $stmt = $this->db->prepare($query);
      // $SemesterID = $_SESSION['SemesterID'];

      $ActivityStatus = 60;
      $studentid = $_SESSION['studentId'];
      $ActivityID = 1000000;
      $ProgramSource = 'SELF';
      $AllDay = 0;
      $DPA = 0;
      $activitySDate = date("Y-m-d", strtotime($activitySDate));
      $activityEDate = date("Y-m-d", strtotime($activityEDate));
      $studentLastName = $_SESSION['studentLastName'];
      $studentFirstName = $_SESSION['studentFirstName'];
      if($_SESSION['studentEngName']){
        $studentEngName = " (".$_SESSION['studentEngName'].") ";
      } else {
        $studentEngName = " ";
      }

      $SemesterID = $this->getSemesterIDFromSDate($activitySDate);

      $c = new studentPortalClass();
      $numrow = $c-> NumRowOfSelfSubmit($SemesterID, $studentid, $ProgramSource, $activityName, $activityLocation, $activitySDate, $activityHours, $activityApprover);

      if($numrow !== '0') {
        $response2 = array();
          $tmp2 = array();
          $tmp2['result'] = 3;
          $tmp2['numrow'] = $numrow;
          array_push($response2, $tmp2);
        return $response2;
        // exit;
      }

      $stmt->bindValue(":SemesterId", $SemesterID);
      $stmt->bindValue(":ActivityStatus", $ActivityStatus);
      $stmt->bindValue(":studentid", $studentid);
      $stmt->bindValue(":ActivityID", $ActivityID);
      $stmt->bindValue(":ProgramSource", $ProgramSource);
      $stmt->bindValue(":ActivityCategory", $activityCategory);
      $stmt->bindValue(":Title", $activityName);
      $stmt->bindValue(":Location", $activityLocation);
      $stmt->bindValue(":SDate", $activitySDate);
      $stmt->bindValue(":EDate", $activityEDate);
      $stmt->bindValue(":Hours", $activityHours);
      $stmt->bindValue(":AllDay", $AllDay);
      $stmt->bindValue(":DPA", $DPA);
      $stmt->bindValue(":VLWE", $activityVLWE);
      $stmt->bindValue(":ApproverStaffID", $activityApprover);
      $stmt->bindValue(":CreateUserID", $studentid);
      $stmt->bindValue(":ModifyUserID", $studentid);
      // $stmt->bindValue(":Witness", $activityWit);
      $stmt->bindValue(":VLWESupervisor", $activitySupervisor);
      $stmt->bindValue(":SELFComment1", $activityComment1);
      $stmt->bindValue(":SELFComment2", $activityComment2);
      $stmt->bindValue(":SELFComment3", $activityComment3);
      if($activityVLWE == 1){
        $VLWETxt = 'YES';
      } else {
        $VLWETxt = 'NO';
      }

      $body = <<<BODY
          <style media="screen">
            .table-mail{
              border-collapse: collapse;
              border:none;
              width:900px;
            }
            th, td {
              border-bottom: 1px solid #d2d2d2;
            }
            .table-mail tr td {
              padding:10px;
              font-size:14px;
            }
            .title{
              color:#30297E;
            }
            .tableTitle{
              font-weight: bold;
              color: #66615b;
              width: 300px;
            }
            .linkContainer{
              margin-top:10px;
            }
          </style>

          <div>
            <h3 class="title">
              Student Portal System Notification<br />
            </h3>
            <br />
              Student self-submitted hours pending your approval.<br />
              <br />
              <br />
              <table class="table-mail" >
                <tr >
                  <td class="tableTitle">Student Name</td>
                  <td colspan="3" >{$studentFirstName}{$studentEngName}{$studentLastName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Activity Name</td>
                  <td colspan="3" >{$activityName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Location</td>
                  <td colspan="3" >{$activityLocation}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Date & Hours</td>
                  <td >{$activitySDate} <span>({$activityHours} Hrs)</span></td>
                  <td ></td>
                  <td ></td>
                </tr>
                <tr>
                  <td class="tableTitle">Approval Status</td>
                  <td colspan="3" style="color:#F44336">Pending Approval</td>
                </tr>
                <tr>
                  <td class="tableTitle">Approver</td>
                  <td colspan="3" >{$activityApproverFullName}</td>
                </tr>
              </table>
          </div>
          <div class="linkContainer">
            Please sign-in to your SP Admin to approve hours.
          </div>
BODY;


      if ($stmt->execute()) {

        $emailClass = new studentPortalClass();
        $emailresult = $emailClass->sendEmailFromAjax($activityApprover, 'self', $body);
          $response = array();
            $tmp = array();
            $tmp['result'] = 1;
            $tmp['emailresult'] = $emailresult;
            array_push($response, $tmp);
         return $response;
      } else {
          return NULL;
      }
    }

    public function NumRowOfSelfSubmit($SemesterID, $studentid, $ProgramSource, $activityName, $activityLocation, $activitySDate, $activityHours, $activityApprover) {
      $query = $this->getSql('num-row-self-submit');
      $stmt = $this->db->prepare($query);
      $date = date('Y-m-d');
      $stmt->bindValue(":SemesterId", $SemesterID);
      $stmt->bindValue(":studentid", $studentid);
      $stmt->bindValue(":ProgramSource", $ProgramSource);
      $stmt->bindValue(":Title", $activityName);
      $stmt->bindValue(":Location", $activityLocation);
      $stmt->bindValue(":SDate", $activitySDate);
      $stmt->bindValue(":Hours", $activityHours);
      $stmt->bindValue(":ApproverStaffID", $activityApprover);
      $stmt->bindValue(":CreateDate", $date);

      if ($stmt->execute()) {
        $row = $stmt->fetch();
        return $row['num'];
      } else {
          return NULL;
      }

    }

    public function UpdateActivityRecords($studentActivityId,$activityName,$activityCategory,$activityLocation,$activitySDate,$activityEDate,$activityHours,$activityVLWE,$activityApprover,$activityApproverFullName,$activitySupervisor,$activityComment1,$activityComment2,$activityComment3){
      $query = $this->getSql('update-activity-record');
      $stmt = $this->db->prepare($query);
      $studentid = $_SESSION['studentId'];
      $today = date("Y-m-d H:i:s");

      $activitySDate = date("Y-m-d", strtotime($activitySDate));
      $activityEDate = date("Y-m-d", strtotime($activityEDate));
      $studentLastName = $_SESSION['studentLastName'];
      $studentFirstName = $_SESSION['studentFirstName'];
      if($_SESSION['studentEngName']){
        $studentEngName = " (".$_SESSION['studentEngName'].") ";
      } else {
        $studentEngName = " ";
      }
      $stmt->bindValue(":StudentActivityID", $studentActivityId);
      $stmt->bindValue(":ActivityCategory", $activityCategory);
      $stmt->bindValue(":Title", $activityName);
      $stmt->bindValue(":Location", $activityLocation);
      $stmt->bindValue(":SDate", $activitySDate);
      $stmt->bindValue(":EDate", $activityEDate);
      $stmt->bindValue(":Hours", $activityHours);
      $stmt->bindValue(":VLWE", $activityVLWE);
      $stmt->bindValue(":ApproverStaffID", $activityApprover);
      $stmt->bindValue(":ModifyUserID", $studentid);
      $stmt->bindValue(":ModifyDate", $today);
      $stmt->bindValue(":SELFComment1", $activityComment1);
      $stmt->bindValue(":SELFComment2", $activityComment2);
      $stmt->bindValue(":SELFComment3", $activityComment3);
      if($activityVLWE == 1){
        $VLWETxt = 'YES';
      } else {
        $VLWETxt = 'NO';
      }

      $body = <<<BODY
          <style media="screen">
            .table-mail{
              border-collapse: collapse;
              border:none;
              width:900px;
            }
            th, td {
              border-bottom: 1px solid #d2d2d2;
            }
            .table-mail tr td {
              padding:10px;
              font-size:14px;
            }
            .title{
              color:#30297E;
            }
            .tableTitle{
              font-weight: bold;
              color: #66615b;
              width: 300px;
            }
            .linkContainer{
              margin-top:10px;
            }
          </style>

          <div>
            <h3 class="title">
              Student Portal System Notification<br />
            </h3>
            <br />
              Student self-submitted hours pending your approval.<br />
              <br />
              <br />
              <table class="table-mail" >
                <tr >
                  <td class="tableTitle">Student Name</td>
                  <td colspan="3" >{$studentFirstName}{$studentEngName}{$studentLastName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Activity Name</td>
                  <td colspan="3" >{$activityName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Location</td>
                  <td colspan="3" >{$activityLocation}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Date & Hours</td>
                  <td >{$activitySDate} <span>({$activityHours} Hrs)</span></td>
                  <td ></td>
                  <td ></td>
                </tr>
                <tr>
                  <td class="tableTitle">Approval Status</td>
                  <td colspan="3" style="color:#F44336">Pending Approval</td>
                </tr>
                <tr>
                  <td class="tableTitle">Approver</td>
                  <td colspan="3" >{$activityApproverFullName}</td>
                </tr>
              </table>
          </div>
          <div class="linkContainer">
            Please sign-in to your SP Admin to approve hours.
          </div>
BODY;

      if ($stmt->execute()) {

        // $emailClass = new studentPortalClass();
        // $emailresult = $emailClass->sendEmailFromAjax($activityApprover, 'self', $body);
          $response = array();
            $tmp = array();
            $tmp['result'] = 1;
            // $tmp['emailresult'] = $emailresult;
            array_push($response, $tmp);
         return $response;
      } else {
          return NULL;
      }
    }

    public function GetCategoryName($category) {
      $categoryName;
      if ($category == '10') {
        $categoryName = 'Physical, Outdoor & Recreation Education';
      } else if ($category == '11') {
        $categoryName = 'Academic, Interest & Skill Development';
      } else if ($category == '12') {
        $categoryName = 'Citizenship, Interaction & Leadership Experience';
      } else if ($category == '13') {
        $categoryName = 'Arts, Culture & Local Exploration';
      }  else {
        $categoryName = 'err';
      }
      return $categoryName;
    }

    public function GetApprovalList(){
      $query = $this->getSql('approval-list');
      $stmt = $this->db->prepare($query);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetAllSchoolActivity(){
      $query = $this->getSql('school-activity-list');
      $SemesterID = $_SESSION['SemesterID'];
      $query=str_replace('{termId}', $SemesterID,$query);
      $stmt = $this->db->prepare($query);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function AddActivityRecordsV2($ActivityID){
      $query = $this->getSql('insert-activity-record-v2');
      $c = new studentPortalClass();
      $ActivityDetail = $c->GetDetailOfActivity($ActivityID);
      $SemesterID = $_SESSION['SemesterID'];
      $ActivityStatus = 10;
      $studentid = $_SESSION['studentId'];
      $ProgramSource = 'BORD';
      $ActivityCategory = $ActivityDetail[0]['categoryCode'];
      $Title = $ActivityDetail[0]['title'];
      $Location = $ActivityDetail[0]['location'];
      $StartDate = $ActivityDetail[0]['startDate'];
      $EndDate = $ActivityDetail[0]['endDate'];
      $BaseHours = $ActivityDetail[0]['baseHours'];
      $AllDay = $ActivityDetail[0]['allDay'];
      $DPA = $ActivityDetail[0]['dpa'];
      $VLWE = $ActivityDetail[0]['VLWE'];
      $ApproverStaffID = $ActivityDetail[0]['staffId'];
      $CreateUserID = $studentid;
      $ModifyUserID = $studentid;

      $StartDate = date("Y-m-d H:i:s", strtotime($StartDate));
      $EndDate = date("Y-m-d H:i:s", strtotime($EndDate));

      $stmt = $this->db->prepare($query);


      $stmt->bindValue(":SemesterId", $SemesterID);
      $stmt->bindValue(":ActivityStatus", $ActivityStatus);
      $stmt->bindValue(":studentid", $studentid);
      $stmt->bindValue(":ActivityID", $ActivityID);
      $stmt->bindValue(":ProgramSource", $ProgramSource);
      $stmt->bindValue(":ActivityCategory", $ActivityCategory);
      $stmt->bindValue(":Title", $Title);
      $stmt->bindValue(":Location", $Location);
      $stmt->bindValue(":SDate", $StartDate);
      $stmt->bindValue(":EDate", $EndDate);
      $stmt->bindValue(":Hours", $BaseHours);
      $stmt->bindValue(":AllDay", $AllDay);
      $stmt->bindValue(":DPA", $DPA);
      $stmt->bindValue(":VLWE", $VLWE);
      $stmt->bindValue(":ApproverStaffID", $ApproverStaffID);
      $stmt->bindValue(":CreateUserID", $CreateUserID);
      $stmt->bindValue(":ModifyUserID", $ModifyUserID);

      if ($stmt->execute()) {
          $response = array();
            $tmp = array();
            $tmp['result'] = 1;
            $spc = new studentPortalClass();
            $enr = $spc->UpdateActivityEnrollRecord($ActivityID);
            if($enr[0]['result'] == 1){
              array_push($response, $tmp);
            }
         return $response;
      } else {
          return NULL;
      }
    }

    public function GetDetailOfActivity($ActivityID){
      $query = $this->getSql('activity-detail');
      $SemesterID = $_SESSION['SemesterID'];
      $query=str_replace('{termId}', $SemesterID,$query);
      $query=str_replace('{activityId}', $ActivityID,$query);
      $stmt = $this->db->prepare($query);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetNumOfActivityJoined($ActivityID){
      $query = $this->getSql('chk-activity-join');
      $stmt = $this->db->prepare($query);
      $SemesterID = $_SESSION['SemesterID'];
      $studentid = $_SESSION['studentId'];

      $stmt->bindParam(1, $studentid);
      $stmt->bindParam(2, $SemesterID);
      $stmt->bindParam(3, $ActivityID);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function UpdateActivityEnrollRecord($ActivityID){
      $query = $this->getSql('update-pending-enroll');
      $query=str_replace('{activityId}', $ActivityID,$query);
      $stmt = $this->db->prepare($query);

      if($stmt->execute()){
        $response = array();
          $tmp = array();
          $tmp['result'] = 1;
          array_push($response, $tmp);
        return $response;
      } else {
        return NULL;
      }

      $stmt->close();
    }

    public function getStaffEmailbyId($staffId){
      $query = $this->getSql('get-staff-email-by-id');
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(1, $staffId);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    function getSemesterIDFromSDate($sDate) {
      $query = $this->getSql('find-semesterid-date');
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(1, $sDate);
      $stmt->bindValue(2, $sDate);
      if ($stmt->execute()) {
        $row = $stmt->fetch();
        return $row['SemesterID'];
      } else {
        return '999';
      }
      $stmt->close();
    }

    function getListCareerSubject($SemesterID) {
      $query = $this->getSql('get-list-career-subject');
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(1, $SemesterID);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response['data'][] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    function getListCareer($SemesterID) {
      $query = $this->getSql('get-career-path');
      $stmt = $this->db->prepare($query);
      $studentid = $_SESSION['studentId'];
      $stmt->bindParam(1, $studentid);
      $stmt->bindParam(2, $SemesterID);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response['data'][] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function UpdateCareerLife($projectId,$subjectID,$subjectName,$teacherID,$topic,$firstName,$lastName,$email,$phone,$position,$desc,$category){
      $query = $this->getSql('update-career-life');
      $SemesterID = $_SESSION['SemesterID'];
      $ModifyUserID = $_SESSION['studentId'];
      $today = date("Y-m-d H:i:s");

      $stmt = $this->db->prepare($query);

      $stmt->bindValue(":ProjectID", $projectId);
      $stmt->bindValue(":SubjectID", $subjectID);
      $stmt->bindValue(":SubjectName", $subjectName);
      $stmt->bindValue(":TeacherID", $teacherID);
      $stmt->bindValue(":ProjectTopic", $topic);
      $stmt->bindValue(":MentorFName", $firstName);
      $stmt->bindValue(":MentorLName", $lastName);
      $stmt->bindValue(":MentorEmail", $email);
      $stmt->bindValue(":MentorPhone", $phone);
      $stmt->bindValue(":MentorDesc", $position);
      $stmt->bindValue(":ProjectDesc", $desc);
      $stmt->bindValue(":ProjectCategory", $category);
      $stmt->bindValue(":ModifyUserID", $ModifyUserID);
      $stmt->bindValue(":ModifyDate", $today);

      $c = new studentPortalClass();
      $subjectInfo = $c->GetSubjectInfo($subjectID, $SemesterID, $ModifyUserID);
      $staffFName = $subjectInfo[0]['staffFName'];
      $staffLName = $subjectInfo[0]['staffLName'];

      $studentLastName = $_SESSION['studentLastName'];
      $studentFirstName = $_SESSION['studentFirstName'];
      if($_SESSION['studentEngName']){
        $studentEngName = " (".$_SESSION['studentEngName'].") ";
      } else {
        $studentEngName = " ";
      }

      $body = <<<BODY
          <style media="screen">
            .table-mail{
              border-collapse: collapse;
              border:none;
              width:900px;
            }
            th, td {
              border-bottom: 1px solid #d2d2d2;
            }
            .table-mail tr td {
              padding:10px;
              font-size:14px;
            }
            .title{
              color:#30297E;
            }
            .tableTitle{
              font-weight: bold;
              color: #66615b;
              width: 300px;
            }
            .linkContainer{
              margin-top:10px;
            }
          </style>

          <div>
            <h3 class="title">
              Student Portal System Notification<br />
            </h3>
              <br />
              <br />
              <table class="table-mail" >
                <tr>
                  <td class="tableTitle">Course</td>
                  <td colspan="3" >{$subjectName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Student</td>
                  <td colspan="3" >{$studentFirstName}{$studentEngName}{$studentLastName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Topic</td>
                  <td colspan="3" >{$topic}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Guide</td>
                  <td colspan="3" >{$firstName} {$lastName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Teacher</td>
                  <td colspan="3" >{$staffFName} {$staffLName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Approval Status</td>
                  <td colspan="3" style="color:#F44336">Pending Approval</td>
                </tr>
                <tr>
                  <td class="tableTitle">Student Comment</td>
                  <td colspan="3">{$desc}</td>
                </tr>

              </table>
          </div>
          <div class="linkContainer">
            <a href="https://admin.bodwell.edu/BHS/SPAdmin/?page=career">View in SP Admin</a>
          </div>
BODY;

      if ($stmt->execute()) {
          $response = array();
            $tmp = array();
            $emailClass = new studentPortalClass();
            $emailresult = $emailClass->sendEmailFromAjax($teacherID, 'career', $body);
            $tmp['emailresult'] = $emailresult;
            $tmp['result'] = 1;
            array_push($response, $tmp);
         return $response;
      } else {
          return NULL;
      }
    }


    function AddCareerLifePathway($course,$description,$email,$firstName,$lastName,$phone,$position,$topic,$category) {
      $query = $this->getSql('insert-career-record');
      $stmt = $this->db->prepare($query);
      $SemesterID = $_SESSION['SemesterID'];
      $studentid = $_SESSION['studentId'];
      $status = 60;

      $c = new studentPortalClass();
      $subjectInfo = $c->GetSubjectInfo($course, $SemesterID, $studentid);

      $studentCourseId = $subjectInfo[0]['studentCourseId'];
      $courseName = $subjectInfo[0]['courseName'];
      $TeacherID = $subjectInfo[0]['TeacherID'];
      $CourseCd = $subjectInfo[0]['CourseCd'];
      $staffFName = $subjectInfo[0]['staffFName'];
      $staffLName = $subjectInfo[0]['staffLName'];

      $stmt->bindValue(":StudSubjID", $studentCourseId);
      $stmt->bindValue(":StudentID", $studentid);
      $stmt->bindValue(":SubjectID",$course);
      $stmt->bindValue(":SubjectName", $courseName);
      $stmt->bindValue(":TeacherID", $TeacherID);
      $stmt->bindValue(":CourseCd", $CourseCd);
      $stmt->bindValue(":SemesterID", $SemesterID);
      $stmt->bindValue(":ProjectTopic", $topic);
      $stmt->bindValue(":MentorFName", $firstName);
      $stmt->bindValue(":MentorLName", $lastName);
      $stmt->bindValue(":MentorDesc", $position);
      $stmt->bindValue(":MentorEmail", $email);
      $stmt->bindValue(":MentorPhone", $phone);
      $stmt->bindValue(":ProjectDesc", $description);
      $stmt->bindValue(":ProjectCategory", $category);

      $stmt->bindValue(":StudentComment", "");
      $stmt->bindValue(":TeacherComment", "");
      $stmt->bindValue(":ApprovalStatus", $status);
      $stmt->bindValue(":ModifyUserID", $studentid);
      $stmt->bindValue(":CreateUserID", $studentid);

      $studentLastName = $_SESSION['studentLastName'];
      $studentFirstName = $_SESSION['studentFirstName'];
      if($_SESSION['studentEngName']){
        $studentEngName = " (".$_SESSION['studentEngName'].") ";
      } else {
        $studentEngName = " ";
      }

      $body = <<<BODY
          <style media="screen">
            .table-mail{
              border-collapse: collapse;
              border:none;
              width:900px;
            }
            th, td {
              border-bottom: 1px solid #d2d2d2;
            }
            .table-mail tr td {
              padding:10px;
              font-size:14px;
            }
            .title{
              color:#30297E;
            }
            .tableTitle{
              font-weight: bold;
              color: #66615b;
              width: 300px;
            }
            .linkContainer{
              margin-top:10px;
            }
          </style>

          <div>
            <h3 class="title">
              Student Portal System Notification<br />
            </h3>
              <br />
              <br />
              <table class="table-mail" >
                <tr>
                  <td class="tableTitle">Course</td>
                  <td colspan="3" >{$courseName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Student</td>
                  <td colspan="3" >{$studentFirstName}{$studentEngName}{$studentLastName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Project TOPIC</td>
                  <td colspan="3" >{$topic}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Guide</td>
                  <td colspan="3" >{$firstName} {$lastName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Teacher</td>
                  <td colspan="3" >{$staffFName} {$staffLName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Approval Status</td>
                  <td colspan="3" style="color:#F44336">Pending Approval</td>
                </tr>
                <tr>
                  <td class="tableTitle">Student Comment</td>
                  <td colspan="3">{$description}</td>
                </tr>

              </table>
          </div>
          <div class="linkContainer">
            <a href="https://admin.bodwell.edu/BHS/SPAdmin/?page=career">View in SP Admin</a>
          </div>
BODY;


      if ($stmt->execute()) {
          $response = array();
          $emailClass = new studentPortalClass();
          $emailresult = $emailClass->sendEmailFromAjax($TeacherID, 'career', $body);
          $tmp['emailresult'] = $emailresult;
          $tmp = array();
          $tmp['result'] = 1;
          array_push($response, $tmp);
         return $response;
      } else {
          return NULL;
      }
    }

    public function GetSubjectInfo($course, $SemesterID, $studentid) {
      $query = $this->getSql('get-subject-info');
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(1, $SemesterID);
      $stmt->bindParam(2, $studentid);
      $stmt->bindParam(3, $course);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function sendEmailFromAjax($staffId,$subjectchk, $body) {
        $from = array('email' => 'helpdesk@bodwell.edu', 'name' => 'BHS IT Help Desk');
        $c = new studentPortalClass();
        $staffinfo = $c->getStaffEmailbyId($staffId);
        $email = $staffinfo[0]['Email3'];
        $firstName = $staffinfo[0]['FirstName'];
        $lastName = $staffinfo[0]['LastName'];
        $studentLastName = $_SESSION['studentLastName'];
        $studentFirstName = $_SESSION['studentFirstName'];
        if($_SESSION['studentEngName']){
          $studentEngName = " (".$_SESSION['studentEngName'].") ";
        } else {
          $studentEngName = " ";
        }

        $to = array(
            array('email' => $email, 'name' => "{$firstName} {$lastName}")
        );
        $cc = array();
        if($subjectchk == 'self'){
          $subject = "Self-submitted hours - {$studentFirstName}{$studentEngName} {$studentLastName}";
        } elseif ($subjectchk == 'career') {
          $subject = "Career Life Pathway - Pending Approval";
          $cc = array(
            array('email' => 'daniella.gentile@bodwell.edu', 'name' => 'Daniella Gentile')
          );
          // $cc = array(
          //   array('email' => 'chanho.lee@bodwell.edu', 'name' => 'Daniella Gentile')
          // );
        } else {
          $subject = 'test';
        }
        // $encryptedStaffId = base64_encode($staffId);
        // $url = "https://dev.bodwell.edu/admin.bodwell.edu/BHS/SPAdmin/auth.php?teacher=1234567{$encryptedStaffId}1234567";
        $res = sendEmail($from, $to, $cc, $subject, $body);
        return $res;
    }

    public function GetMyAEPCourses($SemesterID){
      $query = $this->getSql('course-aep-list');
      $stmt = $this->db->prepare($query);
      $studentid = $_SESSION['studentId'];
      $stmt->bindParam(1, $studentid);
      $stmt->bindParam(2, $SemesterID);
      if ($stmt->execute()) {
        $i = 0;
          while ($row = $stmt->fetch()) {
            $response['courses'][$row['SubjectID']] = array_map('utf8_encode', $row);;
            $response['coursesInfo'][$i]['SubjectID'] = $row['SubjectID'];
            $response['coursesInfo'][$i]['SubjectName'] = $row['SubjectName'];
            $i++;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();

    }

    public function GetIAPRubric($SemesterID){
      $query = $this->getSql('iap-rubric');
      $stmt = $this->db->prepare($query);
      $studentid = $_SESSION['studentId'];
      $stmt->bindParam(1, $studentid);
      $stmt->bindParam(2, $SemesterID);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function semesterListForAssessments()  {
      $query = $this->getSql('semester-list-assessments');
      $stmt = $this->db->prepare($query);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function getAssessmentsScore() {
      $query = $this->getSql('assessments-score');
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(1, $_SESSION['studentId']);

      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            // $response[$row['AssessmentID']][] = $row;
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function ResetEmailPassword($pEmail,$dob) {
      $c = new studentPortalClass();
      $DOBarr = $c->CheckDob($dob);
      if($DOBarr['chk'] == 1) {
        return 'errDOB';
      } else {
        $query = $this->getSql('add-request-reset-school-email');
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":StudentID", $_SESSION['studentId']);
        $stmt->bindValue(":SchoolEmail", $DOBarr['email']);
        $stmt->bindValue(":PersonalEmail", $pEmail);
        $stmt->bindValue(":Status", 0);
        $stmt->bindValue(":ModifyUserID", $_SESSION['studentId']);
        $stmt->bindValue(":CreateUserID", $_SESSION['studentId']);

        if ($stmt->execute()) {
           return 'success';
        } else {
            return NULL;
        }
        $stmt->close();
      }
    }

    public function ResetEmailPasswordOptionalSID($StudentID,$SchoolEmail,$PersonalEmail,$sCountry,$sCity,$sFirstName,$sLastName,$sDOB,$sDateTime, $sPhoneNumber, $translation, $sCounsellor, $sCounsellorEmail) {
        $query = $this->getSql('add-request-reset-school-email-optional-sid');
        $today = date("Y-m-d H:i:s");
        $query = str_replace('{StudentID}', $StudentID, $query);
        $query = str_replace('{SchoolEmail}', $SchoolEmail, $query);
        $query = str_replace('{PersonalEmail}', $PersonalEmail, $query);
        $query = str_replace('{Status}', 0, $query);
        $query = str_replace('{sCountry}', $sCountry, $query);
        $query = str_replace('{sCity}', $sCity, $query);
        $query = str_replace('{sFirstName}', $sFirstName, $query);
        $query = str_replace('{sLastName}', $sLastName, $query);
        $query = str_replace('{sDOB}', $sDOB, $query);
        $query = str_replace('{sDateTime}', $sDateTime, $query);
        $query = str_replace('{sPhoneNumber}', $sPhoneNumber, $query);
        $query = str_replace('{translation}', $translation, $query);
        $query = str_replace('{sCounsellor}', $sCounsellor, $query);
        $query = str_replace('{ModifyUserID}', $StudentID, $query);
        $query = str_replace('{CreateUserID}', $StudentID, $query);
        $stmt = $this->db->prepare($query);
        if ($stmt->execute()) {

          $from = array('email' => 'no-reply@bodwell.edu', 'name' => 'Student Portal');
          $to = array(
              array('email' => 'helpdesk@bodwell.edu', 'name' => 'IT helpdesk')
          );
          $cc = array(array('email' => "{$sCounsellorEmail}", 'name' => "{$sCounsellor}"));
          $subject = "School email password reset ({$sFirstName} {$sLastName})​";
          $body = <<<EOD
          <style media="screen">
            .table-mail{
              border-collapse: collapse;
              border:none;
              width:900px;
            }
            th, td {
              border-bottom: 1px solid #d2d2d2;
            }
            .table-mail tr td {
              padding:10px;
              font-size:14px;
            }
            .title{
              color:#30297E;
            }
            .tableTitle{
              font-weight: bold;
              color: #66615b;
              width: 300px;
            }
            .linkContainer{
              margin-top:10px;
            }
          </style>

          <div>
              <br />
              <br />
              <table class="table-mail" >
                <tr >
                  <td class="tableTitle">First Name</td>
                  <td colspan="3" >{$sFirstName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Last Name</td>
                  <td colspan="3" >{$sLastName}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Counsellor</td>
                  <td colspan="3" >{$sCounsellor}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Current City</td>
                  <td colspan="3" >{$sCity}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Call request time</td>
                  <td colspan="3" >{$sDateTime}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Status</td>
                  <td colspan="3" >Pending</td>
                </tr>
              </table>
          </div>
          <div>
            <p>
            Last modified : {$today}
            </p>
          </div>
EOD;

          $res = sendEmail($from, $to, $cc, $subject, $body);

           return 'success';
        } else {
            return NULL;
        }
        $stmt->close();
    }

    public function CheckDob($dob) {
      $dobquery = $this->getSql('check-dob');
      $stmt = $this->db->prepare($dobquery);
      $stmt->bindParam(1, $_SESSION['studentId']);

      if ($stmt->execute()) {
          $row = $stmt->fetch();
          if($dob == $row['DOB']){
            $response['chk'] = 0;
          } else {
            $response['chk'] = 1;
          }
          $response['email'] = $row['SchoolEmail'];
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetStaffList($Rolebogs) {
      if(is_array($Rolebogs)){
        $str = implode(',', $Rolebogs);
      } else {
        $str = $Rolebogs;
      }

      $query = $this->getSql('staff-list-by-rolebogs');
      $query = str_replace('{Rolebogs}', $str, $query);

      $stmt = $this->db->prepare($query);

      if ($stmt->execute()) {
          $row = $stmt->fetchAll();
          $response[] = $row;
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();

    }

    public function GetCountryList() {
      $query = $this->getSql('country-list');
      $stmt = $this->db->prepare($query);

      if ($stmt->execute()) {
          $row = $stmt->fetchAll();
          $response[] = $row;
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();

    }

    public function searchStudentByName($param){
      $query = $this->getSql('search-student-list');
      $stmt = $this->db->prepare($query);
      $param = addslashes($param);
      $param = '%'.$param.'%';
      $stmt->bindParam(1, $param, PDO::PARAM_STR);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
         }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }


    public function InsertBHSLaptopReturnPlan($StudentID,$authDate,$authName,$deviceLists,$returnto) {

        $c = new studentPortalClass();
        $sID = $c->GetBHSLaptopReturnPlanByStudentID($StudentID);
        if($sID){
          return 'duplicate';
        } else {
          $query = $this->getSql('insert-bhs-return-plan');
          $query = str_replace('{StudentID}', $StudentID, $query);
          $query = str_replace('{ReturnDevices}', $deviceLists, $query);
          $query = str_replace('{ReturnOptions}', $returnto, $query);
          $query = str_replace('{AuthDate}', $authDate, $query);
          $query = str_replace('{AuthUser}', $authName, $query);
          $query = str_replace('{ModifyUserID}', $StudentID, $query);
          $query = str_replace('{CreateUserID}', $StudentID, $query);


          $stmt = $this->db->prepare($query);
          if ($stmt->execute()) {
             return 'success';
          } else {
              return NULL;
          }
          $stmt->close();
        }


    }


    public function GetBHSLaptopReturnPlanByStudentID($StudentID){
      $query = $this->getSql('search-bhs-return-plan');
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(1, $StudentID);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
         }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function AddLeaveRequestForm($studentId, $leavetype, $startDate, $endDate, $hours, $approver, $leaveReason, $comment, $doing) {
        $query = $this->getSql('insert-leave-request-form');
        $today = date("Y-m-d H:i:s");
        $leaveReason = str_replace("'","''",$leaveReason);
        $doing = str_replace("'","''",$doing);
        $comment = str_replace("'","''",$comment);

        $c = new studentPortalClass();
        $leaveBan = $c->GetStudentLeaveBan($startDate,$endDate);
        if($leaveBan) {
          $leaveBan['result'] = 2;
          return $leaveBan;
        } else {
          $query = str_replace('{LeaveType}', $leavetype, $query);
          $query = str_replace('{StudentID}', $studentId, $query);
          $query = str_replace('{SDate}', $startDate, $query);
          $query = str_replace('{EDate}', $endDate, $query);
          $query = str_replace('{Reason}', $leaveReason, $query);
          $query = str_replace('{Comment}', $comment, $query);
          $query = str_replace('{ToDo}', $doing, $query);
          $query = str_replace('{ApprovalStaff}', $approver, $query);
          $query = str_replace('{LeaveTime}', $hours, $query);
          $query = str_replace('{LeaveStatus}', 'P', $query);
          $query = str_replace('{ModifyUserID}', $studentId, $query);
          $query = str_replace('{CreateUserID}', $studentId, $query);
          $stmt = $this->db->prepare($query);
          if ($stmt->execute()) {
              $response = array();
              $tmp = array();
              $tmp['result'] = 1;
              array_push($response, $tmp);
             return $response;
          } else {
              return NULL;
          }
        }

    }

    public function GetStudentLeaveBan($startDate, $endDate){
      $StudentID = $_SESSION['studentId'];
      $query = $this->getSql('get-student-leave-ban');
      $query = str_replace('{StudentID}', $StudentID, $query);
      $query = str_replace('{FromDate}', $startDate, $query);
      $query = str_replace('{ToDate}', $endDate, $query);
      $stmt = $this->db->prepare($query);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
         }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetStudentLeaveRequest(){
      $StudentID = $_SESSION['studentId'];
      $query = $this->getSql('get-student-leave-request');
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(1, $StudentID);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
         }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }



    public function getSelfAssessmentForm($grade) {
      $query = $this->getSql('get-self-assessment-form');
      $query = str_replace('{Grade}', $grade, $query);
      $query = str_replace('{SemesterID}', $_SESSION['SemesterID'], $query);
      $stmt = $this->db->prepare($query);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
         }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function SaveAssessmentGrade10($AssessmentFormID,$AssessmentID, $cRate, $communication, $tRate, $thinking, $pRate, $personal, $personal1) {
      $today = date("Y-m-d H:i:s");
      $personalTxt = $personal.':*:'.$personal1;
        if($AssessmentID){
          $query = $this->getSql('update-assessment');
          $stmt = $this->db->prepare($query);
          $stmt->bindValue(":AssessmentID", $AssessmentID);
          $stmt->bindValue(":ModifyDate", $today, PDO::PARAM_STR);
        } else {
          $query = $this->getSql('insert-assessment');
          $stmt = $this->db->prepare($query);
          $stmt->bindValue(":CreateUserID", $_SESSION['studentId']);
          $stmt->bindValue(":CurrentGrade", $_SESSION['CurrentGrade']);
        }
        $personalTxt = $personal.':*:'.$personal1;
        $stmt->bindValue(":AssessmentFormID", $AssessmentFormID);
        $stmt->bindValue(":StudentID", $_SESSION['studentId']);
        $stmt->bindValue(":CommunicationText", $communication);
        $stmt->bindValue(":ThinkingText", $thinking);
        $stmt->bindValue(":PersonalText", $personalTxt);
        $stmt->bindValue(":CommunicationRate", $cRate);
        $stmt->bindValue(":ThinkingRate", $tRate);
        $stmt->bindValue(":PersonalRate", $pRate);
        $stmt->bindValue(":ModifyUserID", $_SESSION['studentId']);

        if ($stmt->execute()) {
            $response = array();
            $tmp = array();
            $tmp['result'] = 1;
            array_push($response, $tmp);
           return $response;
        } else {
            return NULL;
        }
    }

    public function SaveAssessmentGrade8($AssessmentFormID,$AssessmentID, $cRate, $tRate, $pRate,
     $Communication1, $Communication2, $Communication3, $Communication4, $Communication5, $Communication6, $Communication7, $Communication8, $Communication9
   , $Communication10, $Communication11, $Communication12
  , $Personal1, $Personal2, $Personal3, $Personal4, $Personal5, $Personal6, $Personal7, $Personal8, $Personal9, $Personal10, $Personal11, $Personal12
  , $Thinking1, $Thinking2, $Thinking3, $Thinking4, $Thinking5, $Thinking6, $Thinking7, $Thinking8, $Thinking9, $Thinking10, $Thinking11, $Thinking12) {
      $today = date("Y-m-d H:i:s");
        if($AssessmentID){
          $query = $this->getSql('update-assessment');
          $stmt = $this->db->prepare($query);
          $stmt->bindValue(":AssessmentID", $AssessmentID);
          $stmt->bindValue(":ModifyDate", $today, PDO::PARAM_STR);
        } else {
          $query = $this->getSql('insert-assessment');
          $stmt = $this->db->prepare($query);
          $stmt->bindValue(":CreateUserID", $_SESSION['studentId']);
          $stmt->bindValue(":CurrentGrade", $_SESSION['CurrentGrade']);
        }

        $personalTxt = $Personal1.':*:'.$Personal2.':*:'.$Personal3.':*:'.$Personal4.':*:'.$Personal5.':*:'.$Personal6.':*:'.$Personal7.':*:'.$Personal8.':*:'.$Personal9.':*:'.$Personal10.':*:'.$Personal11.':*:'.$Personal12;
        $thinkingTxt = $Thinking1.':*:'.$Thinking2.':*:'.$Thinking3.':*:'.$Thinking4.':*:'.$Thinking5.':*:'.$Thinking6.':*:'.$Thinking7.':*:'.$Thinking8.':*:'.$Thinking9.':*:'.$Thinking10.':*:'.$Thinking11.':*:'.$Thinking12;
        $comunicationTxt = $Communication1.':*:'.$Communication2.':*:'.$Communication3.':*:'.$Communication4.':*:'.$Communication5.':*:'.$Communication6.':*:'.$Communication7.':*:'.$Communication8.':*:'.$Communication9.':*:'.$Communication10.':*:'.$Communication11.':*:'.$Communication12;
        $stmt->bindValue(":AssessmentFormID", $AssessmentFormID);
        $stmt->bindValue(":StudentID", $_SESSION['studentId']);
        $stmt->bindValue(":CommunicationText", $comunicationTxt);
        $stmt->bindValue(":ThinkingText", $thinkingTxt);
        $stmt->bindValue(":PersonalText", $personalTxt);
        $stmt->bindValue(":CommunicationRate", $cRate);
        $stmt->bindValue(":ThinkingRate", $tRate);
        $stmt->bindValue(":PersonalRate", $pRate);
        $stmt->bindValue(":ModifyUserID", $_SESSION['studentId']);

        if ($stmt->execute()) {
            $response = array();
            $tmp = array();
            $tmp['result'] = 1;
            array_push($response, $tmp);
           return $response;
        } else {
            return NULL;
        }
    }

    public function GetStudentAssessment($AssessmentFormID){
      $StudentID = $_SESSION['studentId'];
      $query = $this->getSql('get-assessment');
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(":AssessmentFormID", $AssessmentFormID);
      $stmt->bindValue(":StudentID", $StudentID);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
         }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetTranscriptRequest(){
      $StudentID = $_SESSION['studentId'];
      $query = $this->getSql('get-transcript-request');
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(":CreateUserID", $StudentID);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
         }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetReportCard($sId) {
      $query = $this->getSql('get-report-card');
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':StudentID', $_SESSION['studentId']);
      $stmt->bindValue(':SemesterID', $sId);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[$row['SubjectName']] = array_map('utf8_encode', $row);
            // $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetReportCardSummary($sId) {
      $query = $this->getSql('get-report-card-summary');
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':StudentID', $_SESSION['studentId']);
      $stmt->bindValue(':SemesterID', $sId);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[$row['SubjectID']] = array_map('utf8_encode', $row);
            // $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function GetReportCardSemester() {
      $query = $this->getSql('get-report-card-semester');
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':StudentID', $_SESSION['studentId']);
      if ($stmt->execute()) {
          while ($row = $stmt->fetch()) {
            $response[] = $row;
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }



    public function GetOutstandingFee($FeeType, $Amount) {
      $query = $this->getSql('get-outstanding-fee');
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':FeeType', $FeeType, PDO::PARAM_STR);
      $stmt->bindValue(':Amount', $Amount);
      $stmt->bindValue(':StudentID', $_SESSION['studentId']);
      if ($stmt->execute()) {
          $data = $stmt->fetchAll();
          if($data) {
            $response['Eligible'] = 'No';
          } else {
            $response['Eligible'] = 'Yes';
          }
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function addTranscriptRequest($requestDate, $physicalcopy, $mailingMethod, $deadLine, $applyingto, $UniversityName, $ApplicationNumber, $Address, $copytype) {
      $query = $this->getSql('add-transcript-request');
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':RequestDate', $requestDate);
      $stmt->bindValue(':CopyType', $copytype);
      $stmt->bindValue(':ApplyTo', $applyingto);
      $stmt->bindValue(':PhysicalCopy', $physicalcopy);
      $stmt->bindValue(':Deadline', $deadLine);
      $stmt->bindValue(':ApplicationID', $ApplicationNumber);
      $stmt->bindValue(':StudentID', $_SESSION['studentId']);
      $stmt->bindValue(':SchoolName', $UniversityName);
      $stmt->bindValue(':Address', $Address);
      $stmt->bindValue(':MailingMethod', $mailingMethod);
      $stmt->bindValue(':Paid', 'N');
      $stmt->bindValue(':Status', 'P');
      $stmt->bindValue(':ModifyUserID', $_SESSION['studentId']);
      $stmt->bindValue(':CreateUserID', $_SESSION['studentId']);



      if ($stmt->execute()) {
          $response['result'] = 1;
          $c = new studentPortalClass();
          $email = $c->sendEmailTranscript($requestDate, $physicalcopy, $deadLine, $applyingto);
          $response['email'] = $email;
         return $response;
      } else {
          return NULL;
      }
      $stmt->close();
    }

    public function sendEmailTranscript($requestDate, $physicalcopy, $deadLine, $applyingto){

      $from = array('email' => 'helpdesk@bodwell.edu', 'name' => 'BHS IT Help Desk');
      $to = array(
        array('email' => 's_sun@bodwell.edu', 'name' => 'Sunny Sun')
      );

      $cc = array();
      $subject = 'Transcript Request Notification';
      $studentId = $_SESSION['studentId'];

      $lastname = $_SESSION['studentLastName'];
      $firstname = $_SESSION['studentFirstName'];
      $engname = $_SESSION['studentEngName'];

      $body = <<<BODY
          <style media="screen">
            .table-mail{
              border-collapse: collapse;
              border:none;
              width:900px;
            }
            th, td {
              border-bottom: 1px solid #d2d2d2;
            }
            .table-mail tr td {
              padding:10px;
              font-size:14px;
            }
            .title{
              color:#30297E;
            }
            .tableTitle{
              font-weight: bold;
              color: #66615b;
              width: 300px;
            }
            .linkContainer{
              margin-top:10px;
            }
          </style>

          <div>
            <h3 class="title">
              Student Portal System Notification<br />
            </h3>
            <br />
              <br />
              <br />
              <table class="table-mail" >
                <tr>
                  <td class="tableTitle">Student ID</td>
                  <td colspan="3" >{$studentId}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Student Name</td>
                  <td colspan="3" >{$firstname} {$lastname} {$engname}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Request Date</td>
                  <td colspan="3" >{$requestDate}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Apply To</td>
                  <td colspan="3" >{$applyingto}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Physical Copy</td>
                  <td colspan="3" >{$physicalcopy}</td>
                </tr>
                <tr>
                  <td class="tableTitle">Deadline</td>
                  <td>{$deadLine}</td>
                  <td ></td>
                  <td ></td>
                </tr>
              </table>
          </div>
          <div class="linkContainer">
            Please sign-in to your SP Admin to see the detail.
          </div>
BODY;
      $res = sendEmail($from, $to, $cc, $subject, $body);
      return $res;

    }

}
?>
