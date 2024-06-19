<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../helpers/data.php';

session_start();

if (!isset($_SESSION['id']) || !$_SESSION['logged_in']) {
    require_once '../../login.php';
    exit;
}

$error = null;

if (!isset($_GET['id'])) {
    echo "<h1>No ID provided.</h1>";
    exit;
}

$id = $_GET['id'];
$post = getPostById($id);

if (!$post || $_SESSION['id'] != $post['creator']) {
    require_once '../../errors/post_not_found.html';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SESSION['id'] == $post['creator']) {
        deletePost($id);

        header('Location: ../../index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu Studio - Delete Post: <?= htmlspecialchars($post['title']) ?></title>
</head>
<body>
    <h1>Are you sure you want to delete this post?</h1>
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <form action="delete.php?id=<?= htmlspecialchars($post['id']) ?>" method="POST">
        <input type="submit" value="Yes">
        <a href="../../post.php?id=<?= htmlspecialchars($post['id']) ?>">No</a>
    </form>
</body>
</html>
