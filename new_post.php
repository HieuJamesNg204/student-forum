<?php
// Include database connection
include 'includes/db.php';
include 'includes/functions.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: intro.php");
    exit();
}

// Fetch modules from the database
$modules = getModules();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <button class="btn btn-primary btn-sm" onclick="backIndex()">< Home page</button>
    <h2>New Post</h2>
    <form action="process_new_post.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="module">Module:</label>
            <select class="form-control" id="module" name="module" required>
                <option value="">Select Module</option>
                <?php foreach ($modules as $module) : ?>
                    <option value="<?php echo $module['id']; ?>"><?php echo $module['module_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control-file" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script src="js/back_home.js"></script>
</body>
</html>