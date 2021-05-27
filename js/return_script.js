  $(document).ready(function() {
    dateTimePicker();
    $('.form-authorize').hide();
    $('.return-status-checkbox').change(function() {
       if ($('.return-authorize').is(':checked')) {
         $('.form-authorize').show();
       } else {
         $('.form-authorize').hide();
         $('.form-authorize input').val('');
       }
     });



     $(".image-checkbox").each(function () {
         if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
             $(this).addClass('image-checkbox-checked');
         } else {
             $(this).removeClass('image-checkbox-checked');
         }
     });

     // sync the state to the input
     $(".image-checkbox").on("click", function (e) {
         $(this).toggleClass('image-checkbox-checked');
         var $checkbox = $(this).find('input[type="checkbox"]');
         $checkbox.prop("checked", !$checkbox.prop("checked"))

         e.preventDefault();
     });


     $(".return-StudentName").keypress(function (e) {
       var key = e.which;
       if(key == 13)  // the enter key code
        {
          searchStudentByName('.return-StudentName');
          return false;
        }
     });

     $("#return-cancel-btn").click(function(event) {
       $('.return-StudentID').val('');
       $('#returnSchoolDevicesModal').modal('toggle');
     });

     $("#return-submit-btn").click(function (event) {
       var val = [];
       $('.check-device:checked').each(function(i){
         val[i] = $(this).val();
       });

       var sId = $('.return-StudentID').val();
       if(!sId){
         alert('Please Search Student');
         return;
       }

       if(val.length == 0) {
         alert('Please check at least one of the BHSD Devices that you are returning');
         return;
       }

       var options = document.querySelectorAll(".return-status-checkbox:checked");
       if(options.length == 0){
         alert('PLEASE SELECT ONE OF THE FOLLOWING OPTIONS');
         return;
       }


       var devices = val.join(',');
       // $('#return-submit-btn').prop('disabled', true);
       updateForm = getAllValInForm("form-returnSchoolDevices");
       updateForm.deviceLists = devices;

       if(updateForm.returnto == '4'){
         if(!updateForm.authDate || !updateForm.authName) {
           alert('Please Fill Date AND Name');
           return;
         }
       }


       insertBHSLaptopReturnPlan(updateForm);

     });

  });
