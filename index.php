<?php
session_start();
include 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: intro.php");
    exit();
}

// Fetch user information
$userId = $_SESSION["user_id"];
$user = getUserById($userId);

// Logout if logout request received
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: intro.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Forum</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Student Forum</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <?php if ($user['role'] === "user"):?>
                <li class="nav-item">
                    <a class="nav-link" href="contact_admin.php">Contact Admin</a>
                </li>
            <?php endif;?>
            <?php if ($user['role'] === "admin"):?>
                <li class="nav-item">
                    <a class="nav-link" href="manage_users.php">Manage Users</a>
                </li>
            <?php endif;?>
            <li class="nav-item">
                <a class="nav-link" href="modules.php">View Modules</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="edit_profile.php">Edit Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?logout">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Welcome, <?php echo $user['username']; ?>!</h2>
    
    <button type="button" class="btn btn-primary mb-3" onclick="window.location.href='new_post.php'">Ask a Question</button>
    <?php if ($user['role'] === "admin"):?>
        <a href="add_module.php" class="btn btn-primary mb-3">Add a new module</a>
    <?php endif;?>
    
    <h3>Recent Questions</h3>
    <?php include 'posts.php'; ?>
</div>

</body>
</html>