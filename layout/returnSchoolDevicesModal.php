<!-- Modal for Self-Submit Hours -->
<div class="modal" id="returnSchoolDevicesModal">
  <div class="modal-dialog modal-login modal-lg">
    <div class="modal-content">
      <div class="card card-login card-plain">
        <div class="modal-header justify-content-center">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="modal-close-icon">&times;</span>
          </button>
          <div class="header header-primary text-center">
            <h4>BHS Laptop Return Plan</h4>
          </div>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <form id="form-returnSchoolDevices" class="needs-validation" novalidate>
              <h6 class="mg-t-40">Student Name ( Press Enter to search )</h6>
              <div class="form-group">
                <input type="text" class="form-control return-StudentName" name="StudentName">
                <input type="hidden" class="form-control return-StudentID" name="StudentID" required>
                <div class="invalid-feedback">
                  This field is required.
                </div>
              </div>
              <h6 class="mg-t-40">BHSD Checklikst (Please check the items you are returning)</h6>
              <div class="row">
                <div class="col-md-3">
                  <div class="gallery-card">
                  <div class="gallery-card-body">
                    <label class="block-check">
                   <img src="img/dell-laptop.png" class="img-responsive" />
                   <input type="checkbox" class="check-device" name="devices[]" value="laptop">
                    <span class="checkmark"></span>
                    </label>
                     <div class="mycard-footer">
                    <a href="#" class="card-link">Dell 5290 2 in 1 laptop</a>
                    </div>
                  </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="gallery-card">
                  <div class="gallery-card-body">
                    <label class="block-check">
                   <img src="img/dell-keyboard.png" class="img-responsive" />
                   <input type="checkbox" name="devices[]" class="check-device" value="keyboard">
                    <span class="checkmark"></span>
                    </label>
                     <div class="mycard-footer">
                    <a href="#" class="card-link">Keyboard</a>
                    </div>
                  </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="gallery-card">
                  <div class="gallery-card-body">
                    <label class="block-check">
                   <img src="img/dell-charger.png" class="img-responsive" />
                   <input type="checkbox" class="check-device"  name="devices[]" value="charger">
                    <span class="checkmark"></span>
                    </label>
                     <div class="mycard-footer">
                    <a href="#" class="card-link">Charger & Power code</a>
                    </div>
                  </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="gallery-card">
                  <div class="gallery-card-body">
                    <label class="block-check">
                   <img src="img/dell-pen.png" class="img-responsive" />
                   <input type="checkbox" class="check-device" name="devices[]" value="pen">
                    <span class="checkmark"></span>
                    </label>
                     <div class="mycard-footer">
                    <a href="#" class="card-link">Pen</a>
                    </div>
                  </div>
                  </div>
                </div>
              </div>


              <h6 class="mg-t-40">Please select one of the following options:</h6>
              <div class="form-group">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="1" class="return-status-checkbox return-inPerson" name="returnto" onclick="selectOnlyThis(this)"> I will return the laptop and accessories in person
                  </label>
                </div>
              </div>

              <div class="form-group">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="2" class="return-status-checkbox return-byCourier" name="returnto" onclick="selectOnlyThis(this)"> I will return the laptop and accessories by Courier
                  </label>
                </div>
              </div>

              <div class="form-group">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="3" class="return-status-checkbox return-penalty" name="returnto" onclick="selectOnlyThis(this)"> I will not return the laptop, and I will pay the $800 penalty
                  </label>
                </div>
              </div>

              <div class="form-group">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="4" class="return-status-checkbox return-authorize" name="returnto" onclick="selectOnlyThis(this)"> I will authorize someone to return the laptop and accessories on my behalf (please fill out details below)
                  </label>
                </div>
              </div>


              <div class="form-group form-authorize">
                <span class="font-weight-light">I hereby authorize <input type="text" class="form-input-bottom-line" name="authName" value="" required>   (friend or sibling's name) to return my laptop and accessories by <input type="text" class="form-input-bottom-line datepicker" data-date-format="YYYY-MM-DD" onkeydown="return false" name="authDate" value="" required>(Date)
*I understand that I will be FULLY RESPONSIBLE for any lost or damages on the equipment</span>
              </div>

          </div>
          <div class="modal-footer text-center">
            <button type="button" class="btn btn-success custom-btn" id="return-submit-btn">Submit</button>
            <button type="reset" class="btn btn-default custom-btn" id="return-cancel-btn">Cancel</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
  include_once('layout/searchStudentModal.html');
?>
<script src="js/return_script.js" charset="utf-8"></script>
