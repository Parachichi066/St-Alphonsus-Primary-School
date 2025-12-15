<?php

include "connection.php";

$id = $_GET['id'];
$sql = "SELECT * FROM students WHERE student_id='$id'";
$result = $conn->query($sql)->fetch_assoc();

if(isset($_POST['cancel'])) {
    redirect();
}

if (isset($_POST['save'])) {
    $student_name = $_POST['student_name'];
    $age = $_POST['age'];
    $medical_information = $_POST['medical_information'];
    if($_SESSION['role'] == 'admin') {
        $class_id = $_POST['class_id'];
    } else {
        $class_id = $result['class_id'];
    }
    if($_SESSION['role'] == 'parent' || $_SESSION['role'] == 'admin') {
        $student_address = $_POST['student_address'];
    } else {
        $student_address = $result['student_address'];
    }

    $sql = "UPDATE students SET student_name='$student_name', age='$age', class_id='$class_id' student_address='$student_address', medical_information='$medical_information' WHERE student_id='$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['action'] = 'edit';
        redirect();
    } else {
        echo "<p class='error'>Error updating student details: " . $conn->error . "</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Student</title>
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
            <h1>Edit Student</h1>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="student_name" class="form-label">Student Name</label>
                    <input type="text" class="form-control" id="student_name" name="student_name" value="<?php echo htmlspecialchars($result['student_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($result['age']); ?>" required>
                </div>
                <?php

                if ($_SESSION['role'] == 'admin') {
                    ?>
                
                <div class="mb-3">
                    <label for="class_id" class="form-label">Assigned Year</label>
                    <select class="form-select" id="class_id" name="class_id" required>
                        <?php

                        $sql_classes_list = "SELECT * FROM classes";
                        $classes_result = $conn->query($sql_classes_list);
                        if ($classes_result->num_rows > 0) {
                            while ($class_row = $classes_result->fetch_assoc()) {
                                $selected = ($class_row['class_id'] == $result['class_id']) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($class_row['class_id']) . "' $selected>" . htmlspecialchars($class_row['class_name']) . "</option>";
                            }
                        }

                        ?>
                    </select>
                </div>
                    <?php
                }
                if ($_SESSION['role'] == 'parent' || $_SESSION['role'] == 'admin') {
                    ?>
                <div class="mb-3">
                    <label for="student_address" class="form-label">Student Address</label>
                    <input type="text" class="form-control" id="student_address" name="student_address" value="<?php echo htmlspecialchars($result['student_address']); ?>" required>
                </div>
                    <?php
                }
                ?>
                <div class="mb-3">
                    <label for="medical_information" class="form-label">Medical Information</label>
                    <input type="text" class="form-control" id="medical_information" name="medical_information" value="<?php echo htmlspecialchars($result['medical_information']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary" name="save">Save</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
        </main>
    </body>
</html>