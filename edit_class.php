<?php

include "connection.php";

$id = $_GET['id'];
$sql = "SELECT * FROM classes WHERE class_id='$id'";
$result = $conn->query($sql)->fetch_assoc();

if(isset($_POST['cancel'])) {
    redirect();
}

if (isset($_POST['save'])) {
    $class_name = $_POST['class_name'];
    $class_email = $_POST['class_email'];
    $class_telephone = $_POST['class_telephone'];
    $class_address = $_POST['class_address'];
    $class_salary = $_POST['class_salary'];
    $background_check = $_POST['background_check'];
    $class_id = $_POST['class_id'];

    $sql = "UPDATE classes SET class_name='$class_name', class_email='$class_email', class_telephone='$class_telephone', class_address='$class_address', class_salary='$class_salary', background_check='$background_check', class_id='$class_id' WHERE class_id='$id'";

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
        <title>Edit class</title>
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
            <h1>Edit class</h1>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="class_name" class="form-label">class Name</label>
                    <input type="text" class="form-control" id="class_name" name="class_name" value="<?php echo htmlspecialchars($result['class_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="class_email" class="form-label">class Email</label>
                    <input type="email" class="form-control" id="class_email" name="class_email" value="<?php echo htmlspecialchars($result['class_email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="class_telephone" class="form-label">class Telephone</label>
                    <input type="tel" class="form-control" id="class_telephone" name="class_telephone" value="<?php echo htmlspecialchars($result['class_telephone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="class_address" class="form-label">class Address</label>
                    <input type="text" class="form-control" id="class_address" name="class_address" value="<?php echo htmlspecialchars($result['class_address']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="class_salary" class="form-label">class Salary</label>
                    <input type="float" class="form-control" id="class_salary" name="class_salary" value="<?php echo htmlspecialchars($result['class_salary']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="background_check" class="form-label">Background Check Status</label>
                    <select class="form-select" id="background_check" name="background_check" required>
                        <option value="1" <?php if ($result['background_check']) echo 'selected'; ?>>Cleared</option>
                        <option value="0" <?php if (!$result['background_check']) echo 'selected'; ?>>Pending</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="class_id" class="form-label">Assigned Year</label>
                    <select class="form-select" id="class_id" name="class_id" required>
                        <option value="1" <?php if ($result['class_id'] == 1) echo 'selected'; ?>>Reception Year</option>
                        <option value="2" <?php if ($result['class_id'] == 2) echo 'selected'; ?>>Year One</option>
                        <option value="3" <?php if ($result['class_id'] == 3) echo 'selected'; ?>>Year Two</option>
                        <option value="4" <?php if ($result['class_id'] == 4) echo 'selected'; ?>>Year Three</option>
                        <option value="5" <?php if ($result['class_id'] == 5) echo 'selected'; ?>>Year Four</option>
                        <option value="6" <?php if ($result['class_id'] == 6) echo 'selected'; ?>>Year Five</option>
                        <option value="7" <?php if ($result['class_id'] == 7) echo 'selected'; ?>>Year Six</option>
                    </select>
                </div>                
                <button type="submit" class="btn btn-primary" name="save">Save</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
        </main>
    </body>
</html>