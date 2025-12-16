<?php

include "connection.php";

$id = $_GET['id'];
$sql = "SELECT * FROM teachers WHERE teacher_id='$id'";
$result = $conn->query($sql)->fetch_assoc();

if(isset($_POST['cancel'])) {
    redirect();
}

if (isset($_POST['save'])) {
    $teacher_name = $_POST['teacher_name'];
    $teacher_email = $_POST['teacher_email'];
    $teacher_telephone = $_POST['teacher_telephone'];
    $teacher_address = $_POST['teacher_address'];
    $teacher_salary = $_POST['teacher_salary'];
    $background_check = $_POST['background_check'];
    $class_id = $_POST['class_id'];
    $user_id = $_POST['user_id'];

    $sql = "UPDATE teachers SET teacher_name='$teacher_name', teacher_email='$teacher_email', teacher_telephone='$teacher_telephone', teacher_address='$teacher_address', teacher_salary='$teacher_salary', background_check='$background_check', class_id='$class_id', user_id='$user_id' WHERE teacher_id='$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['action'] = 'edit';
        redirect();
    } else {
        echo "<p class='error'>Error updating Teacher details: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Teacher</title>
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
            <h1>Edit Teacher</h1>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="teacher_name" class="form-label">Teacher Name</label>
                    <input type="text" class="form-control" id="teacher_name" name="teacher_name" value="<?php echo htmlspecialchars($result['teacher_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="teacher_email" class="form-label">Teacher Email</label>
                    <input type="email" class="form-control" id="teacher_email" name="teacher_email" value="<?php echo htmlspecialchars($result['teacher_email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="teacher_telephone" class="form-label">Teacher Telephone</label>
                    <input type="tel" class="form-control" id="teacher_telephone" name="teacher_telephone" value="<?php echo htmlspecialchars($result['teacher_telephone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="teacher_address" class="form-label">Teacher Address</label>
                    <input type="text" class="form-control" id="teacher_address" name="teacher_address" value="<?php echo htmlspecialchars($result['teacher_address']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="teacher_salary" class="form-label">Teacher Salary</label>
                    <input type="float" class="form-control" id="teacher_salary" name="teacher_salary" value="<?php echo htmlspecialchars($result['teacher_salary']); ?>" required>
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
                <div class="mb-3">
                    <label for="user_id" class="form-label">Linked User Account</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">-- No User Account Linked --</option>
                        <?php
                        // 1. Fetch all users (you might want to filter WHERE role='parent')
                        $user_sql = "SELECT user_id, username, role FROM users WHERE user_id != 1"; // Exclude admin
                        $user_result = $conn->query($user_sql);

                        if ($user_result->num_rows > 0) {
                            while ($u_row = $user_result->fetch_assoc()) {
                                // 2. Check if this user is the one currently saved for this parent
                                $selected = ($u_row['user_id'] == $result['user_id']) ? 'selected' : '';
                                
                                // 3. Display the option (e.g., "john_doe (Teacher)")
                                echo "<option value='" . $u_row['user_id'] . "' $selected>" 
                                     . htmlspecialchars($u_row['username']) . " (" . htmlspecialchars($u_row['role']) . ")" 
                                     . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <div class="form-text">Select the login account that belongs to this teacher.</div>
                </div>
                <button type="submit" class="btn btn-primary" name="save">Save</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
        </main>
    </body>
</html>