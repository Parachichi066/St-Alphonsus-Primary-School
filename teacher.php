<?php

include 'connection.php';
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM teachers WHERE user_id='{$_SESSION['id']}'";
$result = $conn->query($sql)->fetch_assoc();
$teacher = $result['teacher_name'];
$class = $result['class_id'];
$class_name = "";
switch ($class) {
    case 1:
        $class_name = "Reception Year";
        break;
    case 2:
        $class_name = "Year One";
        break;
    case 3:
        $class_name = "Year Two";
        break;
    case 4:
        $class_name = "Year Three";
        break;
    case 5:
        $class_name = "Year Four";
        break;
    case 6:
        $class_name = "Year Five";
        break;
    case 7:
        $class_name = "Year Six";
        break;
}

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

            $sql = "SELECT s.student_id, s.student_name, s.age, s.medical_information
                FROM students s
                INNER JOIN classes c ON s.class_id = c.class_id
                INNER JOIN teachers t ON c.teacher_id = t.teacher_id
                WHERE t.user_id = {$_SESSION['id']}";            
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
                    echo "<td><a href='student.php?id=" . urlencode($row['student_id']) . "' class='btn btn-primary'>Edit</a>  <a href='delete_student.php?id=" . urlencode($row['student_id']) . "' class='delete btn btn-primary'>Delete</a></td>";

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