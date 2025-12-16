<?php
// Start session and include database connection
session_start();
include 'connection.php';

// Check if the form is submitted
if (isset($_POST['register'])) {
    // Remove whitespace and get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role']; 

    // Validate form data on the server side
    if (empty($username) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Username already taken
            $error = "Username is already taken. Please choose another.";
        } else {
            // Hash the password and insert new user into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $username, $hashed_password, $role);

            // Execute the insert statement
            if ($insert_stmt->execute()) {
                // Registration successful, redirect to login page, display success message
                $_SESSION['action'] = 'register';
                header("Location: login.php");
                exit();
            } else {
                // Error handling
                $error = "Registration failed: " . $conn->error;
            }
            // Close the insert statement
            $insert_stmt->close();
        }
        // Close the check statement
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register your account</title>
        <link rel="stylesheet" href="bootstrap-5.3.8-dist/css/bootstrap.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <div class="register-container">
                <h3 class="text-center mb-4">Create Account</h3>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">I am a...</label>
                        <select name="role" class="form-select" required>
                            <option value="parent">Parent</option>
                            <option value="teacher">Teacher</option>
                            </select>
                    </div>

                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                
                </form>
                <div class="mt-3 text-center">
                    <small>Already have an account? <a href="login.php">Login here</a></small>
                </div>
            </div>
        </div>
    </body>
</html>