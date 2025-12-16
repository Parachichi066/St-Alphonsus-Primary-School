<?php
// Start session and include necessary functions and connections
include "connection.php";

// Check if user is admin
if($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Function to redirect to previous page
if(isset($_POST['cancel'])) {
    redirect();
}

// Handle form submission to add a new teacher
if (isset($_POST['add'])) {
    $teacher_name = $_POST['teacher_name'];
    $teacher_email = $_POST['teacher_email'];
    $teacher_telephone = $_POST['teacher_telephone'];
    $teacher_address = $_POST['teacher_address'];
    $teacher_salary = $_POST['teacher_salary'];
    $background_check = $_POST['background_check'];
    $class_id = $_POST['class_id'];

    if (empty($teacher_name) || empty($teacher_email) || empty($class_id)) {
        echo "<p class='alert alert-danger'>Teacher Name, Email, and Assigned Year are required fields.</p>";
    } else {

    // Prepare and bind
    $sql = "INSERT INTO teachers (teacher_name, teacher_email, teacher_telephone, teacher_address, teacher_salary, background_check, class_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // s=string, d=double (salary), i=integer
    $stmt->bind_param("ssssdii", $teacher_name, $teacher_email, $teacher_telephone, $teacher_address, $teacher_salary, $background_check, $class_id);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['action'] = 'add';
        redirect();
        exit();
    } else {
        echo "<p class='alert alert-danger'>Error adding Teacher details: " . $conn->error . "</p>";
    }
    $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Teacher</title>
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
            <h1>Add Teacher</h1>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="teacher_name" class="form-label">Teacher Name</label>
                    <input type="text" class="form-control" id="teacher_name" name="teacher_name">
                </div>
                <div class="mb-3">
                    <label for="teacher_email" class="form-label">Teacher Email</label>
                    <input type="email" class="form-control" id="teacher_email" name="teacher_email">
                </div>
                <div class="mb-3">
                    <label for="teacher_telephone" class="form-label">Teacher Telephone</label>
                    <input type="tel" class="form-control" id="teacher_telephone" name="teacher_telephone">
                </div>
                <div class="mb-3">
                    <label for="teacher_address" class="form-label">Teacher Address</label>
                    <input type="text" class="form-control" id="teacher_address" name="teacher_address">
                </div>
                <div class="mb-3">
                    <label for="teacher_salary" class="form-label">Teacher Salary</label>
                    <input type="float" class="form-control" id="teacher_salary" name="teacher_salary">
                </div>
                <div class="mb-3">
                    <label for="background_check" class="form-label">Background Check Status</label>
                    <select class="form-select" id="background_check" name="background_check">
                        <option value="0">Pending</option>
                        <option value="1">Cleared</option>
                    </select>
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
                <button type="submit" class="btn btn-primary" name="add">Add</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
        </main>
    </body>
</html>