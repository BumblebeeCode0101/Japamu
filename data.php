<?php 
$pdo = new PDO('mysql:host=localhost;dbname=japamu', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false
]);

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

function updatePost($post) {
    global $pdo;
    $q = $pdo->prepare("UPDATE posts SET title = ?, subtitle = ?, content = ?, visibility = ? WHERE id = ?");
    $q->execute([$post['title'], $post['subtitle'], $post['content'], $post['visibility'], $post['id']]);

    return $q->rowCount();
}

function deletePost($id) {
    global $pdo;
    $q = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $q->execute([$id]);
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

function getUserByUsername($username) {
    global $pdo;
    $q = $pdo->prepare("SELECT * FROM users WHERE name = ?");
    $q->execute([$username]);

    return $q->fetch(PDO::FETCH_ASSOC);
}

function createUser($tag, $name, $password, $description, $follower) {
    global $pdo;
    
    $id = uniqid();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (id, tag, name, password, description, follower) 
                               VALUES (:id, :tag, :name, :password, :description, :follower)");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':tag', $tag);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':follower', $follower);

        $stmt->execute();

        return $id;

    } catch (PDOException $e) {
        die("Error creating user: " . $e->getMessage());
    }
}


function tagAlreadyExists($tag) {
    global $pdo;
    
    $query = "SELECT COUNT(*) as count FROM users WHERE tag = :tag";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':tag', $tag);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] > 0) {
        return true;
    } else {
        return false;
    }
}

$posts = getPosts();
?>