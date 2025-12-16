<?php

include 'connection.php';
$id = $_GET['id'];

if (isset($_GET['parent_id'])) {
    $parent_id_to_remove = $_GET['parent_id'];
    $student_id = $_GET['id'];

    $stmt_unlink = $conn->prepare("DELETE FROM student_parent WHERE student_id = ? AND parent_id = ?");
    $stmt_unlink->bind_param("ii", $student_id, $parent_id_to_remove);
    if($stmt_unlink->execute()) {
        $_SESSION['action'] = 'delete';
        redirect();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt_unlink->close();
} elseif (isset($_GET['class_id'])) {
    $class_id_to_remove = $_GET['class_id'];
    $student_id = $_GET['id'];

    $stmt_unlink = $conn->prepare("UPDATE students SET class_id = -1 WHERE student_id = ? AND class_id = ?");
    $stmt_unlink->bind_param("ii", $student_id, $class_id_to_remove);
    if($stmt_unlink->execute()) {
        $_SESSION['action'] = 'delete';
        redirect();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt_unlink->close();
} else {
    $sql_student = "DELETE FROM students WHERE student_id='$id'";
    if ($conn->query($sql_student) === TRUE) {
        $_SESSION['action'] = 'delete';
        redirect();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

?>