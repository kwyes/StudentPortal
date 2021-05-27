var globalAbsense;
var globalApprovalList
var myParticirationResponse;
var careerSubjectList;
var career;
var reportResponse;

function ajaxtologin() {
  var userID = $('#userID').val();
  var userPW = $('#userPW').val();
  if (userID == '' || userPW == '') {
    alert("Enter User Name and Password");
    return;
  }
  $.ajax({
    url: 'ajax_php/a.login.php',
    type: 'POST',
    dataType: "json",
    data: {
      "userID": userID,
      "userPW": userPW
    },
    success: function (response) {
      console.log(response);
      if (response[0]['status'] == 0) {
        location.href = "?page=dashboard";
        // console.log(response[0]);
      } else {
        alert("You put Wrong Id or Wrong PW");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function ajaxtostudentinfo(StartDate, NextStartDate) {
  $.ajax({
    url: 'ajax_php/a.studentInfo.php',
    type: 'POST',
    async: false,
    dataType: "json",
    success: function (response) {
      console.log(response);
      var fullName;
      if (response[0].englishName) {
        fullName = response[0].lastName + ', ' + response[0].firstName + ' (' + response[0].englishName + ')';
      } else {
        fullName = response[0].lastName + ', ' + response[0].firstName;
      }
      $('.dashboard-fullName').html(fullName);
      $('#student-inf .dashboard-studentId').html(response[0].studentId);
      var imgsrc = response[0].imgsrc;
      $('.dashboard-userPic').attr("src", imgsrc);
      $('#student-inf .dashboard-pen').html(response[0].pen);
      $('#student-inf .dashboard-grade').html(response[0].currentGrade);
      $('#student-inf .dashboard-Counsellor').html(response[0].counsellor);
      var location = chkLocation(response[0].residence, response[0].homestay);
      $('#student-inf .dashboard-location').html(location);
      $('#student-inf .dashboard-house').html(response[0].houses);
      $('#student-inf .dashboard-hall').html(response[0].halls);
      $('#student-inf .dashboard-room').html(response[0].roomNo);
      $('#student-inf .dashboard-advisor1').html(response[0].youthAdvisor);
      $('#student-inf .dashboard-advisor2').html(response[0].youthAdvisor2);
      $('#student-inf .dashboard-mentor').html(response[0].mentor);
      $('#student-inf .dashboard-terms').html(response[0].numTerms);
      $('#student-inf .dashboard-aepTerms').html(response[0].numOfAepTerm);
      ajaxtoEnrollTerm(response[0].EnrollmentDate);
      ajaxtoMyParticipation(StartDate, NextStartDate, response[0].EnrollmentDate);

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}


function ajaxtoCurrentTerm() {
  $.ajax({
    url: 'ajax_php/a.CurrentTerm.php',
    type: 'POST',
    dataType: "json",
    async: true,
    success: function (response) {
      console.log(response);
      $('.dashboard-startDate').html(response[0].StartDate);
      $('.dashboard-midCutoff').html(response[0].MidCutOffDate);
      $('.dashboard-endDate').html(response[0].EndDate);
      $('.dashboard-CurrentTerm').html(response[0].SemesterName);
      $('.dashboard-semesterName').html(response[0].SemesterName);
      $('.dashboard-termstatus').html(response['text']);

      var EndDate = new Date(response[0].EndDate).getTime()
      var StartDate = new Date(response[0].StartDate).getTime()
      var Today = new Date().getTime();
      var Total = EndDate - StartDate;
      var per = EndDate - Today;
      var param = per / Total * 100;
      makeProgress(param.toFixed(2));

      ajaxtostudentinfo(response[0].StartDate, response[0].NextStartDate);
      GetReportCardSummary(response[0].SemesterID);
      ajaxtoMyCourses(response[0].SemesterID);
      ajaxtoMyGrades();
      // ajaxtoMyParticipation(response[0].SemesterID);
      // ajaxtoMyParticipation();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function ajaxtoMyAbsenseLate(globalAbsense) {
  // console.log(globalAbsense);
  if (globalAbsense) {
    var response = globalAbsense['Absense'];
    for (var i = 0; i < response.length; i++) {
      var studentCourseId = response[i].studentCourseId;
      var absenceCount = response[i].absenceCount;
      var lateCount = response[i].lateCount;

      $('.' + studentCourseId + '-absense').html(absenceCount);
      $('.' + studentCourseId + '-late').html(lateCount);
    }
  }
}


function ajaxtoMyGrades() {
  console.log(reportResponse);
  $.ajax({
    url: 'ajax_php/a.MyGrades.php',
    type: 'POST',
    async: true,
    dataType: "json",
    success: function (response) {
      var fr = '';
      var fl = '';
      var discretionarymarks = '';
      var discretionarymarksinfo = '';
      if(reportResponse) {
        for (var i = 0; i < response.length; i++) {

          var courseId = response[i].courseId;
          var courseRateScaled = response[i].courseRateScaled;
          var finalRate = parseFloat((courseRateScaled * 100));
          var finalLetter = getGradeLetter(courseRateScaled);
          var reportFinalRate = (Math.round(reportResponse[courseId].GradeFinal * 100) / 100).toFixed(1);
          var reportFinalLetter = reportResponse[courseId].LtrGradeFinal;

          if(reportFinalRate >= finalRate + 1) {
            fr = reportFinalRate + '%' + " <span style='color:red;'>*</span>";
            fl = reportFinalLetter + " <span style='color:red;'>*</span>";
            discretionarymarks = '*';
            discretionarymarksinfo = "<span style='color:red;'>*</span> Discretionary mark was given by the teacher based on the student's overall effort in the course";
          } else {
            fr = finalRate.toFixed(1) + '%';
            fl = finalLetter;
            discretionarymarks = '';
          }
          $('.discretionarymarks-info').html(discretionarymarksinfo);
          // $('.' + courseId + '-discretionarymarks').html(discretionarymarks);


          $('.' + courseId + '-p').html(fr);
          $('.' + courseId + '-l').html(fl);

          $('.' + courseId + '-p').removeClass('naText');
          $('.' + courseId + '-l').removeClass('naText');

        }
      } else {
        for (var i = 0; i < response.length; i++) {
          var courseId = response[i].courseId;
          var courseRateScaled = response[i].courseRateScaled;
          var finalRate = (courseRateScaled * 100).toFixed(1) + '%';
          var finalLetter = getGradeLetter(courseRateScaled);

          $('.' + courseId + '-p').html(finalRate);
          $('.' + courseId + '-l').html(finalLetter);

          $('.' + courseId + '-p').removeClass('naText');
          $('.' + courseId + '-l').removeClass('naText');
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}


function ajaxtoMyCourses(SemesterID) {
  $.ajax({
    url: 'ajax_php/a.MyCourses.php',
    type: 'POST',
    async: false,
    dataType: "json",
    success: function (response) {
      // console.log(response);
      var tr;
      var careerChk;
      var responseCourses = response['courses'];
      for (var i = 0; i < responseCourses.length; i++) {
        var courseType = responseCourses[i].courseType;
        var courseId = responseCourses[i].courseId;
        var studentCourseId = responseCourses[i].studentCourseId;
        var courseName = responseCourses[i].courseName;
        var courseCd = responseCourses[i].CourseCd;
        courseName = courseName.replace('YYY', '');
        courseName = courseName.replace('ZZZ', '');
        var credit = responseCourses[i].credit;
        var teacherName = responseCourses[i].teacherName;
        var roomNo = responseCourses[i].roomNo;
        var pGrade = 'n/a';
        var lGrade = 'n/a';
        var late = '';
        var absense = '';
        var trClass;
        if (courseType.toUpperCase() != 'P' && courseType.toUpperCase() != 'N') {
          trClass = 'tr-nonCredit';
        } else {
          trClass = 'tr-Credit';
        }
        if (!careerChk) {
          if (courseCd == 'CLC' || courseCd == 'CLE') {
            careerChk = 'C';
            $('.span-' + courseCd + '-coursename').html(courseName);
            $('.dashboard-' + courseCd + '-teacher').html(teacherName);
            $('.dashboard-' + courseCd + '-teacher').css("color", '#252422');
            $('.dashboard-' + courseCd + '-teacher').css("font-style", 'normal');
            $('#hidden-courseId').val(courseId);
          }
        }
        tr = '<tr class="' + trClass + '"><td class="text-left">' + courseName + '</td><td class="' + courseId + '-p naText">' + pGrade + '</td><td class="' + courseId + '-l naText">' + lGrade + '</td><td>' + credit + '</td><td class="text-left">' + teacherName + '</td><td>' + roomNo + '</td><td class="' + studentCourseId + '-late">' + late + '</td><td class="' + studentCourseId + '-absense">' + absense + '</td></tr>';
        $('#dashboard-mycourses tbody').append(tr);
      }
      ajaxtoMyAbsenseLate(response);
      if (careerChk == 'C') {
        ajaxtoMyCareerLife(SemesterID);
      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
  // only type p OR N = credit. except p Or N all non credit
}

function ajaxtoMyCareerLife(SemesterID) {
  $('#display-career-card').show();
  ajaxToGetListCareerSubejct(SemesterID);
  $.ajax({
    url: 'ajax_php/a.GetCareerPath.php',
    type: 'POST',
    data: {
      "SemesterID": SemesterID
    },
    dataType: "json",
    success: function (response) {
      console.log(response['data']);
      if (response.result == 0) {
        console.log("IT")
      } else {
        var CLEtr;
        var CLCtr;
        var errtr;
        career = response['data'];
        for (var i = 0; i < career.length; i++) {
          if (career[i].CourseCd == 'CLE') {
            CLEtr += '<tr>' +
              '<td><a href="#careerLifeModal" data-toggle="modal" class="career-link-CLE" data-id="' + career[i].ProjectID + '">CLE<br /></a>' +
              '<span style="font-size: 10px"><a href="#careerLifeModal" data-toggle="modal" class="career-link-CLE" data-id="' + career[i].ProjectID + '">' + career[i].SubjectName + '</a></span>' +
              '</td>' +
              '<td class="dashboard-CLE-teacher showTooltip" data-toggle="tooltip">' + career[i].FirstName + " " + career[i].LastName + '</td>' +
              '<td class="dashboard-CLE-topic text-left showTooltip" data-toggle="tooltip">' + career[i].ProjectTopic + '</td>' +
              '<td class="dashboard-CLE-guide showTooltip" data-toggle="tooltip">' + career[i].MentorFName + " " + career[i].MentorLName + '</td>' +
              '<td class="dashboard-CLE-date">' + career[i].CreateDateV + '</td>' +
              '<td class="dashboard-CLE-status">' + GetStatusIcon(career[i].ApprovalStatus) + '</td>' +
              '</tr>';
          } else if (career[i].CourseCd == 'CLC') {
            CLCtr += '<tr>' +
              '<td><a href="#careerLifeModal" data-toggle="modal" class="career-link-CLC" data-id="' + career[i].ProjectID + '">CLC/Capstone<br /></a>' +
              '<span style="font-size: 10px"><a href="#careerLifeModal" data-toggle="modal" class="career-link-CLC" data-id="' + career[i].ProjectID + '">' + career[i].SubjectName + '</a></span>' +
              '</td>' +
              '<td class="dashboard-CLC-teacher showTooltip" data-toggle="tooltip">' + career[i].FirstName + " " + career[i].LastName + '</td>' +
              '<td class="dashboard-CLC-topic text-left showTooltip" data-toggle="tooltip">' + career[i].ProjectTopic + '</td>' +
              '<td class="dashboard-CLC-guide showTooltip" data-toggle="tooltip">' + career[i].MentorFName + " " + career[i].MentorLName + '</td>' +
              '<td class="dashboard-CLC-date">' + career[i].CreateDateV + '</td>' +
              '<td class="dashboard-CLC-status">' + GetStatusIcon(career[i].ApprovalStatus) + '</td>' +
              '</tr>';
          } else {
            errtr += '<tr><td colspan="6">Err</td></tr>';
          }

        }
        var html;
        if (CLEtr) {
          html += CLEtr
        } else {
          html += '<tr>' +
            '<td>CLE<br />' +
            '<span style="font-size: 10px">Career Life Education</span>' +
            '</td>' +
            '<td class="dashboard-CLE-teacher italic-grey">Not Submitted</td>' +
            '<td class="dashboard-CLE-topic italic-grey">Not Submitted</td>' +
            '<td class="dashboard-CLE-guide italic-grey">Not Submitted</td>' +
            '<td class="dashboard-CLE-date italic-grey">Not Submitted</td>' +
            '<td class="dashboard-CLE-status italic-grey">Not Submitted</td>' +
            '</tr>';
        }

        if (CLCtr) {
          html += CLCtr
        } else {
          html += '<tr>' +
            '<td>CLC/Capstone<br />' +
            '<span style="font-size: 10px">Career Life Connections</span>' +
            '</td>' +
            '<td class="dashboard-CLC-teacher italic-grey">Not Submitted</td>' +
            '<td class="dashboard-CLC-topic italic-grey">Not Submitted</td>' +
            '<td class="dashboard-CLC-guide italic-grey">Not Submitted</td>' +
            '<td class="dashboard-CLC-date italic-grey">Not Submitted</td>' +
            '<td class="dashboard-CLC-status italic-grey">Not Submitted</td>' +
            '</tr>';
        }

        if (errtr) {
          html = errtr;
        }
        // console.log(html);
        $('#dashboard-Career tbody').html(html);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}


function ajaxToGetListCareerSubejct(SemesterID) {
  $.ajax({
    url: 'ajax_php/a.getListCareerSubject.php',
    type: 'POST',
    data: {
      "SemesterID": SemesterID
    },
    dataType: "json",
    success: function (response) {
      if (response.result == 0) {
        console.log("IT")
      } else {
        careerSubjectList = response['data'];
        $('#career-course').append('<option value="">Select..</option>');
        for (var i = 0; i < careerSubjectList.length; i++) {
          let TeacherID = careerSubjectList[i].TeacherID;
          let FullName = careerSubjectList[i].FullName;
          let SubjectID = careerSubjectList[i].SubjectID;
          let SubjectName = careerSubjectList[i].SubjectName;
          $('#career-course').append('<option value="' + SubjectID + '">' + SubjectName + ' (' + FullName + ')' + '</option>');
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function ajaxtoMyParticipation(StartDate, NextStartDate, EnrollmentDate) {
  $.ajax({
    url: 'ajax_php/a.ParticipationHours.php',
    type: 'POST',
    dataType: "json",
    success: function (response) {
      console.log(response);
      var currentAISD = 0;
      var currentACLE = 0;
      var currentCILE = 0;
      var currentPORE = 0;
      var currentVLWE = 0;
      var accumAISD = 0;
      var accumACLE = 0;
      var accumCILE = 0;
      var accumPORE = 0;
      var accumVLWE = 0;
      var currenthours = 0;
      var accumhours = 0;

      var dt = new Date(EnrollmentDate);
      dt.setDate(dt.getDate() - 1);
      var dateString = new Date(dt.getTime() - (dt.getTimezoneOffset() * 60000))
        .toISOString()
        .split("T")[0];
      console.log(dateString);
      for (var i = 0; i < response.length; i++) {
        var category = response[i].category;
        var termId = response[i].termId;
        var vlwe = response[i].VLWE;
        var hours = parseFloat(response[i].hours);
        var activityDate = response[i].activityDate;
        if (activityDate >= dateString) {
          if (category == '10') {
            accumPORE += hours;
          } else if (category == '11') {
            accumAISD += hours;
          } else if (category == '12') {
            accumCILE += hours;
          } else if (category == '13') {
            accumACLE += hours;
          }
          if (vlwe == 1) {
            accumVLWE += hours;
          }
        }

        // if (termId == SemesterID) {
        if (activityDate >= StartDate && NextStartDate >= activityDate) {
          if (vlwe == 1) {
            currentVLWE += hours;
          }
          if (category == '10') {
            currentPORE += hours;
          } else if (category == '11') {
            currentAISD += hours;
          } else if (category == '12') {
            currentCILE += hours;
          } else if (category == '13') {
            currentACLE += hours;
          }
        }
      }
      // accumCILE += accumVLWE;
      // currentCILE += currentVLWE;
      currenthours = currentPORE + currentAISD + currentCILE + currentACLE;
      accumhours = accumPORE + accumAISD + accumCILE + accumACLE;
      $('.dashboard-PORE-C').html(currentPORE.toFixed(1));
      $('.dashboard-AISD-C').html(currentAISD.toFixed(1));
      $('.dashboard-CILE-C').html(currentCILE.toFixed(1));
      $('.dashboard-ACLE-C').html(currentACLE.toFixed(1));
      $('.dashboard-TOTAL-C').html(currenthours.toFixed(1));
      $('.dashboard-VLWE-C').html(currentVLWE.toFixed(1));

      $('.dashboard-PORE-A').html(accumPORE.toFixed(1));
      $('.dashboard-AISD-A').html(accumAISD.toFixed(1));
      $('.dashboard-CILE-A').html(accumCILE.toFixed(1));
      $('.dashboard-ACLE-A').html(accumACLE.toFixed(1));
      $('.dashboard-TOTAL-A').html(accumhours.toFixed(1));
      $('.dashboard-VLWE-A').html(accumVLWE.toFixed(1));

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function ajaxtoEnrollTerm(EnrollmentDate) {
  $.ajax({
    url: 'ajax_php/a.EnrollTerm.php',
    type: 'POST',
    dataType: "json",
    data: {
      "EnrollmentDate": EnrollmentDate
    },
    success: function (response) {
      var EnrollTerm = getEnrollmentTerm(response[0].SemesterID, response[0].SemesterName);
      $('.dashboard-EnrollmetTermName').html(EnrollTerm);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function showGradeBySubject() {
  var table = $('#datatable-gradebook').DataTable();
  var html = "";
  var eData = [];
  var tData = [];
  $.ajax({
    url: 'ajax_php/a.GetGradeBySubject.php',
    type: 'POST',
    dataType: "json",
    async: true,
    success: function (response) {
      var scorePoint;
      var scoreRate;
      var exempted;
      var scoreTxt;
      var overdue;
      console.log(response.length);
      if (response.length > 0) {
        $('.gradebook-currentterm').html(response['grade'][0].SemesterName);
      }

      if (response.result == 0) {
        console.log("IT")
      } else {
        table.clear();
        // ajaxtoAvgScore(response['courseId']);
        console.log(response);
        var chk;
        var breakCheck1 = false;

        for (var i = 0; i < response['grade'].length; i++) {
          scorePoint = response['grade'][i].scorePoint;
          exempted = response['grade'][i].exempted;
          overdue = response['grade'][i].overdue;
          scoreRate = response['grade'][i].scoreRate * 100;
          scoreRate = scoreRate.toFixed(1) + '%';
          if (exempted == 1) {
            scoreTxt = 'Exempted';
          } else {
            if (scorePoint == null) {
              if (overdue == 1) {
                scoreTxt = 'Overdue';
              } else {
                scoreTxt = 'Pending';
              }
            } else {
              scorePoint = parseFloat(scorePoint).toFixed(1);
              scoreTxt = scorePoint + ' (' + scoreRate + ')';
            }
          }
          var ItemId = response['grade'][i].itemId;
          for (var s = 0; s < response['Avg'].length; s++) {
            if (response['Avg'][s].itemId == ItemId) {
              AvgScore = response['Avg'][s].averageScore;
              breakCheck1 = true;
              break;
            } else {
              AvgScore = 0;
            }
          }
          AvgScore = (Math.round(AvgScore * 10) / 10).toFixed(1);

          if (breakCheck1) {
            if ($(window).width() < 480) {
              eData.push([response['grade'][i].categoryTitle.trim(),
                response['grade'][i].itemTitle,
                response['grade'][i].assignDate,
                scoreTxt,
                response['grade'][i].maxScore.slice(0, -1),
                AvgScore,
                response['grade'][i].comment,
                response['grade'][i].SubjectName,
                response['grade'][i].courseId,
                response['grade'][i].categoryLabel
              ]);

              $('#datatable-gradebook thead').html(
                '<tr>' +
                '<th class="gradebook-category">Category</th>' +
                '<th>Item Name</th>' +
                '<th>Assigned<br /> Date</th>' +
                '<th>Score</th>' +
                '<th>Out Of</th>' +
                '<th>Class<br /> Average</th>' +
                '<th>Comment</th>' +
                '<th class="gradebook-subject">SUBJECT</th>' +
                '<th class="gradebook-subjectId">categoryId</th>' +
                '</tr>'
              )
            } else {
              eData.push([response['grade'][i].assignDate,
                response['grade'][i].categoryTitle.trim(),
                response['grade'][i].itemTitle,
                scoreTxt,
                response['grade'][i].maxScore.slice(0, -1),
                AvgScore,
                response['grade'][i].comment,
                response['grade'][i].SubjectName,
                response['grade'][i].courseId,
                response['grade'][i].categoryLabel
              ]);

              $('#datatable-gradebook thead').html(
                '<tr>' +
                '<th>Assigned<br /> Date</th>' +
                '<th class="gradebook-category">Category</th>' +
                '<th>Item Name</th>' +
                '<th>Score</th>' +
                '<th>Out Of</th>' +
                '<th>Class<br /> Average</th>' +
                '<th>Comment</th>' +
                '<th class="gradebook-subject">SUBJECT</th>' +
                '<th class="gradebook-subjectId">categoryId</th>' +
                '</tr>'
              )
            }


            tData.push([response['grade'][i].courseId,
              response['grade'][i].roomNo,
              response['grade'][i].teacherName
            ]);

          }
        }

        table = $('#datatable-gradebook').DataTable({
          data: eData,
          deferRender: true,
          bDestroy: true,
          autoWidth: false,
          // info: false,
          pagingType: "simple_numbers",
          oLanguage: {
            "sEmptyTable": "No data available"
          },
          language: {
            paginate: {
              next: '<i class="nc-icon nc-minimal-right">', // or '→'
              previous: '<i class="nc-icon nc-minimal-left">', // or '←'
              // emptyTable: "No data available"
            }
          },
          order: [
            [0, "desc"]
          ],
          lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
          ],
          columnDefs: [{
              targets: 0,
              // width: "12%"
              width: "185px"
            },
            {
              targets: 1,
              // width: "18%"
              width: "273px"

            },
            {
              targets: 2,
              // width: "18%"
              width: "273px"
            },
            {
              targets: 3,
              // width: "11%"
              width: "150px"
            },
            {
              targets: 4,
              // width: "11%"
              width: "138px"
            },
            {
              targets: 5,
              // width: "12%"
              width: "153px"
            },
            {
              targets: 6,
              // width: "18%"
              width: "368px"
            },
            {
              visible: false,
              targets: 7
            },
            {
              visible: false,
              targets: 8
            },
            {
              visible: false,
              targets: 9
            }
          ],
          responsive: true,
          // scrollX: true
        });
        var select = $(
            '<select class="select-category" id="gradebook-select-subject"></select>'
          )
          .prependTo($('#datatable-gradebook_filter'));
        createFilter(table, select);
        // $('#datatable-gradebook_filter').html('');
        var val2 = $('#gradebook-select-subject').val();
        table.column(8)
          .search(val2 ? '^' + val2 + '$' : '', true, false)
          .draw();
        var FullClassInfo2 = findClassInfo(tData, val2);
        $('.gradebook-classinfo').html(FullClassInfo2);
        prependToCategory('e');

        table.columns().every(function (index) {

          if (index == 8) {
            var that = this;

            select.on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );
              var FullClassInfo = findClassInfo(tData, val);
              $('.gradebook-classinfo').html(FullClassInfo);

              that
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();

              if (val == '') {
                $('.gradebook-classinfo').html('');
                prependToCategory('e');
              } else {
                prependToCategory('f');
              }

            });

          }
        });

        $('#datatable-gradebook_length').parent().removeClass('col-md-6');
        $('#datatable-gradebook_length').parent().addClass('col-md-4');
        $('#datatable-gradebook_filter').parent().removeClass('col-md-6');
        $('#datatable-gradebook_filter').parent().addClass('col-md-8');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function createFilter(table, select) {
  var sVal = [];
  var rVal = [];
  for (var i = 7; i < 9; i++) {
    table.column(i)
      .cache('search')
      .unique()
      .each(function (d) {
        if (i == 8) {
          sVal.push(d);
        } else {
          rVal.push(d);
        }
      });
  }
  for (var j = 0; j < sVal.length; j++) {
    select.append($('<option value="' + sVal[j] + '">' + rVal[j] + '</option>'));
  }
}

function createFilterV2(table, select, num1, num2, way) {
  var sVal = [];
  var rVal = [];
  var result = [];
  var s = 0;
  var t = 0;
  var final;
  for (var i = num1; i <= num2; i++) {
    table.column(i)
      .cache('search')
      .unique()
      .each(function (d) {
        if (i == num2) {
          sVal.push(d);
          sVal[s] = {
            id: d
          };
          s++;
        } else {
          rVal[t] = {
            text: d
          };
          t++;
        }
      });
  }

  for (var w = 0; w < sVal.length; w++) {
    result[w] = {
      id: sVal[w].id,
      text: rVal[w].text
    }
  }
  if (way == 'asc') {
    final = result.slice(0);
    final.sort(function (a, b) {
      return a.id - b.id;
    });

  } else {
    final = result;
  }

  console.log(final);
  for (var j = 0; j < final.length; j++) {
    select.append($('<option value="' + final[j].id + '">' + final[j].text + '</option>'));
  }
}

function prependToCategory(chk) {
  var select2;
  if ($("#gradebook-select-category").length) {
    select2 = $('#gradebook-select-category');
  } else {
    select2 = $(
        '<select class="select-category" id="gradebook-select-category"><option value="">All Category</option></select>'
      )
      .insertAfter($('#gradebook-select-subject'));
  }
  select2.append('<option value="' + chk + '">' + chk + '</option>');

  var table = $('#datatable-gradebook').DataTable();
  // var val = $('#gradebook-select-subject').val();

  table.column(9, {
    filter: 'applied'
  }).every(function () {
    var that = this;
    //empty filter again

    $('#gradebook-select-category')
      .empty()
      .append('<option value="">All Category</option>');
    var val2 = '';
    that
      .search(val2 ? '^' + val2 + '$' : '', true, false)
      .draw();

    // empty
    this
      .cache('search')
      .sort()
      .unique()
      .each(function (d) {
        select2.append($('<option value="' + d + '">' + d + '</option>'));
      });
    select2.on('change', function () {
      var val = $.fn.dataTable.util.escapeRegex(
        $(this).val()
      );
      that
        .search(val ? '^' + val + '$' : '', true, false)
        .draw();
    });
  });
}

function ajaxtoAvgScore(arr) {
  $.ajax({
    url: 'ajax_php/a.ItemAverage.php',
    type: 'POST',
    dataType: "json",
    data: {
      "courseIdArr": arr
    },
    success: function (response) {
      var test = response['Avg'];
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }

  });
}

function showBrowseParticipation() {
  var table = $('#participation-act').DataTable();
  var eData = [];
  $.ajax({
    url: 'ajax_php/a.GetAllSchoolActivity.php',
    type: 'POST',
    dataType: "json",
    async: true,

    success: function (response) {
      // console.log(response);
      if (response.result == 0) {
        console.log("IT")
      } else {
        var joinBtn;
        for (var i = 0; i < response.length; i++) {
          if (response[i].overdue == 0) {
            if (response[i].SubstractNum <= 0) {
              joinBtn = '<button class="btn btn-default custom-btn browseAct-close-btn" disabled>closed</button>';
            } else {
              joinBtn = '<button class="btn btn-info custom-btn browseAct-join-btn" data-toggle="modal" data-name-id="' + response[i].title + '" data-id="' + response[i].activityId + '" data-target="">apply</button>';
            }
          } else {
            joinBtn = '<button class="btn btn-default custom-btn browseAct-close-btn" disabled>closed</button>';
          }
          var title = '<a href="#" data-toggle="modal" class="actTitleLink" data-id="' + response[i].activityId + '">' + response[i].title + '</a>';
          var categoryIcon = GetCategoryIcon(response[i].categoryCode);
          var vlweIcon = GetVlweIcon(response[i].VLWE);
          eData.push([
            title,
            response[i].staffName,
            response[i].startDate,
            response[i].baseHours,
            response[i].activityType,
            categoryIcon,
            vlweIcon,
            response[i].maxEnroll,
            response[i].curEnroll,
            response[i].SubstractNum,
            joinBtn
          ]);
        }
        table.clear();
        table = $('#participation-act').DataTable({
          data: eData,
          deferRender: true,
          bDestroy: true,
          autoWidth: false,
          ordering: true,
          // info: false,
          pagingType: "simple_numbers",
          language: {
            paginate: {
              next: '<i class="nc-icon nc-minimal-right">',
              previous: '<i class="nc-icon nc-minimal-left">'
            }
          },
          order: [
            [2, "desc"]
          ],
          responsive: true
        });

        table.column(5).every(function () {
          var column = this;
          var select = $(
              '<select class="select-category"><option value="">All Categories</option></select>')
            .prependTo($('#participation-act_filter'))
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          column.data().unique().sort().each(function (d, j) {
            var cate_name = d.slice(-4)
            select.append('<option value="' + cate_name + '">' + cate_name + '</option>')
          });
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });


}

function showMyParticipation() {
  var table = $('#participationHours').DataTable();
  var html = "";
  var eData = [];
  $.ajax({
    url: 'ajax_php/a.GetMyParticipation.php',
    type: 'POST',
    dataType: "json",
    async: true,
    success: function (response) {
      // console.log(response);
      if (response.result == 0) {
        console.log("IT")
      } else {
        myParticirationResponse = response;
        $('.participation-CurrentTerm').html(response[0].SemesterName);
        var hrSubmiitedBy;
        for (var i = 0; i < response.length; i++) {
          hrSubmiitedBy = response[i].CreateUserName;
          var SemesterName = response[i].SemesterName;
          var StatusIcon = GetStatusIcon(response[i].ActivityStatus);
          // var ActivitySourceIcon = GetActivitySourceIcon(response[i].ProgramSource);
          var ActivitySourceIcon = response[i].ProgramSource;
          var CategoryIcon = GetCategoryIcon(response[i].category);
          var VLWEIcon = GetVlweIcon(response[i].VLWE);
          var paticipationDate = '<div class="pDate">' + response[i].sDate + '</div>'
          var participationTitle = response[i].title;
          participationTitle = participationTitle.replace('YYY', '');
          participationTitle = participationTitle.replace('ZZZ', '');
          if (ActivitySourceIcon == 'SELF') {
            participationTitle = '<a data-toggle="modal" data-target="" data-id="' + [response[i].activityId, response[i].ActivityStatus] + '" class="modalLink" >' + participationTitle + '</a>';
          }
          eData.push([
            paticipationDate,
            participationTitle,
            ActivitySourceIcon,
            CategoryIcon,
            response[i].category,
            VLWEIcon,
            response[i].hours,
            hrSubmiitedBy,
            response[i].FullStaffName,
            StatusIcon,
            SemesterName,
            response[i].termId
          ]);
        }
        table.clear();
        table = $('#participationHours').DataTable({
          data: eData,
          deferRender: true,
          bDestroy: true,
          autoWidth: false,
          ordering: true,
          // info: false,
          pagingType: "simple_numbers",
          dom: '<"row"<"col-md-4"l><"col-md-8 text-right"f>>t<"row"<"col-md-5"i><"col-md-7"p>>',
          oLanguage: {
            "sEmptyTable": "No data available"
          },
          language: {
            paginate: {
              next: '<i class="nc-icon nc-minimal-right">',
              previous: '<i class="nc-icon nc-minimal-left">'
            }
          },
          order: [
            [0, "desc"]
          ],
          lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
          ],
          columnDefs: [{
              // width: '9%',
              width: '185px',
              targets: 0
            },
            {
              // width: '30.5%',
              responsivePriority: 1,
              width: '468px',
              targets: 1
            },
            {
              // width: '11%',
              width: '145px',
              targets: 2
            },
            {
              // width: '9%',
              width: '145px',
              targets: 3
            },
            {
              // width: '9%',
              visible: false,
              targets: 4
            },
            {
              // width: '8%',
              width: '120px',
              targets: 5
            },
            {
              // width: '8%',
              width: '120px',
              targets: 6
            },
            {
              // width: '15.5%',
              responsivePriority: 3,
              width: '237px',
              targets: 7
            },
            {
              // width: '15.5%',
              responsivePriority: 3,
              width: '237px',
              targets: 8
            },
            {
              // width: '9%',
              width: '120px',
              targets: 9
            },
            {
              targets: 10,
              visible: false,
            },
            {
              targets: 11,
              visible: false,
            },
          ],
          aoColumns: [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            {
              "sType": "num-html"
            },
            null,
            null
          ],
          responsive: true
          // scrollX: true
        });


        var select = $(
            '<select id="participation-category-select" class="select-category"><option value="">All Categories</option></select>')
          .prependTo($('#participationHours_filter'))


        select.append($('<option value="10">PORE</option><option value="11">AISD</option><option value="12">CILE</option><option value="13">ACLE</option>'));



        var val = $('#participation-category-select').val();
        table.column(4)
          .search(val ? '^' + val + '$' : '', true, false)
          .draw();

        var select2 = $(
            '<select id="participation-term-select" class="select-category"><option value="">All Terms</option></select>')
          .prependTo($('#participationHours_filter'))

        createFilterV2(table, select2, 10, 11, 'none');

        var val1 = $('#participation-term-select').val();
        table.column(11)
          .search(val1 ? '^' + val1 + '$' : '', true, false)
          .draw();


        table.columns().every(function (index) {
          if (index == 11) {
            var that = this;

            select2.on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              that
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          }
        });

        table.columns().every(function (index) {
          if (index == 4) {
            var that = this;

            select.on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              that
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          }
        });

      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function getDetailofMyParticipation(dataid) {
  for (let i = 0; i < myParticirationResponse.length; i++) {
    var participation = myParticirationResponse[i];
    if (participation.activityId === dataid) {
      console.log(participation)
      $("#sSubmitMdl-student-img").attr("src", "https://asset.bodwell.edu/OB4mpVpg/student/bhs" + participation.studentId + ".jpg");
      var imgsrc = "https://asset.bodwell.edu/OB4mpVpg/staff/" + participation.staffId + ".jpg";
      $('#sSubmitMdl-approverPic').attr("src", imgsrc);
      $('#sSubmitMdl-student-name').val(participation.FullName);
      $('#sSubmitMdl-studentId').val(participation.studentId);
      $('#sSubmitMdl-approver').val(participation.staffId).change();
      var statusText = GetStatusText(participation.ActivityStatus);
      $('#sSubmitMdl-status').val(statusText);
      $('#sSubmitMdl-title').val(participation.title);
      var categoryText = GetCategoryText(participation.category);
      $("#sSubmitMdl-category").val(participation.category).change();
      // if (participation.category == 12) {
      //   $(".dispVLWE").css("display", "block");
      $("input[name='vlwe'][value='" + participation.VLWE + "']").prop("checked", true);
      // } else {
      //   $(".dispVLWE").css("display", "none");
      // }
      $('#sSubmitMdl-location').val(participation.location);
      $('#sSubmitMdl-sDate').val(participation.sDate.substring(0, 10));
      $('#sSubmitMdl-eDate').val(participation.eDate.substring(0, 10));
      $('#sSubmitMdl-hours').val(participation.hours);
      $('#sSubmitMdl-aprComment').val(participation.approverComment1);
      $('#sSubmitMdl-stuComment1').val(participation.stuComment1);
      $('#sSubmitMdl-stuComment2').val(participation.stuComment2);
      $('#sSubmitMdl-stuComment3').val(participation.stuComment3);
      var modifyTxt = participation.ModifyDate + ' by ' + participation.ModifyUserName;
      var createTxt = participation.CreateDate + ' by ' + participation.CreateUserName;
      $('#sSubmitMdl-modifiedBy').html(modifyTxt);
      $('#sSubmitMdl-createdBy').html(createTxt);
    }

  }
}

function ajaxToUpdateCareer() {
  $('#career-save-btn').prop('disabled', 'disabled');
  var projectId = $('#hidden-projectId').val();
  var course = $('#career-course').val();
  var params = $("#form-careerLife").serializeArray();
  var paramsObject = {};
  $.each(params, function (i, v) {
    paramsObject[v.name] = v.value;
  });

  for (let i = 0; i < careerSubjectList.length; i++) {
    if (course == careerSubjectList[i].SubjectID) {
      var TeacherID = careerSubjectList[i].TeacherID;
      var SubjectName = careerSubjectList[i].SubjectName;
    }
  }
  paramsObject['teacherID'] = TeacherID;
  paramsObject['subjectName'] = SubjectName;
  paramsObject['projectId'] = projectId;
  console.log(paramsObject);


  $.ajax({
    url: 'ajax_php/a.updateCareerLife.php',
    type: 'POST',
    dataType: 'json',
    data: paramsObject,
    success: function (response) {
      if (response[0].result == 1) {
        // console.log(response);
        location.reload();
      } else {
        alert('Contact IT');
        location.reload();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });

}

function ajaxToAddActivityRecord() {
  $('#sSubmit-submit-btn').attr("disabled", "disabled");
  var activityName = $('#sSubmit-actName').val();
  var activityCategory = $('#sSubmit-actCategory').val();
  var activityLocation = $('#sSubmit-location').val();
  var activitySDate = $('#sSubmit-sDate').val();
  var activityEDate = $('#sSubmit-eDate').val();
  var activityHours = $('#sSubmit-hours').val();
  var activityVLWE = $('input[name=vlwe]:checked').val();
  // var activityWit = $('#sSubmit-witness').val();
  var activityApprover = $('#sSubmit-approver').val();
  var activityApproverFullName = $('#sSubmit-approver-FullName').val();
  var activitySupervisor = "";
  var activityComment1 = $('#sSubmit-comment1').val();
  var activityComment2 = $('#sSubmit-comment2').val();
  var activityComment3 = $('#sSubmit-comment3').val();
  $.ajax({
    url: 'ajax_php/a.addActivityRecord.php',
    type: 'POST',
    dataType: "json",
    data: {
      "activityName": activityName,
      "activityCategory": activityCategory,
      "activityLocation": activityLocation,
      "activitySDate": activitySDate,
      "activityEDate": activitySDate,
      "activityHours": activityHours,
      "activityVLWE": activityVLWE,
      // "activityWit": activityWit,
      "activityApprover": activityApprover,
      "activityApproverFullName": activityApproverFullName,
      "activitySupervisor": activitySupervisor,
      "activityComment1": activityComment1,
      "activityComment2": activityComment2,
      "activityComment3": activityComment3
    },
    success: function (response) {
      // console.log(response);
      if (response[0].result == 1) {
        // console.log(response);
        location.reload();
      } else if (response[0].result == 3) {
        console.log(response);
        alert('You have already submited');
        location.reload();
      } else {
        alert('Contact IT');
        location.reload();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function ajaxToUpdateActivityRecord() {
  $('#sSubmitMdl-submit-btn').attr("disabled", "disabled");
  var params = $("#selfSubmitDetailForm").serializeArray();
  var paramsObject = {};
  $.each(params, function (i, v) {
    paramsObject[v.name] = v.value;
  });
  var studentActivityId = $("#sSubmitMdl-hidden-actId").val();
  paramsObject['studentActivityId'] = studentActivityId;
  $.ajax({
    url: 'ajax_php/a.updateActivityRecord.php',
    type: 'POST',
    dataType: "json",
    data: paramsObject,
    success: function (response) {
      // console.log(response);
      if (response[0].result == 1) {
        // console.log(response);
        location.reload();
      } else {
        alert('Contact IT');
        location.reload();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function ajaxtoApprovalList(id) {
  $.ajax({
    url: 'ajax_php/a.ApprovalList.php',
    type: 'POST',
    dataType: "json",
    success: function (response) {
      globalApprovalList = response;
      $(id).html('');
      $(id).append('<option value="" disabled selected>Select..</option>');
      for (var i = 0; i < response.length; i++) {
        let staffId = response[i].StaffID;
        let fullName = response[i].FullName;
        let positionTitle = response[i].PositionTitle2;
        $(id).append('<option value="' + staffId + '">' + fullName + ' (' + positionTitle + ')' + '</option>');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }

  });
}

function pagerNumResize() {
  var width = $(window).width();
  if (width < 577) {
    $.fn.DataTable.ext.pager.numbers_length = 4;
  }
}

function ajaxToAddActivityRecordV2() {
  var ActivityID = $('.joinActivityId').val();
  if (ActivityID == '') {
    alert('Try Again After Refresh the PG');
    return;
  }
  $.ajax({
    url: 'ajax_php/a.addActivityRecordV2.php',
    type: 'POST',
    dataType: "json",
    data: {
      'ActivityID': ActivityID
    },
    success: function (response) {
      if (response[0].result == 1) {
        location.reload();
      } else {
        alert('Close all websites and Try again');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function GetNumOfActivityJoined(target, type) {
  var ActivityID = target.attr('data-id');
  if (ActivityID == '') {
    alert('Try Again After Refresh the PG');
    return;
  }
  var btn;
  $.ajax({
    url: 'ajax_php/a.GetNumOfActivityJoined.php',
    type: 'POST',
    dataType: "json",
    data: {
      'ActivityID': ActivityID
    },
    success: function (response) {
      if (type == 'detail') {
        ajaxtoDetailActivity(ActivityID);
        if (response[0].Num == 0) {
          btn = '<button type="button" class="btn btn-success custom-btn actDetail-apply-btn" id="actDetail-apply-btn" data-id="' + ActivityID + '">Apply</button><button class="btn btn-default custom-btn" id="actDetail-cancel-btn" data-dismiss="modal">Close</button>';
          $('#actDetailModal .modal-footer').html(btn);
        } else {
          btn = '<button class="btn btn-default custom-btn" id="actDetail-cancel-btn" data-dismiss="modal">Close</button>';
          $('#actDetailModal .modal-footer').html(btn);
        }
      } else {
        if (response[0].Num == 0) {
          $('.joinActivityId').val(target.attr('data-id'));
          $('.actDetailModal-title').html(target.attr('data-name-id'));
          $('#enrConfModal').modal('toggle');
        } else {
          $('.actDetailModal-title').html(target.attr('data-name-id'));
          $('#enrForbModal').modal('toggle');
        }
        $('#schoolActModal').css('opacity', '0.3')
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function ajaxtoDetailActivity(ActivityID) {
  $.ajax({
    url: 'ajax_php/a.GetActivityDetail.php',
    type: 'POST',
    dataType: "json",
    data: {
      'ActivityID': ActivityID
    },
    success: function (response) {
      // console.log(response);
      $('.stf2-name').html('');
      $('.joinActivityId').val(response[0].activityId);
      $('.actDetailModal-title').html(response[0].title);
      $('.actDetailModal-term').html(response[0].SemesterName);
      $('.actDetailModal-StfLeader .stf1-name').html(response[0].staffName);
      var imgsrc = "https://asset.bodwell.edu/OB4mpVpg/staff/" + response[0].staffId + ".jpg";
      $('.actDetailModal-StfLeader .stf1-img').attr("src", imgsrc);


      if (response[0].staffId2 == 'nothi' || response[0].staffId2 == '') {
        $('.actDetailModal-StfLeader .stf2-img').css('display', 'none');
      } else {
        $('.actDetailModal-StfLeader .stf2-img').css('display', '');
        $('.actDetailModal-StfLeader .stf2-name').html(response[0].staff2Name);
        var imgsrc2 = "https://asset.bodwell.edu/OB4mpVpg/staff/" + response[0].staffId2 + ".jpg";
        $('.actDetailModal-StfLeader .stf2-img').attr("src", imgsrc2);
      }
      if (response[0].VLWE == 0) {
        $('.actDetailModal-vlweN').attr('checked', true);
        $('.actDetailModal-vlweY').attr('checked', false);
      } else {
        $('.actDetailModal-vlweY').attr('checked', true);
        $('.actDetailModal-vlweN').attr('checked', false);
      }
      $(".actDetailModal-vlweY,.actDetailModal-vlweN").attr("disabled", true);
      $('.actDetailModal-start').html(response[0].startDate);
      $('.actDetailModal-end').html(response[0].endDate);
      $('.actDetailModal-hours').html(response[0].baseHours);
      $('.actDetailModal-enrType').html(response[0].activityType);
      $('.actDetailModal-location').html(response[0].location);
      $('.actDetailModal-meetPlace').html(response[0].meetingPlace);
      $('.actDetailModal-category').html(response[0].categoryTitle);
      $('.actDetailModal-description').html(response[0].description);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }

  });
}

function ajaxToAddCareerLifePathway() {
  $("#career-course").prop('disabled', false);
  $('#career-submit-btn').prop('disabled', 'disabled');
  var params = $("#form-careerLife").serializeArray();
  var paramsObject = {};
  $.each(params, function (i, v) {
    paramsObject[v.name] = v.value;
  });
  console.log(paramsObject);
  $.ajax({
    url: 'ajax_php/a.addCareerLifePathway.php',
    type: 'POST',
    dataType: "json",
    data: paramsObject,
    success: function (response) {
      if (response[0].result == 1) {
        location.reload();
      } else {
        alert('Close all websites and Try again');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }

  });

}

function requestReset() {
  var studentId = $('.rp-studentId').val();
  var dob = $('.rp-dob').val();
  if (!studentId || !dob) {
    alert('Type StudentId and Date of birth');
    return;
  }
  var obj2 = {
    "studentId": studentId,
    "dob": dob
  };
  $.ajax({
    url: 'api/index.php?cmd=request-reset',
    contentType: "application/json; charset=utf-8",
    type: 'POST',
    dataType: "json",
    data: JSON.stringify(obj2),
    success: function (response) {
      console.log(response);
      alert('Please check your school email and follow the instruction there. Visit IT Helpdesk if you have any questions.');
      location.href = "/";
    },
    error: function (err) {
      console.log(err['responseText']);
      alert(err['responseJSON'].message);
    }
  })
}

function changePw() {
  var username = $('#change-username').val();
  var pw1 = $('#change-pw1').val();
  var pw2 = $('#change-pw2').val();
  var token = $('#change-token').val();
  var refUrl = $('#change-url').val();

  if (!username || !pw1 || !pw2) {
    alert('Type Username, Passwords');
    return;
  }
  if (pw1 !== pw2) {
    alert('Passwords not match');
    return;
  }

  if (pw2.length < 8) {
    alert('Password must be 8 characters or more with at least one capital letter, small letter and a number');
    return;
  }

  var lowerCaseLetters = /[a-z]/g;

  if (!pw2.match(lowerCaseLetters)) {
    alert('Password must be 8 characters or more with at least one capital letter, small letter and a number');
    return;
  }

  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if (!pw2.match(upperCaseLetters)) {
    alert('Password must be 8 characters or more with at least one capital letter, small letter and a number');
    return;
  }
  // Validate numbers
  var numbers = /[0-9]/g;
  if (!pw2.match(numbers)) {
    alert('Password must be 8 characters or more with at least one capital letter, small letter and a number');
    return;
  }

  var banWord = ['bodwell', '123', 'vancouver', 'canada', 'fuck', 'shit', 'ass', 'damn'];
  var pwLower = pw2.toLowerCase();
  for (var i = 0; i < banWord.length; i++) {
    var n = pwLower.includes(banWord[i]);
    if (n) {
      // alert('Password should not contain "' + banWord[i] + '"');
      alert('Sorry, you can not use that word in your password!');
      return;
    }
  }

  var obj2 = {
    "token": token,
    "username": username,
    "password": pw1,
    "password2": pw2
  };
  $.ajax({
    url: '../api/index.php?cmd=reset-password',
    contentType: "application/json; charset=utf-8",
    type: 'POST',
    dataType: "json",
    data: JSON.stringify(obj2),
    success: function (response) {
      location.href = refUrl;
    },
    error: function (err) {
      alert(err['responseJSON'].message);
    }
  })
}

function minimizeSidebar() {
  $('#minimizeSidebar').click(function () {
    if ($('body').hasClass('sidebar-mini')) {
      $('.logo').css('display', 'block');
      $('.user').css('display', 'none');
    } else {
      $('.logo').css('display', 'none');
      $('.user').css('display', 'block');
    }
  });
}

function ajaxtoAEPCourses(SemesterID) {
  $.ajax({
    url: 'ajax_php/a.MyAEPCourses.php',
    type: 'POST',
    dataType: "json",
    data: {
      "SemesterID": SemesterID
    },
    success: function (response) {
      // console.log(response);
      var options = '';
      var careerChk;
      aepCoursesInfo = response['coursesInfo'];
      aepCourses = response['courses'];
      // console.log(response);
      for (var i = 0; i < aepCoursesInfo.length; i++) {
        options += '<option value="' + aepCoursesInfo[i].SubjectID + '">'+aepCoursesInfo[i].SubjectName+'</option>';
      }
      $('#aep-subject').append(options);
      var val = $('#aep-subject').val();
      createAEPTable(aepCourses, val);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
  // only type p OR N = credit. except p Or N all non credit
}

function ajaxtoIAPRubric(SemesterID) {
  $.ajax({
    url: 'ajax_php/a.GetIAPRubric.php',
    type: 'POST',
    dataType: "json",
    data: {
      "SemesterID": SemesterID
    },
    success: function (response) {
     console.log(response)
      $('.iap-levelText').html(response[0].AEPLevel);
      $('.iap-stuName').html(getFullName(response[0].EnglishName, response[0].LastName, response[0].FirstName));
      $('#iap-tutoringInf').val(response[0].TInfo);
      $('#iap-comment').val(response[0].TComment);
      var EIOres = response[0].EIOClass.split(",");
      var ASres = response[0].ASupport.split(",");
      var res = EIOres.concat(ASres);
      for (var i = 0; i < res.length; i++) {
        $('input[value="'+res[i]+'"]:checkbox').attr('checked', 'checked');
      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
  // only type p OR N = credit. except p Or N all non credit
}

function createAEPTable(aepCourses, val) {
  var response = aepCourses[val];
  var aepObject = [
    {
      skill:"Reading",
      st1:convertNulltoNA(response.ST1Reading),
      pg1:convertNulltoNA(response.PG1Reading),
      pg2:convertNulltoNA(response.PG2Reading),
      mt:convertNulltoNA(response.MReading),
      pg3:convertNulltoNA(response.PG3Reading),
      pg4:convertNulltoNA(response.PG4Reading),
      st2:convertNulltoNA(response.ST2Reading),
      ft:convertNulltoNA(response.FReading),
    },
    {
      skill:"Writing",
      st1:convertNulltoNA(response.ST1Writing),
      pg1:convertNulltoNA(response.PG1Writing),
      pg2:convertNulltoNA(response.PG2Writing),
      mt:convertNulltoNA(response.MWriting),
      pg3:convertNulltoNA(response.PG3Writing),
      pg4:convertNulltoNA(response.PG4Writing),
      st2:convertNulltoNA(response.ST2Writing),
      ft:convertNulltoNA(response.FWriting),
    },
    {
      skill:"Speaking",
      st1:convertNulltoNA(response.ST1Speaking),
      pg1:convertNulltoNA(response.PG1Speaking),
      pg2:convertNulltoNA(response.PG2Speaking),
      mt:convertNulltoNA(response.MSpeaking),
      pg3:convertNulltoNA(response.PG3Speaking),
      pg4:convertNulltoNA(response.PG4Speaking),
      st2:convertNulltoNA(response.ST2Speaking),
      ft:convertNulltoNA(response.FSpeaking),
    },
    {
      skill:"Listening",
      st1:convertNulltoNA(response.ST1Listening),
      pg1:convertNulltoNA(response.PG1Listening),
      pg2:convertNulltoNA(response.PG2Listening),
      mt:convertNulltoNA(response.MListening),
      pg3:convertNulltoNA(response.PG3Listening),
      pg4:convertNulltoNA(response.PG4Listening),
      st2:convertNulltoNA(response.ST2Listening),
      ft:convertNulltoNA(response.FListening),
    },
    {
      skill:"Average All Skills",
      st1:calculateAEPAVG(response.ST1Reading, response.ST1Writing, response.ST1Speaking, response.ST1Listening),
      pg1:calculateAEPAVG(response.PG1Reading, response.PG1Writing, response.PG1Speaking, response.PG1Listening),
      pg2:calculateAEPAVG(response.PG2Reading, response.PG2Writing, response.PG2Speaking, response.PG2Listening),
      mt:calculateAEPAVG(response.MReading, response.MWriting, response.MSpeaking, response.MListening),
      pg3:calculateAEPAVG(response.PG3Reading, response.PG3Writing, response.PG3Speaking, response.PG3Listening),
      pg4:calculateAEPAVG(response.PG4Reading, response.PG4Writing, response.PG4Speaking, response.PG4Listening),
      st2:calculateAEPAVG(response.ST2Reading, response.ST2Writing, response.ST2Speaking, response.ST2Listening),
      ft:calculateAEPAVG(response.FReading, response.FWriting, response.FSpeaking, response.FListening),
    }
  ];
  var tr = '';
  for (var i = 0; i < aepObject.length; i++) {
    if (aepObject[i].skill=="Average All Skills") {
      tr += '<tr>' +
      '<td class="text-center font-weight-bold">'+aepObject[i].skill+'</td>'+
      ((aepObject[i].st1 == 'n/a')?'<td class="text-center naText">':'<td class="text-center font-weight-bold">')+aepObject[i].st1+'</td>' +
      ((aepObject[i].pg1 == 'n/a')?'<td class="text-center naText">':'<td class="text-center font-weight-bold">')+aepObject[i].pg1+'</td>' +
      ((aepObject[i].pg2 == 'n/a')?'<td class="text-center naText">':'<td class="text-center font-weight-bold">')+aepObject[i].pg2+'</td>' +
      ((aepObject[i].mt == 'n/a')?'<td class="text-center naText mt-td">':'<td class="text-center font-weight-bold mt-td">')+aepObject[i].mt+'</td>' +
      ((aepObject[i].pg3 == 'n/a')?'<td class="text-center naText">':'<td class="text-center font-weight-bold">')+aepObject[i].pg3+'</td>' +
      ((aepObject[i].pg4 == 'n/a')?'<td class="text-center naText">':'<td class="text-center font-weight-bold">')+aepObject[i].pg4+'</td>' +
      ((aepObject[i].st2 == 'n/a')?'<td class="text-center naText">':'<td class="text-center font-weight-bold">')+aepObject[i].st2+'</td>' +
      ((aepObject[i].ft == 'n/a')?'<td class="text-center naText ft-td">':'<td class="text-center font-weight-bold ft-td">')+aepObject[i].ft+'</td>' +
      '</tr>';
    } else {
      tr += '<tr>' +
    '<td class="text-center font-weight-bold">'+aepObject[i].skill+'</td>'+
    ((aepObject[i].st1 == 'n/a')?'<td class="text-center naText">':'<td class="text-center">')+aepObject[i].st1+'</td>' +
    ((aepObject[i].pg1 == 'n/a')?'<td class="text-center naText">':'<td class="text-center">')+aepObject[i].pg1+'</td>' +
    ((aepObject[i].pg2 == 'n/a')?'<td class="text-center naText">':'<td class="text-center">')+aepObject[i].pg2+'</td>' +
    ((aepObject[i].mt == 'n/a')?'<td class="text-center naText mt-td">':'<td class="text-center mt-td">')+aepObject[i].mt+'</td>' +
    ((aepObject[i].pg3 == 'n/a')?'<td class="text-center naText">':'<td class="text-center">')+aepObject[i].pg3+'</td>' +
    ((aepObject[i].pg4 == 'n/a')?'<td class="text-center naText">':'<td class="text-center">')+aepObject[i].pg4+'</td>' +
    ((aepObject[i].st2 == 'n/a')?'<td class="text-center naText">':'<td class="text-center">')+aepObject[i].st2+'</td>' +
    ((aepObject[i].ft == 'n/a')?'<td class="text-center naText ft-td">':'<td class="text-center ft-td">')+aepObject[i].ft+'</td>' +
    '</tr>';
    }

  }
  $('#datatable-aep tbody').html(tr);
}

function ajaxtoSemesterListForAssessments() {
  $.ajax({
    url: 'ajax_php/a.semesterListforAssessments.php',
    type: 'POST',
    async: false,
    dataType: "json",
    success: function (response) {
     console.log(response);
       $('.semester-assessments-list').append('<option value="all">All Terms</option></select>');
       for (var i = 0; i < response.length; i++) {
         let assessmentsID = response[i].AssessmentID;
         let title = response[i].Title;
         let semesterName = response[i].SemesterName;
         $('.semester-assessments-list').append(
           '<option value="' +
             assessmentsID +
             '">' +
             semesterName +
             "</option>"
         );
       }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function ajaxtoGetAssessmentsScore(){
  $.ajax({
    url: 'ajax_php/a.assessmentsScore.php',
    type: 'POST',
    async: false,
    dataType: "json",
    success: function (response) {
      if (response.result == 0) {
        console.log("IT");
      } else {
        globalAssessments = response;
        console.log(globalAssessments);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function generateAssessTable(globalAssessments, assessmentsID) {
  if(assessmentsID == 'all') {
    if(globalAssessments) {
      var tr = '';
      $('.assessments-info').html('');
      for (let i = 0; i < globalAssessments.length; i++) {
        tr += "<tr><td><span class='font-bold'>"+globalAssessments[i].SemesterName+"</span><br><span class='font12'>"+globalAssessments[i].DateFrom+"</span></td>" +
          "<td>"+chkNull(globalAssessments[i].ListeningScore)+"</td><td>"+chkNull(globalAssessments[i].ReadingScore)+"</td>"  +
          "<td>"+chkNull(globalAssessments[i].WritingScore)+"</td><td>"+chkNull(globalAssessments[i].WritingTask1)+"</td>"  +
          "<td>"+chkNull(globalAssessments[i].WritingTask2)+"</td><td class='font-bold'>"+chkNull(globalAssessments[i].OverallAverage)+"</td></tr>";
      }
      $('#datatable-assessments tbody').html(tr);
    } else {
      $('#datatable-assessments tbody').html('');
    }
  } else {
    var index;
    for (let i = 0; i < globalAssessments.length; i++) {
      if (globalAssessments[i].AssessmentID == assessmentsID) {
        index = i
      }
    }
    var tr = "<tr><td><span class='font-bold'>"+globalAssessments[index].SemesterName+"</span><br><span class='font12'>"+globalAssessments[index].DateFrom+"</span></td>" +
    "<td>"+chkNull(globalAssessments[index].ListeningScore)+"</td><td>"+chkNull(globalAssessments[index].ReadingScore)+"</td>"  +
    "<td>"+chkNull(globalAssessments[index].WritingScore)+"</td><td>"+chkNull(globalAssessments[index].WritingTask1)+"</td>"  +
    "<td>"+chkNull(globalAssessments[index].WritingTask2)+"</td><td class='font-bold'>"+chkNull(globalAssessments[index].OverallAverage)+"</td></tr>";
    $('#datatable-assessments tbody').html(tr);
    $('.assessments-info').html(globalAssessments[index].Title);
  }

}

function requestResetEmailPassword(updateForm) {
  // swal({
  //   title: "Your request has been sent!",
  //   text: "Check your mail box.",
  //   buttonsStyling: false,
  //   confirmButtonClass: "btn btn-success",
  //   type: "success"
  // }).catch(swal.noop);
  //
  // $('.personalEmail').val('');
  // $('.dob').val('');
  // $('.needs-validation').removeClass('was-validated');
  $.ajax({
    url: 'ajax_php/a.resetEmailPassword.php',
    type: 'POST',
    data: updateForm,
    dataType: 'json',
    success: function (response) {
      if(response == 'errDOB'){
        swal({
          title: "Error",
          text: "your date of birth is wrong. Please Put right date of birth.",
          buttonsStyling: false,
          confirmButtonClass: "btn btn-success",
          type: "error"
        }).catch(swal.noop);

        $('.personalEmail').val('');
        $('.dob').val('');
        $('.needs-validation').removeClass('was-validated');
      } else {
        swal({
          title: "Your request has been sent!",
          text: "You will be receiving an email once we reset your school email password.",
          buttonsStyling: false,
          confirmButtonClass: "btn btn-success",
          type: "success"
        }).catch(swal.noop);

        $('.personalEmail').val('');
        $('.dob').val('');
        $('.needs-validation').removeClass('was-validated');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function requestResetEmailPasswordFromLogin(updateForm) {
  $.ajax({
    url: 'ajax_php/a.resetEmailPasswordOptionalSID.php',
    type: 'POST',
    dataType: 'json',
    data: updateForm,
    success: function (response) {
      console.log(response);
      if(response.result == 0){
        swal({
          title: "Something went wrong",
          text: "Contact IT",
          buttonsStyling: false,
          confirmButtonClass: "btn",
          type: "error"
        }).catch(swal.noop);
      } else {
        swal({
          title: "Your request has been sent!",
          text: "Thank you for contacting IT Helpdesk. You will receive a call from us at the designated date and time.",
          buttonsStyling: false,
          confirmButtonClass: "btn btn-success",
          type: "success"
        }).then(function(){
          $('#resetEmailPwModal').find("input").val("").end();
          $('#resetMdl-submit-btn').prop('disabled', false);
          $('#resetEmailPwModal').modal("hide");
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}


function getCounsellorsList(roleBogs) {
  $.ajax({
    url: 'ajax_php/a.getStaffList.php',
    type: 'POST',
    dataType: 'json',
    data: {
      "Rolebogs":roleBogs
    },
    success: function (response) {
      // console.log(response);
      if(response.result == 0){
        swal({
          title: "Something went wrong",
          text: "Contact IT",
          buttonsStyling: false,
          confirmButtonClass: "btn",
          type: "error"
        }).catch(swal.noop);
      } else {
        var data = response[0];
        var options = '<option value="" data-id="">Select your counsellor...</option>';
        var fullName = '';
        data.forEach(element => {
          // console.log(element);
          fullName = element.FirstName + ' ' + element.LastName;
          options += '<option value="'+ fullName +'" data-id="' + element.Email3 + '">' + fullName + '</option>'
        });

        $('.resetMdl-counsellor').html(options);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function getBoardingStaffList(id,roleBogs) {
  $.ajax({
    url: 'ajax_php/a.getStaffList.php',
    type: 'POST',
    dataType: 'json',
    data: {
      "Rolebogs":roleBogs
    },
    success: function (response) {
      console.log(response);
      if(response.result == 0){
        swal({
          title: "Something went wrong",
          text: "Contact IT",
          buttonsStyling: false,
          confirmButtonClass: "btn",
          type: "error"
        }).catch(swal.noop);
      } else {
        $(id).html('');
        $(id).append('<option value="" disabled selected>Select..</option>');
        var list = response[0];
        for (var i = 0; i < list.length; i++) {
          let staffId = list[i].StaffID;
          let fullName = list[i].FirstName + ' ' + list[i].LastName;
          let positionTitle = list[i].PositionTitle2;
          $(id).append('<option value="' + staffId + '">' + fullName + ' (' + positionTitle + ')' + '</option>');
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function getCountriesList() {
  $.ajax({
    url: 'ajax_php/a.GetCountryList.php',
    type: 'POST',
    dataType: 'json',
    success: function (response) {
      // console.log(response);
      if(response.result == 0){
        swal({
          title: "Something went wrong",
          text: "Contact IT",
          buttonsStyling: false,
          confirmButtonClass: "btn",
          type: "error"
        }).catch(swal.noop);
      } else {
        var data = response[0];
        var options = '<option value="" data-id="">Select country...</option>';
        data.forEach(element => {
          // console.log(element);
          options += '<option value="'+ element.CName +'">' + element.CName + '</option>'
        });

        $('.resetMdl-country').html(options);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function searchStudentByName(id) {
  var input = $(id).val();
  $("#searchStuMdl-table tbody").html("");
  if (input == "") {
    showSwal("error", "Please enter search keyword(s)");
    return;
  }
  $.ajax({
    url: "ajax_php/a.getStudentListFromSearch.php",
    type: "POST",
    dataType: "json",
    data: {
      param: input,
    },
    success: function (response) {
      if (response.result == 0) {
        alert("No data");
      } else {
        var tr;
        for (let i = 0; i < response.length; i++) {
          var name = "";
          if (response[i].EnglishName) {
            name =
              response[i].FirstName +
              " (" +
              response[i].EnglishName +
              ") " +
              response[i].LastName;
          } else {
            name = response[i].FirstName + " " + response[i].LastName;
          }
          tr +=
            '<tr data-id="' +
            response[i].StudentID +
            '" data-full-name="' +
            name +
            '" data-status="' +
            response[i].CurrentStatus +
            '"><td class="text-center">' +
            response[i].StudentID +
            "</td><td>" +
            name + "</tr>";
        }
        $("#searchStuMdl-table tbody").html(tr);
        $('#searchStudentModal').modal('toggle');
        $("#pageId-comeFrom").val(id);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    },
  });
}


function insertBHSLaptopReturnPlan(updateForm) {
  console.log(updateForm);
  $.ajax({
    url: 'ajax_php/a.insertBHSLaptopReturnPlan.php',
    type: 'POST',
    data: updateForm,
    dataType: 'json',
    success: function (response) {
      console.log(response);
      if (response.result == 0) {
        alert("Something Went wrong. Contact IT");
      } else {
        if(response == 'duplicate') {
          alert('You have already submitted return plan');
        } else {
          alert('Success');
          location.reload();
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function addLeaveRequestForm() {
  // $('#leave-submit-btn').attr("disabled", "disabled");
  var params = $("#leaveRequestForm").serializeArray();
  var paramsObject = {};
  $.each(params, function (i, v) {
    paramsObject[v.name] = v.value;
  });
  paramsObject['studentId'] = $('.sId').val();

  $.ajax({
    url: 'ajax_php/a.addLeaveRequestForm.php',
    type: 'POST',
    data: paramsObject,
    dataType: 'json',
    success: function (response) {
      console.log(response);
      if (response.result == 0) {
        alert("Something Went wrong. Contact IT");
        location.reload();
      } else if (response.result == 2) {
        alert('You are not allowed to submit during this period. Please Contact your youth advisor.');
        location.reload();
      } else {
        alert('Success');
        location.reload();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}



function showStudentLeaveRequest() {
  var table = $('#requestLeaveTable').DataTable();
  var eData = [];
  $.ajax({
    url: 'ajax_php/a.GetStudentLeaveRequest.php',
    type: 'POST',
    dataType: "json",
    async: true,
    success: function (response) {
      console.log(response);
      if (response.result == 0) {
        console.log("IT")
      } else {
        var joinBtn;
        var statusIcon;
        for (var i = 0; i < response.length; i++) {
          statusIcon = GetStatusIconV2(response[i].LeaveStatus);
          eData.push([
            response[i].LeaveType,
            response[i].SDate,
            response[i].EDate,
            response[i].Reason,
            response[i].StaffFullName,
            statusIcon,
          ]);
        }
        table.clear();
        table = $('#requestLeaveTable').DataTable({
          data: eData,
          deferRender: true,
          bDestroy: true,
          autoWidth: false,
          ordering: true,
          // info: false,
          pagingType: "simple_numbers",
          language: {
            paginate: {
              next: '<i class="nc-icon nc-minimal-right">',
              previous: '<i class="nc-icon nc-minimal-left">'
            }
          },
          order: [
            [1, "desc"]
          ],
          responsive: true
        });



        table.column(0).every(function () {
          var column = this;
          var select = $(
              '<select class="select-LeaveType filter-select"><option value="">All</option></select>')
            .prependTo($('#requestLeaveTable_filter'))
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          column.data().unique().sort().each(function (d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        });

        table.column(4).every(function () {
          var column = this;
          var select = $(
              '<select class="select-LeaveApprover filter-select"><option value="">All</option></select>')
            .prependTo($('#requestLeaveTable_filter'))
            .on('change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          column.data().unique().sort().each(function (d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        });





      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });


}

function displaySelfAssessmentForm(grade){

  $.ajax({
    url: 'ajax_php/a.getSelfAssessmentForm.php',
    type: 'POST',
    async: false,
    data: {
      grade : grade
    },
    dataType: 'json',
    success: function (response) {
      console.log(response);
      if(response.result == 0){
        swal({
          title: "Forbidden",
          text: "Assessment Form Will be up soon. Please Try again.",
          buttonsStyling: false,
          confirmButtonClass: "btn btn-success",
          type: "error"
        }).catch(swal.noop);

        console.log('No Form');
      } else {
        console.log(response);
        $('#AssessmentFormID').val(response[0].AssessmentFormID);
        let today = new Date();
        $('#tableAssessmentFormDIV').html(response[0].FormHtml);
        var cDate = Date.parse(today);
        var toDate = Date.parse(response[0].ValidTo);
        var fromDate = Date.parse(response[0].ValidFrom);

        if(cDate <= toDate && cDate >= fromDate){
          var btnHtml10 = '<button id="save-assessment-grade10" class="btn btn-primary" type="button" name="button">Save</button>';
          var btnHtml8 = '<button id="save-assessment-grade8" class="btn btn-primary" type="button" name="button">Save</button>';
          if(response[0].Grade == '10,11,12'){
            $('.btn-grp-assessment').html(btnHtml10);
          } else if (response[0].Grade == '8,9') {
            $('.btn-grp-assessment').html(btnHtml8);
          } else {
            console.log('No Data');
          }
        } else {
          console.log('Out Of Date');
        }


      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function saveAssessmentGrade10() {
  var params = $("#form-assessment").serializeArray();
  var paramsObject = {};
  $.each(params, function (i, v) {
    paramsObject[v.name] = v.value;
  });
  console.log(paramsObject);
  $.ajax({
    url: 'ajax_php/a.saveAssessmentGrade10.php',
    type: 'POST',
    dataType: 'json',
    data: paramsObject,
    success: function (response) {
      console.log(response);
      if (response[0].result == 1) {
        swal({
          title: "Student Assessment Form",
          text: "Your form has been saved",
          buttonsStyling: false,
          confirmButtonClass: "btn btn-success",
          type: "success"
        }).then(function(){
          location.reload();
        });
      } else {
        alert('Contact IT');
        location.reload();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });

}

function saveAssessmentGrade8() {
  var params = $("#form-assessment").serializeArray();
  var paramsObject = {};
  $.each(params, function (i, v) {
    paramsObject[v.name] = v.value;
  });
console.log(paramsObject);
  // return;
  $.ajax({
    url: 'ajax_php/a.saveAssessmentGrade8.php',
    type: 'POST',
    dataType: 'json',
    data: paramsObject,
    success: function (response) {
      console.log(response);
      if (response[0].result == 1) {
        swal({
          title: "Student Assessment Form",
          text: "Your form has been saved",
          buttonsStyling: false,
          confirmButtonClass: "btn btn-success",
          type: "success"
        }).then(function(){
          location.reload();
        });
      } else {
        alert('Contact IT');
        location.reload();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR);
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });

}


function getAssessment() {
  var assessmentFormID = $('#AssessmentFormID').val();
  $.ajax({
    url: 'ajax_php/a.getSelfAssessment.php',
    type: 'POST',
    dataType: 'json',
    data: {
      AssessmentFormID:assessmentFormID
    },
    success: function (response) {
      console.log(response['result']);
      if(response['result'] == 0){
        console.log('No Data');
      } else {
        if(response[0].Grade == '10,11,12'){
          displayStudentAssessment10(response);
        } else if (response[0].Grade == '8,9') {
          displayStudentAssessment8(response);
        } else {
          console.log('No Data');
        }
      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });

}

function getTranscriptRequest() {
  var tr = '';
  $('#requestTranscriptTable tbody').html('');
  $.ajax({
    url: 'ajax_php/a.GetTranscriptRequest.php',
    type: 'POST',
    dataType: 'json',
    success: function (response) {
      console.log(response);
      if(response.result == 0){
        console.log('No DATA');
        tr = '<tr><td colspan="6">No Record</td></tr>';
      } else {
        for (var i = 0; i < response.length; i++) {
          tr += '<tr><td>'+response[i].RequestDate+'</td><td>'+response[i].ApplyTo+'</td><td>'+response[i].SchoolName+'</td><td>'+response[i].Paid+'</td><td>'+response[i].Status+'</td><td>'+response[i].CreateDate+'</td></tr>';
        }
      }
      $('#requestTranscriptTable tbody').html(tr);

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function addTranscriptRequest(data){
  var checked='';
  $('.applyto:checked').each(function(){
    if(checked){
      checked=checked+','+$(this).val();
    } else {
      checked = $(this).val();
    }
  });
  if($('#otherstxt').val()) {
    checked = checked+','+$('#otherstxt').val();
  }
  data['applyingto'] = checked;
  console.log(data);
  $.ajax({
    url: 'ajax_php/a.addTranscriptRequest.php',
    type: 'POST',
    data: data,
    dataType: 'json',
    success: function (response) {
      console.log(response);
      if(response['result'] == 1) {
        swal({
          title: "Your request has been sent!",
          html: "<div style='text-align:left;font-size:17px;'>Please pay $5 at the front office if you are requesting a first copy. No payment required for updated copy.<br><br> An electronic copy will be emailed to you.</div>",
          buttonsStyling: false,
          confirmButtonClass: "btn btn-success",
          type: "success"
        }).then(function(){
          location.reload();
        });

      } else {
        swal({
          title: "Error",
          text: "Please Contact IT",
          buttonsStyling: false,
          confirmButtonClass: "btn btn-success",
          type: "error"
        }).catch(swal.noop);
        location.reload();
      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });


}

function GetReportCard(type,colspan,sId){
  var studentOverallAvg = '';
  var midtermaepinfo =
  '<table width="720" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt;font-family: Arial, Helvetica, sans-serif;">'+
  '<tr><td colspan="6">AEP achievement mid-term score explanatory notes:<br />'+
  '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt;font-family: Arial, Helvetica, sans-serif;">'+
  '<tr><td valign="top">The student is:&nbsp;</td><td><table border="0" cellspacing="0" cellpadding="1" style="font-size:6pt;border: 1px solid;">'+
  '<tr><td>1</td><td>&nbsp;&nbsp;not meeting the expectations of the skill area</td></tr>'+
  '<tr><td>1.5</td><td>&nbsp;&nbsp;showing minimal progress in the skill area</td></tr>'+
  '<tr><td>2</td><td>&nbsp;&nbsp;progressing and demonstrating some improvements in the skill area</td></tr>'+
  '<tr><td>2.5</td><td>&nbsp;&nbsp;demonstrating steady improvement in the skill area</td></tr>'+
  '<tr><td>3</td><td>&nbsp;&nbsp;just meeting the expectations by demonstrating basic competence in the skill area</td></tr>'+
  '<tr><td>3.5</td><td>&nbsp;&nbsp;demonstrating stronger competence in the skill area</td></tr>'+
  '<tr><td>4</td><td>&nbsp;&nbsp;exceeding the expectations by demonstrating strong ability in the skill area</td></tr>'+
  '</table></td></tr></table></td></tr>'+
  '<tr><td colspan="6"><hr size="0.1" width="100%" color="Black"></td></tr></table>';

  var midtermsateinfo =
  '<table width="720" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt;font-family: Arial, Helvetica, sans-serif;">'+
  '<tr><td colspan="6">'+
  'Sat E achievement mid-term score explanatory notes:<br>'+
  '<table width="550" border="0" cellspacing="0" cellpadding="0" style="font-size:6pt;font-family: Arial, Helvetica, sans-serif;">'+
  '<tr><td>1 - Student is not meeting the expectations of the course.</td><td>3 - Student is almost meeting the expectations of the course.</td></tr>'+
  '<tr><td>2 - Student is progressing in the course.</td><td>4 - Student is meeting the expectations of the course.</td></tr>'+
  '</table></td></tr>'+
  '<tr><td colspan="6"><hr size="0.1" width="100%" color="Black"></td></tr>'+
  '</table>';

  var finaltermaepinfo =
  '<table width="720" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt;font-family: Arial, Helvetica, sans-serif;">'+
  '<tr><td colspan="7"><hr size="0.1" width="100%" color="Black"></td></tr>'+
  '<tr><td colspan="7"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt;font-family: Arial, Helvetica, sans-serif;"><tr><td colspan="2">AEP achievement mid-term score explanatory notes:</td><td width="34%">AEP achievement final average score:</td></tr>'+
  '<tr><td valign="top">The student is:&nbsp;</td><td><table border="0" cellspacing="0" cellpadding="1" style="font-size:6pt;border: 1px solid;">'+
  '<tr><td>1</td><td>&nbsp;&nbsp;not meeting the expectations of the skill area</td></tr>'+
  '<tr><td>1.5</td><td>&nbsp;&nbsp;showing minimal progress in the skill area</td></tr>'+
  '<tr><td>2</td><td>&nbsp;&nbsp;progressing and demonstrating some improvements in the skill area</td></tr>'+
  '<tr><td>2.5</td><td>&nbsp;&nbsp;demonstrating steady improvement in the skill area</td></tr>'+
  '<tr><td>3</td><td>&nbsp;&nbsp;just meeting the expectations by demonstrating basic competence in the skill area</td></tr>'+
  '<tr><td>3.5</td><td>&nbsp;&nbsp;demonstrating stronger competence in the skill area</td></tr>'+
  '<tr><td>4</td><td>&nbsp;&nbsp;exceeding the expectations by demonstrating strong ability in the skill area</td></tr>'+
  '</table></td><td valign="top"><table border="0" cellspacing="0" cellpadding="0" style="font-size:6pt;font-family: Arial, Helvetica, sans-serif;">'+
  '<tr><td>1-2.88</td><td>: I</td><td width="15">&nbsp;</td><td>3-3.38</td><td>: C pass</td><td width="15">&nbsp;</td><td>3.5-3.75</td><td>: B</td><td width="15">&nbsp;</td><td>3.88-4</td><td>: A</td></tr>'+
  '</table></td></tr></table></td></tr>'+
  '<tr><td colspan="7"><hr size="0.1" width="100%" color="Black"></td></tr></table>';


  var finaltermsateinfo =
  '<table width="720" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt;font-family: Arial, Helvetica, sans-serif;">'+
  '<tr><td colspan="7">'+
  'Sat E achievement final score explanatory notes:<br>'+
  '<table width="550" border="0" cellspacing="0" cellpadding="0" style="font-size:6pt;font-family: Arial, Helvetica, sans-serif;">'+
  '<tr><td>1 - Student is not meeting the expectations of the course.</td><td>3 - Student is almost meeting the expectations of the course.</td></tr>'+
  '<tr><td>2 - Student is progressing in the course.</td><td>4 - Student is meeting the expectations of the course.</td></tr>'+
  '</table></td></tr>'+
  '<tr><td colspan="7"><hr size="0.1" width="100%" color="Black"></td></tr></table>';



  $.ajax({
    url: 'ajax_php/a.GetReportCard.php',
    type: 'POST',
    async: false,
    dataType: "json",
    data: {
      sId:sId
    },
    success: function (response) {
      console.log(response);
      if (response.result == 0) {
        console.log("IT");
      } else {
        var aepInfo;
        var sateInfo;
        var comment;
        var tAbsense = '';
        var tLate = '';
        var Mark = '';
        var LetterGrade = '';
        var tr='';
        var SReading = '';
        var SWriting = '';
        var SSpeaking = '';
        var SListening = '';
        var AReading = '';
        var AWriting = '';
        var ASpeaking = '';
        var AListening = '';
        var credit = '';
        var totalMark = 0;
        var totalReg = 0;
        var RMark = '';
        var RFMark = '';
        var gpaCredit = 0;
        var totalCredit = 0;
        var gpa = '';
        var ltrGradeNotinclude = ["E", "L", "N/A", "NC", "NM", "RM", "W"];
        var gradeNotinclude = ["",-1];
        var pAttended = "";

        for(var i in response){
          var s = response[i].StartDate;
          var m = response[i].MidCutOffDate;
          var e = response[i].EndDate;
          var n = response[i].SemesterName;
          if(type == 'Mid'){
            $('.termInfo').html('<b>'+n.slice(0,-4)+'Term: </b>'+s+' - '+m);

            aepInfo = midtermaepinfo;
            sateInfo = midtermsateinfo;

            tAbsense = response[i].MAbsence - response[i].MExcuse;
            tLate = response[i].MLate;
            Mark = response[i].GradeMidterm.replace(/\s+/g, '');
            LetterGrade = response[i].LtrGradeMidterm;
            comment = response[i].CommentMidterm;
            comment = comment.replace(/\n/g, "<br />");


            credit = ' ('+ response[i].Credit * 4 + ' credits)' ;
            pAttended = response[i].PAttended;
            if(Mark < 0) {
              RMark = '';
            } else {
              RMark = Mark + '%'
            }

            if(i.includes('AEP')){
              AReading = response[i].MReading;
              AWriting = response[i].MWriting;
              ASpeaking = response[i].MSpeaking;
              AListening = response[i].MListening;
              $('.aepBasicInfo_1').html(aepInfo);
              var aepGradeTable =
               `<table width="200" border="1" cellspacing="0" cellpadding="0" style="font-size:6pt;font-family: Arial, Helvetica, sans-serif;">
               <tr align="center"><td width="50">Reading</td><td width="50">Writing</td><td width="50">Speaking</td><td width="50">Listening</td></tr>
               <tr align="center" height="11"><td>`+AReading+`</td><td>`+AWriting+`</td><td>`+ASpeaking+`</td><td>`+AListening+`</td></tr>
               </table>`;
              RMark = 'N/A';
              LetterGrade = 'N/A';
              credit = '';
            } else {
              var aepGradeTable = '';
            }

            if(i.includes('Saturday')){
              $('.sateBasicInfo_1').html(sateInfo);
              RMark = '';
              LetterGrade = '';
              SReading = response[i].MReading;
              SWriting = response[i].MWriting;
              SSpeaking = response[i].MSpeaking;
              SListening = response[i].MListening;
              var sateGradeTable =
               `<table width="200" border="1" cellspacing="0" cellpadding="0" style="font-size:6pt;font-family: Arial, Helvetica, sans-serif;">
               <tr align="center"><td width="50">Mindset</td><td width="50">Progress</td><td width="50">Participation</td><td width="50">Work Habits</td></tr>
               <tr align="center" height="11"><td>`+SReading+`</td><td>`+SWriting+`</td><td>`+SSpeaking+`</td><td>`+SListening+`</td></tr>
               </table>`;
               credit = '';
            } else {
              var sateGradeTable = '';
            }

            if(i.includes('ZZZ')){
              tAbsense = '';
              tLate = '';
              RMark = '';
              LetterGrade = '';
              credit = '';
            }

            // totalMark += parseFloat(Mark);
            if(response[i].Credit > 0) {
              if(!ltrGradeNotinclude.includes(LetterGrade.replace(/\s+/g, '')) && Mark >= 0 && Mark !== ''){
                totalReg += 1;
                totalMark += parseFloat(Mark);
                totalCredit += parseFloat(response[i].Credit);
                gpaCredit += calculateGPA(response[i].Credit, LetterGrade);
              }
            }

            tr +=
            `<tr align="center"><td align="left" style="font-weight:bold;">`+response[i].SubjectName.replace("ZZZ", "Co-curricular: ")+`</td><td>`+aepGradeTable+sateGradeTable+`</td><td><span class="td_span">`+tAbsense+`</span></td><td><span class="td_span">`+tLate+`</span></td><td><span class="td_span">`+RMark+`</span></td><td><span class="td_span">`+LetterGrade+`</span></td></tr>`+
            `<tr><td valign="top" style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`+response[i].LastName+`, `+response[i].FirstName+credit+`</td></tr>`+
            `<tr><td colspan="`+colspan+`">`+comment+`</td></tr>`+
            `<tr><td colspan="`+colspan+`"><hr size="0.1" width="100%" color="Black"></td></tr>`;

          } else {
            $('.termInfo').html('<b>'+n.slice(0,-4)+'Term: </b>'+s+' - '+e);

            aepInfo = finaltermaepinfo;
            sateInfo = finaltermsateinfo;
            comment = response[i].CommentFinal;
            comment = comment.replace(/\n/g, "<br />");

            tAbsense = parseInt(response[i].MAbsence + response[i].FAbsence) - parseInt(response[i].MExcuse + response[i].FExcuse);
            tLate = parseInt(response[i].MLate + response[i].FLate);
            Mark = response[i].GradeFinal.replace(/\s+/g, '');
            FMark = response[i].GradeFinal.replace(/\s+/g, '');
            MMark = response[i].GradeMidterm.replace(/\s+/g, '');
            LetterGrade = response[i].LtrGradeFinal;
            credit = ' ('+ response[i].Credit * 4 + ' credits)' ;

            if(MMark < 0) {
              RMark = '';
            } else {
              RMark = MMark + '%'
            }
            if(FMark < 0) {
              RFMark = '';
            } else {
              RFMark = FMark + '%'
            }

            if(i.includes('AEP')){
              var MReading = response[i].MReading;
              var MWriting = response[i].MWriting;
              var MSpeaking = response[i].MSpeaking;
              var MListening = response[i].MListening;

              var FReading = response[i].FReading;
              var FWriting = response[i].FWriting;
              var FSpeaking = response[i].FSpeaking;
              var FListening = response[i].FListening;
              var FAvg = (FReading + FWriting + FSpeaking +FListening) / 4;
              $('.aepBasicInfo_1').html(aepInfo);
              var aepGradeTable =
               `<table width="260" border="1" cellspacing="0" cellpadding="0" style="font-size:6pt;font-family: Arial, Helvetica, sans-serif;">
                <tbody><tr><td colspan="2">&nbsp;</td><td colspan="2">Reading</td><td colspan="2">Writing</td><td colspan="2">Speaking</td><td colspan="2">Listening</td><td>Final Average Score</td></tr>
                <tr><td>Mid-term</td><td style="font-size:7pt;font-weight:bold;">Final</td><td>`+MReading+`</td><td style="font-size:7pt;font-weight:bold;">`+FReading+`</td><td>`+MWriting+`</td>
                <td style="font-size:7pt;font-weight:bold;">`+FWriting+`</td><td>`+MSpeaking+`</td><td style="font-size:7pt;font-weight:bold;">`+FSpeaking+`</td><td>`+MListening+`
                </td><td style="font-size:7pt;font-weight:bold;">`+FListening+`</td><td style="font-size:7pt;font-weight:bold;">`+FAvg+`</td></tr>
                </tbody></table>`;
              RMark = 'N/A';
              RFMark = FAvg;
              LetterGrade = 'N/A';
              credit = '';
            } else {
              var aepGradeTable = '';
            }

            if(i.includes('Saturday')){
              $('.sateBasicInfo_1').html(sateInfo);
              RMark = '';
              RFMark = '';
              LetterGrade = '';
              var MReading = response[i].MReading;
              var MWriting = response[i].MWriting;
              var MSpeaking = response[i].MSpeaking;
              var MListening = response[i].MListening;

              var FReading = response[i].FReading;
              var FWriting = response[i].FWriting;
              var FSpeaking = response[i].FSpeaking;
              var FListening = response[i].FListening;
              var sateGradeTable =
               `<table width="250" border="1" cellspacing="0" cellpadding="0" style="font-size:6pt;font-family: Arial, Helvetica, sans-serif;">
                <tbody><tr align="center"><td colspan="2">&nbsp;</td><td colspan="2">Mindset</td><td colspan="2">Progress</td><td colspan="2">Participation</td><td colspan="2">Work Habits</td></tr>
                <tr align="center"><td>Mid-term</td><td style="font-size:7pt;font-weight:bold;">Final</td>
                <td>`+MReading+`</td><td style="font-size:7pt;font-weight:bold;">`+FReading+`</td><td>`+MWriting+`</td><td style="font-size:7pt;font-weight:bold;">`+FWriting+`
                </td><td>`+MSpeaking+`</td><td style="font-size:7pt;font-weight:bold;">`+FSpeaking+`</td><td>`+MListening+`
                </td><td style="font-size:7pt;font-weight:bold;">`+FListening+`</td></tr>
                </tbody></table>`;
               credit = '';
            } else {
              var sateGradeTable = '';
            }

            if(i.includes('ZZZ')){
              tAbsense = '';
              tLate = '';
              RMark = '';
              LetterGrade = '';
              credit = '';
            }

            // totalMark += parseFloat(Mark);
            if(response[i].Credit > 0) {
              if(!ltrGradeNotinclude.includes(LetterGrade.replace(/\s+/g, '')) && Mark >= 0 && Mark !== ''){
                totalReg += 1;
                totalMark += parseFloat(Mark);
                totalCredit += parseFloat(response[i].Credit);
                gpaCredit += calculateGPA(response[i].Credit, LetterGrade);
              }
            }





            tr +=
            `<tr align="center"><td align="left" style="font-weight:bold;">`+response[i].SubjectName.replace("ZZZ", "Co-curricular: ")+`</td><td>`+aepGradeTable+sateGradeTable+`</td><td><span class="td_span">`+tAbsense+`</span></td><td><span class="td_span">`+tLate+`</span></td><td><span class="td_span">`+RMark+`</span></td><td><span class="td_span">`+RFMark+`</span></td><td><span class="td_span">`+LetterGrade+`</span></td></tr>`+
            `<tr><td valign="top" style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`+response[i].LastName+`, `+response[i].FirstName+credit+`</td></tr>`+
            `<tr><td colspan="`+colspan+`">`+comment+`</td></tr>`+
            `<tr><td colspan="`+colspan+`"><hr size="0.1" width="100%" color="Black"></td></tr>`;
          }

        }

        studentOverallAvg = totalMark / totalReg;
        if(studentOverallAvg){
          var studentOverallAvgLetter = getGradeLetter(Math.round(studentOverallAvg)/100);
          var studentOverallAvgTxt = Math.round(studentOverallAvg) + '%' + ' ('+studentOverallAvgLetter+')';
        }


        if(gpaCredit){
          gpa = roundToTwo(gpaCredit/totalCredit);
        }
        var bottom =
        `<table width="720" border="0" cellspacing="0" cellpadding="0" style="font-size:9pt;font-family: Arial, Helvetica, sans-serif;">`+
        `<tr><td>&nbsp;</td></tr>`+
        `<tr><td rowspan="6" valign="bottom"><img src="https://admin.bodwell.edu/BHS/staffimages/F0627sig.jpg" alt="" border="0"><br>_______________________________________<br>Principal's Signature:</td><td>&nbsp;</td></tr>`+
        `<tr><td>&nbsp;</td></tr>`+
        `<tr><td colspan="4" align="right">Student Overall Average:</td><td align="center" colspan="2">`+studentOverallAvgTxt+`</td></tr>`+
        `<tr class="tr_Gpa"><td colspan="3">&nbsp;</td><td align="right">GPA: </td><td colspan="2" align="center">`+gpa+`</td></tr>`+
        `<tr><td></td></tr>`+
        `<tr><td>&nbsp;</td></tr>`+
        `</table><br>`+
        `<table width="575" border="1" cellspacing="0" cellpadding="0">`+
        `<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:8pt;font-family: Arial, Helvetica, sans-serif;">`+
        `<tr><td colspan="8" style="font-size:8pt;font-family: Arial, Helvetica, sans-serif;">Grades are awarded on the following approved B.C. Ministry of Education Scale:</td></tr>`+
        `<tr><td>&nbsp;&nbsp;&nbsp;A</td><td>: 86 - 100%</td><td>C</td><td>: 60 - 66%</td><td>W</td><td>: Withdrew</td><td>RM</td><td>: Requirements Met</td></tr>`+
        `<tr><td>&nbsp;&nbsp;&nbsp;B</td><td>: 73 - 85%</td><td>C-</td><td>: 50 - 59%</td><td></td><td></td><td>NM</td><td>: Requirements Not Met</td></tr>`+
        `<tr><td>&nbsp;&nbsp;&nbsp;C+</td><td>: 67 - 72%</td><td>I</td><td>: In Progress</td><td>F</td><td>: 0 - 49%</td><td>L</td><td colspan="2">: Left School</td></tr>`+
        `<tr><td align="center" colspan="10" style="font-size:8pt;font-family: Arial, Helvetica, sans-serif;">Bodwell Honour Roll Standing is 86% (A) average or greater in 3 or more courses</td></tr>`+
        `</table></td></tr></table>`;
        $(tr).appendTo('.courseTbl tbody');
        $('.overallDiv').html(bottom);
        if(pAttended.includes('AEP')){
          $('.tr_Gpa').hide();
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function chkReportCardEligible(feetype,amount){
  $.ajax({
    url: 'ajax_php/a.getOutstandingFee.php',
    type: 'POST',
    dataType: 'json',
    data: {
      FeeType: feetype,
      Amount : amount
    },
    success: function (response) {
      console.log(response['Eligible']);
      if(response['result'] == 0){
        console.log('No Data');
        location.href = "?page=reportcard";
      } else {
        if(response['Eligible'] == 'No') {
          swal({
            title: "Report card not available for viewing",
            text: "Please contact your counsellor or our Registration Officer",
            buttonsStyling: false,
            confirmButtonClass: "btn btn-success",
            type: "error"
          }).then(function () {
            location.href = "?page=reportcard";
          })
          .catch(swal.noop);

        } else {

        }
      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function GetReportCardSemester() {
  $('#reportcardTerm').html('');
  var select = '';
  $.ajax({
    url: 'ajax_php/a.GetReportCardSemester.php',
    type: 'POST',
    dataType: 'json',
    success: function (response) {
      console.log(response);
      if(response.result == 0){
        console.log('No DATA');
      } else {
        for (var i = 0; i < response.length; i++) {
          select += '<option value="'+response[i].SemesterID+'">'+response[i].SemesterName+'</option>';
        }
        $('#reportcardTerm').html('<option value=""></option>'+select);
      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });
}

function GetReportCardSummary(sId) {
  $.ajax({
    url: 'ajax_php/a.GetReportCardSummary.php',
    type: 'POST',
    async: false,
    dataType: "json",
    data: {
      sId:sId
    },
    success: function (response) {
      if(response) {
        reportResponse = response;
      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("ajax error : " + textStatus + "\n" + errorThrown);
    }
  });

}
