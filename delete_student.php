<?php

include 'connection.php';
session_start();
$id = $_GET['id'];

$sql = "DELETE FROM students WHERE student_id='$id'";
if ($conn->query($sql) === TRUE) {
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
    };
} else {
    echo "Error deleting record: " . $conn->error;
}

?>