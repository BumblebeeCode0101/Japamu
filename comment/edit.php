<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../helpers/data.php';

session_start();

if (!isset($_SESSION['id']) && !$_SESSION['id']) {
    header('Location: ../login.php');
    exit;
}

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
        $text = trim($_POST['text']);
        if (empty($text)) {
            $error = 'All fields are required.';
        } else {
            updateComment($id, $text);

            if ($comment['reference_type'] == 'post') {
                header('Location: ../post.php?id=' . $comment['reference']);
                exit;
            } else {
                $parentComment = getCommentById($comment['reference']);
                header('Location: ../post.php?id=' . $parentComment['reference']);
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu - Edit Comment</title>
</head>
<body>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
        <textarea name="text" id="text" cols="30" rows="10"><?= htmlspecialchars($comment['text']) ?></textarea> <br>
        <button type="submit">Edit</button>
    </form>
</body>
</html>