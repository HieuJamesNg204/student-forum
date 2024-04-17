<?php
session_start();
include 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["email"])) {
    $userId = $_SESSION["user_id"];
    $username = $_POST["username"];
    $email = $_POST["email"];

    // Update user profile
    $updated = updateUser($userId, $username, $email);
    if ($updated) {
        // Redirect to index after successful update
        echo "<script>alert('Profile is updated!'); window.location.href='index.php';</script>";
        exit();
    } else {
        // Handle error updating profile
        echo "<script>alert('Apology for an error while updating the profile! You can try again.'); window.location.href='edit_profile.php';</script>";
        exit();
    }
} else {
    // Redirect to edit profile page if form fields are not provided
    echo "<script>alert('Username or email are not provided!'); window.location.href='edit_profile.php';</script>";
    exit();
}
?>