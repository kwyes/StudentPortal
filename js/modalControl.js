// Open modal

$(document).ready(function () {
  $(".browseAct-btn, .submitHour-btn, .request-leave-btn, .request-transcript-btn").click(function (event) {
    var target = $(event.target);
    if (target.hasClass("browseAct-btn")) {
      target.attr({"data-target": "#schoolActModal"});
      showBrowseParticipation();
    } else if (target.hasClass("submitHour-btn")) {
      target.attr({"data-target": "#submitHourModal"});
      $("#sSubmit-approverPic").attr("src", "");
      ajaxtoApprovalList("#sSubmit-approver");
    } else if (target.hasClass("request-leave-btn")) {
      target.attr({"data-target": "#leaveRequestModal"});
      $("#leave-approverPic").attr("src", "");
      var rolebogs = ["30", "31", "32"];
      getBoardingStaffList("#leave-approver",rolebogs);
    } else if (target.hasClass("request-transcript-btn")) {
      target.attr({"data-target": "#transcriptRequestModal"});

    }
  });

  $("#schoolActModal").on("shown.bs.modal", function () {
    //Get the datatable which has previously been initialized
    var dataTable = $("#participation-act").DataTable();
    //recalculate the dimensions
    dataTable.columns.adjust().responsive.recalc();
  });

  $("#submitHourModal").on("hidden.bs.modal", function () {
    $("#submitHourModal").find("textarea, :text, select").val("").end();
    $("form").removeClass("was-validated");
  });

  // Close modal (Cancel button)

  $("#sSubmit-cancel-btn, #actDetail-cancel-btn, #enrConf-cancel-btn, #sSubmitMdl-close-btn, #resetMdl-cancel-btn, #leave-cancel-btn").click(function (event) {
    var target = $(event.target);
    var id = target.attr("id");
    var modal = "";
    console.log(id);
    switch (id) {
      case "sSubmit-cancel-btn":
        modal = "#submitHourModal";
        break;
      case "actDetail-cancel-btn":
        modal = "#actDetailModal";
        break;
      case "enrConf-cancel-btn":
        modal = "#enrConfModal";
        break;
      case "sSubmitMdl-close-btn":
        modal = "#selfSubmitDetailModal";
        break;
      case "resetMdl-cancel-btn":
        modal = "#resetEmailPwModal";
        break;

      default:
        break;
    }
    $(modal).modal("hide");
  });

  $(document).on("hidden.bs.modal", "#actDetailModal, #enrConfModal, #enrForbModal", function () {
    $("#schoolActModal").css("opacity", "");
  });

  $(document).on("hidden.bs.modal", "#selfSubmitDetailModal", function () {
    $(".changeNothing").css("display", "none");
  });

  $(document).on("click", "#resetMdl-reset-btn", function () {
    $('#resetEmailPwModal').find("input").val("").end();
  });

  $("#sSubmit-hours").change(function (event) {
    var target = $(event.target);
    var id = "#" + target.attr("id");
    parseHourDecimal(id);
  });

  $("#sSubmit-sDate").datetimepicker({
    format: "YYYY-MM-DD hh:mm:ss",
    maxDate: new Date(),
    icons: {
      time: "fa fa-clock-o",
      date: "fa fa-calendar",
      up: "fa fa-chevron-up",
      down: "fa fa-chevron-down",
      previous: "fa fa-chevron-left",
      next: "fa fa-chevron-right",
      today: "fa fa-screenshot",
      clear: "fa fa-trash",
      close: "fa fa-remove"
    }
  });

  $("#aepScoresMdl-close-btn, #iapMdl-close-btn, #aepCommentMdl-close-btn").click(function (event) {
    console.log("aaa");
    var target = $(event.target);
    var id = target.attr("id");
    var modal = "";
    switch (id) {
      case "aepScoresMdl-close-btn":
        modal = "#aepScoresMdl";
        break;
      case "iapMdl-close-btn":
        modal = "#iapMdl";
        break;
      case "aepCommentMdl-close-btn":
        modal = "#aepCommentMdl";
        break;
      default:
        break;
    }
    $(modal).modal("hide");
  });

  $(document).on("click", ".btn-midComment, .btn-final", function (event) {
    var aep_id = $("#aep-subject").val();
    var data = aepCourses[aep_id];
    console.log(data);
    var tmp_c;
    var comment = "Record not found.";
    var str ='';
    var target = $(event.target);
    var dataid = target.attr("data-id");
    if (target.hasClass("btn-midComment")) {
      tmp_c = data.CommentMidterm;
      $('.aep-modalTitle').html("Mid-Term Comment");
    } else if (target.hasClass("btn-final")) {
      tmp_c = data.CommentFinal;
      $('.aep-modalTitle').html("Final Comment");
    }

    if (tmp_c != null) {
      comment = tmp_c;
      txt = tmp_c.split("\n");
      console.log(txt);
      if(txt.length < 3) {
        str = comment;
      } else {
        var s = txt.splice(2);
        for (var i = 0; i < s.length; i++) {
          str += s[i];
        }
      }

      $(".aep-comment").html(str);
    } else {
      $(".aep-comment-error").html(comment);
    }
  });
});


