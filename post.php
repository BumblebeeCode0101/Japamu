<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'helpers/data.php';
require_once 'helpers/time.php';

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

session_start();

if ($post['visibility'] == 0) {
    if ($_SESSION['id'] != $post['creator']) {
        require_once 'errors/post_not_found.html';
        exit;
    }
}

$postComments = array_filter($comments, function ($comment) use ($post) {
    return $comment['reference'] == $post['id'] && $comment['reference_type'] == 'post';
});
?>
<!DOCTYPE html>
<html lang="en" class="m-6">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu Post - <?= htmlspecialchars($post['title']) ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="button">Home</a>

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

    <h1 class="title is-size-1"><?= htmlspecialchars($post['title']) ?></h1>
    <h2 class="subtitle title is-size-3"><?= htmlspecialchars($post['subtitle']) ?></h2>

    <div class="is-italic">
        <?php if ($user): ?>
            <p>By <a href="author.php?tag=<?= $user['tag'] ?>"><?= htmlspecialchars($user['name']) ?></a> | <?= convertInTimeAgo($post['created_at']) ?></p>
        <?php else: ?>
            <p>By Unknown | <?= convertInTimeAgo($post['created_at']) ?></p>
        <?php endif; ?>
    </div>

    <div class="my-6 is-size-5">
        <p><?= $post['content'] ?></p>
    </div>

    <?php if ($_SESSION['id'] == $post['creator'] && $_SESSION['logged_in']): ?>
        <div class="mb-4">
            <a href="studio/post/edit.php?id=<?= $post['id'] ?>" class="button is-link">Edit</a>
            <a href="studio/post/delete.php?id=<?= $post['id'] ?>" class="button is-danger">Delete</a>
        </div>
    <?php endif; ?>

    <hr>
    <?php if (isset($_SESSION['id']) && $_SESSION['id']): ?>
        <div class="mb-6">
            <form action="comment.php?id=<?= $post['id'] ?>&&type=post" method="post" class="control">
                <textarea name="text" id="text" rows="4" class="textarea"></textarea> <br>
                <button class="button is-link" type="submit">Comment</button>
            </form>
        </div>
    <?php endif; ?>

    <?php if ($postComments): ?>
        <?php foreach ($postComments as $comment): ?>
            <?php $commentUser = getUserById($comment['creator']); ?>
            <div class="mb-4">
                <p class="is-italic is-size-7"><a href="author.php?tag=<?= $commentUser['tag'] ?>"><?= htmlspecialchars($commentUser['name']) ?></a> | Created <?= convertInTimeAgo($comment['created_at']) ?></p>
                <p><?= nl2br(htmlspecialchars($comment['text'])) ?></p>

                <?php if ($comment['creator'] == $_SESSION['id']): ?>
                    <form action="comment.php?id=<?= $comment['id'] ?>&&type=comment" method="post">
                        <a href="comment/delete.php?id=<?= $comment['id'] ?>">Delete</a> <br>
                        <a href="comment/edit.php?id=<?= $comment['id'] ?>">Edit</a>
                    </form>
                <?php endif; ?>

                <div class="ml-6">
                <?php if (isset($_SESSION['id']) && $_SESSION['id']): ?>
                        <div class="mb-4">
                            <form action="comment.php?id=<?= $comment['id'] ?>&&type=comment" method="post">
                                <textarea name="text" id="text" cols="30" rows="2" class="textarea"></textarea> <br>
                                <button class="button is-link" type="submit">Reply</button>
                            </form>
                        </div>
                    <?php endif; ?>
                
                    <?php
                    $commentReplyComments = array_filter($comments, function ($replyComment) use ($comment) {
                        return $replyComment['reference'] == $comment['id'] && $replyComment['reference_type'] == 'comment';
                    })
                        ?>
                
                    <?php if ($commentReplyComments): ?>
                        <?php foreach ($commentReplyComments as $replyComment): ?>
                            <?php $replyCommentUser = getUserById($replyComment['creator']); ?>
                            <div>
                                <p class="is-italic is-size-7"><a href="author.php?tag=<?= $replyCommentUser['tag'] ?>"><?= htmlspecialchars($replyCommentUser['name']) ?></a> | Created <?= convertInTimeAgo($replyComment['created_at']) ?></p>

                                <p><?= nl2br(htmlspecialchars($replyComment['text'])) ?></p>
                
                                <?php if ($replyComment['creator'] == $_SESSION['id']): ?>
                                    <form action="comment.php?id=<?= $replyComment['id'] ?>&&type=reply" method="post">
                                        <a href="comment/delete.php?id=<?= $replyComment['id'] ?>">Delete</a>
                                        <a href="comment/edit.php?id=<?= $replyComment['id'] ?>">Edit</a>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h3>No replies yet.</h3>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <h3>No comments yet.</h3>
    <?php endif; ?>

    <script src="/js/theme.js"></script>
</body>
</html>