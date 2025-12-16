<?php

include "connection.php";

if(isset($_POST['cancel'])) {
    redirect();
}

if (isset($_POST['add'])) {
    $class_name = $_POST['class_name'];
    $class_capacity = $_POST['class_capacity'];

    $sql = "INSERT INTO classes (class_name, class_capacity) VALUES ('$class_name', '$class_capacity')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['action'] = 'edit';
        redirect();
    } else {
        echo "<p class='error'>Error updating class details: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Class</title>
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
                    <input type="text" class="form-control" id="class_name" name="class_name">
                </div>
                <div class="mb-3">
                    <label for="class_capacity" class="form-label">Class Capacity</label>
                    <input type="num" class="form-control" id="class_capacity" name="class_capacity">
                </div>
                <button type="submit" class="btn btn-primary" name="add">Add</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
        </main>
    </body>
</html>