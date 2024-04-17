<?php
// Include database connection
include 'includes/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: intro.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["commentId"])) {
    $commentId = $_POST["commentId"];

    // Get the user ID of the logged-in user
    $userId = $_SESSION['user_id'];

    // Check if the user has permission to delete the comment
    $query = "SELECT user_id FROM answer WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $commentId);
    $stmt->execute();
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($comment && $comment['user_id'] == $userId) {
        // User has permission to delete the comment

        // Delete the comment from the database
        $query = "DELETE FROM answer WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $commentId);

        if ($stmt->execute()) {
            // Redirect back to the post page after successful deletion
            header("Location: index.php");
            exit();
        } else {
            // Handle error, if any
            echo "<script>alert('Apology for the error while deleting the comment! You can try again.'); window.location.href='index.php';</script>";
            exit();
        }
    } else {
        // User does not have permission to delete the comment
        echo "<script>alert('Sorry! You are not allowed to delete or modify other users\' comments'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    // If comment ID is not provided
    echo "<script>alert('Comment ID is not provided!'); window.location.href='index.php';</script>";
    exit();
}
?>