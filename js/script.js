var activityCategory = ['Physical, Outdoor & Recreation Education', 'Academic, Interest & Skill Development', 'Citizenship, Interaction & Leadership Experience', 'Arts, Culture & Local Exploration'];
var activityCategoryVal = [10, 11, 12, 13];
var defaltForm = '';
var updateForm = '';
var capstoneCategory = [
  'Agriculture, Food and Natural Resources',
  'Architecture and Construction',
  'Arts, Audio/Video Technology and Communications',
  'Business, Management and Administration',
  'Education and Training',
  'Finance',
  'Government and Public Administration',
  'Health Science',
  'Hospitality and Tourism',
  'Human Services',
  'Information Technology',
  'Law, Public Safety, Corrections and Security',
  'Manufacturing',
  'Marketing, Sales and Service',
  'Science, Technology, Engineering and Mathematics',
  'Transportation, Distribution and Logistics',
  'Other (Specify below)'
]

function chkLocation(res, hom) {
  var subCategoryTxt;
  if (hom == 'Y' && res == 'Y') {
    subCategoryTxt = 'Boarding';
  } else if (hom == 'N' && res == 'N') {
    subCategoryTxt = 'Day Program';
  } else if (hom == 'Y' && res == 'N') {
    subCategoryTxt = 'Homestay';
  } else if (hom == 'N' && res == 'Y') {
    subCategoryTxt = 'Boarding';
  } else {
    subCategoryTxt = 'Error';
  }
  return subCategoryTxt;
}

function getGradeLetter(rate) {
  if (rate >= .86) {
    return 'A'
  } else if (rate >= .73 && rate < .86) {
    return 'B';
  } else if (rate >= .67 && rate < .73) {
    return 'C+';
  } else if (rate >= .60 && rate < .67) {
    return 'C';
  } else if (rate >= .50 && rate < .60) {
    return 'C-';
  } else {
    return 'F';
  }
}

function getEnrollmentTerm(semesterid, semestername) {
  if (semesterid <= 70) {
    return "WINTER 2018";
  } else {
    return semestername;
  }
}

function sumParticipationHours(category, hours) {
  var subTotalHours = 0;
  if (category == 10) {
    subTotalHours += hours;
  } else if (category == 11) {
    subTotalHours += hours;
  } else if (category == 12) {
    subTotalHours += hours;
  } else if (category == 13) {
    subTotalHours += hours;
  }
  return subTotalHours;
}

function GetCategoryIcon(categoryname) {
  var categoryIcon;
  if (categoryname == '10') {
    categoryIcon = '<i class="material-icons-outlined">directions_bike</i>';
  } else if (categoryname == '11') {
    categoryIcon = '<i class="material-icons-outlined">school</i>';
  } else if (categoryname == '12') {
    categoryIcon = '<i class="material-icons-outlined">language</i>';
  } else if (categoryname == '13') {
    categoryIcon = '<i class="material-icons-outlined">palette</i>';
  } else if (categoryname == '21') {
    categoryIcon = '<i class="material-icons-outlined">all_inclusive</i>';
  } else {
    categoryIcon = 'err';
  }
  return categoryIcon;
}

function GetCategoryText(categoryId) {
  var categoryText;
  if (categoryId == '10') {
    categoryText = 'Physical, Outdoor & Recreation Education';
  } else if (categoryId == '11') {
    categoryText = 'Academic, Interest & Skill Development';
  } else if (categoryId == '12') {
    categoryText = 'Citizenship, Interaction & Leadership Experience';
  } else if (categoryId == '13') {
    categoryText = 'Arts, Culture & Local Exploration';
  } else if (categoryId == '21') {
    // categoryText = 'CREX';
  } else {
    categoryText = 'err';
  }
  return categoryText;
}

function GetStatusIcon(statusid) {
  var statusIcon;
  if (statusid == 10) {
    // statusIcon = '<i class="fas fa-pen-alt text-warning"></i>';
  } else if (statusid == 20) {
    // joined
    statusIcon = '<i class="material-icons-outlined">date_range</i>';
  } else if (statusid == 60) {
    // pending approval
    statusIcon = '<i class="material-icons-outlined">hourglass_empty</i>';
  } else if (statusid == 70) {
    // in Progress
    statusIcon = '<i class="material-icons-outlined">play_arrow</i>';
  } else if (statusid == 80) {
    // hours approval
    statusIcon = '<i class="material-icons-outlined">done_outline</i>';
  } else if (statusid == 90) {
    // cancelled
    statusIcon = '<i class="material-icons-outlined">link_off</i>';
  } else {
    statusIcon = 'err';
  }
  return statusIcon;
}

