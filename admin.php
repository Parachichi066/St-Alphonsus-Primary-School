<?php
/*
 * Admin Dashboard Controller
 * --------------------------
 * The central hub for school administration.
 */

// Start session and include database connection
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
            <p class="text-muted">Manage the school here.</p>
            <div class="admin-actions">
                <form method="get">
                    <button class="btn btn-primary" type="submit" name="students" value="1">Manage Students</button>
                    <button class="btn btn-primary" type="submit" name="parents" value="1">Manage Parents</button>
                    <button class="btn btn-primary" type="submit" name="teachers" value="1">Manage Teachers</button>
                    <button class="btn btn-primary" type="submit" name="classes" value="1">Manage Classes</button>
                </form>
            </div>
            <?php
            // Feedback Messages for CRUD Operations
            if (isset($_SESSION['action'])) {
                switch ($_SESSION['action']) {
                    case 'add':
                        echo "<div class='alert alert-success'>Record added successfully.</div>";
                        break;
                    case 'edit':
                        echo "<div class='alert alert-success'>Record updated successfully.</div>";
                        break;
                    case 'delete':
                        echo "<div class='alert alert-success'>Record deleted successfully.</div>";
                        break;
                    default:
                        echo "<div class='alert alert-danger'>An error occurred. Please try again.</div>";
                        break;
                }
                unset($_SESSION['action']);
            }

            /*-------------------
            * STUDENTS MANAGEMENT
            * -------------------
            * View, filter, add, edit, and delete student records.
            */
            if(isset($_GET['students'])) {
                $_SESSION['table'] = 'students';

                // Form above the table to capture search inputs
                ?>
                <div class="card mb-3 p-3 bg-light">
                    <form method="GET" class="row g-3">
                        <input type="hidden" name="students" value="1">

                        <div class="col-md-4">
                            <input type="text" name="search_name" class="form-control" 
                                placeholder="Search by Student Name" 
                                value="<?php echo htmlspecialchars($_GET['search_name'] ?? ''); ?>">
                        </div>

                        <div class="col-md-4">
                            <select name="filter_class" class="form-select">
                                <option value="">-- All Classes --</option>
                                <?php
                                // Fetch  all classes from the database
                                $class_sql = "SELECT class_id, class_name FROM classes";
                                $class_result = $conn->query($class_sql);

                                if ($class_result->num_rows > 0) {
                                    // Loop through each class found
                                    while($c_row = $class_result->fetch_assoc()) {
                                        // Check if this option was previously selected (Sticky Input)
                                        $selected = '';
                                        if (isset($_GET['filter_class']) && $_GET['filter_class'] == $c_row['class_id']) {
                                            $selected = 'selected';
                                        }

                                        // Output the option tag
                                        echo "<option value='" . htmlspecialchars($c_row['class_id']) . "' $selected>" . htmlspecialchars($c_row['class_name']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="admin.php?students=1" class="btn btn-secondary">Reset</a>
                            <a href="add_student.php" class="btn btn-success ms-auto">Add Student</a>
                        </div>
                    </form>
                </div>
                <?php
                
                // Dynamic Sql Query Construction
                $sql = "SELECT students.*, classes.class_name 
                        FROM students
                        LEFT JOIN classes ON students.class_id = classes.class_id
                        WHERE 1=1"; // 'Allows easy appending of AND conditions

                $params = []; // Array to store parameters for binding
                $types = "";  // String to store types (s = string, i = integer)

                // Check if Name Search is used
                if (!empty($_GET['search_name'])) {
                    $sql .= " AND students.student_name LIKE ?";
                    $params[] = $_GET['search_name'] . "%"; // Wildcards for partial match
                    $types .= "s";
                }

                // Check if Class Filter is used
                if (!empty($_GET['filter_class'])) {
                    $sql .= " AND students.class_id = ?";
                    $params[] = $_GET['filter_class'];
                    $types .= "i";
                }

                // Prepared Statements to prevent SQL Injection
                $stmt = $conn->prepare($sql);

                if (!empty($params)) {
                    // Dynamically bind the parameters
                    $stmt->bind_param($types, ...$params);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                // Display Results in a Table
                if ($result->num_rows > 0) {
                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th>Name</th><th>Age</th><th>Year</th><th>Address</th><th>Medical Info</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['class_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['student_address']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['medical_information']) . "</td>";
                        echo "<td>
                                <a href='edit_student.php?id=" . urlencode($row['student_id']) . "' class='btn btn-primary'>Edit</a>  
                                <a href='delete_student.php?id=" . urlencode($row['student_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete " . htmlspecialchars($row['student_name']) . "? This cannot be undone.');\">Delete</a>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-warning'>No students found matching your filters.</div>";
                }
                
                // Close the statement to free resources
                $stmt->close();
            }

            /*-------------------
            * PARENTS MANAGEMENT
            * -------------------
            * View, filter, add, edit, and delete parent records.
            */
            if(isset($_GET['parents'])) {
                $_SESSION['table'] = 'parents';

                // Parent Filter Form
                ?>
                <div class="card mb-3 p-3 bg-light">
                    <form method="GET" class="row g-3">
                        <input type="hidden" name="parents" value="1">

                        <div class="col-md-5">
                            <input type="text" name="search_parent_name" class="form-control" 
                                placeholder="Search by Parent Name" 
                                value="<?php echo htmlspecialchars($_GET['search_parent_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="admin.php?parents=1" class="btn btn-secondary">Reset</a>
                            <a href="add_parent.php" class="btn btn-success ms-auto">Add Parent</a>
                        </div>
                    </form>
                </div>
                <?php

                // Dynamic SQL Construction
                $sql = "SELECT * FROM parents WHERE 1=1";
                $params = [];
                $types = "";

                // Filter by Name
                if (!empty($_GET['search_parent_name'])) {
                    $sql .= " AND parent_name LIKE ?";
                    $params[] = "%" . $_GET['search_parent_name'] . "%";
                    $types .= "s";
                }

                // Execute Prepared Statement
                $stmt = $conn->prepare($sql);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                // Teacher Table
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
                        echo "<td>
                                <a href='edit_parent.php?id=" . urlencode($row['parent_id']) . "' class='btn btn-primary'>Edit</a>  
                                <a href='delete_parent.php?id=" . urlencode($row['parent_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete " . htmlspecialchars($row['parent_name']) . "? This cannot be undone.');\">Delete</a>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    // No Results Found Message
                    echo "<div class='alert alert-warning'>No parents found matching your filters.</div>";
                }
                $stmt->close();
            }

            if(isset($_GET['teachers'])) {
                $_SESSION['table'] = 'teachers';

                // Teacher Filter Form
                ?>
                <div class="card mb-3 p-3 bg-light">
                    <form method="GET" class="row g-3">
                        <input type="hidden" name="teachers" value="1">

                        <div class="col-md-3">
                            <input type="text" name="search_teacher_name" class="form-control" 
                                placeholder="Search by Teacher Name" 
                                value="<?php echo htmlspecialchars($_GET['search_teacher_name'] ?? ''); ?>">
                        </div>

                        <div class="col-md-3">
                            <select name="filter_teacher_class" class="form-select">
                                <option value="">-- All Assigned Classes --</option>
                                <?php
                                // Dynamic Loop to display Classes
                                $class_sql = "SELECT class_id, class_name FROM classes";
                                $c_result = $conn->query($class_sql);
                                while($c_row = $c_result->fetch_assoc()) {
                                    $sel = (($_GET['filter_teacher_class'] ?? '') == $c_row['class_id']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($c_row['class_id']) . "' $sel>" . htmlspecialchars($c_row['class_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <select name="filter_bg" class="form-select">
                                <option value="">-- Background Check --</option>
                                <option value="1" <?php if(($_GET['filter_bg'] ?? '') == '1') echo 'selected'; ?>>Cleared</option>
                                <option value="0" <?php if(($_GET['filter_bg'] ?? '') == '0') echo 'selected'; ?>>Pending</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="admin.php?teachers=1" class="btn btn-secondary">Reset</a>
                            <a href="add_teacher.php" class="btn btn-success ms-auto">Add teacher</a>
                        </div>
                    </form>
                </div>
                <?php

                // Dynamic SQL Construction
                $sql = "SELECT teachers.*, classes.class_name 
                        FROM teachers
                        LEFT JOIN classes ON teachers.class_id = classes.class_id
                        WHERE 1=1";
                        
                $params = [];
                $types = "";

                // Name Search
                if (!empty($_GET['search_teacher_name'])) {
                    $sql .= " AND teacher_name LIKE ?";
                    $params[] = "%" . $_GET['search_teacher_name'] . "%";
                    $types .= "s";
                }

                // Class Filter
                if (!empty($_GET['filter_teacher_class'])) {
                    $sql .= " AND teachers.class_id = ?";
                    $params[] = $_GET['filter_teacher_class'];
                    $types .= "i";
                }

                // Background Check Filter
                if (isset($_GET['filter_bg']) && $_GET['filter_bg'] !== '') {
                    $sql .= " AND background_check = ?";
                    $params[] = $_GET['filter_bg'];
                    $types .= "i"; // Since background_check is stored as boolean/integer (0 or 1)
                }

                // Execute Prepared Statement
                $stmt = $conn->prepare($sql);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                // Display Teacher Table
                if ($result->num_rows > 0) {
                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th>Name</th><th>Email</th><th>Telephone</th><th>Salary</th><th>Background Check</th><th>Assigned Year</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['teacher_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['teacher_email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['teacher_telephone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['teacher_salary']) . "</td>";
                        echo "<td>" . bg_check_status(htmlspecialchars($row['background_check'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['class_name'] ?? 'Unassigned') . "</td>";
                        echo "<td>
                                <a href='edit_teacher.php?id=" . urlencode($row['teacher_id']) . "' class='btn btn-primary'>Edit</a>  
                                <a href='delete_teacher.php?id=" . urlencode($row['teacher_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete " . htmlspecialchars($row['teacher_name']) . "? This cannot be undone.');\">Delete</a>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    // No Results Found Message
                    echo "<div class='alert alert-warning'>No teachers found matching your filters.</div>";
                }
                $stmt->close();
            }

            /*-------------------
            * CLASSES MANAGEMENT
            * -------------------
            * View, add, edit, and delete class records.
            */
            if(isset($_GET['classes'])) {
                $_SESSION['table'] = 'classes';
                // Class Management Form
                ?>
                <div class="card mb-3 p-3 bg-light">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <a href="add_class.php" class="btn btn-success ms-auto">Add Class</a>
                        </div>
                    </form>
                </div>
                <?php
                // Fetch and Display Classes
                $sql = "SELECT classes.class_id, class_name, class_capacity, teacher_name
                        FROM classes
                        LEFT JOIN teachers ON classes.class_id = teachers.class_id
                        WHERE classes.class_id > 0";
                
                // Execute Query
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th>Class Name</th><th>Capacity</th><th>Assigned Teacher</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['class_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['class_capacity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['teacher_name'] ?? 'Unassigned') . "</td>";
                        echo "<td><a href='edit_class.php?id=" . urlencode($row['class_id']) . "' class='btn btn-primary'>Edit</a>  <a href='delete_class.php?id=" . urlencode($row['class_id']) . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete " . htmlspecialchars($row['class_name']) . "? This cannot be undone.');\">Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    // No Classes Found Message
                    echo "<p>There are no registered classes.</p>";
                }
            }

            ?>
        </section>
    </body>
</html>