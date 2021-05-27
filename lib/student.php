<?php
require_once __DIR__.'/pdo-enabled.php';
require_once __DIR__.'/sql.php';
require_once __DIR__.'/send-email.php';

class Student extends Woonysoft\PDO\PDOEnabled {

    private $studentId;

    /**
     * Student constructor.
     * @param PDO $db
     * @param string $studentId
     */
    function __construct($db, $studentId = '') {
        parent::__construct($db, 'tblBHSStudent');
        $this->studentId = $studentId;
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function onDefaultValue($name) {
        return false;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return true|array
     */
    protected function onValidate($name, $value) {
        return true;
    }

    function getSql($name) {
        global $_SQL;
        return $_SQL[$name];
    }

    /**
     * @param $email
     * @return array
     * @throws BadRequest
     * @throws Exception
     */
    private function getProfileByEmail($email) {
        $sql = $this->getSql('profile-by-email');
        $profile = $this->queryOne($sql, array($email));
        if(!$profile) {
            throw new BadRequest('Invalid email');
        }
        $term = $this->getTerm();
        $termId = $term['termId'];
        $sql = $this->getSql('num-terms-by-id');
        $numTerms = $this->queryOne($sql, array($profile['studentId'], $termId));
        $sql = $this->getSql('num-aep-terms-by-id');
        $numAEPTerms = $this->queryOne($sql, array($profile['studentId'], $termId));
        return array_merge($profile, $numTerms, $numAEPTerms);
    }

    private function generateHash($password, $cost=12){
        /* To generate the salt, first generate enough random bytes. Because
         * base64 returns one character for each 6 bits, the we should generate
         * at least 22*6/8=16.5 bytes, so we generate 17. Then we get the first
         * 22 base64 characters
         */
        $salt=substr(base64_encode(openssl_random_pseudo_bytes(17)),0,22);
        /* As blowfish takes a salt with the alphabet ./A-Za-z0-9 we have to
         * replace any '+' in the base64 string with '.'. We don't have to do
         * anything about the '=', as this only occurs when the b64 string is
         * padded, which is always after the first 22 characters.
         */
        $salt=str_replace("+",".",$salt);
        /* Next, create a string that will be passed to crypt, containing all
         * of the settings, separated by dollar signs
         */
        $param='$'.implode('$',array(
                "2y", //select the most secure version of blowfish (>=PHP 5.3.7)
                str_pad($cost,2,"0",STR_PAD_LEFT), //add the cost in two digits
                $salt //add the salt
            ));

        //now do the actual hashing
        return crypt($password,$param);
    }

    private function hashEquals($str1, $str2) {
        if(strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
            return !$ret;
        }
    }

    private function hashEquals2($str1, $str2) {
        if(strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $ret = 0;
            return !$ret;
        }
    }

    /**
     * @example
     * $student = new Student();
     * $profile = $student->verify(array('username'=>'bryan@example.com', 'password'=>'secret'));
     * if(!profile) {
     *     throw new Exception('Invalid credential');
     * }
     * @param array $credential array('username'=>'{$username}', 'password'=>'{$password}')
     * @return array
     * @throws BadRequest
     * @throws Unauthorized
     * @throws Exception
     */
    function verify($credential) {
        $sql = $this->getSql('student-password-by-email');
        $email = $credential['username'];
        $password = $credential['password'];
        $res = $this->queryOne($sql, array($email));
        if(!$res) {
            throw new BadRequest('Invalid login id!');
        }
        if($res['password'] === null) {
            throw new BadRequest('Password not set yet!');
        }
        $hash = $res['password'];
        // echo $hash;
        // if(!$this->hashEquals($hash, crypt($password, $hash))) {
        //     throw new Unauthorized();
        // }

        if($password !== $hash) {
            throw new Unauthorized();
        }
        return $this->getProfileByEmail($email);
    }

    /**
     * @param $input
     * @return array
     * @throws BadRequest
     * @throws Exception
     */
    function resetPassword($input) {
        $password = $input['password'];
        $password2 = $input['password2'];
        $email = $input['username'];
        $token = $input['token'];
        if($token) {
            // If token exists, token must be validated.
            // Token expires in 10 minutes.
            $now = date('Y-m-d H:i:s');
            $sql = "SELECT EmailID email, DATEDIFF(second, CreateDate, '{$now}') secondsElapsed FROM tblBHSUserAuthToken WHERE Token='{$token}'";
            $res = $this->queryOne($sql);
            if(!$res) {
                throw new BadRequest('Invalid reset token!');
            }
            if($res['secondsElapsed'] > 1800) { // 600 seconds(10 minutes)
                throw new BadRequest('Token expired!');
            }
        }
        if($password != $password2) {
            throw new BadRequest('Password not match!');
        }
        if(strlen($password) < 8) {
            throw new BadRequest('Password too short!');
        }
        if($token) {
            $sql = "UPDATE tblBHSUserAuth SET PW1=?,PW3=?,ModifyUserID=UserID,ModifyDate=getdate() WHERE LoginID=?";
        } else {
            //$sql = "UPDATE tblBHSUserAuth SET PW1=?,ModifyUserID=UserID,ModifyDate=getdate() WHERE LoginID=? AND PW1 IS NULL";
            throw new BadRequest('Need valid reset token!');
        }
        $hash = $this->generateHash($password);
        $count = $this->executeAndCount($sql, array($hash,$password,$email));
        if($count != 1) {
            throw new BadRequest('Failed to reset password!');
        }

        return true;

        // return $this->getProfileByEmail($email);
    }

    /**
     * @param $studentId
     * @return array
     * @throws Exception
     */
    function getProfile($studentId = null) {
        $term = $this->getTerm();
        $termId = $term['termId'];
        $sql = $this->getSql('num-terms-by-id');
        $numTerms = $this->queryOne($sql, array($studentId ? $studentId : $this->studentId, $termId));
        $sql = $this->getSql('num-aep-terms-by-id');
        $numAEPTerms = $this->queryOne($sql, array($studentId ? $studentId : $this->studentId, $termId));
        $sql = $this->getSql('profile-by-id');
        $res = $this->queryOne($sql, array($studentId ? $studentId : $this->studentId));
        if($res) {
            return array_merge($res, $numTerms, $numAEPTerms);
        }
        return $res;
    }


    private function getResetToken($email) {

        $now = date('Y-m-d H:i:s');
        $token = bin2Hex(openssl_random_pseudo_bytes(64));
        $data = array(
            'EmailID' => $email,
            'Token' => $token,
            'CreateDate' => $now,
        );
        $this->executeInsert('tblBHSUserAuthToken', $data);
        return $token;

    }

    /**
     * @param $input
     * @return array
     * @throws Exception
     */
    private function getStudentProfileFromResetInfo($input) {
        //$sql = "SELECT ";
        $studentId = $input['studentId'];
        //$firstName = $input['firstName'];
        //$lastName = $input['lastName'];
        $dob = $input['dob'];
        $sql = $this->getSql('profile-by-reset-info');
        return $this->queryOne($sql, array($studentId, $dob));
    }

    /**
     * @param $input
     * @param $resetUrl
     * @param $env
     * @return array|bool
     * @throws BadRequest
     * @throws Exception
     */
    function requestReset($input, $resetUrl, $env) {

        $profile = $this->getStudentProfileFromResetInfo($input);
        if(!$profile) {
          // return $input;
            throw new BadRequest('Your student information didn’t match. Please try again or visit IT Helpdesk if you need assistance.');
        }
        $email = $profile['schoolEmail'];
        $firstName = $profile['firstName'];
        $lastName = $profile['lastName'];
        $token = $this->getResetToken($email);
        $from = array('email' => 'helpdesk@bodwell.edu', 'name' => 'BHS IT Help Desk');
        $to = array(
            array('email' => $email, 'name' => "{$firstName} {$lastName}")
        );
        $cc = array();
        $subject = 'Bodwell Student Portal Password Reset';
        $url = "{$resetUrl}?token={$token}&email={$email}";
        $body = <<<BODY
            <p>Hi {$firstName},</p>
            <p>We've received a request to reset your password for your Bodwell Student Portal account.
            Click the button below to reset it.
            If you did not request a password reset, please ignore this mail and let us know by replying to this email.</p>
            <p style="font-size: 18px;"><a href="{$url}">Reset your password</a></p>
            <p>This password reset link is valid for the next 30 minutes.</p>
            <p>
            Thanks!<br/>
            <br/>
            IT Help Desk
            </p>
            <p>
            BODWELL HIGH SCHOOL<br/>
            955 Harbourside Drive, North Vancouver, BC V7P 3S4 Canada<br/>
            helpdesk@bodwell.edu | Extension: x2400 | Direct: 604-998-2400<br/>
            Room 242 (2F) | Mon-Fri (7:45am-4:45pm)<br/>
            </p>
BODY;
        if($env == 'production' || $env == 'staging') {
            $res = sendEmail($from, $to, $cc, $subject, $body);
            if($res !== true) {
                $resJson = json_encode($res);
                throw new Exception("Failed to send reset token. (from: {$from['email']} {$from['name']}, to: {$to[0]['email']} {$to[0]['name']}, url: {$url}, error:".$resJson);
            }
            return true;
        } else {
            return array(
                'from' => $from,
                'to' => $to,
                'cc' => $cc,
                'subject' => $subject,
                'body' => $body,
                'url' => $url
            );
        }

    }

}
