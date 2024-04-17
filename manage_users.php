<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION["user_id"])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user_id"];
$currentUser = getUserById($userId);

if ($currentUser["role"] === "user") {
    echo "<script>alert('Sorry! Only admin can manage all users'); window.location.href='index.php';</script>";
    exit();
}

$users = getUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <button class="btn btn-primary btn-sm" onclick="backIndex()">< Home page</button>
    <h2>Users</h2>
    <ul class="list-group">
        <?php foreach ($users as $user): ?>
            <?php
            if ($user['id'] === $userId) {
                continue;
            }
            ?>

            <li class="list-group-item">
                <?php echo "Username: $user[username]<br>Email: $user[email]<br>Role: $user[role]<br>Action: "; ?>
                <?php if ($user['role'] === "admin"): ?>
                    <form action="admin_set_role.php" method="post" style="display: inline;">
                    <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                    <input type="hidden" name="newRole" value="user">
                    <button type="submit" class="btn btn-primary btn-sm">Set role to user</button>
                    </form>
                <?php elseif ($user['role'] === "user"):?>
                    <form action="admin_set_role.php" method="post" style="display: inline;">
                    <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                    <input type="hidden" name="newRole" value="admin">
                    <button type="submit" class="btn btn-primary btn-sm">Set role to admin</button>
                    </form>
                <?php endif;?>
                
                <form action="admin_delete_user.php" method="post" style="display: inline;">
                <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script src="js/back_home.js"></script>
</body>
</html>