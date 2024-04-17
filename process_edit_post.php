<?php
// Include database connection
include 'includes/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" 
        && isset($_POST["postId"]) 
        && isset($_POST["title"])
        && isset($_POST["content"])
        && isset($_POST["module"])) {
    $postId = $_POST["postId"];
    $moduleId = $_POST["module"];
    $title = $_POST["title"];
    $content = $_POST["content"];

    // Get the user ID of the logged-in user
    $userId = $_SESSION['user_id'];

    // Check if the user has permission to edit the post
    $query = "SELECT user_id FROM posts WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $postId);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && $post['user_id'] == $userId) {
        // User has permission to edit the post

        // Update the post in the database
        $query = "UPDATE posts SET module_id = :moduleId, title = :title, content = :content WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':moduleId', $moduleId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $postId);

        if ($stmt->execute()) {
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = 'images/';
                $uploadedFile = $uploadDir . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
                    $image = basename($_FILES['image']['name']);
                }

                $imgQuery = "UPDATE posts SET image = :image WHERE id = :id";
                $imgStmt = $pdo->prepare($imgQuery);
                $imgStmt->bindParam(':image', $image);
                $imgStmt->bindParam(':id', $postId);

                if ($imgStmt->execute()) {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<script>alert('Apology for an error while editing the post! You can try again.'); window.location.href='index.php';</script>";
                    exit();
                }
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            // Handle error, if any
            echo "<script>alert('Apology for an error while editing the post! You can try again.'); window.location.href='index.php';</script>";
            exit();
        }
    } else {
        // User does not have permission to edit the post
        echo "<script>alert('Sorry! You cannot delete or modify other users\' posts'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    // Redirect to index if post ID is not provided
    echo "<script>alert('Post ID is not provided'); window.location.href='index.php';</script>";
    exit();
}
?>