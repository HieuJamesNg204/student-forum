<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: intro.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["postId"])) {
    $postId = $_POST["postId"];
    $post = getPostById($postId);

    $userId = $_SESSION["user_id"];
    if ($post['user_id'] != $userId) {
        echo "<script>alert('Sorry! You cannot delete or modify other users\' posts'); window.location.href='index.php';</script>";
        exit();
    }

    $modules = getModules();
} else {
    // Redirect to index if post ID is not provided
    echo "<script>alert('Post ID is not provided!'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <button class="btn btn-primary btn-sm" onclick="backIndex()">< Home page</button>
    <h2>Edit Post</h2>
    <form action="process_edit_post.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="postId" value="<?php echo $postId; ?>">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $post['title']; ?>" required>
        </div>
        <div class="form-group">
            <label for="module">Module:</label>
            <select class="form-control" id="module" name="module" required>
                <?php foreach ($modules as $module): ?>
                    <?php if ($module['id'] == $post['module_id']): ?>
                        <option value="<?php echo $module['id']; ?>" selected><?php echo $module['module_name']; ?></option>
                    <?php else: ?>
                        <option value="<?php echo $module['id']; ?>"><?php echo $module['module_name']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="6" required><?php echo $post['content']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control-file" id="image" name="image">
            <?php if (!empty($post['image'])): ?>
                <img src="images/<?php echo $post['image']; ?>" alt="Post Image" class="mt-3 img-fluid">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script src="js/back_home.js"></script>
</body>
</html>