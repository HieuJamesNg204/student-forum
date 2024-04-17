<?php
// Include database connection
include 'includes/db.php';

// Fetch posts from the database along with the username of the poster
$query = "SELECT posts.*, users.username, modules.module_name
          FROM posts 
          INNER JOIN users ON posts.user_id = users.id
          INNER JOIN modules ON posts.module_id = modules.id";
$result = $pdo->query($query);

// Display posts
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="container">';
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';

        echo '<h3 class="card-title">' . $row['username'] . '</h3>';
        
        echo '<h6 class="card-title" style="color:gray;">Module: ' . $row['module_name'] . '</h6>';
        echo '<h6 class="card-title" style="color:gray;">Posted at ' . $row['created_at'] . '</h6>';

        echo '<form action="edit_post.php" method="post">'; // Form added
        echo '<input type="hidden" name="postId" value="' . $row['id'] . '">'; // Hidden input for post ID
        echo '<button type="submit" class="nav-link" style="border: none; background: none; padding: 0; margin: 0; color: blue">Edit Post</button>';
        echo '</form>';

        echo '<form action="delete_post.php" method="post">'; // Form added
        echo '<input type="hidden" name="postId" value="' . $row['id'] . '">'; // Hidden input for post ID
        echo '<button type="submit" class="nav-link" style="border: none; background: none; padding: 0; margin: 0; color: red">Delete Post</button>';
        echo '</form>';
        echo '<hr>';
        echo '<h5 class="card-title">' . $row['title'] . '</h5>';
        echo '<p class="card-text">' . $row['content'] . '</p>';
        if ($row['image'] != '') {
            echo '<img src="images/' . $row['image'] . '" class="img-fluid" alt="Image">';
        }

        echo '<hr><hr>';
        echo '<h5 class="card-title">Comments</h5>';

        // Fetch comments for this post
        $comments = getCommentsForPost($row['id']);
        $commentCount = 0;

        foreach ($comments as $comment) {
            $commentCount++;

            if ($commentCount <= 3) {
                echo "<p><strong>$comment[username]:</strong> $comment[content]</p>";

                echo '<form action="edit_comment.php" method="post" style="display: inline;">';
                echo '<input type="hidden" name="commentId" value="' . $comment['id'] . '">';
                echo '<button type="submit" class="btn btn-primary btn-sm">Edit</button>';
                echo '</form>';

                echo '<form action="delete_comment.php" method="post" style="display: inline;">';
                echo '<input type="hidden" name="commentId" value="' . $comment['id'] . '">';
                echo '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
                echo '</form>';

                echo "<hr>";
            } else {
                echo '<form action="post_detail.php" method="post">'; // Form added
                echo '<input type="hidden" name="postId" value="' . $row['id'] . '">'; // Hidden input for post ID
                echo '<button type="submit" class="btn btn-primary btn-sm">Show more</button>';
                echo '</form>';
            }
        }

        // Comment form
        echo '<div style="max-width: 1100px; margin: auto; padding-top: 10px; padding-bottom: 10px">';
        echo '<form action="process_comment.php" method="post">';
        echo '<input type="hidden" name="post_id" value="' . $row["id"] . '">';
        echo '<div class="form-group">';
        echo '<textarea class="form-control" name="content" rows="3" placeholder="Write your comment..." required></textarea>';
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary">Comment</button>';
        echo '</form>';
        echo '</div>'; // Close comment-form

        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo "<hr>";
    }
} else {
    echo '<p>No posts found.</p>';
}
?>