<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: intro.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"]) && isset($_POST["content"]) && isset($_POST["module"])) {
    $userId = $_SESSION["user_id"];
    $moduleId = $_POST["module"];
    $title = $_POST["title"];
    $content = $_POST["content"];

    // Check if user ID exists in the users table
    $user = getUserById($userId);
    if (!$user) {
        // Handle error if user ID doesn't exist
        echo "<script>alert('User does not exist'); window.location.href='new_post.php';</script>";
        exit(); // Exit script
    }

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'images/';
        $originalFile = $_FILES['image']['name'];
        $uniqueFile = uniqid() . '_' . $originalFile;
        
        $uploadedFile = $uploadDir . basename($uniqueFile);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
            $image = basename($uniqueFile);
        }
    }

    $added = addPost($userId, $moduleId, $title, $content, $image);
    if ($added) {
        // Redirect to index after successful addition
        header("Location: index.php");
        exit();
    } else {
        // Handle error, if any
        echo "<script>alert('Apology for an error while posting! You can try again.'); window.location.href='new_post.php';</script>";
        exit();
    }
} else {
    // Redirect to index if form fields are not provided
    echo "<script>alert('Title and content are not provided'); window.location.href='new_post.php';</script>";
    exit();
}
?>