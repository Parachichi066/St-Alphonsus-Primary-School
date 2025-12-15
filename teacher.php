<?php

include 'connection.php';
if ($_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM teachers WHERE user_id='{$_SESSION['id']}'";
$result = $conn->query($sql)->fetch_assoc();
$teacher = $result['teacher_name'];
$class = $result['class_id'];
$class_name = class_name($class);

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
            <h2>Welcome, <?php echo $teacher; ?> of <?php echo $class_name ?></h2>
            <p>Manage your classes and students here.</p>
            <?php
            
            if (isset($_SESSION['action'])) {
                if($_SESSION['action'] == 'edit') {
                    echo "<p class='success'>Student details updated successfully.</p>";
                }
                if ($_SESSION['action'] == 'delete') {
                    echo "<p class='success'>Student deleted successfully.</p>";
                }
                $_SESSION['action'] = null;
            }

            $sql = "SELECT student_id, student_name, age, medical_information
                FROM students
                INNER JOIN classes ON students.class_id = classes.class_id
                INNER JOIN teachers ON classes.teacher_id = teachers.teacher_id
                WHERE teachers.user_id = {$_SESSION['id']}";            
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>Name</th><th>Age</th><th>Medical Info</th><th>Action</th></tr></thead>";
                echo "<tbody>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['medical_information']) . "</td>";
                    echo "<td><a href='edit_student.php?id=" . urlencode($row['student_id']) . "' class='btn btn-primary'>Edit</a>  <a href='delete_student.php?id=" . urlencode($row['student_id']) . "' class='btn btn-danger'>Delete</a></td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No students in your class.</p>";
            }

            ?>
        </section>
    </body>
</html>