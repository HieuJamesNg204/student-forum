<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: intro.php");
    exit();
}

$userId = $_SESSION["user_id"];
$user = getUserById($userId);

// Fetch modules
$modules = getModules();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <button class="btn btn-primary btn-sm" onclick="window.location.href = 'index.php';">< Home page</button>
    <h2>Modules</h2>
    <ul class="list-group">
        <?php foreach ($modules as $module): ?>
            <li class="list-group-item"><?php echo $module['module_name']; ?>
                <?php if ($user['role'] === "admin"):?>
                    <br>
                    <form action="edit_module.php" method="post" style="display: inline;">
                    <input type="hidden" name="moduleId" value="<?php echo $module['id']; ?>">
                    <button type="submit" class="btn btn-primary btn-sm">Edit</button>
                    </form>

                    <form action="delete_module.php" method="post" style="display: inline;">
                    <input type="hidden" name="moduleId" value="<?php echo $module['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                <?php endif;?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>