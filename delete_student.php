<?php
// Functions and connections
include 'connection.php';
$id = $_GET['id'];

// Prevent unauthorised access
if ($_SESSION['role'] != 'admin') {
    header("location: login.php");
    exit();
}
// Check if url passes parent_id to unlink
if (isset($_GET['parent_id'])) {
    $parent_id_to_remove = $_GET['parent_id'];
    $student_id = $_GET['id'];

    // Unlink parent from student
    $stmt_unlink = $conn->prepare("DELETE FROM student_parent WHERE student_id = ? AND parent_id = ?");
    $stmt_unlink->bind_param("ii", $student_id, $parent_id_to_remove);
    if($stmt_unlink->execute()) {
        $_SESSION['action'] = 'delete';
        redirect();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt_unlink->close();
// Check if url passes class_id to unlink
} elseif (isset($_GET['class_id'])) {
    // Unlink class from student
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
// If no specific unlinking, delete the student record
} else {
    // Check if user is admin
    if ($_SESSION['role'] == 'admin') {
        // Secure Delete
        $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
        $stmt->bind_param("i", $id); // 'i' for integer ID
        
        if ($stmt->execute()) {
            $_SESSION['action'] = 'delete';
            redirect();
        } else {
            echo "Error deleting student: " . $conn->error;
        }
        $stmt->close();
    } else {
        // Not authorized
        exit();
    }
}

?>