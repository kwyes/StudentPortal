<?php
$_SQL = array(
    'student-info' => "SELECT
    COUNT(DISTINCT c.SemesterID) numTerms,
    COUNT(DISTINCT (Case when c.SubjectName LIKE 'AEP%' then c.SemesterID end)) numOfAepTerm,
	  student.StudentID studentId,
    student.PEN pen,
    student.FirstName firstName,
    student.LastName lastName,
    student.EnglishName englishName,
    student.SchoolEmail schoolEmail,
    student.CurrentGrade currentGrade,
	  student.Counselor counsellor,
    student.Mentor mentor,
    student.Houses houses,
    homestay.Homestay homestay,
    homestay.Residence residence,
    homestay.Halls halls,
    homestay.RoomNo roomNo,
    homestay.Hadvisor youthAdvisor,
    homestay.Hadvisor2 youthAdvisor2,
    homestay.Tutor tutor,
    student.EnrolmentDate EnrollmentDate
	  FROM tblBHSStudent student
    LEFT JOIN tblBHSHomestay homestay ON student.StudentID = homestay.StudentID
	  LEFT JOIN tblBHSStudentSubject b ON student.StudentID = b.StudNum
    LEFT JOIN tblBHSSubject c ON b.SubjectID = c.SubjectID
    LEFT JOIN tblBHSSemester d ON c.SemesterID = d.SemesterID
    WHERE student.StudentID=? AND student.CurrentStudent='Y' AND d.SemesterID<=?
	GROUP BY
	student.StudentID,
	student.PEN,
  student.FirstName,
  student.LastName,
  student.EnglishName,
  student.SchoolEmail,
  student.CurrentGrade,
	student.Counselor,
  student.Mentor,
  student.Houses,
  homestay.Homestay,
  homestay.Residence,
  homestay.Halls,
  homestay.RoomNo,
  homestay.Hadvisor,
  homestay.Hadvisor2,
  homestay.Tutor,
  student.EnrolmentDate",

  'student-login' => "SELECT a.LoginID schoolEmail, a.UserID studentId, a.PW3 password, b.FirstName, b.LastName, b.EnglishName, b.CurrentGrade, b.PEN, b.Mentor
  FROM tblBHSUserAuth a
  JOIN tblBHSStudent b ON a.UserID = b.StudentID
  WHERE a.LoginID='{LoginID}' AND a.Category='20' AND b.CurrentStudent='Y'",

  'find-currentsemester' => "SELECT *
  ,CONVERT(char(10), StartDate, 126) as StartDate
  ,CONVERT(char(10), EndDate, 126) as EndDate
  ,CONVERT(char(10), MidCutOffDate, 126) as MidCutOffDate
  FROM tblBHSSemester WHERE CurrentSemester = 'Y' ",
  'find-Enrollmentsemester' => "SELECT SemesterID, SemesterName
  FROM tblBHSSemester WHERE ? BETWEEN StartDate AND EndDate ",

  'num-terms-by-id' => "
      SELECT COUNT(DISTINCT c.SemesterID) numTerms
      FROM tblBHSStudent a
      JOIN tblBHSStudentSubject b ON a.StudentID = b.StudNum
      JOIN tblBHSSubject c ON b.SubjectID = c.SubjectID
      JOIN tblBHSSemester d ON c.SemesterID = d.SemesterID
      WHERE a.StudentID=? AND a.CurrentStudent = 'Y' AND d.SemesterID<=?
  ",
  'num-aep-terms-by-id' => "
      SELECT COUNT(DISTINCT c.SemesterID) numAEPTerms
      FROM tblBHSStudent a
      JOIN tblBHSStudentSubject b ON a.StudentID = b.StudNum
      JOIN tblBHSSubject c ON b.SubjectID = c.SubjectID
      JOIN tblBHSSemester d ON c.SemesterID = d.SemesterID
      WHERE a.StudentID=? AND a.CurrentStudent = 'Y' AND d.SemesterID<=? AND c.SubjectName LIKE 'AEP%'
  ",
  'course-grade-list' => "SELECT
    studentId,
    courseId,
    COUNT(categoryId) categoryCount,
    SUM(categoryWeight) categoryWeightTotal,
    SUM(categoryRateScaled * categoryWeight) courseRateOrigin,
    SUM(categoryRateScaled * categoryWeight) * (1 / SUM(categoryWeight)) courseRateScaled
  FROM (
    SELECT
      student.StudentID studentId,
      course.SubjectID courseId,
      category.CategoryID categoryId,
      category.CategoryWeight categoryWeight,
      SUM((grade.ScorePoint / item.MaxValue) * item.ItemWeight) * (1 / SUM(item.ItemWeight)) categoryRateScaled
    FROM tblBHSOGSGrades grade
      JOIN tblBHSOGSCategoryItems item ON grade.CategoryItemID = item.CategoryItemID
      JOIN tblBHSOGSCourseCategory category ON item.CategoryID = category.CategoryID
      JOIN tblBHSSubject course ON category.SubjectID = course.SubjectID
      JOIN tblBHSStudentSubject studentSubject ON grade.StudSubjID = studentSubject.StudSubjID
      JOIN tblBHSStudent student ON studentSubject.StudNum = StudentID
    WHERE student.StudentID='{studentId}' AND grade.SemesterID='{termId}' AND grade.ScorePoint IS NOT NULL AND grade.Exempted <> 1
    GROUP BY student.StudentID, course.SubjectID, category.CategoryID, category.CategoryWeight
  ) categoryGrade
  GROUP BY studentId, courseId",


  'course-list' => "SELECT
      course.SemesterID termId,
      studentCourse.StudSubjID studentCourseId,
      studentCourse.StudNum studentId,
      studentCourse.SubjectID courseId,
      course.SubjectName courseName,
      staff.StaffID teacherId,
      CONCAT(staff.FirstName, ' ', staff.LastName) teacherName,
      staff.FirstName teacherFirstName,
      staff.LastName teacherLastName,
      course.PName provincialName,
      course.CourseCd courseCode,
      course.RoomNo roomNo,
      course.Cap cap,
      course.Spa spa,
      course.Credit credit,
      course.Type courseType,
      course.CourseCd
  FROM tblBHSStudentSubject studentCourse
  JOIN tblBHSSubject course ON studentCourse.SubjectID = course.SubjectID
  JOIN tblStaff staff ON course.TeacherID = staff.StaffID
  WHERE course.SemesterID=? AND studentCourse.StudNum=? AND course.SubjectName NOT LIKE 'YYY%'
  ORDER BY
      course.Credit DESC,
      course.SubjectName ASC",

  'absent-list-by-courselist'  => "SELECT StudSubjID studentCourseId, SUM(AbsencePeriod) absenceCount, SUM(LatePeriod) lateCount
  FROM tblBHSAttendance
  WHERE StudSubjID IN ({studentCourseList}) AND Excuse = '0'
  GROUP BY StudSubjID",


      'student-activity-list-v2' => "
          SELECT
               A.StudentActivityID activityId
              ,A.Title title
              ,A.ActivityCategory category
              ,CONVERT(CHAR(10), A.SDate, 126) activityDate
              ,A.Body description
              ,A.ApproverStaffID staffId
              ,D.FirstName firstName
              ,D.LastName lastName
              ,A.Hours hours
              ,A.VLWE qvwh
              ,A.StudentID studentId
              ,A.SemesterID termId
              ,A.VLWE VLWE
          FROM tblBHSSPStudentActivities A
          LEFT JOIN tblStaff D ON A.ApproverStaffID = D.StaffID
          WHERE A.StudentID=? AND A.ActivityStatus = '80' AND A.SDate >= '2018-01-01'
          ORDER BY A.SDate DESC
      ",
      'student-activity-list-v3' => "SELECT
               A.StudentActivityID activityId
              ,A.Title title
              ,A.ActivityCategory category
              ,CONVERT(CHAR(10), A.SDate, 126) activityDate
              ,A.Body description
              ,A.ApproverStaffID staffId
              ,CONCAT(D.FirstName, ' ', D.LastName) as FullStaffName
      			  ,A.ActivityStatus
      			  ,A.ProgramSource
              ,A.Hours hours
              ,A.VLWE qvwh
              ,A.StudentID studentId
      			  ,CONCAT(S.FirstName, ' ', S.LastName) as FullName
      			  ,A.StudentID studentId
              ,A.SemesterID termId
              ,M.SemesterName SemesterName
              ,A.VLWE VLWE
          FROM tblBHSSPStudentActivities A
          LEFT JOIN tblStaff D ON A.ApproverStaffID = D.StaffID
	        LEFT JOIN tblBHSStudent S ON S.StudentID = A.StudentID
          LEFT JOIN tblBHSSemester M ON A.SemesterID = M.SemesterID
          WHERE A.StudentID=? AND A.SemesterID <= ?
          ORDER BY A.SDate DESC",

          'student-activity-list-v4' => "SELECT
                   A.StudentActivityID activityId
                  ,A.Title title
                  ,A.ActivityCategory category
                  ,CONVERT(CHAR(10), A.SDate, 126) sDate
                  ,CONVERT(CHAR(10), A.EDate, 126) eDate
                  ,A.Body description
                  ,A.ApproverStaffID staffId
                  ,CONCAT(D.FirstName, ' ', D.LastName) as FullStaffName
          			  ,A.ActivityStatus
          			  ,A.ProgramSource
                  ,A.Hours hours
                  ,A.VLWE qvwh
                  ,A.StudentID studentId
          			  ,CONCAT(S.FirstName, ' ', S.LastName) as FullName
          			  ,A.StudentID studentId
                  ,A.SemesterID termId
                  ,M.SemesterName SemesterName
                  ,A.VLWE VLWE
                  ,A.Location location
                  ,A.SELFComment1 stuComment1
                  ,A.SELFComment2 stuComment2
                  ,A.SELFComment3 stuComment3
                  ,A.ApproverComment1 approverComment1
        				  ,CreateUserID
        				  ,CASE
        					WHEN
        						ISNUMERIC(LEFT(A.CreateUserID,1)) = 0
        					THEN
        						CONCAT(E.FirstName, ' ', E.LastName)
        					ELSE
        						CONCAT(S.FirstName, ' ', S.LastName)
        					END AS CreateUserName
                  ,CONVERT(varchar, A.CreateDate, 120) CreateDate
    							,CONVERT(varchar, A.ModifyDate, 120) ModifyDate
                  ,CASE
              		WHEN
              			ISNUMERIC(LEFT(A.ModifyUserID,1)) = 0
              		THEN
              			CONCAT(F.FirstName, ' ', F.LastName)
              		ELSE
              			CONCAT(S.FirstName, ' ', S.LastName)
              		END AS ModifyUserName
              FROM tblBHSSPStudentActivities A
              LEFT JOIN tblStaff D ON A.ApproverStaffID = D.StaffID
              LEFT JOIN tblStaff E ON A.CreateUserID = E.StaffID
              LEFT JOIN tblStaff F ON A.ModifyUserID = F.StaffID
    	        LEFT JOIN tblBHSStudent S ON S.StudentID = A.StudentID
              LEFT JOIN tblBHSSemester M ON A.SemesterID = M.SemesterID
              WHERE A.StudentID=? AND CONVERT(CHAR(10), A.SDate, 126) >= ?
              ORDER BY A.SDate DESC",

      'student-activity-list' => "
          SELECT
            studentActivity.StudentActivityID studentActivityId,
            studentActivity.ActivityStatus activityStatus,
            studentActivity.StudentID studentId,
            studentActivity.Title titleAlt,
            studentActivity.Body descriptionAlt,
            studentActivity.ActualLocation actualLocation,
            studentActivity.ActualSDate actualStartDate,
            studentActivity.ActualEDate actualEndDate,
            studentActivity.ActualHours actualHours,
            studentActivity.ActualAllDay actualAllDay,
            studentActivity.ActualDPA actualDpa,
            studentActivity.ActualWorkExp actualWorkExp,
            studentActivity.ActualCommService actualCommService,
            studentActivity.ApproverStaffID approverId,
            studentActivity.Witness witness,
            category.ActivityCategory category,
            category.Title categoryTitle,
            category.Body categoryDescription,
            activity.SemesterID termId,
            activity.ActivityID activityId,
            activity.Title title,
            activity.Body description,
            activity.StaffID staffId,
            CONCAT(staff.FirstName, ' ', staff.LastName) staffName,
            activity.StaffID2 staffId2,
            CONCAT(staff2.FirstName, ' ', staff2.LastName) staff2Name,
            activity.Location location,
            activity.BaseHours baseHours,
            activity.StartDate startDate,
            activity.EndDate endDate,
            activity.AllDay allDay,
            activity.DPA dpa,
            activity.WorkExp workExp,
            activity.CommService commService,
            activity.MaxEnrolment maxEnroll,
            student.FirstName firstName,
            student.LastName lastName,
            student.EnglishName englishName,
            student.SchoolEmail schoolEmail
          FROM tblBHSSPStudentActivities studentActivity
            LEFT JOIN tblBHSSPActivity activity ON activity.ActivityID = studentActivity.ActivityID
            LEFT JOIN tblBHSSPActivityConfig category ON studentActivity.ActivityCategory = category.ActivityCategory
            LEFT JOIN tblBHSStudent student ON studentActivity.StudentID = student.StudentID
            LEFT JOIN tblStaff staff ON activity.StaffID = staff.StaffID
            LEFT JOIN tblStaff staff2 ON activity.StaffID2 = staff2.StaffID
          WHERE studentActivity.StudentID='{studentId}' AND studentActivity.SemesterID='{termId}'
          ORDER BY
            studentActivity.ActualSDate ASC,
            studentActivity.Title ASC
      ",
      'student-activity-by-id' => "
          SELECT
            studentActivity.StudentActivityID studentActivityId,
            studentActivity.ActivityStatus activityStatus,
            studentActivity.StudentID studentId,
            studentActivity.Title titleAlt,
            studentActivity.Body descriptionAlt,
            studentActivity.ActualLocation actualLocation,
            studentActivity.ActualSDate actualStartDate,
            studentActivity.ActualEDate actualEndDate,
            studentActivity.ActualHours actualHours,
            studentActivity.ActualAllDay actualAllDay,
            studentActivity.ActualDPA actualDpa,
            studentActivity.ActualWorkExp actualWorkExp,
            studentActivity.ActualCommService actualCommService,
            studentActivity.ApproverStaffID approverId,
            studentActivity.Witness witness,
            category.ActivityCategory category,
            category.Title categoryTitle,
            category.Body categoryDescription,
            activity.SemesterID termId,
            activity.ActivityID activityId,
            activity.Title title,
            activity.Body description,
            activity.StaffID staffId,
            CONCAT(staff.FirstName, ' ', staff.LastName) staffName,
            activity.StaffID2 staffId2,
            CONCAT(staff2.FirstName, ' ', staff2.LastName) staff2Name,
            activity.Location location,
            activity.BaseHours baseHours,
            activity.StartDate startDate,
            activity.EndDate endDate,
            activity.AllDay allDay,
            activity.DPA dpa,
            activity.WorkExp workExp,
            activity.CommService commService,
            activity.MaxEnrolment maxEnroll,
            student.FirstName firstName,
            student.LastName lastName,
            student.EnglishName englishName,
            student.SchoolEmail schoolEmail
          FROM tblBHSSPStudentActivities studentActivity
            LEFT JOIN tblBHSSPActivity activity ON activity.ActivityID = studentActivity.ActivityID
            LEFT JOIN tblBHSSPActivityConfig category ON studentActivity.ActivityCategory = category.ActivityCategory
            LEFT JOIN tblBHSStudent student ON studentActivity.StudentID = student.StudentID
            LEFT JOIN tblStaff staff ON activity.StaffID = staff.StaffID
            LEFT JOIN tblStaff staff2 ON activity.StaffID2 = staff2.StaffID
          WHERE studentActivity.StudentActivityID='{activityId}' AND studentActivity.SemesterID='{termId}'
      ",
      'school-activity-list' => "
      SELECT
        category.ActivityCategory categoryCode,
        category.Title categoryTitle,
        category.Body categoryDescription,
        activity.SemesterID termId,
        activity.ActivityID activityId,
        activity.Title title,
        activity.Body description,
        activity.ActivityType activityType,
        activity.MeetingPlace meetingPlace,
        activity.StaffID staffId,
        CONCAT(staff.FirstName, ' ', staff.LastName) staffName,
        activity.StaffID2 staffId2,
        CONCAT(staff2.FirstName, ' ', staff2.LastName) staff2Name,
        activity.Location location,
        activity.BaseHours baseHours,
        CONVERT(CHAR(10), activity.StartDate, 126) startDate,
        CONVERT(CHAR(10), activity.EndDate, 126) endDate,
        activity.AllDay allDay,
        activity.DPA dpa,
        activity.VLWE VLWE,
        ISNULL(activity.CurrentEnrolment,0) curEnroll,
        ISNULL(activity.PendingEnrolment,0) penEnroll,
        ISNULL(activity.MaxEnrolment,0) maxEnroll,
        ISNULL(activity.MaxEnrolment,0) - ISNULL(activity.CurrentEnrolment,0) SubstractNum,
        CASE WHEN activity.StartDate <= GETDATE() THEN 1 ELSE 0 END overdue
        FROM tblBHSSPActivity activity
        LEFT JOIN tblBHSSPActivityConfig category ON activity.ActivityCategory = category.ActivityCategory
        LEFT JOIN tblStaff staff ON activity.StaffID = staff.StaffID
        LEFT JOIN tblStaff staff2 ON activity.StaffID2 = staff2.StaffID
      WHERE activity.SemesterID='{termId}'
      ORDER BY
        activity.StartDate DESC,
        activity.Title ASC
      ",
        'grade-list-by-student' => "SELECT
            course.SemesterID termId,
	          semester.SemesterName,
            studentCourse.StudSubjID studentCourseId,
            course.SubjectName SubjectName,
            studentCourse.StudNum studentId,
            studentCourse.SubjectID courseId,
            item.CategoryID categoryId,
            item.CategoryItemID itemId,
            grade.GradeID gradeId,
            item.Title itemTitle,
            item.ItemWeight itemWeight,
            grade.ScorePoint scorePoint,
            grade.ScorePoint / item.MaxValue scoreRate,
            item.ScoreType scoreType,
            grade.Comment comment,
            grade.Exempted exempted,
            item.MaxValue maxScore,
            course.RoomNo roomNo,
            CONCAT(staff.FirstName, ' ', staff.LastName) teacherName,
            CONVERT(CHAR(10), item.AssignDate, 126) assignDate,
            CONVERT(CHAR(10), item.DueDate, 126) dueDate,
            CASE WHEN item.AssignDate <= GETDATE() AND ABS(DATEDIFF(DAY, item.AssignDate, GETDATE())) > 3 AND grade.ScorePoint IS NULL THEN 1 ELSE 0 END overdue,
            CASE
              WHEN grade.Exempted = 1 THEN 'exempted'
              WHEN grade.ScorePoint IS NOT NULL THEN 'normal'
              WHEN item.AssignDate <= GETDATE() AND ABS(DATEDIFF(DAY, item.AssignDate, GETDATE())) > 3 THEN 'overdue'
              ELSE 'pending'
            END gradeStatus,
            category.CategoryCode categoryCode,
            category.Text categoryTitle,
            CONCAT(category.Text, ' (', FORMAT(ROUND(category.CategoryWeight * 100, 2),'g15'), '%)') categoryLabel,
            category.CategoryWeight categoryWeight,
            CONCAT(ROUND(category.CategoryWeight * 100, 2), '%') categoryWeightLabel
        FROM tblBHSStudentSubject studentCourse
        JOIN tblBHSSubject course ON studentCourse.SubjectID = course.SubjectID
        JOIN tblBHSOGSCategoryItems item ON item.SemesterID = course.SemesterID AND item.SubjectID = course.SubjectID
        JOIN tblStaff staff ON course.TeacherID = staff.StaffID
	      LEFT JOIN tblBHSSemester semester ON semester.SemesterID = course.SemesterID
        LEFT JOIN tblBHSOGSCourseCategory category ON category.SemesterID = course.SemesterID AND category.CategoryID = item.CategoryID and category.SubjectID = item.SubjectID
        LEFT JOIN tblBHSOGSGrades grade ON studentCourse.StudSubjID = grade.StudSubjID AND item.CategoryItemID = grade.CategoryItemID
        WHERE course.SemesterID=? AND studentCourse.StudNum=? AND item.AssignDate > '1900-01-01'
        ORDER BY item.SubjectID ASC, item.Title ASC",

        'item-average-list' => "SELECT
            grade.CategoryItemID itemId,
            COUNT(grade.CategoryItemID) itemCount,
            AVG(grade.ScorePoint) averageScore,
            AVG(grade.ScorePoint) / AVG(item.MaxValue) averageRate
        FROM tblBHSOGSGrades grade
        JOIN tblBHSOGSCategoryItems item ON grade.CategoryItemID = item.CategoryItemID
        JOIN tblBHSOGSCourseCategory category ON item.CategoryID = category.CategoryID
        JOIN tblBHSSubject course ON category.SubjectID = course.SubjectID
        WHERE course.SubjectID IN ({subjectid}) AND grade.ScorePoint IS NOT NULL AND grade.Exempted <> 1
        GROUP BY grade.CategoryItemID",

        'insert-activity-record' => "INSERT INTO tblBHSSPStudentActivities
               (SemesterID, ActivityStatus,StudentID,ActivityID,ProgramSource,ActivityCategory, Title, Location, SDate,EDate,Hours,AllDay,DPA,VLWE,ApproverStaffID,CreateUserID,ModifyUserID, VLWESupervisor, SELFComment1, SELFComment2, SELFComment3)
               VALUES
                 (:SemesterId, :ActivityStatus, :studentid, :ActivityID, :ProgramSource, :ActivityCategory, :Title, :Location, :SDate, :EDate, :Hours, :AllDay, :DPA, :VLWE, :ApproverStaffID, :CreateUserID, :ModifyUserID, :VLWESupervisor, :SELFComment1, :SELFComment2, :SELFComment3)
               ",

        'insert-activity-record-v2' => "INSERT INTO tblBHSSPStudentActivities
                (SemesterID, ActivityStatus,StudentID,ActivityID,ProgramSource,ActivityCategory, Title, Location, SDate,EDate,Hours,AllDay,DPA,VLWE,ApproverStaffID,CreateUserID,ModifyUserID)
                VALUES
                  (:SemesterId, :ActivityStatus, :studentid, :ActivityID, :ProgramSource, :ActivityCategory, :Title, :Location, :SDate, :EDate, :Hours, :AllDay, :DPA, :VLWE, :ApproverStaffID, :CreateUserID, :ModifyUserID)
                ",

        'update-activity-record' => "UPDATE tblBHSSPStudentActivities
                                        SET ActivityCategory = :ActivityCategory, Title = :Title, Location = :Location, SDate = :SDate, EDate = :EDate, Hours = :Hours, VLWE = :VLWE, ApproverStaffID = :ApproverStaffID, ModifyUserID = :ModifyUserID, ModifyDate = :ModifyDate, SELFComment1 = :SELFComment1, SELFComment2 = :SELFComment2, SELFComment3 = :SELFComment3
                                      WHERE StudentActivityID = :StudentActivityID",

        'update-career-life' => "UPDATE tblBHSStudentCareerLifePathway
                                    SET SubjectID = :SubjectID, SubjectName = :SubjectName, TeacherID = :TeacherID, ProjectTopic= :ProjectTopic, MentorFName = :MentorFName, MentorLName = :MentorLName, MentorEmail = :MentorEmail, MentorPhone = :MentorPhone, MentorDesc = :MentorDesc, ProjectDesc = :ProjectDesc, ProjectCategory = :ProjectCategory, ModifyUserID = :ModifyUserID, ModifyDate = :ModifyDate
                                  WHERE ProjectID = :ProjectID",

        'approval-list' => "SELECT StaffID, CONCAT(FirstName, ' ' ,LastName) AS FullName, PositionTitle2, Sex
        FROM tblStaff
        WHERE RoleBOGS IN ('10','20','21','30','31','32','40','50') AND CurrentStaff = 'Y' AND StaffID NOT IN ('F2242', 'F2145')
        ORDER BY FirstName ASC",

        'activity-detail' => "SELECT
        category.ActivityCategory categoryCode,
        category.Title categoryTitle,
        category.Body categoryDescription,
        activity.SemesterID termId,
	      semester.SemesterName SemesterName,
        activity.ActivityID activityId,
        activity.Title title,
        activity.Body description,
        activity.ActivityType activityType,
        activity.MeetingPlace meetingPlace,
        activity.StaffID staffId,
        CONCAT(staff.FirstName, ' ', staff.LastName) staffName,
        REPLACE(ISNULL(activity.StaffID2, 'nothing'), ' ', '') staffId2,
        CONCAT(staff2.FirstName, ' ', staff2.LastName) staff2Name,
        activity.Location location,
        activity.BaseHours baseHours,
        CONVERT(CHAR(10), activity.StartDate, 126) startDate,
        CONVERT(CHAR(10), activity.EndDate, 126) endDate,
        activity.AllDay allDay,
        activity.DPA dpa,
        activity.VLWE VLWE,
        ISNULL(activity.CurrentEnrolment,0) curEnroll,
        ISNULL(activity.PendingEnrolment,0) penEnroll,
        ISNULL(activity.MaxEnrolment,0) maxEnroll,
        ISNULL(activity.MaxEnrolment,0) - ISNULL(activity.CurrentEnrolment,0) SubstractNum,
        CASE WHEN activity.StartDate <= GETDATE() THEN 1 ELSE 0 END overdue
        FROM tblBHSSPActivity activity
        LEFT JOIN tblBHSSPActivityConfig category ON activity.ActivityCategory = category.ActivityCategory
	      LEFT JOIN tblBHSSemester semester ON semester.SemesterID = activity.SemesterID
        LEFT JOIN tblStaff staff ON activity.StaffID = staff.StaffID
        LEFT JOIN tblStaff staff2 ON activity.StaffID2 = staff2.StaffID
        WHERE activity.SemesterID='{termId}' AND activity.ActivityID = '{activityId}'",

        'chk-activity-join' => "SELECT COUNT(StudentActivityID) Num
        FROM tblBHSSPStudentActivities
        WHERE StudentID = ? AND SemesterID = ? AND ActivityID = ?",

        'update-pending-enroll' => "UPDATE tblBHSSPActivity SET PendingEnrolment = ISNULL(PendingEnrolment,0)+ 1 WHERE ActivityID = '{activityId}'",


        'get-staff-email-by-id' => "SELECT StaffID
                                      	  ,FirstName
                                      	  ,LastName
                                          ,Email3
                                        FROM tblStaff
                                        WHERE CurrentStaff = 'Y' AND StaffID = ?",

        'find-semesterid-date' => "SELECT TOP 1 SemesterID, SemesterName, StartDate, NextStartDate
        FROM tblBHSSemester
        WHERE ? >= StartDate AND ? < NextStartDate",

        'get-list-career-subject' => "SELECT SubjectID, SubjectName, PName, TeacherID, CONCAT(B.FirstName,' ',B.LastName) AS FullName, Credit, RoomNo FROM
            tblBHSSubject as A
        left join tblStaff as B ON A.TeacherID = B.StaffID
        WHERE
        A.SemesterID = ?
        AND CourseCd IN ('CLE','CLC')",

        'get-career-path' => "SELECT P.*,
                                     CONVERT(char(10), ApprovalDate, 126) ApprovalDate,
                                     CONVERT(char(10), P.CreateDate, 126) CreateDateV,
                                     S.FirstName,
                                     S.LastName,
                                     CASE
                                      WHEN
                                        ISNUMERIC(LEFT(P.CreateUserID,1)) = 0
                                      THEN
                                        CONCAT(S.FirstName, ' ', S.LastName)
                                      ELSE
                                        CONCAT(B.FirstName, ' ', B.LastName)
                                      END AS CreateUserName,
                                     CASE
                                      WHEN
                                        ISNUMERIC(LEFT(P.ModifyUserID,1)) = 0
                                      THEN
                                        CONCAT(S.FirstName, ' ', S.LastName)
                                      ELSE
                                        CONCAT(B.FirstName, ' ', B.LastName)
                                      END AS ModifyUserName
                                FROM tblBHSStudentCareerLifePathway P
                           LEFT JOIN tblStaff S ON P.TeacherID = S.StaffID
                           LEFT JOIN tblBHSStudent B ON P.StudentID = B.StudentID
                               WHERE P.StudentID = ? AND P.SemesterID = ?",

        'get-subject-info' => "SELECT
            course.SemesterID termId,
            studentCourse.StudSubjID studentCourseId,
            studentCourse.StudNum studentId,
            studentCourse.SubjectID courseId,
            course.SubjectName courseName,
    	      course.TeacherID,
            course.CourseCd,
            staff.FirstName staffFName,
	          staff.LastName staffLName
        FROM tblBHSStudentSubject studentCourse
        JOIN tblBHSSubject course ON studentCourse.SubjectID = course.SubjectID
        JOIN tblStaff staff ON course.TeacherID = staff.StaffID
        WHERE course.SemesterID=? AND studentCourse.StudNum=? AND studentCourse.SubjectID = ?",

        'insert-career-record' => "INSERT INTO tblBHSStudentCareerLifePathway
        (StudSubjID,StudentID,SubjectID,SubjectName,TeacherID,CourseCd,SemesterID,ProjectTopic,MentorFName,MentorLName,MentorDesc,MentorEmail,MentorPhone,ProjectDesc,ProjectCategory,StudentComment,TeacherComment,ApprovalStatus,ModifyUserID,CreateUserID)
        VALUES
        (:StudSubjID,:StudentID,:SubjectID,:SubjectName,:TeacherID,:CourseCd,:SemesterID,:ProjectTopic,:MentorFName,:MentorLName,:MentorDesc,:MentorEmail,:MentorPhone,:ProjectDesc,:ProjectCategory,:StudentComment,:TeacherComment,:ApprovalStatus,:ModifyUserID,:CreateUserID)",

        'num-row-self-submit' => "SELECT COUNT(StudentActivityID) as num FROM tblBHSSPStudentActivities
        WHERE StudentID = :studentid AND SemesterID = :SemesterId AND ProgramSource = :ProgramSource
        AND Title = :Title AND Location = :Location AND SDate = :SDate AND Hours = :Hours
        AND ApproverStaffID = :ApproverStaffID AND CONVERT(CHAR(10), CreateDate, 126) = :CreateDate
        ",

        'insert-user-auth-log' => "INSERT INTO tblBHSUserAuthLog(Username, UserCategory, StudentID, AppSystem, UserIPAddress, InternalStaff, StaffID, CreateDate)
        VALUES('{Username}', '{UserCategory}', '{StudentID}', '{AppSystem}', '{UserIPAddress}', '{InternalStaff}', '{StaffID}', '{CreateDate}')",

        'course-aep-list' => "SELECT B.SubjectName, A.*
        FROM tblBHSStudentSubject A
        LEFT JOIN tblBHSSubject B ON B.SubjectID = A.SubjectID
        WHERE A.StudNum = ? AND B.SemesterID = ?
        AND B.SubjectName LIKE '%AEP%' ORDER BY B.SubjectName ASC",

        'iap-rubric' => "SELECT B.FirstName, B.LastName, B.EnglishName, A.* FROM tblBHSAPLPRubric A
        LEFT JOIN tblBHSStudent B ON A.StudentID = B.StudentID WHERE A.StudentID = ? AND A.SemesterID = ?",

        'semester-list-assessments' => "SELECT a.AssessmentID, a.SemesterID, a.Title, s.SemesterName
        FROM tblBHSAssessmentMain a
        LEFT JOIN tblBHSSemester s ON s.SemesterID = a.SemesterID
        ORDER BY a.SemesterID DESC",

        'assessments-score' => "SELECT r.*, a.Title, a.SemesterID, s.SemesterName, CONVERT(CHAR(10), a.DateFrom, 126) DateFrom
        FROM tblBHSAssessmentEPAResult r
	      LEFT JOIN tblBHSAssessmentMain a on a.AssessmentID = r.AssessmentID
        LEFT JOIN tblBHSSemester s ON s.SemesterID = a.SemesterID
        WHERE r.StudentID = ?
        ORDER BY r.AssessmentID DESC",

        'check-dob' => "SELECT StudentID,
      		CONVERT(char(10), DOB, 126) as DOB,
      		CurrentStudent,
      		SchoolEmail
        FROM tblBHSStudent
        where StudentID = ?",

        'add-request-reset-school-email' => "INSERT INTO tblBHSResetSchoolEmail
         (StudentID, SchoolEmail, PersonalEmail, Status, ModifyUserID, CreateUserID)
         VALUES
           (:StudentID, :SchoolEmail, :PersonalEmail, :Status, :ModifyUserID, :CreateUserID)",

       'add-request-reset-school-email-optional-sid' => "INSERT INTO tblBHSResetSchoolEmail
        (StudentID, SchoolEmail, PersonalEmail, sCountry, sCity, sFirstName, sLastName, sDOB, sDateTime, sPhoneNumber, translation, Counsellor, Status, ModifyUserID, CreateUserID)
        VALUES
          ('{StudentID}', '{SchoolEmail}', '{PersonalEmail}', '{sCountry}'
            , '{sCity}', '{sFirstName}', '{sLastName}', '{sDOB}', '{sDateTime}', '{sPhoneNumber}', '{translation}', '{sCounsellor}'
            , '{Status}', '{ModifyUserID}', '{CreateUserID}')",

      'staff-list-by-rolebogs' => "SELECT FirstName,LastName, StaffID, Email3, PositionTitle2, Sex
      FROM tblStaff
      WHERE (RoleBOGS IN ({Rolebogs}) AND CurrentStaff = 'Y') OR RoleSys = '30' OR StaffID = 'F2246'
      ORDER BY FirstName ASC",

      'country-list' => "SELECT CID, CName FROM tblCountry WHERE CCode != 'Y' ORDER BY CName ASC",

      'search-student-list' => "SELECT StudentID,
      FirstName,
      LastName,
      EnglishName,
      CurrentStudent,
      CASE
        WHEN CurrentStudent = 'Y' THEN 'Current'
        WHEN CurrentStudent = 'N' THEN 'Not Current'
        ELSE 'Error'
      END as 'CurrentStatus'
      FROM tblBHSStudent
      WHERE CONCAT(CONCAT(FirstName,' ',LastName), ' ', EnglishName) LIKE ?
      AND SchoolID = 'BHS' AND StudentID >= 201500001
      ORDER BY CurrentStudent DESC, FirstName ASC",

      'insert-bhs-return-plan' =>"INSERT INTO tblBHSReturnDevices
       (StudentID,ReturnDevices,ReturnOptions,AuthDate,AuthUser,ModifyUserID,CreateUserID)
       VALUES
         ('{StudentID}','{ReturnDevices}','{ReturnOptions}','{AuthDate}','{AuthUser}','{ModifyUserID}','{CreateUserID}')",

      'search-bhs-return-plan' => "SELECT StudentID FROM tblBHSReturnDevices WHERE StudentID = ?",

      'insert-leave-request-form' => "INSERT INTO tblBHSStudentLeaveRequest
      (LeaveType, StudentID, SDate, EDate, Reason, Comment, ToDo, ApprovalStaff, LeaveTime, LeaveStatus, ModifyUserID, CreateUserID)
      VALUES ('{LeaveType}', '{StudentID}', '{SDate}', '{EDate}', '{Reason}', '{Comment}', '{ToDo}', '{ApprovalStaff}', '{LeaveTime}', '{LeaveStatus}', '{ModifyUserID}', '{CreateUserID}')",

      'get-student-leave-request' => "SELECT L.LeaveType, CONVERT(varchar, L.SDate, 100) SDate, CONVERT(varchar, L.EDate, 100) EDate, L.Reason, L.LeaveStatus, CONCAT(S.FirstName, ' ', S.LastName) AS StaffFullName FROM tblBHSStudentLeaveRequest L
      LEFT JOIN tblStaff S ON L.ApprovalStaff = S.StaffID
      WHERE StudentID = ?
      ORDER BY L.SDate DESC",

      'get-self-assessment-form' => "SELECT * FROM tblBHSAssessmentForm WHERE Grade LIKE '%{Grade}%' AND SemesterID = '{SemesterID}'",

      // 'insert-assessment' => "INSERT INTO tblBHSStudentAssessment (AssessmentFormID,StudentID,CurrentGrade,CommunicationText, ThinkingText, PersonalText, ModifyUserID, CreateUserID)
      //     VALUES(:AssessmentFormID, :StudentID, :CurrentGrade, :CommunicationText, :ThinkingText, :PersonalText, :ModifyUserID, :CreateUserID)",

      'insert-assessment' => "INSERT INTO tblBHSStudentAssessment (AssessmentFormID,StudentID, CurrentGrade, CommunicationText, ThinkingText, PersonalText, CommunicationRate, ThinkingRate, PersonalRate, ModifyUserID, CreateUserID)
          VALUES(:AssessmentFormID, :StudentID, :CurrentGrade, :CommunicationText, :ThinkingText, :PersonalText, :CommunicationRate, :ThinkingRate, :PersonalRate, :ModifyUserID,:CreateUserID)",

      'update-assessment' => "UPDATE tblBHSStudentAssessment SET AssessmentFormID = :AssessmentFormID,
      CommunicationText = :CommunicationText, ThinkingText = :ThinkingText, PersonalText = :PersonalText,
      CommunicationRate = :CommunicationRate, ThinkingRate = :ThinkingRate, PersonalRate = :PersonalRate,
      ModifyUserID = :ModifyUserID, ModifyDate = :ModifyDate
       WHERE StudentID = :StudentID AND AssessmentID = :AssessmentID",

      'get-assessment' => "SELECT S.*, F.Grade FROM tblBHSStudentAssessment S
        LEFT JOIN tblBHSAssessmentForm F ON F.AssessmentFormID = S.AssessmentFormID
         WHERE S.AssessmentFormID = :AssessmentFormID AND S.StudentID = :StudentID",

      'get-student-leave-ban' => "SELECT * FROM tblBHSStudentLeaveBan
  WHERE ((FromDate < '{FromDate}' AND ToDate > '{FromDate}')
    OR
(FromDate < '{ToDate}' AND ToDate > '{ToDate}')) AND StudentID = '{StudentID}' AND Status = 'A'",

      'get-transcript-request' => "SELECT CONVERT(char(10), CreateDate, 126) CreateDate, CONVERT(char(10), RequestDate, 126) RequestDate, ApplyTo, SchoolName, Paid, Status FROM tblBHSTranscriptRequest WHERE CreateUserID = :CreateUserID ORDER BY RequestDate DESC",

      'add-transcript-request' => "INSERT INTO tblBHSTranscriptRequest (RequestDate, CopyType ,Deadline, ApplyTo,ApplicationID,PhysicalCopy,StudentID,SchoolName
      ,Address,MailingMethod,Paid,Status,ModifyUserID,CreateUserID) VALUES(:RequestDate, :CopyType,:Deadline,:ApplyTo, :ApplicationID,:PhysicalCopy,:StudentID,:SchoolName
      ,:Address,:MailingMethod,:Paid,:Status,:ModifyUserID,:CreateUserID)",

      'get-report-card' => "SELECT C.SemesterName,
      FORMAT(C.StartDate, 'MMMM yyyy') StartDate,
	    FORMAT(C.MidCutOffDate, 'MMMM yyyy') MidCutOffDate,
	    FORMAT(C.EndDate, 'MMMM yyyy') EndDate,
      B.*,
      A.*,
      D.FirstName,
      D.LastName
      FROM tblbhsstudentsubject A
      LEFT JOIN tblBHSSubject B ON B.SubjectID = A.SubjectID
      LEFT JOIN tblBHSSemester C ON C.SemesterID = B.SemesterID
      LEFT JOIN tblStaff D ON D.StaffID = B.TeacherID
      WHERE A.StudNum = :StudentID
      AND C.SemesterID = :SemesterID
      AND NOT B.SubjectName LIKE 'YYY%' ORDER BY B.SubjectName",

      'get-report-card-summary' => "SELECT C.SemesterName,
      FORMAT(C.StartDate, 'MMMM yyyy') StartDate,
	    FORMAT(C.MidCutOffDate, 'MMMM yyyy') MidCutOffDate,
	    FORMAT(C.EndDate, 'MMMM yyyy') EndDate,
      B.*,
      A.*,
      D.FirstName,
      D.LastName
      FROM tblbhsstudentsubject A
      LEFT JOIN tblBHSSubject B ON B.SubjectID = A.SubjectID
      LEFT JOIN tblBHSSemester C ON C.SemesterID = B.SemesterID
      LEFT JOIN tblStaff D ON D.StaffID = B.TeacherID
      WHERE A.StudNum = :StudentID
      AND C.SemesterID = :SemesterID
      AND NOT B.SubjectName LIKE 'YYY%' ORDER BY B.SubjectName",

      'get-outstanding-fee' => "SELECT *
      FROM tblBHSOutstanding
      WHERE StudentID = :StudentID AND FeeType = :FeeType AND Amount > :Amount AND (StartDate IS NULL OR StartDate <= CONVERT(DATETIME, GETDATE(), 102))",

      'get-report-card-semester' => "SELECT C.SemesterID,C.SemesterName FROM tblbhsstudentsubject A
      LEFT JOIN tblBHSSubject B ON B.SubjectID = A.SubjectID
      LEFT JOIN tblBHSSemester C ON C.SemesterID = B.SemesterID
      LEFT JOIN tblStaff D ON D.StaffID = B.TeacherID
      WHERE A.StudNum = :StudentID AND C.SemesterID > 78 AND C.MidCutOffDate <= GETDATE() AND GradeMidterm != 0 group by C.SemesterID, C.SemesterName ORDER BY C.SemesterID ASC",


  );



?>
