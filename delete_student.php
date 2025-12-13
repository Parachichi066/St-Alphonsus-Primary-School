<?php

include 'connection.php';
$id = $_GET['id'];

$sql_parent = "DELETE FROM student_parent WHERE student_id='$id'"; 
$sql_student = "DELETE FROM students WHERE student_id='$id'";
if ($conn->query($sql_parent) === TRUE && $conn->query($sql_student) === TRUE) {
    $_SESSION['action'] = 'delete';
    redirect();
} else {
    echo "Error deleting record: " . $conn->error;
}

?>