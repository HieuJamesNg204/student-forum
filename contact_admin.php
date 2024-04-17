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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <button class="btn btn-primary btn-sm" onclick="backIndex()">< Home page</button>
    <h2>Contact Admin</h2>
    <form action="send_email.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>

<script src="js/back_home.js"></script>
</body>
</html>