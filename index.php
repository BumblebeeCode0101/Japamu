<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'data.php';

session_start();
$_SESSION['id'] = 1;
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
        <div>
            <?php foreach ($posts as $post): ?>
                <?php if ($post['visibility'] == 0):?>
                    <?php if ($_SESSION['id'] != $post['creator']):?>
                        <?php continue; ?>
                    <?php endif; ?>
                <?php endif; ?>
            
                <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>">
                    <div>
                        <h2><?= htmlspecialchars($post['title']) ?></h2>
                        <h3><?= htmlspecialchars($post['subtitle']) ?></h3>
                        <?php $user = getUserById($post['creator']);?>
                        <?php if ($user):?>
                            <h3>By: <?= htmlspecialchars($user['name']) ?></h3>
                        <?php else: ?>
                            <h3>By: Unknown</h3>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <h2>No posts found</h2>
    <?php endif; ?>
</body>
</html>