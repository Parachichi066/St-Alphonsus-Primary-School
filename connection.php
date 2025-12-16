<?php
/*----------------------------------------------------------------------
* This file handles the database connection and global session security.
* It is included in every page to ensure consistent security checks.
*-----------------------------------------------------------------------
*/
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'st_alphonsus';
// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect to login if not authenticated, except for login and registration pages
if(basename($_SERVER['SCRIPT_NAME']) !== 'login.php' && basename($_SERVER['SCRIPT_NAME']) !== 'register.php') {
    session_start();
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }
}

/*----------------
* HELPER FUNCTIONS
*-----------------
*/

// Function to redirect users based on their role
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
        default:
            header("Location: login.php");
            break;
    }
}

// Function to check and return background check status
function bg_check_status($status) {
    return $status ? "Cleared" : "Pending";
}
?>