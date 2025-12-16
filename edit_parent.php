<?php

include "connection.php";

$id = $_GET['id'];
$sql = "SELECT * FROM parents WHERE parent_id='$id'";
$result = $conn->query($sql)->fetch_assoc();

if(isset($_POST['cancel'])) {
    redirect();
}

if (isset($_POST['save'])) {
    $parent_name = $_POST['parent_name'];
    $parent_email = $_POST['parent_email'];
    $parent_telephone = $_POST['parent_telephone'];
    $parent_address = $_POST['parent_address'];
    $user_id = $_POST['user_id'];

    $sql = "UPDATE parents SET parent_name='$parent_name', parent_email='$parent_email', parent_telephone='$parent_telephone', parent_address='$parent_address', user_id='$user_id' WHERE parent_id='$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['action'] = 'edit';
        redirect();
    } else {
        echo "<p class='error'>Error updating parent details: " . $conn->error . "</p>";
    }
}
            
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Parent</title>
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
            <h1>Edit Parent</h1>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="parent_name" class="form-label">Parent Name</label>
                    <input type="text" class="form-control" id="parent_name" name="parent_name" value="<?php echo htmlspecialchars($result['parent_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="parent_email" class="form-label">Parent Email</label>
                    <input type="email" class="form-control" id="parent_email" name="parent_email" value="<?php echo htmlspecialchars($result['parent_email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="parent_telephone" class="form-label">Parent Telephone</label>
                    <input type="tel" class="form-control" id="parent_telephone" name="parent_telephone" value="<?php echo htmlspecialchars($result['parent_telephone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="parent_address" class="form-label">Parent Address</label>
                    <input type="text" class="form-control" id="parent_address" name="parent_address" value="<?php echo htmlspecialchars($result['parent_address']); ?>" required>
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
                                
                                // 3. Display the option (e.g., "john_doe (Parent)")
                                echo "<option value='" . $u_row['user_id'] . "' $selected>" 
                                     . htmlspecialchars($u_row['username']) . " (" . htmlspecialchars($u_row['role']) . ")" 
                                     . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <div class="form-text">Select the login account that belongs to this parent.</div>
                </div>
                <button type="submit" class="btn btn-primary" name="save">Save</button>
                <button type="submit" class="btn btn-danger" name="cancel">Cancel</button>
            </form>
        </main>
    </body>
</html>