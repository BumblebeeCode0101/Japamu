<?php 
$pdo = new PDO('mysql:host=localhost;dbname=japamu', 'root', 'Y3P?}NUCy),J3#W^x0F?_b>Ad!wcuPF*B)o1-8Uc@WxhKh)9>H_i4o4XUvk#uN%5ccAwQVJvP8ANYQ_^WgfKF!Qb7mj+rLk,HXuU', [
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

    $comments = getComments();
    
    $postComments = array_filter($comments, function ($comment) use ($id) {
        return $comment['reference'] == $id && $comment['reference_type'] == 'post';
    });

    foreach ($postComments as $comment) {
        deleteComment($comment['id']);
    }
}

function postIdExists($id) {
    global $pdo;
    
    $query = "SELECT COUNT(*) as count FROM users WHERE name = :name";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] > 0) {
        return true;
    } else {
        return false;
    }
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

function nameAlreadyExists($name) {
    global $pdo;
    
    $query = "SELECT COUNT(*) as count FROM users WHERE name = :name";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] > 0) {
        return true;
    } else {
        return false;
    }
}

function getCommentById($id) {
    global $pdo;
    $q = $pdo->prepare("SELECT * FROM comments WHERE id = ?");
    $q->execute([$id]);

    return $q->fetch(PDO::FETCH_ASSOC);
}

function createComment($reference, $creator, $text) {
    global $pdo;

    try {
        $id = uniqid();
        $query = "INSERT INTO comments (id, reference, reference_type, creator, text) VALUES (:id, :reference, 'post', :creator, :text)";
        $stmt = $pdo->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->bindParam(':reference', $reference, PDO::PARAM_STR);
        $stmt->bindParam(':creator', $creator, PDO::PARAM_STR);
        $stmt->bindParam(':text', $text, PDO::PARAM_STR);
        
        $stmt->execute();

        return $id;
    } catch (PDOException $e) {
        throw new Exception("Error creating comment: " . $e->getMessage());
    }
}

function createReplyComment($reference, $creator, $text) {
    global $pdo;

    try {
        $id = uniqid();
        $query = "INSERT INTO comments (id, reference, reference_type, creator, text) VALUES (:id, :reference, 'comment', :creator, :text)";
        $stmt = $pdo->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->bindParam(':reference', $reference, PDO::PARAM_STR);
        $stmt->bindParam(':creator', $creator, PDO::PARAM_STR);
        $stmt->bindParam(':text', $text, PDO::PARAM_STR);
        
        $stmt->execute();

        return $id;
    } catch (PDOException $e) {
        throw new Exception("Error creating comment: " . $e->getMessage());
    }
}

function getComments() {
    global $pdo;
    $q = $pdo->prepare("SELECT * FROM comments");
    $q->execute();

    return $q->fetchAll(PDO::FETCH_ASSOC);
}

function deleteComment($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return true;
}

function updateComment($id, $text) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE comments SET text = :text WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':text', $text);
    $stmt->execute();
    return true;
}
function commentIdExists($comment_id) {
    global $pdo;
    $query = 'SELECT COUNT(*) as count FROM comments WHERE id = :comment_id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':comment_id', $comment_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['count'] > 0) {
        return true;
    } else {
        return false;
    }
}

$posts = getPosts();
$comments = getComments();
?>