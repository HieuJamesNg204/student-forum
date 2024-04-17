<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page when user is not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["oldPassword"]) && isset($_POST["newPassword"])) {
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];

    $userId = $_SESSION['user_id'];
    $user = getUserById($userId);
    $hashedInDb = $user["password"];

    if (password_verify($oldPassword, $hashedInDb)) {
        if (strlen($newPassword) < 8) {
            echo "<script>alert('New password must be at least 8 characters long. Please try again.'); window.location.href='edit_profile.php';</script>";
            exit();
        }

        if ($newPassword === $oldPassword) {
            echo "<script>alert('New password cannot be the same as old one'); window.location.href='edit_profile.php';</script>";
            exit();
        }

        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = :password WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':password', $hashedNewPassword);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            echo "<script>alert('Password has been changed!'); window.location.href='edit_profile.php';</script>";
            exit();
        } else {
            echo "<script>alert('Apology for an error while changing password! You can try again.'); window.location.href='edit_profile.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Old password is wrong'); window.location.href='edit_profile.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Password is not provided'); window.location.href='edit_profile.php';</script>";
    exit();
}
?>