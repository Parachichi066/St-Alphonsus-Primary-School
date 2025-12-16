<?php
/* Add Student Functionality
 * -------------------------
 */

// Start session and include necessary functions and connections
include "connection.php";

// Function to redirect forbidden users
if (($_SESSION['role']) != 'admin') {
    header("Location: login.php");
    exit();
}

// Handle Cancel button
if(isset($_POST['cancel'])) {
    redirect();
}

// Handle Add Student form submission
if (isset($_POST['add'])) {
    // Sanitize form inputs
    $student_name = trim($_POST['student_name']);
    $age = $_POST['age'];
    $medical_information = $_POST['medical_information'];
    $class_id = $_POST['class_id'];
    $student_address = $_POST['student_address'];

    // Validate required fields
    if (empty($student_name) || empty($age) || empty($class_id) || empty($student_address)) {
        echo "<p class='alert alert-danger'>Name, Age, and Assigned Year are required fields.</p>";
    } else {
        // Check class capacity before adding student
        if (!empty($class_id)) {
            // Prepare a query to get the current count AND the max capacity in one go
            $cap_sql = "SELECT classes.class_capacity, 
                            (SELECT COUNT(*) FROM students WHERE class_id = ?) as current_count 
                        FROM classes 
                        WHERE class_id = ?";
            
            // Prepare and execute the statement
            $stmt_cap = $conn->prepare($cap_sql);
            $stmt_cap->bind_param("ii", $class_id, $class_id);
            $stmt_cap->execute();
            $cap_result = $stmt_cap->get_result()->fetch_assoc();
            $stmt_cap->close();

            // Check if class is full
            if ($cap_result) {
                $limit = $cap_result['class_capacity'];
                $current = $cap_result['current_count'];

                if ($current >= $limit) {
                    // STOP: The class is full
                    echo "<p class='alert alert-danger'>Error: This class is full ($current/$limit). Cannot add more students.</p>";
                    // We use a 'goto' to skip the INSERT part below
                    goto end_save;
                }
            }
        }
        if (empty($medical_information)) {
            $medical_information = "None"; // Default value if none provided
        }
        // Prepare and execute SQL insert statement
        $sql = "INSERT INTO students (student_name, age, medical_information, class_id, student_address) VALUES (?, ?, ?, ?, ?)";
            
        $stmt = $conn->prepare($sql);
        
        // s = string, i = integer
        $stmt->bind_param("sisis", $student_name, $age, $medical_information, $class_id, $student_address);

        if ($stmt->execute()) {
            $_SESSION['action'] = 'add'; // Set flash message for the next page
            redirect();
            exit();
        } else {
            // Error handling
            echo "<p class='alert alert-danger>Error adding student: " . $conn->error . "</p>";
        }
        $stmt->close();
    }
    end_save: {}
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
                        // Fetch classes from the database to populate the dropdown
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