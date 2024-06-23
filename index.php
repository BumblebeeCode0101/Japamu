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
<html lang="en" class="m-6">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
</head>
<body>
    <nav class="navbar">
        <button onclick="switchTheme()" class="button" id="theme">Switch Theme</button>

        <?php if (!isset($_SESSION['id']) || !$_SESSION['logged_in']): ?>
            <a href="login.php" class="button">Login</a>
            <a href="register.php" class="button">Register</a>
        <?php else: ?>
            <a href="/studio" class="button">Japamu Studio</a>
            <a href="/studio/post/create.php" class="button">Create Post</a><a href="/author.php?id=<?= $_SESSION['id'] ?>"></a>
            <a href="/logout.php" class="button">Logout</a>
        <?php endif; ?>
    </nav>

    <?php if (!$_SESSION['id']): ?>
        <div>
            <h1 class="title is-size-1">Hey, Welcome to Japamu!</h1>
            <p class="has-text-info">Here you are right if you search for cool blog posts and articles.</p>
        </div>
    <?php endif; ?>
    
    <?php if ($posts): ?>
        <div>
            <h4 class="title is-size-4 m-4">Posts</h4>
            <?php foreach ($posts as $post): ?>
                <?php 
                if ($post['visibility'] == 0 && $_SESSION['id'] != $post['creator']): 
                    continue; 
                endif; 
                ?>
                <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>">
                    <div class="card box p-4 mt-4">
                        <h4 class="title"><?= htmlspecialchars($post['title']) ?></h4>
                        <h5 class="subtitle"><?= htmlspecialchars($post['subtitle']) ?></h5>
                        <?php 
                        $user = getUserById($post['creator']); 
                        ?>
                        <div class="is-italic">
                            <?php if ($user): ?>
                                <p>By <?= htmlspecialchars($user['name']) ?> | <?= convertInTimeAgo($post['created_at']) ?></p>
                            <?php else: ?>
                                <p>By Unknown | <?= convertInTimeAgo($post['created_at']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <h2>No posts found</h2>
    <?php endif; ?>

    <script src="/js/theme_switcher.js"></script>
</body>
</html>