<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: intro.php");
    exit();
}

$userId = $_SESSION["user_id"];
$user = getUserById($userId);
if ($user["role"] === "user") {
    echo "<script>alert('Sorry! Only admin can edit the module'); window.location.href='modules.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["moduleId"])) {
    $moduleId = $_POST["moduleId"];
    $moduleName = $_POST["module_name"];

    $updated = updateModule($moduleId, $moduleName);
    if ($updated) {
        echo "<script>alert('Module has been updated!'); window.location.href='modules.php';</script>";
        exit();
    } else {
        echo "<script>alert('Apology for an error while updating the module! You can try again.'); window.location.href='edit_module.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Module ID is not provided'); window.location.href='modules.php';</script>";
    exit();
}
?>