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
    $module = getModuleById($moduleId);
} else {
    echo "<script>alert('Module ID is not provided!'); window.location.href='modules.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit module</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <button class="btn btn-primary btn-sm" onclick="backModulePage()" style="margin-bottom: 25px;">< Back</button>
            <div class="card">
                <div class="card-header">
                    Edit Module
                </div>
                <div class="card-body">
                    <form action="process_edit_module.php" method="post">
                        <input type="hidden" name="moduleId" value="<?php echo $module['id'];?>">
                        <div class="form-group">
                            <label for="module_name">Module name</label>
                            <input class="form-control" type="text" name="module_name" id="module_name" rows="1"
                            value="<?php echo $module['module_name'];?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update module</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/back_home.js"></script>

</body>
</html>