<?php
// Handle session and redirection
include "connection.php";

// Prevent unauthorised access
if ($_SESSION['role'] == "teacher") {
    header("Location: login.php");
    exit();
}

// Prevent unauthorised parents
if ($_SESSION['role'] == 'parent') {
    $sql_parent = "SELECT * FROM parents
                    INNER JOIN users ON parents.user_id = users.user_id
                    INNER JOIN student_parent ON parents.parent_id = student_parent.parent_id
                    INNER JOIN students ON student_parent.student_id = students.student_id
                    WHERE students.student_id = ? AND parents.user_id = ?";
    $stmt_authorise = $conn->prepare($sql_parent);
    $stmt_authorise->bind_param('ii', $_GET['id'], $_SESSION['id']);
    $stmt_authorise->execute();
    // Redirect when wrong parent attempts to edit
    if ($stmt_authorise->get_result()->num_rows == 0) {
        redirect();
    }
}


// Handle linking and unlinking parents
if (isset($_POST['link_parent'])) {
    $parent_id = $_POST['parent_id'];
    $student_id = $_GET['id'];

    // Checks if already linked to avoid duplicates
    $check_sql = "SELECT * FROM student_parent WHERE student_id = ? AND parent_id = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ii", $student_id, $parent_id);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows == 0) {
        // Link them
        $stmt_link = $conn->prepare("INSERT INTO student_parent (student_id, parent_id) VALUES (?, ?)");
        $stmt_link->bind_param("ii", $student_id, $parent_id);
        if($stmt_link->execute()) {
            echo "<div class='alert alert-success m-3'>Parent linked successfully!</div>";
        }
        $stmt_link->close();
    } else {
        // Already linked
        echo "<div class='alert alert-warning m-3'>This parent is already linked.</div>";
    }
    $stmt_check->close();
}

// Unlink parent
if (isset($_POST['unlink_parent'])) {
    $parent_id_to_remove = $_POST['remove_parent_id'];
    $student_id = $_GET['id'];

    // Unlink them using prepared statement
    $stmt_unlink = $conn->prepare("DELETE FROM student_parent WHERE student_id = ? AND parent_id = ?");
    $stmt_unlink->bind_param("ii", $student_id, $parent_id_to_remove);
    if($stmt_unlink->execute()) {
        echo "<div class='alert alert-success m-3'>Parent unlinked successfully.</div>";
    }
    $stmt_unlink->close();
}

$id = $_GET['id'];
$student = null;

// Fetch current student details safely
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if student exists
if($result->num_rows == 0) { die("Student not found."); }
$student = $result->fetch_assoc();
$stmt->close();

// Redirect function when cancelling
if(isset($_POST['cancel'])) {
    redirect();
}

