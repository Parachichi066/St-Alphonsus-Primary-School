<?php
// Functions and connections
include 'connection.php';

// Authorization check
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Input validation
if (!isset($_GET['id'])) {
    redirect(); // If no ID is provided, just go back
    exit();
}

$id = $_GET['id'];

// Securely delete the teacher record
$stmt = $conn->prepare("DELETE FROM teachers WHERE teacher_id = ?");
$stmt->bind_param("i", $id); // 'i' ensures the ID is treated as an integer

if ($stmt->execute()) {
    // Set flash message for the admin dashboard
    $_SESSION['action'] = 'delete';
    redirect();
} else {
    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
?>