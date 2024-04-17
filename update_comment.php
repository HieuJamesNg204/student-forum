<?php
// Include database connection
include 'includes/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: intro.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment_id"])) {
    $commentId = $_POST["comment_id"];
    $content = $_POST["content"];

    // Get the user ID of the logged-in user
    $userId = $_SESSION['user_id'];

    // Check if the user has permission to edit the comment
    $query = "SELECT user_id FROM answer WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $commentId);
    $stmt->execute();
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($comment && $comment['user_id'] == $userId) {
        // User has permission to edit the comment

        // Update the comment in the database
        $query = "UPDATE answer SET content = :content WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $commentId);

        if ($stmt->execute()) {
            // Redirect back to the post page after successful update
            header("Location: index.php");
            exit();
        } else {
            // Handle error, if any
            echo "<script>alert('Apology for an error while updating comment! You can try again.'); window.location.href='edit_comment.php';</script>";
            exit();
        }
    } else {
        // User does not have permission to edit the comment
        echo "<script>alert('Sorry! You cannot delete or modify other users\' comments'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    // Redirect to index if comment ID is not provided
    echo "<script>alert('Comment ID is not provided'); window.location.href='index.php';</script>";
    exit();
}
?>