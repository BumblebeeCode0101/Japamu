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
        <h2>By <a href="author.php?tag=<?= $user['tag'] ?>"><?= htmlspecialchars($user['name']) ?></a></h2>
    <?php else: ?>
        <h2>By Unknown</h2>
    <?php endif; ?>
    <span>
        <p>Posted <?=  htmlspecialchars(convertInTimeAgo($post['created_at'])) ?>.</p>
        <p>Created at <?= $post['created_at'] ?></p>
    </span>

    <span>
        <p><?= $post['content'] ?></p>
    </span>

    <?php if ($_SESSION['id'] == $post['creator'] && $_SESSION['logged_in']): ?>
        <a href="studio/post/edit.php?id=<?= $post['id'] ?>">Edit</a>
        <a href="studio/post/delete.php?id=<?= $post['id'] ?>">Delete</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['id']) && $_SESSION['id']): ?>
        <form action="comment.php?id=<?= $post['id'] ?>&&type=post" method="post">
            <textarea name="text" id="text" cols="30" rows="10"></textarea> <br>
            <button type="submit">Comment</button>
        </form>
    <?php endif; ?>

    <?php if ($postComments):?>
        <?php foreach ($postComments as $comment): ?>
            <?php $commentUser = getUserById($comment['creator']);?>
            <div>
                <a href="author.php?tag=<?= $commentUser['tag'] ?>"><h3><?= htmlspecialchars($commentUser['name']) ?></h3></a>
                <h4>Created <?= convertInTimeAgo($comment['created_at']) ?>.</h4>
                <p><?= nl2br(htmlspecialchars($comment['text'])) ?></p>
                
                <?php if (isset($_SESSION['id']) && $_SESSION['id']): ?>
                    <div>
                        <form action="comment.php?id=<?= $comment['id'] ?>&&type=comment" method="post">
                            <textarea name="text" id="text" cols="30" rows="5"></textarea> <br>
                            <button type="submit">Comment</button>
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
                        <?php $replyCommentUser = getUserById($replyComment['creator']);?>
                        <div>
                            <a href="author.php?tag=<?= $replyCommentUser['tag'] ?>"><h3><?= htmlspecialchars($replyCommentUser['name']) ?></h3></a>
                            <h4>Created <?= convertInTimeAgo($replyComment['created_at']) ?>.</h4>
                            <p><?= nl2br(htmlspecialchars($replyComment['text'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h3>No replies yet.</h3>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <h3>No comments yet.</h3>
    <?php endif; ?>
</body>
</html>