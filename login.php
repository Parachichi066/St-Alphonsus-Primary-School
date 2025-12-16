<?php
// Start session at the very top before any HTML
session_start();
include 'connection.php'; // Ensures database connection and redirect() function are available
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log in</title>
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
                            <a class="nav-link" href="login.php">Log in</a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <section class="form student-form">
            <h2>Sign in to your account</h2>
            
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <input type="submit" class="btn btn-primary" value="Sign In">
                <div class="mt-3">
                    <small>No account? <a href="register.php">Register here</a></small>
                </div>
            </form>

            <?php
            if(isset($_SESSION['action']) && $_SESSION['action'] == 'register') {
                echo "<p class='success mt-3'>Registration successful! Please log in.</p>";
                $_SESSION['action'] = null;
            }

            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];

                // 1. Prepare Statement to prevent SQL Injection
                // We ONLY check the username here, not the password yet
                $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                // 2. Check if user exists
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    
                    // 3. Verify the password hash
                    // This checks the typed "password123" against the "$2y$10$..." hash in the DB
                    if (password_verify($password, $user['password'])) {
                        
                        // Success: Set session variables
                        $_SESSION['id'] = $user['user_id'];
                        $_SESSION['role'] = $user['role'];
                        
                        // Redirect using your custom function
                        redirect();
                        exit();
                        
                    } else {
                        // Password incorrect
                        echo "<p class='invalid mt-3 text-danger'>Invalid username or password.</p>";
                    }
                } else {
                    // Username incorrect
                    echo "<p class='invalid mt-3 text-danger'>Invalid username or password.</p>";
                }
                
                $stmt->close();
            }
            ?>
        </section>
    </body>
</html>

