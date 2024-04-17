<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userId"]) && isset($_POST["newRole"])) {
    $userId = $_POST["userId"];
    $newRole = $_POST["newRole"];

    $query = "UPDATE users SET role = :role WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':role', $newRole);
    $stmt->bindParam(':id', $userId);

    if ($stmt->execute()) {
        echo "<script>alert('New role has been set'); window.location.href='manage_users.php';</script>";
        exit();
    } else {
        echo "<script>alert('Apology for an error while setting the role! You can try again.'); window.location.href='manage_users.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('User ID and role are not provided!'); window.location.href='manage_users.php';</script>";
    exit();
}

?>