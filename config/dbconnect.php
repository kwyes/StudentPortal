<?php
class DBController {
    protected $db;
    function __construct() {
        $this->db = $this->connectDB();
    }
    function __destruct() {
		    $this->db = null;
    }
    private function connectDB() {
        $hostName = $_SERVER['SERVER_NAME'];

        if($hostName == 'student.bodwell.edu') {
          
        } elseif ($hostName == 'dev.bodwell.edu') {
          
        } else {
          
        }

        $conn = new PDO($dsn);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}
?>
