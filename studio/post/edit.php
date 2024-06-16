<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../data.php';

session_start();

if (!isset($_SESSION['id']) || !$_SESSION['logged_in']) {
    require_once '../../login.php';
    exit;
}

$error = null;

if (!isset($_GET['id'])) {
    echo "<h1>No ID provided.</h1>";
    exit;
}

$id = $_GET['id'];
$post = getPostById($id);

if (!$post || $_SESSION['id'] != $post['creator']) {
    require_once '../../errors/post_not_found.html';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $content = trim($_POST['content']);
    $visibility = $_POST['visibility'];

    // Validate input
    if (empty($title) || empty($subtitle) || empty($content)) {
        $error = 'All fields are required.';
    } else {
        // Update post data
        $post['title'] = $title;
        $post['subtitle'] = $subtitle;
        $post['content'] = $content;
        $post['visibility'] = $visibility;

        // Update the post in the database
        updatePost($post);

        // Redirect to the post view page after updating
        header('Location: ../../post.php?id=' . $post['id']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu Studio - Edit Post: <?= htmlspecialchars($post['title']) ?></title>

    <script src="https://cdn.tiny.cloud/1/j3fjx0g2ta78d2x4a0371gfxnzirn8t5xv075ykerqvinnro/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
    <h1>Edit Post: <?= htmlspecialchars($post['title']) ?></h1>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . htmlspecialchars($post['id']) ?>" method="post">
        <label for="title">Title:</label><br>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($post['title']) ?>"><br><br>

        <label for="subtitle">Subtitle:</label><br>
        <input type="text" name="subtitle" id="subtitle" value="<?= htmlspecialchars($post['subtitle']) ?>"><br><br>

        <label for="visibility">Visibility:</label><br>
        <select name="visibility" id="visibility">
            <option value="1" <?= $post['visibility'] == 1 ? 'selected' : '' ?>>Public</option>
            <option value="0" <?= $post['visibility'] == 0 ? 'selected' : '' ?>>Private</option>
        </select><br><br>

        <label for="content">Content:</label><br>
        <textarea name="content" id="content"><?= htmlspecialchars($post['content']) ?></textarea><br><br>

        <input type="submit" value="Save Changes">
    </form>

    <script>
        tinymce.init({
            selector: '#content',
            menubar: false,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>
</body>
</html>
