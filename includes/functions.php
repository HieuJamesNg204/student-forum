<?php
include 'db.php';

function getUsersRowCount() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) AS Row_Count FROM users");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addUser($username, $email, $password) {
    global $pdo;
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $rowCount = getUsersRowCount();
    if ($rowCount["Row_Count"] == 0) {
        $role = "admin";
    } else {
        $role = "user";
    }
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed);
    $stmt->bindParam(':role', $role);
    return $stmt->execute();
}

function getUserByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserById($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :userId");
    $stmt->execute(['userId' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUser($userId, $username, $email) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email WHERE id = :userId");
    return $stmt->execute(['userId' => $userId, 'username' => $username, 'email' => $email]);
}

function addModule($moduleName) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO modules (module_name) VALUES (:moduleName)");
    return $stmt->execute(['moduleName' => $moduleName]);
}

function getModules() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM modules");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getModuleById($moduleId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM modules WHERE id = :moduleId");
    $stmt->execute(['moduleId' => $moduleId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getModuleByName($moduleName) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM modules WHERE module_name = :moduleName");
    $stmt->execute(['moduleName' => $moduleName]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateModule($moduleId, $moduleName) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE modules SET module_name = :moduleName WHERE id = :moduleId");
    return $stmt->execute(['moduleId' => $moduleId, 'moduleName' => $moduleName]);
}

function addPost($userId, $moduleId, $title, $content, $image) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, module_id, title, content, image) VALUES (:userId, :moduleId, :title, :content, :image)");
    return $stmt->execute(['userId' => $userId, 'moduleId' => $moduleId, 'title' => $title, 'content' => $content, 'image' => $image]);
}

function getPostById($postId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :postId");
    $stmt->execute(['postId' => $postId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPostsByModule($moduleId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE module_id = :moduleId");
    $stmt->execute(['moduleId' => $moduleId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPostsByUser($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = :userId");
    $stmt->execute(['userId' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCommentsForPost($postId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT answer.*, users.username 
                            FROM answer 
                            INNER JOIN users ON answer.user_id = users.id 
                            WHERE answer.post_id = :postId");
    $stmt->execute(['postId' => $postId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCommentById($commentId) {
    global $pdo;
    $query = "SELECT * FROM answer WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $commentId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addComment($postId, $userId, $content) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO answer (post_id, user_id, content) VALUES (:postId, :userId, :content)");
    return $stmt->execute(['postId' => $postId, 'userId' => $userId, 'content' => $content]);
}
?>