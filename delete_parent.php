<?php

include 'connection.php';
$id = $_GET['id'];

$sql_parent = "DELETE FROM parents WHERE parent_id='$id'";
if ($conn->query($sql_parent) === TRUE) {
    $_SESSION['action'] = 'delete';
    redirect();
} else {
    echo "Error deleting record: " . $conn->error;
}

?>