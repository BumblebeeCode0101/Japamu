<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if data.php exists
if (!file_exists('data.php')) {
    echo "<h1>Required data file is missing.</h1>";
    exit;
}

require_once 'data.php';

if (!isset($_GET['tag'])) {
    echo "<h1>No tag provided.</h1>";
    exit;
}

$tag = htmlspecialchars($_GET['tag']);
$user = getUserByTag($tag);

if (!$user) {
    require_once 'errors/user_not_found.html';
    exit;
}

$postsByUser = array_filter($posts, function($post) use ($user) {
    return $post['creator'] == $user['id'];
});
$postsCount = count($postsByUser);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu Author - <?= htmlspecialchars($user['name']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($user['name']) ?></h1>
    <h2>
        <?= htmlspecialchars($user['tag']) ?> - 
        <?= htmlspecialchars($user['follower']) ?> 
        <?= htmlspecialchars($user['follower']) == 1 ? 'Follower' : 'Followers' ?> - 
        <?= $postsCount ?> Post<?= $postsCount === 1 ? '' : 's' ?>
    </h2>
    <p><?= htmlspecialchars($user['description']) ?></p>
    <div>
        <?php foreach ($postsByUser as $post): ?>
            <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>">
                <div>
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                    <h3><?= htmlspecialchars($post['subtitle']) ?></h3>
                    <p>By: <?= htmlspecialchars($user['name']) ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</body>
</html>
