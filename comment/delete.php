<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../helpers/data.php';

session_start();

if (!isset($_SESSION['id']) || !$_SESSION['logged_in']) {
    require_once '../login.php';
    exit;
}

$error = null;

if (!isset($_GET['id'])) {
    echo "<h1>No ID provided.</h1>";
    exit;
}

$id = $_GET['id'];
$comment = getCommentById($id);

if (!$comment || $_SESSION['id'] != $comment['creator']) {
    require_once '../errors/post_not_found.html';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SESSION['id'] == $comment['creator']) {
        deleteComment($id);

        $commentReplyComments = array_filter($comments, function ($comment) use ($id) {
            return $comment['reference'] == $id && $comment['reference_type'] == 'comment';
        });

        foreach ($commentReplyComments as $comment) {
            deleteComment($comment['id']);
        }

        if ($comment['reference_type'] == 'post') {
            header('Location: ../../post.php?id=' . $comment['reference']);
            exit;
        } else {
            $parentComment = getCommentById($comment['reference']);
            header('Location: ../../post.php?id=' . $parentComment['reference']);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu Studio - Delete Comment</title>
</head>
<body>
    <h1>Are you sure you want to delete this comment?</h1>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST">
        <input type="submit" value="Yes">
        <a href="../../post.php?id=<?= htmlspecialchars($post['id']) ?>">No</a>
    </form>
</body>
</html>
