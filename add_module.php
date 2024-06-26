<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: intro.php");
    exit();
}

$userId = $_SESSION["user_id"];
$user = getUserById($userId);
if ($user["role"] === "user") {
    echo "<script>alert('Sorry! Only admin can add a new module'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Modules</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
<button class="btn btn-primary btn-sm" onclick="window.location.href = 'index.php';">< Home page</button>
    <h2>Add a module</h2>

    <!-- Add Module Form -->
    <form action="process_add_module.php" method="post" class="mt-3">
        <div class="form-group">
            <label for="module_name">Module Name</label>
            <input type="text" class="form-control" id="module_name" name="module_name" required>
        </div>
        <button type="submit" class="btn btn-primary" name="add_module">Add Module</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>