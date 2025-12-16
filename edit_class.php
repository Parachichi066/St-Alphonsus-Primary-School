<?php
// Include session, connection and redirect functions
include "connection.php";

// Redirect if not admin
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

// Function to redirect to classes list
$id = $_GET['id'];
$result = null;

// Fetch class details
$stmt = $conn->prepare("SELECT * FROM classes WHERE class_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
// Check if class exists
if($res->num_rows == 0) { die("Class not found."); }
$result = $res->fetch_assoc();
$stmt->close();

// Handle Cancel
if(isset($_POST['cancel'])) {
    redirect();
    exit();
}

// Handle Save
if (isset($_POST['save'])) {
    $class_name = trim($_POST['class_name']);
    $class_capacity = $_POST['class_capacity'];

    // Validate required fields
    if(empty($class_name) || empty($class_capacity)) {
        echo "<p class='alert alert-danger'>Class name and capacity are required.</p>";
    } else {
        // Update class data securely
        $sql = "UPDATE classes SET class_name=?, class_capacity=? WHERE class_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $class_name, $class_capacity, $id);

        // Execute the update
        if ($stmt->execute()) {
            $_SESSION['action'] = 'edit';
            redirect();
            exit();
        } else {
            echo "<p class='alert alert-danger'>Error updating class: " . $conn->error . "</p>";
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
        <title>Edit Class</title>
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
            <h1>Edit Class</h1>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="class_name" class="form-label">Class Name</label>
                    <input type="text" class="form-control" id="class_name" name="class_name" value="<?php echo htmlspecialchars($result['class_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="class_capacity" class="form-label">Class Capacity</label>
                    <input type="num" class="form-control" id="class_capacity" name="class_capacity" value="<?php echo htmlspecialchars($result['class_capacity']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary" name="save">Save</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
        </main>
    </body>
</html>