function GetStatusIconV2(statusid) {
  var statusIcon;
   if (statusid == 'P') {
    // pending approval
    statusIcon = '<i class="material-icons-outlined">hourglass_empty</i>';
  } else if (statusid == 'A') {
    // hours approval
    statusIcon = '<i class="material-icons-outlined">done_outline</i>';
  } else if (statusid == 'R') {
    // cancelled
    statusIcon = '<i class="material-icons-outlined">link_off</i>';
  } else {
    statusIcon = 'err';
  }
  return statusIcon;
}

function GetStatusText(statusid) {
  var statusText;
  if (statusid == 10) {
    // statusIcon = '<i class="fas fa-pen-alt text-warning"></i>';
  } else if (statusid == 20) {
    // joined
    statusText = 'Joined';
  } else if (statusid == 60) {
    // pending approved
    statusText = 'Pending Approval';
  } else if (statusid == 80) {
    // hours approved
    statusText = 'Hours Approved';
  } else if (statusid == 90) {
    // cancelled
    statusText = 'Cancelled';
  } else {
    statusText = 'err';
  }
  return statusText;
}

function GetVlweIcon(statusid) {
  var statusIcon;
  if (statusid == 1) {
    statusIcon = '<i class="material-icons-outlined">check_box</i>';
  } else if (statusid == 0) {
    statusIcon = '';
  } else {
    statusIcon = 'err';
  }
  return statusIcon;
}

function getStaffPic(staffId, id) {
  var imgsrc = "https://asset.bodwell.edu/OB4mpVpg/staff/" + staffId + ".jpg";
  $(id).attr("src", imgsrc);

}

function dateTimePicker() {
  if ($(".datetimepicker").length != 0) {
    $('.datetimepicker').datetimepicker({
      ignoreReadonly: true,
      // debug:true,
      icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-chevron-up",
        down: "fa fa-chevron-down",
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-screenshot',
        clear: 'fa fa-trash',
        close: 'fa fa-remove'
      }
    });
  }

  if ($(".datepicker").length != 0) {
    $('.datepicker').datetimepicker({
      ignoreReadonly: true,
      // debug:true,
      icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-chevron-up",
        down: "fa fa-chevron-down",
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-screenshot',
        clear: 'fa fa-trash',
        close: 'fa fa-remove'
      }
    })
  }
}

function dateTimePickerV2() {
  if ($(".datetimepickerV2").length != 0) {
    $('.datetimepickerV2').datetimepicker({
      ignoreReadonly: true,
      stepping:15,
      icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-chevron-up",
        down: "fa fa-chevron-down",
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-screenshot',
        clear: 'fa fa-trash',
        close: 'fa fa-remove'
      }
    });
  }

  if ($(".datepickerV2").length != 0) {
    $('.datepickerV2').datetimepicker({
      ignoreReadonly: true,
      // debug:true,
      icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-chevron-up",
        down: "fa fa-chevron-down",
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-screenshot',
        clear: 'fa fa-trash',
        close: 'fa fa-remove'
      }
    })
  }
}

function findClassInfo(arr, val) {
  for (var i = 0; i < arr.length; i++) {
    if (arr[i][0] == val) {
      var roomNo = arr[i][1];
      var teacherName = arr[i][2];
      break;
    } else {
      var roomNo = '';
      var teacherName = '';
    }
  }
  var fullTxt = 'Teacher : ' + teacherName + ', Room : ' + roomNo;
  return fullTxt;
}

function makeProgress(i) {
  var j = 100 - i;
  if (j < 100) {
    j = j + 1;
    $(".progress-bar").css("width", j + "%");
  }
  // Wait for sometime before running this script again
  // setTimeout("makeProgress()", 100);
}

// function getAllValInForm(formId) {
//   return $('#' + formId).serializeArray()
// }

function getAllValInForm(formId) {
  var params = $("#" + formId).serializeArray();
  var paramsObject = {};
  $.each(params, function (i, v) {
    paramsObject[v.name] = v.value;
  });
  return paramsObject;

}

