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

if(basename($_SERVER['SCRIPT_NAME']) !== 'login.php' && basename($_SERVER['SCRIPT_NAME']) !== 'register.php') {
    session_start();
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }
}

function redirect() {
    switch ($_SESSION['role']) {
        case 'admin':
            $location = 'admin.php';
            $location .= isset($_SESSION['table']) ? "?{$_SESSION['table']}" : '';
            header("Location: $location");
            break;
        case 'teacher':
            header("Location: teacher.php");
            break;
        case 'parent':
            header("Location: parent.php");
            break;
    }
}

function bg_check_status($status) {
    return $status ? "Cleared" : "Pending";
}
?>