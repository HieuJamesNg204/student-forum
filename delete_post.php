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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["postId"])) {
    $postId = $_POST["postId"];

    // Get the user ID of the logged-in user
    $userId = $_SESSION['user_id'];

    // Check if the user has permission to delete the post
    $query = "SELECT user_id FROM posts WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $postId);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && $post['user_id'] == $userId) {
        // User has permission to delete the post

        // Delete related records in the `answer` table
        $query = "DELETE FROM answer WHERE post_id = :postId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':postId', $postId);
        $stmt->execute();

        // Delete the post from the database
        $query = "DELETE FROM posts WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $postId);

        if ($stmt->execute()) {
            // Redirect back to the post page after successful deletion
            header("Location: index.php");
            exit();
        } else {
            // Handle error, if any
            echo "<script>alert('Apology for the error while deleting the post! You can try again.'); window.location.href='index.php';</script>";
            exit();
        }
    } else {
        // User does not have permission to delete the post
        echo "<script>alert('Sorry! You cannot delete or modify other users\' posts'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    // If post ID is not provided
    echo "<script>alert('Post ID is not provided!'); window.location.href='index.php';</script>";
    exit();
}
?>