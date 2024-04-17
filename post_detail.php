<?php
session_start();
include 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch user information
$userId = $_SESSION["user_id"];
$user = getUserById($userId);

// Logout if logout request received
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["postId"])) {
    $postId = $_POST["postId"];
    
    $query = "SELECT posts.*, users.username, modules.module_name
          FROM posts 
          INNER JOIN users ON posts.user_id = users.id
          INNER JOIN modules ON posts.module_id = modules.id
          WHERE posts.id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $postId);
    
    if ($stmt->execute()) {
        $comments = getCommentsForPost($postId);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<script>alert('Apology for an error while fetching the post! You can try again.'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Post ID is not provided'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Student Forum</a>
</nav>

<div class="container mt-5">
    
    <div class="container">
        <button class="btn btn-primary btn-sm" onclick="backIndex()">< Home page</button>
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="card-title"><?php echo $post["username"];?></h3>
                <h6 class="card-title" style="color: gray;">Module: <?php echo $post["module_name"];?></h6>
                <h6 class="card-title" style="color: gray;">Posted at: <?php echo $post["created_at"];?></h6>
                <form action="edit_post.php" method="post">
                    <input type="hidden" name="postId" value="<?php echo $postId;?>">
                    <button type="submit" class="nav-link" style="border: none; background: none; padding: 0; margin: 0; color: blue">Edit Post</button>
                </form>
                <form action="delete_post.php" method="post">
                    <input type="hidden" name="postId" value="<?php echo $postId;?>">
                    <button type="submit" class="nav-link" style="border: none; background: none; padding: 0; margin: 0; color: red">Delete Post</button>
                </form>
                <hr>
                <h5 class="card-title"><?php echo $post["title"];?></h5>
                <p class="card-text"><?php echo $post["content"];?></p>
                <?php if ($post["image"] != ''):?>
                    <img src="images/<?php echo $post["image"]?>" class="img-fluid" alt="Image">
                <?php endif;?>
                <hr><hr>
                <h5 class="card-title">Comments</h5>
                <?php foreach ($comments as $comment):?>
                    <p><strong><?php echo $comment["username"];?>:</strong> <?php echo $comment["content"];?></p>
                    <form action="edit_comment.php" method="post" style="display: inline;">
                        <input type="hidden" name="commentId" value="<?php echo $comment["id"];?>">
                        <button type="submit" class="btn btn-primary btn-sm">Edit</button>
                    </form>
                    <form action="delete_comment.php" method="post" style="display: inline;">
                        <input type="hidden" name="commentId" value="<?php echo $comment["id"];?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <hr>
                <?php endforeach;?>

                <div style="max-width: 1100px; margin: auto; padding-top: 10px; padding-bottom: 10px">
                    <form action="process_comment.php" method="post">
                        <input type="hidden" name="post_id" value="<?php echo $postId;?>">
                        <div class="form-group">
                        <textarea class='form-control' name='content' rows='3' placeholder='Write your comment...' required></textarea>
                        </div>
                        <button type='submit' class='btn btn-primary'>Comment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/back_home.js"></script>
</body>
</html>