function checkSomethingChanged(defVal, updtVal) {
  var isChanged = false;

  if (defVal.length == updtVal.length) {
    let len = defVal.length
    for (let i = 0; i < len; i++) {
      if (defVal[i].name == updtVal[i].name) {
        if (defVal[i].value != updtVal[i].value) {
          isChanged = true
        }
      }
    }
  } else {
    alert('Contact IT');
  }

  return isChanged;
}

function generateSelector(select, arr, arr2, def_val, disable) {
  var options = "";

  for (let i = 0; i < arr.length; i++) {
    options += '<option value="' + arr2[i] + '">' + arr[i] + "</option>";
  }
  $(select).html(options);
  if (def_val != "none") {
    $(select).val(def_val).prop("selected", true);
  } else {
    $(select).val("").prop("selected", true);
  }

  $(select).prop("disabled", disable);
}


$.extend($.fn.dataTableExt.oSort, {
  "num-html-asc": function (a, b) {
    return ((a < b) ? -1 : ((a > b) ? 1 : 0));
  },

  "num-html-desc": function (a, b) {
    return ((a < b) ? 1 : ((a > b) ? -1 : 0));
  }
});

$(document).ready(function () {
  $('input').prop('autocomplete', 'off')

  $('#sSubmit-submit-btn').click(function (event) {

  });

  $('#sSubmit-approver').change(function (event) {
    getStaffPic(this.value, '#sSubmit-approverPic');
  });

  $('#leave-approver').change(function (event) {
    getStaffPic(this.value, '#leave-approverPic');
  });

  $('#sSubmitMdl-approver').change(function (event) {
    getStaffPic(this.value, '#sSubmitMdl-approverPic');
  });

  $(".checkbox-credit").click(function () {
    if ($('input.checkbox-credit').is(':checked')) {
      $('.tr-nonCredit').show();
    } else {
      $('.tr-nonCredit').hide();
    }
  });

  $('.submitCapstone-btn').click(function (event) {
    var target = $(event.target);
    if (target.hasClass('submitCapstone-btn')) {
      target.attr({
        'data-target': "#careerLifeModal"
      })
    }
    $('#createEdit-text').css('display', 'none');
    $('#career-submit-btn').css('display', 'inline');
    $('#career-save-btn').css('display', 'none');
    var courseId = $('#hidden-courseId').val();
    $("#career-course").val(courseId).change();
    $("#career-course").prop('disabled', true);
  });

  $('#career-cancel-btn').click(function (event) {
    $("#careerLifeModal").modal('hide')
  })

  $('#careerLifeModal').on('hidden.bs.modal', function () {
    $('#careerLifeModal').find("textarea, :text, select, input[type=email]").val("").end();
    $('form').removeClass('was-validated');
  })

  // $('#resetEmailPwModal').on('hidden.bs.modal', function () {
  //   $('#resetEmailPwModal').find("input").val("").end();
  //   $('form').removeClass('was-validated');
  // })

  $('#career-guide-phone').keyup(function (e) {
    var regex1 = RegExp('[0-9]')
    var regex2 = RegExp('[a-zA-Z]{2}')
    if (regex1.test(e.key) || regex2.test(e.key)) {
      $(this).val($(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/, '$1-$2-$3').slice(0, 12))
    } else {
      var len = $(this).val().length;
      $(this).val($(this).val().slice(0, len - 1))
    }
  });
});

