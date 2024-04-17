<?php
session_start();
include 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Handle form submission for adding a new comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["post_id"]) && isset($_POST["content"])) {
    $postId = $_POST["post_id"];
    $userId = $_SESSION["user_id"];
    $content = $_POST["content"];

    $added = addComment($postId, $userId, $content);
    if ($added) {
        // Redirect to index page after successful addition
        header("Location: index.php");
        exit();
    } else {
        // Handle error, if any
        echo "<script>alert('Apology for an error while adding a comment! You can try again.'); window.location.href='index.php';</script>";
        exit();
    }
}
?>