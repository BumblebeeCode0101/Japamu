<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'helpers/data.php';

session_start();

$type = isset($_GET['type']) ? $_GET['type'] : 'post';
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if (!($type == 'post' || $type == 'comment')) {
    $type = 'post';
}

if (!isset($_SESSION['id']) || !$_SESSION['id']) {
    require_once 'login.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $text = isset($_POST['text']) ? trim($_POST['text']) : '';

    if (empty($text)) {
        echo "<h1>No text provided.</h1>";
        exit;
    }

    if ($type == 'post') {
        $post = getPostById($id);

        if (!$post || ($post['visibility'] == 0 && $_SESSION['id'] != $post['creator'])) {
            echo "<h1>Post not found.</h1>";
            exit;
        }

        createComment($id, $_SESSION['id'], $text);

        header("Location: post.php?id=$id");
        exit;
    } elseif ($type == 'comment') {
        $comment = getCommentById($id);
        $post = getPostById($comment['reference']);

        if (!$comment && $_SESSION['id'] != $comment['creator']) {
            echo "<h1>Comment not found.</h1>";
            exit;
        }

        if (!$post || ($post['visibility'] == 0 && $_SESSION['id'] != $post['creator'])) {
            echo "<h1>Post not found.</h1>";
            exit;
        }

        createReplyComment($id, $_SESSION['id'], $text);
        header('Location: post.php?id=' . $post['id']);
        exit;
    }
}
?>