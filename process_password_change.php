<?php
// Include database connection
include 'includes/db.php';
include 'includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" 
        && isset($_POST["username"])
        && isset($_POST["password"])) {
    $username = $_POST["username"];
    $newPassword = $_POST["password"];

    if (strlen($newPassword) < 8) {
        echo "<script>alert('Password must be at least 8 characters long. Please try again.'); window.location.href='forgot_password.php';</script>";
        exit();
    }

    // Check if username exists
    $user = getUserByUsername($username);

    if ($user) {
        // Username exists
        $hashedOldPassword = $user["password"];
        if (password_verify($newPassword, $hashedOldPassword)) {
            echo "<script>alert('The new password cannot be your old one'); window.location.href='forgot_password.php';</script>";
            exit();
        }

        // Update the password in the database

        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = "UPDATE users SET password = :password WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':password', $hashedNewPassword);
        $stmt->bindParam(':id', $user["id"]);

        if ($stmt->execute()) {
            // Redirect back to login form
            echo "<script>alert('Password has been changed! Please log in!'); window.location.href='login.php';</script>";
            exit();
        } else {
            // Handle error, if any
            echo "<script>alert('Apology for an error while changing password! You can try again.'); window.location.href='forgot_password.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Username does not exist'); window.location.href='forgot_password.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Username and password not provided'); window.location.href='forgot_password.php';</script>";
    exit();
}
?>