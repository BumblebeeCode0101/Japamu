<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu</title>
</head>
<body>
    <a href="/studio">Japamu Studio</a>
    <?php if ($posts): ?>
        <?php foreach ($posts as $post): ?>
            <?php if ($post['visibility'] == 0):?>
                <?php continue; ?>
            <?php endif; ?>
            
            <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>">
                <div>
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                    <h3><?= htmlspecialchars($post['subtitle']) ?></h3>
                    <?php $user = getUserById($post['creator']);?>
                    <?php if ($user):?>
                        <p>By: <?= htmlspecialchars($user['name']) ?></p>
                    <?php else: ?>
                        <p>By: Unknown</p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <h2>No posts found</h2>
    <?php endif; ?>
</body>
</html>