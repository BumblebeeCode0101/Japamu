<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'helpers/data.php';
require_once 'helpers/time.php';

session_start();

if (!isset($_SESSION['logged_in'])) {
    $_SESSION['logged_in'] = false;
}

if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu</title>
</head>
<body>
    <?php if (!$_SESSION['id']): ?>
        <h1>Hey, Welcome to Japamu!</h1>
        <p>Here you are right if you search for cool blog posts and articles. <br>
        If you want to join Japamu please <a href="/register.php">Register</a> <br>
        or if you are already a user <a href="/login.php">Login</a>  here .</p>
    <?php endif; ?>

    <nav>
        <?php if (!isset($_SESSION['id']) || !$_SESSION['logged_in']): ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php else: ?>
            <a href="/studio">Japamu Studio</a>
            <a href="/studio/post/create.php">Create Post</a><a href="/author.php?id=<?= $_SESSION['id'] ?>"></a>
            <a href="/logout.php">Logout</a>
        <?php endif; ?>
    </nav>
    
    <?php if ($posts): ?>
        <div>
            <?php foreach ($posts as $post): ?>
                <?php 
                if ($post['visibility'] == 0 && $_SESSION['id'] != $post['creator']): 
                    continue; 
                endif; 
                ?>
                <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>">
                    <div>
                        <h2><?= htmlspecialchars($post['title']) ?></h2>
                        <h3><?= htmlspecialchars($post['subtitle']) ?></h3>
                        <?php 
                        $user = getUserById($post['creator']); 
                        ?>
                        <?php if ($user): ?>
                            <h3>By <?= htmlspecialchars($user['name']) ?></h3>
                        <?php else: ?>
                            <h3>By Unknown</h3>
                        <?php endif; ?>
                        <p><?=  convertInTimeAgo($post['created_at']) ?></p>
                        <p><?= htmlspecialchars($post['read_count']) ?> person<?= $post['read_count'] === 1 ? '' : 's' ?> have read this</p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <h2>No posts found</h2>
    <?php endif; ?>
</body>
</html>