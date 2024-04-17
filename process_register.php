<?php
include 'includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long. Please try again.'); window.location.href='register.php';</script>";
        exit();
    }

    // Check if username is available
    $existingUser = getUserByUsername($username);
    if ($existingUser) {
        echo "<script>alert('Username already exists'); window.location.href='register.php';</script>";
        exit();
    } else {
        // Add new user
        $added = addUser($username, $email, $password);
        if ($added) {
            echo "<script>alert('You have been successfully registered. Please log in!'); window.location.href='login.php';</script>";
            exit();
        } else {
            echo "<script>alert('Apology for an error! You can try registering again.'); window.location.href='register.php';</script>";
            exit();
        }
    }
} else {
    // Redirect to registration page if form fields are not provided
    echo "<script>alert('Fields are not provided'); window.location.href='register.php';</script>";
    exit();
}
?>