<?php
/*
 * Parent Dashboard Controller
 * Displays the list of children linked to the logged-in parent.
 * Uses SQL JOINS to connect Parents -> Student_Parent -> Students -> Classes.
 */

// Start session and include database connection
include 'connection.php';

// Check if user is logged in and is a parent
if ($_SESSION['role'] != 'parent') {
    header("Location: login.php");
    exit();
}
// Initialize variables
$parent = null;
$students_result = null;

// Prepared statement to securely fetch the parent's details based on their User ID.
$stmt = $conn->prepare("SELECT * FROM parents WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['id']); // 'i' for integer
$stmt->execute();
$result = $stmt->get_result();

// If no parent profile found, prompt to contact admin.
if($result->num_rows == 0) {
    echo "Contact the administrator to set up your parent profile.";
    echo "<br><a href='logout.php'>Log out</a>";
    exit();
} 

// Fetch parent details
$parent = $result->fetch_assoc();
$stmt->close();

// Handle Feedback Messages
if (isset($_SESSION['action'])) {
    if($_SESSION['action'] == 'edit') {
        echo "<p class='alert alert-success'>Child details updated successfully.</p>";
    } elseif ($_SESSION['action'] == 'delete') {
        echo "<p class='alert alert-success'>Child details deleted successfully.</p>";
    }
    unset($_SESSION['action']); // Clear message after displaying
}

// Fetch Children Data by joining 4 tables (students, student_parent, parents, classes) to get the child's name, class, and medical info.
$sql = "SELECT students.student_id, student_name, age, classes.class_name, student_address, medical_information
        FROM students
        LEFT JOIN student_parent ON students.student_id = student_parent.student_id
        LEFT JOIN parents ON student_parent.parent_id = parents.parent_id
        LEFT JOIN classes ON students.class_id = classes.class_id
        WHERE parents.user_id = ?";

// Prepare and execute the statement
$stmt_students = $conn->prepare($sql);
$stmt_students->bind_param("i", $_SESSION['id']);
$stmt_students->execute();
$students_result = $stmt_students->get_result();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Parent Dashboard</title>
        <link rel="stylesheet" href="bootstrap-5.3.8-dist/css/bootstrap.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="index.html">St. Alphonsus Primary School</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <a class="nav-link" href="logout.php">Log out</a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <section class="dashboard">
            <h2>Welcome, <?php echo $parent['parent_name']; ?></h2>
            <p>Manage your children here.</p>
            <?php
            if ($students_result->num_rows > 0) {
                // Display children in a table
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>Name</th><th>Age</th><th>Year</th><th>Address</th><th>Medical Info</th><th>Action</th></tr></thead>";
                echo "<tbody>";
                while($row = $students_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['class_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['student_address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['medical_information']) . "</td>";
                    echo "<td><a href='edit_student.php?id=" . urlencode($row['student_id']) . "' class='btn btn-primary'>Edit</a>  <a href='delete_student.php?id=" . urlencode($row['student_id']) . "&parent_id=" . urlencode($parent['parent_id']) . "' class='btn btn-danger'>Remove</a></td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                // No children found
                echo "<p>You have no registered children.</p>";
            }

            ?>
        </section>
    </body>
</html>