$(document).on("click", ".actTitleLink, .modalLink, button.browseAct-join-btn, #actDetail-join-btn, #actDetail-apply-btn", function (event) {
  var target = $(event.target);
  var dataid = target.attr("data-id");
  if (target.hasClass("actTitleLink")) {
    GetNumOfActivityJoined(target, "detail");
    target.attr({href: "#actDetailModal", "data-target": "#actDetailModal"});
    $("#schoolActModal").css("opacity", "0.3");
  } else if (target.hasClass("modalLink")) {
    target.attr({href: "#selfSubmitDetailModal", "data-target": "#selfSubmitDetailModal"});
    var id = dataid.split(",")[0];
    var status = dataid.split(",")[1];
    if (status == 60) {
      $("#sSubmitMdl-save-btn").css("display", "inline");
    } else {
      $("#sSubmitMdl-save-btn").css("display", "none");
    }
    $("#sSubmitMdl-hidden-actId").val(id);
    getDetailofMyParticipation(id);

    defaltForm = $('#selfSubmitDetailForm').serializeArray()
  } else if (target.hasClass("browseAct-join-btn")) {
    GetNumOfActivityJoined(target, "confirm");
  } else if (target.hasClass("actDetail-join-btn")) {
    ajaxToAddActivityRecordV2();
  } else if (target.hasClass("actDetail-apply-btn")) {
    ajaxToAddActivityRecordV2();
  }
});

(function () {
  "use strict";
  window.addEventListener("load", function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName("needs-validation");
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
      $("#sSubmit-submit-btn").click(function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add("was-validated");
        } else {
          ajaxToAddActivityRecord();
        }
      });

      $("#sSubmitMdl-save-btn").click(function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add("was-validated");
        } else {
          updateForm = $('#selfSubmitDetailForm').serializeArray();
          var isChanged = checkSomethingChanged(defaltForm, updateForm);
          if (isChanged == true) {
            $(".changeNothing").css("display", "none");
            ajaxToUpdateActivityRecord();
          } else {
            $(".changeNothing").css("display", "block");
          }
          // ajaxToUpdateActivityRecord();
        }
      });

      $('#transcript-submit-btn').click(function (event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add("was-validated");
        } else {
          var params = $("#transcriptRequestForm").serializeArray();
          var paramsObject = {};
          $.each(params, function (i, v) {
            paramsObject[v.name] = v.value;
          });
          // console.log(updateForm)
          addTranscriptRequest(paramsObject);
        }
      });

      $("#leave-submit-btn").click(function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
          form.classList.add("was-validated");
        } else {
          updateForm = $('#leaveRequestForm').serializeArray();
          addLeaveRequestForm();
        }

      });
    });
  }, false);
})();

function parseHourDecimal(id) {
  var val = $(id).val();
  var parsVal = parseFloat(val).toFixed(1);
  $(id).val(parsVal);
}

function getFullStaffName(id1, id2) {
  var prefix;
  var x = $("#" + id1).val();
  for (var i = 0; i < globalApprovalList.length; i++) {
    if (globalApprovalList[i].StaffID == x) {
      if (globalApprovalList[i].Sex == "F") {
        prefix = "Ms. ";
      } else {
        prefix = "Mr. ";
      }
      $("#" + id2).val(prefix + globalApprovalList[i].FullName);
    }
  }
}
