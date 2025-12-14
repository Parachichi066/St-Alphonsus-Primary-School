<?php

include 'connection.php';
$id = $_GET['id'];

$sql_teacher = "DELETE FROM teachers WHERE teacher_id='$id'";
if ($conn->query($sql_teacher) === TRUE) {
    $_SESSION['action'] = 'delete';
    redirect();
} else {
    echo "Error deleting record: " . $conn->error;
}

?>