// Update student details
if (isset($_POST['save'])) {
    $student_name = $_POST['student_name'];
    $age = $_POST['age'];
    // Only allow certain roles to update specific fields
    if($_SESSION['role'] == 'admin') {
        $class_id = $_POST['class_id'];
    } else {
        $class_id = $student['class_id'];
    }
    $student_address = $_POST['student_address'];
    $medical_information = $_POST['medical_information'];

    // Server-side validation
    if(empty($student_name)) {
        echo "<p class='alert alert-danger'>Student Name is required.</p>";
    } else {
        if($_SESSION['role'] == 'admin') {
            $new_class_id = $_POST['class_id'];
        } else {
            $new_class_id = $student['class_id']; // Keep existing
        }

        // CHECK: Only enforce capacity if the class is DIFFERENT from their current one
        if ($new_class_id != $student['class_id']) {
            
            // Run the same check as above
            $cap_sql = "SELECT classes.class_capacity, 
                            (SELECT COUNT(*) FROM students WHERE class_id = ?) as current_count 
                        FROM classes 
                        WHERE class_id = ?";
            
            $stmt_cap = $conn->prepare($cap_sql);
            $stmt_cap->bind_param("ii", $new_class_id, $new_class_id);
            $stmt_cap->execute();
            $cap_result = $stmt_cap->get_result()->fetch_assoc();
            $stmt_cap->close();

            if ($cap_result && $cap_result['current_count'] >= $cap_result['class_capacity']) {
                echo "<p class='alert alert-danger'>Error: Target class is full ({$cap_result['current_count']}/{$cap_result['class_capacity']}). Move denied.</p>";
                goto end_save; // Skip the update
            }
        }
        // Update using prepared statements to prevent SQL injection
        $sql = "UPDATE students SET student_name=?, age=?, class_id=?, student_address=?, medical_information=? WHERE student_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssi", $student_name, $age, $class_id, $student_address, $medical_information, $id);

        // Execute and check for success
        if ($stmt->execute()) {
            $_SESSION['action'] = 'edit';
            redirect();
            exit();
        } else {
            echo "<p class='alert alert-danger'>Error updating student: " . $conn->error . "</p>";
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
                    <input type="text" class="form-control" id="student_name" name="student_name" value="<?php echo htmlspecialchars($student['student_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($student['age']); ?>" required>
                </div>
                <?php

                if ($_SESSION['role'] == 'admin') {
                    ?>
                
                <div class="mb-3">
                    <label for="class_id" class="form-label">Assigned Year</label>
                    <select class="form-select" id="class_id" name="class_id" required>
                        <?php
                        // Populate classes dropdown
                        $sql_classes_list = "SELECT * FROM classes";
                        $classes_result = $conn->query($sql_classes_list);
                        if ($classes_result->num_rows > 0) {
                            while ($class_row = $classes_result->fetch_assoc()) {
                                $selected = ($class_row['class_id'] == $student['class_id']) ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($class_row['class_id']) . "' $selected>" . htmlspecialchars($class_row['class_name']) . "</option>";
                            }
                        }

                        ?>
                    </select>
                </div>
                    <?php
                }
                // Only show address field to parents and admins
                if ($_SESSION['role'] == 'parent' || $_SESSION['role'] == 'admin') {
                    ?>
                <div class="mb-3">
                    <label for="student_address" class="form-label">Student Address</label>
                    <input type="text" class="form-control" id="student_address" name="student_address" value="<?php echo htmlspecialchars($student['student_address']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="medical_information" class="form-label">Medical Information</label>
                    <input type="text" class="form-control" id="medical_information" name="medical_information" value="<?php echo htmlspecialchars($student['medical_information']); ?>" required>
                </div>
                    <?php
                }
                ?>
                <button type="submit" class="btn btn-primary" name="save">Save</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
            <?php
            // Only show parent management to admins
            if ($_SESSION['role'] == 'admin') {

            ?>
            <hr class="my-5">

            <div class="card bg-light mb-5">
                <div class="card-header">
                    <h4>Manage Guardians / Parents</h4>
                </div>
                <div class="card-body">
                    
                    <h5>Current Parents/Guardians</h5>
                    <table class="table table-bordered bg-white">
                        <thead>
                            <tr>
                                <th>Parent Name</th>
                                <th>Telephone</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $current_id = $_GET['id'];
                            // Join query to find parents linked to this specific student
                            $sql_parents = "SELECT parents.parent_id, parents.parent_name, parents.parent_telephone, parent_address 
                                            FROM parents 
                                            INNER JOIN student_parent ON parents.parent_id = student_parent.parent_id 
                                            WHERE student_parent.student_id = ?";
                            
                            // Prepare and execute
                            $stmt_p = $conn->prepare($sql_parents);
                            $stmt_p->bind_param("i", $current_id);
                            $stmt_p->execute();
                            $res_p = $stmt_p->get_result();

                            // Display linked parents
                            if ($res_p->num_rows > 0) {
                                while($p_row = $res_p->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($p_row['parent_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($p_row['parent_telephone']) . "</td>";
                                    echo "<td>" . htmlspecialchars($p_row['parent_address']) . "</td>";
                                    echo "<td>
                                            <form method='POST' style='display:inline;' onsubmit=\"return confirm('Unlink this parent?');\">
                                                <input type='hidden' name='remove_parent_id' value='" . $p_row['parent_id'] . "'>
                                                <button type='submit' name='unlink_parent' class='btn btn-sm btn-danger'>Unlink</button>
                                            </form>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-muted'>No parents linked yet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <h5 class="mt-4">Link a New Parent</h5>
                    <form method="POST" class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label">Select Parent from Database</label>
                            <select name="parent_id" class="form-select" required>
                                <option value="">-- Choose Parent --</option>
                                <?php
                                // Populate dropdown with ALL parents
                                $all_parents = $conn->query("SELECT parent_id, parent_name, parent_email FROM parents ORDER BY parent_name ASC");
                                while($all_p = $all_parents->fetch_assoc()) {
                                    echo "<option value='" . $all_p['parent_id'] . "'>" . htmlspecialchars($all_p['parent_name']) . " (" . htmlspecialchars($all_p['parent_email']) . ")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="link_parent" class="btn btn-success w-100">Link Parent</button>
                        </div>
                    </form>
                    <div class="mt-2">
                         <small>Parent not in list? <a href="add_parent.php">Register them here</a> first.</small>
                    </div>

                </div>
            </div>
            <?php

            }

            ?>
        </main>
    </body>
</html>