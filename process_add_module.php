<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: intro.php");
    exit();
}

// If the role is user, adding a module is not allowed
$userId = $_SESSION["user_id"];
$user = getUserById($userId);
if ($user["role"] === "user") {
    echo "<script>alert('Sorry! Only admin can add a new module'); window.location.href='index.php';</script>";
    exit();
}

// Handle form submission for adding a new module
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_module"])) {
    $moduleName = $_POST["module_name"];

    $moduleAlreadyExists = getModuleByName($moduleName);
    if ($moduleAlreadyExists) {
        echo "<script>alert('The module already exists'); window.location.href='add_module.php';</script>";
        exit();
    }

    $added = addModule($moduleName);
    if ($added) {
        // Redirect to modules page after successful addition
        header("Location: modules.php");
        exit();
    } else {
        // Handle error, if any
        echo "<script>alert('Apology for the error while adding the module! You can try again.'); window.location.href='add_module.php';</script>";
        exit();
    }
}
?>