$(document).on('click', ".career-link-CLE, .career-link-CLC", function (event) {
  var target = $(event.target);
  var dataid = target.attr('data-id');
  var courseCd = "";
  // target.attr({
  //   'href': '#careerLifeModal',
  //   'data-target': "#careerLifeModal"
  // })
  if (target.hasClass('career-link-CLE')) {
    courseCd = "CLE";
  } else if (target.hasClass('career-link-CLC')) {
    courseCd = "CLC";
  }

  $('#career-submit-btn').css('display', 'none');
  $('#career-save-btn').css('display', 'inline');

  $('#createEdit-text').css('display', 'block');

  for (let i = 0; i < career.length; i++) {
    if (career[i].ProjectID == dataid) {
      $('#hidden-projectId').val(dataid);
      $('#career-course').val(career[i].SubjectID);
      $('#career-topic').val(career[i].ProjectTopic);
      $('#career-guide-fName').val(career[i].MentorFName);
      $('#career-guide-lName').val(career[i].MentorLName);
      $('#career-guide-email').val(career[i].MentorEmail);
      $('#career-guide-phone').val(career[i].MentorPhone);
      $('#career-guide-position').val(career[i].MentorDesc);
      $('#career-description').val(career[i].ProjectDesc);


      const isInArray = capstoneCategory.includes(career[i].ProjectCategory);
      if (isInArray == true) {
        $("#career-capCategory").val(career[i].ProjectCategory).change();
      } else {
        $("#career-capCategory").val('Other (Specify below)').change();
        $("#career-capCategory-other").val(career[i].ProjectCategory);
      }

      var modifyText = career[i].ModifyDate + " by " + career[i].ModifyUserName
      var createText = career[i].CreateDate + " by " + career[i].CreateUserName

      console.log(career[i].ModifyUserName)
      $('#career-modifiedBy').html(modifyText);
      $('#career-createdBy').html(createText);
    }
  }

  defaltForm = $('#form-careerLife').serializeArray()
});

$(document).on('mouseover', ".showTooltip", function (event) {
  var $this = $(this);

  if (this.offsetWidth < this.scrollWidth && !$this.attr('title')) {
    $('[data-toggle="tooltip"]').tooltip({
      title: function () {
        return $(this).text()
      }
    });
  }
});

(function () {
  'use strict';
  window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    1
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
      $('#career-submit-btn').click(function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add('was-validated');
        } else {
          ajaxToAddCareerLifePathway();
        }
      });

      $('#career-save-btn').click(function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add('was-validated');
        } else {
          updateForm = $('#form-careerLife').serializeArray()
          var isChanged = checkSomethingChanged(defaltForm, updateForm);
          if (isChanged == true) {
            $('.changeNothing').css("display", "none");
            ajaxToUpdateCareer();
          } else {
            $('.changeNothing').css("display", "block");
          }
        }
      });

      $("#resetEmailBtn").click(function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add("was-validated");
        } else {
          updateForm = getAllValInForm("form-resetEmail");
          requestResetEmailPassword(updateForm);
        }
      });

      $("#resetMdl-submit-btn").click(function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add("was-validated");
        } else {
          $('#resetMdl-submit-btn').prop('disabled', true);
          $('.resetMdl-hiddenCounsellorEmail').prop('type', 'text')
          updateForm = getAllValInForm("form-resetEmailPw_Login");
          var dateTime = updateForm.sDate + ' ' + updateForm.sHour + ':' + updateForm.sMin + ' ' + updateForm.sAmpm;
          updateForm.sDateTime = dateTime;
          $('.resetMdl-hiddenCounsellorEmail').prop('type', 'hidden');
          console.log(updateForm);
          requestResetEmailPasswordFromLogin(updateForm);
        }
      });



    });
  }, false);
})();


function convertNulltoNA(val){
  var str = '';
  if(val == null) {
    str = 'n/a';
  } else {
    str = parseFloat(val).toFixed(2);
  }
  return str;
}

function calculateAEPAVG(val1,val2,val3,val4){
  var sum = 0;
  var num = 0;
  if(val1 !== null){
    num += 1;
    sum += parseFloat(val1);
  }
  if(val2 !== null){
    num += 1;
    sum += parseFloat(val2);
  }
  if(val3 !== null){
    num += 1;
    sum += parseFloat(val3);
  }
  if(val4 !== null){
    num += 1;
    sum += parseFloat(val4);
  }
  var avg = (isNaN(parseFloat(sum/num)) == true) ? 'n/a' : parseFloat(sum/num).toFixed(2);

  return avg;
}

function getFullName(ename, lastname, firstname){
  var fullName;
  if (ename) {
    fullName = lastname + ', ' + firstname + ' (' + ename + ')';
  } else {
    fullName = lastname + ', ' + firstname;
  }
  return fullName;
}

function chkNull(val) {
  var str = "";
  if(val == null) {
    str = "<span class='italic-grey'>n/a</span>";
  } else {
    str = val;
  }
  return str;
}

