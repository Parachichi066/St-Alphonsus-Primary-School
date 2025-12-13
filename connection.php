<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'st_alphonsus';
 
$conn = new mysqli($host, $user, $password, $database);
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(basename($_SERVER['SCRIPT_NAME']) !== 'login.php') {
    session_start();
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }
}

function redirect() {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: admin.php");
            break;
        case 'teacher':
            header("Location: teacher.php");
            break;
        case 'parent':
            header("Location: parent.php");
            break;
    }
}

function class_name($class_id) {
    switch ($class_id) {
        case 1:
            return "Reception Year";
        case 2:
            return "Year One";
        case 3:
            return "Year Two";
        case 4:
            return "Year Three";
        case 5:
            return "Year Four";
        case 6:
            return "Year Five";
        case 7:
            return "Year Six";
    }
}
?>