<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: intro.php");
    exit();
}

// Check if comment ID is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['commentId'])) {
    $commentId = $_POST['commentId'];
    $comment = getCommentById($commentId);
    
    $userId = $_SESSION["user_id"];
    if ($comment['user_id'] != $userId) {
        echo "<script>alert('Sorry! You cannot delete or modify other users\' comments'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Comment ID is not provided!'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <button class="btn btn-primary btn-sm" onclick="window.location.href = 'index.php';" style="margin-bottom: 25px;">< Home page</button>
            <div class="card">
                <div class="card-header">
                    Edit Comment
                </div>
                <div class="card-body">
                    <form action="update_comment.php" method="post">
                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" name="content" id="content" rows="3"
                                        required><?php echo $comment['content']; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Comment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>