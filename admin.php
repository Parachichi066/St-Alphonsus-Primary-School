<?php

include 'connection.php';
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
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
            <h2>Welcome, Admin</h2>
            <p>Manage the school here.</p>
            <div class="admin-actions">
                <form method="get">
                    <button class="btn btn-primary" type="submit" name="students">Manage Students</button>
                    <button class="btn btn-primary" type="submit" name="parents">Manage Parents</button>
                    <button class="btn btn-primary" type="submit" name="teachers">Manage Teachers</button>
                    <button class="btn btn-primary" type="submit" name="classes">Manage Classes</button>
                </form>
            </div>
            <?php
            
            if (isset($_SESSION['action'])) {
                if($_SESSION['action'] == 'edit') {
                    echo "<p class='success'>Details updated successfully.</p>";
                }
                if ($_SESSION['action'] == 'delete') {
                    echo "<p class='success'>Details deleted successfully.</p>";
                }
                $_SESSION['action'] = null;
            }

            if(isset($_GET['students'])) {
                $_SESSION['table'] = 'students';

                $sql = "SELECT * FROM students";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th>Name</th><th>Age</th><th>Year</th><th>Address</th><th>Medical Info</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                        echo "<td>" . class_name(htmlspecialchars($row['class_id'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['student_address']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['medical_information']) . "</td>";
                        echo "<td><a href='edit_student.php?id=" . urlencode($row['student_id']) . "' class='btn btn-primary'>Edit</a>  <a href='delete_student.php?id=" . urlencode($row['student_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete " . htmlspecialchars($row['student_name']) . "? This cannot be undone.');\">Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>There are no registered students.</p>";
                }
            }

            if(isset($_GET['parents'])) {
                $_SESSION['table'] = 'parents';

                $sql = "SELECT * FROM parents";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th>Name</th><th>Email</th><th>Telephone</th><th>Address</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['parent_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['parent_email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['parent_telephone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['parent_address']) . "</td>";
                        echo "<td><a href='edit_parent.php?id=" . urlencode($row['parent_id']) . "' class='btn btn-primary'>Edit</a>  <a href='delete_parent.php?id=" . urlencode($row['parent_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete " . htmlspecialchars($row['parent_name']) . "? This cannot be undone.');\">Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>There are no registered parents.</p>";
                }
            }

            if(isset($_GET['teachers'])) {
                $_SESSION['table'] = 'teachers';

                $sql = "SELECT * FROM teachers";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th>Name</th><th>Email</th><th>Telephone</th><th>Address</th><th>Salary</th><th>Background Check</th><th>Assigned Year</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['teacher_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['teacher_email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['teacher_telephone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['teacher_address']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['teacher_salary']) . "</td>";
                        echo "<td>" . bg_check_status(htmlspecialchars($row['background_check'])) . "</td>";
                        echo "<td>" . class_name(htmlspecialchars($row['class_id'])) . "</td>";
                        echo "<td><a href='edit_teacher.php?id=" . urlencode($row['teacher_id']) . "' class='btn btn-primary'>Edit</a>  <a href='delete_teacher.php?id=" . urlencode($row['teacher_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete " . htmlspecialchars($row['teacher_name']) . "? This cannot be undone.');\">Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>There are no registered teachers.</p>";
                }
            }

            if(isset($_GET['classes'])) {
                $_SESSION['table'] = 'classes';

                $sql = "SELECT classes.class_id, class_capacity, teacher_name
                        FROM classes
                        LEFT JOIN teachers ON classes.class_id = teachers.class_id";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th>Class Name</th><th>Capacity</th><th>Assigned Teacher</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . class_name(htmlspecialchars($row['class_id'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['class_capacity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['teacher_name'] ?? 'Unassigned') . "</td>";
                        echo "<td><a href='edit_class.php?id=" . urlencode($row['class_id']) . "' class='btn btn-primary'>Edit</a>  <a href='delete_class.php?id=" . urlencode($row['class_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete " . class_name(htmlspecialchars($row['class_id'])) . "? This cannot be undone.');\">Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>There are no registered classes.</p>";
                }
            }

            ?>
        </section>
    </body>
</html>