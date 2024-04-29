<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: intro.php");
    exit();
}

$currentUserId = $_SESSION["user_id"];
$currentUser = getUserById($currentUserId);

if ($currentUser["role"] === "user") {
    echo "<script>alert('Sorry! This action can only be done by admin!'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userId"])) {
    $userId = $_POST["userId"];

    $postsByUser = getPostsByUser($userId);
    foreach ($postsByUser as $post) {
        $postId = $post['id'];
        $query = "DELETE FROM answer WHERE post_id = :postId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':postId', $postId);
        $stmt->execute();
    }

    $query = "DELETE FROM answer WHERE user_id = :userId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();

    $query = "DELETE FROM posts WHERE user_id = :userId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userId' => $userId]);

    $query = "DELETE FROM users WHERE id = :userId";
    $stmt = $pdo->prepare($query);
    $deleted = $stmt->execute(['userId' => $userId]);

    if ($deleted) {
        echo "<script>alert('User has been deleted!'); window.location.href='manage_users.php';</script>";
        exit();
    } else {
        echo "<script>alert('Apology for the error while deleting the user! You can try again.'); window.location.href='manage_users.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('User ID is not provided'); window.location.href='manage_users.php';</script>";
    exit();
}
?>