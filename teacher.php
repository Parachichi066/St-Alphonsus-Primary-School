<?php
/*
 * Teacher Dashboard Controller
 * ----------------------------
 * Displays the specific class assigned to the logged-in teacher.
 * Restricts access so teachers can only see students in THEIR class.
 */

// Start session and check user role
include 'connection.php';
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Initialize variables
$teacher = null;
$students_result = null;

// Fetch Teacher Profile & Class Assignment
// LEFT JOIN classes to find out which class this teacher is responsible for.
$sql = "SELECT teacher_name, classes.class_name, teachers.class_id 
        FROM teachers
        LEFT JOIN classes ON teachers.class_id = classes.class_id
        WHERE user_id = ?";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

// If no teacher profile found, prompt to contact admin.
if($result->num_rows == 0) {
    echo "Contact the administrator to set up your teacher profile.";
    echo "<br><a href='logout.php'>Log out</a>";
    exit();
} 

// Fetch teacher details
$teacher = $result->fetch_assoc();
$stmt->close();

// Handle Feedback
if (isset($_SESSION['action'])) {
    if($_SESSION['action'] == 'edit') {
        echo "<p class='alert alert-success'>Student details updated successfully.</p>";
    } elseif ($_SESSION['action'] == 'delete') {
        echo "<p class='alert alert-success'>Student deleted successfully.</p>";
    }
    unset($_SESSION['action']);
}

// Fetch Students in this Class
// User ID links to the Teacher, then to the Class, then to the Students.
$sql_students = "SELECT student_id, student_name, age, medical_information
                 FROM students
                 INNER JOIN classes ON students.class_id = classes.class_id
                 INNER JOIN teachers ON classes.class_id = teachers.class_id
                 WHERE teachers.user_id = ?";

// Prepare and execute the statement
$stmt_students = $conn->prepare($sql_students);
$stmt_students->bind_param("i", $_SESSION['id']);
$stmt_students->execute();
$students_result = $stmt_students->get_result();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Teacher Dashboard</title>
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
            <h2>Welcome, <?php echo $teacher['teacher_name']; ?> of <?php echo $teacher['class_name'] ?></h2>
            <p>Manage your classes and students here.</p>
            <?php
            if ($students_result->num_rows > 0) {
                // Display Students Table
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>Name</th><th>Age</th><th>Medical Info</th><th>Action</th></tr></thead>";
                echo "<tbody>";
                while($row = $students_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['medical_information']) . "</td>";
                    // Action buttons for Edit and Remove, passing student_id and class_id, with confirmation on delete
                    echo "<td><a href='delete_student.php?id=" . urlencode($row['student_id']) . "&class_id=" . urlencode($teacher['class_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to remove this student?');\">Remove</a></td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                // No students found
                echo "<p>No students in your class.</p>";
            }

            ?>
        </section>
    </body>
</html>