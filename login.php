<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log in</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="bootstrap-5.3.8-dist/css/bootstrap.css">
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
            <form action="#" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                <input type="submit" value="Submit">
            </form>
            <?php

            include 'connection.php';

            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $role = $result->fetch_assoc()['role'];
                    if ($role == 'admin') {
                        header("Location: admin.php");
                    } elseif ($role == 'teacher') {
                        header("Location: teacher.php");
                    } else {
                        header("Location: parent.php");
                    }
                } else {
                    echo "<p class='invalid'>Invalid username or password. Please try again.</p>";
                }
            }

            ?>
        </section>
    </body>
</html>