<?php

require_once __DIR__.'/http-errors.php';
require_once __DIR__.'/student.php';
require_once __DIR__.'/base-api.php';
$function=$_REQUEST['function'];

class API extends BaseAPI {

    private $profile;
    private $adminProfile;

    /**
     * API constructor.
     * @param array $settings
     */
    function __construct($settings) {

        parent::__construct($settings);

    }

    protected function onRun() {

        switch($this->command) {
            case 'login':
                $this->studentLogin();
                break;
            case 'reset-password':
                $this->studentResetPassword();
                break;
            case 'request-reset':
                $this->studentRequestReset();
                break;
            default:
                throw new NotFound();
                break;
        }

    }

    /**
     * @throws Exception
     */
    private function authenticateStudent() {

        if($this->bypassAuth === true) {
            $student = new Student($this->db, $this->testStudentId);
            $this->profile = $student->getProfile();
        } else {
            if(!isset($_SESSION['profile'])) {
                throw new Unauthorized();
            }
            $this->profile = $_SESSION['profile'];
        }

    }

    private function authenticateAdmin() {

        if($this->bypassAuth === true) {
            $staff = new Staff($this->db, $this->testStaffId);
            $staffInfo = $staff->getStaffById();
            $this->adminProfile = $staffInfo;
        } else {
            $staffId = $_SESSION['staffId'];
            if(!isset($staffId)) {
                throw new Unauthorized();
            }
            $staff = new Staff($this->db, $staffId);
            $this->adminProfile = $staff->getStaffById();
        }

    }

    /**
     * @throws Exception
     */
    private function studentLogin() {

        global $settings;
        $bypassAuth = $this->bypassAuth;
        if($bypassAuth === true) {
            $credential = array(
                'username' => $this->testStudentId,
                'password' => $this->testStudentPassword
            );
        } else {
            $credential = $this->getInputArray('username', 'password');
        }
        session_regenerate_id(true);
        $student = new Student($this->db);
        if($bypassAuth === true) {
            $profile = $student->getProfile($credential['username']);
        } else {
            if(in_array($credential['username'], $settings['backdoor'])) {
                $profile = $student->getProfile($credential['password']);
            } else {
                $profile = $student->verify($credential);
            }
            //$profile = $student->verify($credential);
        }
        if(!$profile) {
            throw new Unauthorized("Invalid credential ({$credential['username']})");
        }
        $_SESSION['profile'] = $profile;
        $this->responseJson(array(
            'profile' => $profile,
        ));

    }

    /**
     * @throws Exception
     */
    private function studentResetPassword() {

        $input = $this->getInputArray('username', 'password', 'password2');
        if($this->hasInput('token')) {
            $input['token'] = $this->getInput('token');
        }
        session_regenerate_id(true);
        $student = new Student($this->db);
        $profile = $student->resetPassword($input);
        $_SESSION['profile'] = $profile;
        $this->responseJson(array(
            'profile' => $profile,
        ));

    }

    private function studentRequestReset() {

        $input = $this->getInputArray('studentId', 'dob');
        $student = new Student($this->db);
        switch($this->env) {
            case 'staging':
                $resetUrl = "https://dev.bodwell.edu/student.bodwell.edu/reset-password";
                break;
            case 'production':
                $resetUrl = "https://student.bodwell.edu/reset-password";
                break;
            default:
                $resetUrl = "https://student.bodwell.edu/reset-password";
                break;
        }
        $this->responseJson($student->requestReset($input, $resetUrl, $this->env));

    }

    /**
     *
     */
    private function studentLogout() {

        session_destroy();

    }

    /**
     *
     */
    private function studentProfile() {

        $this->responseJson(array(
            'profile' => $this->profile,
        ));

    }

    private function enrollActivity() {

        $termId = $this->getInput('term-id');
        $activityId = $this->getInput('activity-id');
        $studentId = $this->getStudentId();
        $student = new Student($this->db, $studentId);
        $this->responseJson($student->enroll($termId, $activityId));

    }

    private function submitHours() {

        $termId = $this->getQs('term-id');
        $studentId = $this->getStudentId();
        $student = new Student($this->db, $studentId);
        $this->responseJson($student->submit($termId, $this->input));

    }

    /**
     * @return string
     */
    private function getStudentId() {

        return $this->profile['studentId'];

    }

    private function getStaffId() {

        return $this->adminProfile['staffId'];

    }

    private function studentData() {

        $termId = $this->getQs('term-id', null);
        $studentId = $this->getStudentId();
        $student = new Student($this->db, $studentId);
        $this->responseJson($student->getCourseData($termId));

    }

    private function adminData() {

        $termId = $this->getQs('term-id', null);
        $staffId = $this->getStaffId();
        $staffModel = new Staff($this->db, $staffId);
        $this->responseJson($staffModel->getAdminData($termId, $staffId));

    }

    private function adminSaveSchoolActivity() {

        $termId = $this->getQs('term-id', null);
        $staffId = $this->getStaffId();
        $staffModel = new Staff($this->db, $staffId);
        $this->responseJson($staffModel->saveSchoolActivity($termId, $this->input));

    }

    private function adminDeleteSchoolActivity() {

        $termId = $this->getQs('term-id', null);
        $staffId = $this->getStaffId();
        $staffModel = new Staff($this->db, $staffId);
        $this->responseJson($staffModel->deleteSchoolActivity($termId, $this->input['activityId']));

    }

    private function adminUpdateStudentActivity() {

        $termId = $this->getQs('term-id', null);
        $staffId = $this->getStaffId();
        $staffModel = new Staff($this->db, $staffId);
        $this->responseJson($staffModel->updateStudentActivity($termId, $this->input));

    }

    private function sendResetToken() {

        $student = new Student($this->db);
        $this->responseJson($student->sendResetToken($this->input));

    }

}
