<?php

include "connection.php";

// --- HANDLE PARENT LINKING/UNLINKING ---
if (isset($_POST['link_parent'])) {
    $parent_id = $_POST['parent_id'];
    $student_id = $_GET['id'];

    // 1. Check if already linked to avoid duplicates
    $check_sql = "SELECT * FROM student_parent WHERE student_id = ? AND parent_id = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ii", $student_id, $parent_id);
    $stmt_check->execute();
    
    if ($stmt_check->get_result()->num_rows == 0) {
        // 2. Link them
        $stmt_link = $conn->prepare("INSERT INTO student_parent (student_id, parent_id) VALUES (?, ?)");
        $stmt_link->bind_param("ii", $student_id, $parent_id);
        if($stmt_link->execute()) {
            echo "<div class='alert alert-success m-3'>Parent linked successfully!</div>";
        }
        $stmt_link->close();
    } else {
        echo "<div class='alert alert-warning m-3'>This parent is already linked.</div>";
    }
    $stmt_check->close();
}

if (isset($_POST['unlink_parent'])) {
    $parent_id_to_remove = $_POST['remove_parent_id'];
    $student_id = $_GET['id'];

    $stmt_unlink = $conn->prepare("DELETE FROM student_parent WHERE student_id = ? AND parent_id = ?");
    $stmt_unlink->bind_param("ii", $student_id, $parent_id_to_remove);
    if($stmt_unlink->execute()) {
        echo "<div class='alert alert-success m-3'>Parent unlinked successfully.</div>";
    }
    $stmt_unlink->close();
}

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

    $sql = "UPDATE students SET student_name='$student_name', age='$age', class_id='$class_id', student_address='$student_address', medical_information='$medical_information' WHERE student_id='$id'";

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
            <?php

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
                            
                            $stmt_p = $conn->prepare($sql_parents);
                            $stmt_p->bind_param("i", $current_id);
                            $stmt_p->execute();
                            $res_p = $stmt_p->get_result();

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