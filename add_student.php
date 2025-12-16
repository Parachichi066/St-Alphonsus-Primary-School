<?php

include "connection.php";

if(isset($_POST['cancel'])) {
    redirect();
}

if (isset($_POST['add'])) {
    $student_name = $_POST['student_name'];
    $age = $_POST['age'];
    $medical_information = $_POST['medical_information'];
    $class_id = $_POST['class_id'];
    $student_address = $_POST['student_address'];
    
    $sql = "INSERT INTO students (student_name, age, medical_information, class_id, student_address) VALUES ('$student_name', '$age', '$medical_information', '$class_id', '$student_address')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['action'] = 'add';
        redirect();
    } else {
        echo "<p class='error'>Error adding student details: " . $conn->error . "</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Student</title>
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
        <main class="container mt-4">
            <h1>Add Student</h1>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="student_name" class="form-label">Student Name</label>
                    <input type="text" class="form-control" id="student_name" name="student_name">
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age">
                </div>                
                <div class="mb-3">
                    <label for="class_id" class="form-label">Assigned Year</label>
                    <select class="form-select" id="class_id" name="class_id">
                        <?php

                        $sql_classes_list = "SELECT * FROM classes";
                        $classes_result = $conn->query($sql_classes_list);
                        if ($classes_result->num_rows > 0) {
                            while ($class_row = $classes_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($class_row['class_id']) . "'>" . htmlspecialchars($class_row['class_name']) . "</option>";
                            }
                        }

                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="student_address" class="form-label">Student Address</label>
                    <input type="text" class="form-control" id="student_address" name="student_address">
                </div>
                <div class="mb-3">
                    <label for="medical_information" class="form-label">Medical Information</label>
                    <input type="text" class="form-control" id="medical_information" name="medical_information">
                </div>
                <button type="submit" class="btn btn-primary" name="add">Add</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
        </main>
    </body>
</html>