<?php
include 'includes/functions.php';
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();
    $hashed = $user["password"];
    
    if ($user && password_verify($password, $hashed)) {
        session_start();

        $_SESSION["user_id"] = $user["id"];
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Wrong username or password'); window.location.href='login.php';</script>";
        exit();
    }
} else {
    // Redirect to login page if form fields are not provided
    echo "<script>alert('Username and password are not provided'); window.location.href='login.php';</script>";
    exit();
}
?>