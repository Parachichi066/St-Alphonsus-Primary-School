<?php
// Start session and include necessary functions and connections
include "connection.php";

// Function to redirect to parents list
if (!isset($_SESSION['role']) != 'admin') {
    header("Location: login.php");
}

// Function to redirect to parents list
if(isset($_POST['cancel'])) {
    redirect();
}

// Handle form submission for adding a parent
if (isset($_POST['add'])) {
    $parent_name = trim($_POST['parent_name']);
    $parent_email = trim($_POST['parent_email']);
    $parent_telephone = $_POST['parent_telephone'];
    $parent_address = $_POST['parent_address'];

    if (empty($parent_name) || empty($parent_email)) {
        echo "<p class='alert alert-danger'>Parent Name and Email are required fields.</p>";
    } else {

    // Validate required fields
    $sql = "INSERT INTO parents (parent_name, parent_email, parent_telephone, parent_address) VALUES (?, ?, ?, ?)";
    
    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $parent_name, $parent_email, $parent_telephone, $parent_address);

    if ($stmt->execute()) {
        $_SESSION['action'] = 'add';
        redirect();
        exit();
    } else {
        // Error handling
        echo "<p class='alert alert-danger'>Error adding parent details: " . $conn->error . "</p>";
    }
    stmt->close();
    }
}
            
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Parent</title>
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
            <h1>Add Parent</h1>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="parent_name" class="form-label">Parent Name</label>
                    <input type="text" class="form-control" id="parent_name" name="parent_name">
                </div>
                <div class="mb-3">
                    <label for="parent_email" class="form-label">Parent Email</label>
                    <input type="email" class="form-control" id="parent_email" name="parent_email">
                </div>
                <div class="mb-3">
                    <label for="parent_telephone" class="form-label">Parent Telephone</label>
                    <input type="tel" class="form-control" id="parent_telephone" name="parent_telephone">
                </div>
                <div class="mb-3">
                    <label for="parent_address" class="form-label">Parent Address</label>
                    <input type="text" class="form-control" id="parent_address" name="parent_address">
                </div>
                <button type="submit" class="btn btn-primary" name="add">Add</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
        </main>
    </body>
</html>