function getHourList(){
  var hour = '';
  var hourOpt = '<option value="">Select hour...</option>';

  for (let i = 0; i <= 12; i++) {
    hour = ('0' + i ).slice(-2);
    hourOpt += '<option value="' + hour + '">' + hour + '</option>';
  }

  return hourOpt;
}

function getMinList(){
  var min = '';
  var minOpt = '<option value="">Select min...</option>';

  for (let i = 0; i <= 45; i += 15) {
    min = ('0' + i ).slice(-2);
    minOpt += '<option value="' + min + '">' + min + '</option>';
  }

  return minOpt;
}

function selectOnlyThis(id){
  var myCheckbox = document.getElementsByName("returnto");
  Array.prototype.forEach.call(myCheckbox,function(el){
  	el.checked = false;
  });
  id.checked = true;
}


function displayStudentAssessment10(response){
  $('#AssessmentID').val(response[0].AssessmentID);
  var str = response[0].PersonalText;
  var res = str.split(":*:");

  $("[name='communication']").val(response[0].CommunicationText);
  $("[name='thinking']").val(response[0].ThinkingText);
  $("[name='personal']").val(res[0]);
  $("[name='personal1']").val(res[1]);

  $("[name='cRate']").val(response[0].CommunicationRate);
  $("[name='tRate']").val(response[0].ThinkingRate);
  $("[name='pRate']").val(response[0].PersonalRate);

}

function displayStudentAssessment8(response) {
  $('#AssessmentID').val(response[0].AssessmentID);
  var p = response[0].PersonalText;
  var res_p = p.split(":*:");
  var t = response[0].ThinkingText;
  var res_t = t.split(":*:");
  var c = response[0].CommunicationText;
  var res_c = c.split(":*:");
  console.log(res_c);
  for(var i = 0; i < res_c.length; i++){
    var inum = parseInt(i+1);
    var cName = 'Communication'+inum;
    $("[name="+cName+"]").val(res_c[i]);
  }

  for(var i = 0; i < res_t.length; i++){
    var inum = parseInt(i+1);
    var cName = 'Thinking'+inum;
    $("[name="+cName+"]").val(res_t[i]);
  }

  for(var i = 0; i < res_p.length; i++){
    var inum = parseInt(i+1);
    var cName = 'Personal'+inum;
    $("[name="+cName+"]").val(res_p[i]);
  }



  $("[name='cRate']").val(response[0].CommunicationRate);
  $("[name='tRate']").val(response[0].ThinkingRate);
  $("[name='pRate']").val(response[0].PersonalRate);

}

function calculateGPA(credit, letter){
  var ltrGrade = letter.replace(/\s+/g, '');
  var totalCredit = 0;
  if(ltrGrade == 'A'){
    totalCredit = credit * 4;
  } else if (ltrGrade == 'B') {
    totalCredit = credit * 3;
  } else if (ltrGrade == 'C+') {
    totalCredit = credit * 2.5;
  } else if (ltrGrade == 'C') {
    totalCredit = credit * 2;
  } else if (ltrGrade == 'C-') {
    totalCredit = credit * 1;
  } else {
    totalCredit = 0;
  }
  return totalCredit;
}

function roundToTwo(num) {
   return num.toFixed(2);
    // return +(Math.round(num + "e+2")  + "e-2").toFixed(2);
}

function post(path, params, method='post') {

  // The rest of this code assumes you are not using a library.
  // It can be made less verbose if you use one.
  const form = document.createElement('form');
  form.method = method;
  form.action = path;

  for (const key in params) {
    if (params.hasOwnProperty(key)) {
      const hiddenField = document.createElement('input');
      hiddenField.type = 'hidden';
      hiddenField.name = key;
      hiddenField.value = params[key];

      form.appendChild(hiddenField);
    }
  }

  document.body.appendChild(form);
  form.submit();
}

function redirectToReportCard(type) {

  var t = $('#reportcardTerm').val();
  if(!t) {
    alert('Please choose Term');
    return;
  }
    if(type == 'M') {
      post('?page=reportcardMidterm', {sId: t});
    } else {
      post('?page=reportcardFinalterm', {sId: t});
    }

  }

function getCounter(letter) {
  let letterCounter = letter.charCodeAt(0) * 10;
  if(letter[1] === "+"){
    letterCounter -= 1;
  }
  else if(letter[1] === "-"){
    letterCounter += 1;
  }
  return letterCounter;
}
