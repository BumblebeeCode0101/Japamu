<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'data.php';

if (!isset($_GET['id'])) {
    echo "<h1>No ID provided.</h1>";
    exit;
}

$id = $_GET['id'];
$post = getPostById($id);

if (!$post) {
    require_once 'errors/post_not_found.html';
    exit;
}

$user = getUserById($post['creator']);

if ($post['visibility'] == 0) {
    require_once 'errors/post_not_found.html';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu Post - <?= htmlspecialchars($post['title']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <h2><?= htmlspecialchars($post['subtitle']) ?></h2>

    <?php if ($user):?>
        <h2>By: <a href="author.php?tag=<?= $user['tag'] ?>"><?= htmlspecialchars($user['name']) ?></a></h2>
    <?php else: ?>
        <h2>By: Unknown</h2>
    <?php endif; ?>

    <span>
        <p><?= $post['content'] ?></p>
    </span>
</body>
</html>