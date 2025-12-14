<?php

include 'connection.php';
$id = $_GET['id'];

$sql_class = "DELETE FROM classes WHERE class_id='$id'";
if ($conn->query($sql_class) === TRUE) {
    $_SESSION['action'] = 'delete';
    redirect();
} else {
    echo "Error deleting record: " . $conn->error;
}

?>