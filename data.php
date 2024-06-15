<?php 
$pdo = new PDO('mysql:host=localhost;dbname=japamu', 'root', '');

function getPosts() {
    global $pdo;
    $q = $pdo->prepare("SELECT * FROM posts");
    $q->execute();

    return $q->fetchAll(PDO::FETCH_ASSOC);
}

function getPostById($id) {
    global $pdo;
    $q = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $q->execute([$id]);

    return $q->fetch(PDO::FETCH_ASSOC);
}

function createPost($title, $subtitle, $content, $creator, $visibility) {
    global $pdo;
    $id = uniqid();
    $q = $pdo->prepare("INSERT INTO `posts`(`id`, `title`, `subtitle`, `content`, `creator`, `visibility`) VALUES (?, ?, ?, ?, ?, ?)");
    $q->execute([$id, $title, $subtitle, $content, $creator, $visibility]);
}

function getUserById($id) {
    global $pdo;
    $q = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $q->execute([$id]);

    return $q->fetch(PDO::FETCH_ASSOC);
}

function getUserByTag($tag) {
    global $pdo;
    $q = $pdo->prepare("SELECT * FROM users WHERE tag = ?");
    $q->execute([$tag]);

    return $q->fetch(PDO::FETCH_ASSOC);
}

$posts = getPosts();
?>