<?php
include 'includes/db.php';
include 'includes/functions.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// If the role is user, then deleting a module is not allowed
$userId = $_SESSION["user_id"];
$user = getUserById($userId);
if ($user["role"] === "user") {
    echo "<script>alert('Sorry! Only admin can delete the module'); window.location.href='modules.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["moduleId"])) {
    $moduleId = $_POST["moduleId"];

    $postsByModule = getPostsByModule($moduleId);
    foreach ($postsByModule as $post) {
        // Delete all answers of the posts assigned to the module
        $postId = $post['id'];
        $query = "DELETE FROM answer WHERE post_id = :postId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':postId', $postId);
        $stmt->execute();
    }

    // Delete those posts
    $query = "DELETE FROM posts WHERE module_id = :moduleId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['moduleId' => $moduleId]);

    // Delete the module from the database
    $query = "DELETE FROM modules WHERE id = :moduleId";
    $stmt = $pdo->prepare($query);
    $deleted = $stmt->execute(['moduleId' => $moduleId]);

    if ($deleted) {
        // Redirect back to the modules page after successful deletion
        echo "<script>alert('Module has been deleted!'); window.location.href='modules.php';</script>";
        exit();
    } else {
        // Handle error, if any
        echo "<script>alert('Apology for the error while deleting the module! You can try again.'); window.location.href='modules.php';</script>";
        exit();
    }
} else {
    // If module ID is not provided
    echo "<script>alert('Module ID is not provided!'); window.location.href='modules.php';</script>";
    exit();
}
?>