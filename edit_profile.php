<?php 
session_start();
include 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page when user is not logged in
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$user = getUserById($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
<button class="btn btn-primary btn-sm" onclick="backIndex()">< Home page</button>
    <h2>Edit Profile</h2>
    <form action="process_edit_profile.php" method="post">
        <h4>Edit information</h4>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>

    <br>
    
    <form action="update_password.php" method="post">
        <h4>Change password</h4>
        <div class="form-group">
            <label for="oldPassword">Old password</label>
            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
        </div>
        <div class="form-group">
            <label for="newPassword">New password</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
        </div>
        <button type="submit" class="btn btn-primary">Change password</button>
    </form>
</div>

<script src="js/back_home.js"></script>
</body>
</html>