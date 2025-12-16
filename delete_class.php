<?php
// Functions and connections
include 'connection.php';

// Authentication check
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Redirect function
if (!isset($_GET['id'])) {
    redirect();
    exit();
}

$id = $_GET['id'];

// Securely delete the class record
$stmt = $conn->prepare("DELETE FROM classes WHERE class_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['action'] = 'delete';
    redirect();
} else {
    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
?>