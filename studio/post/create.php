<?php
require_once '../../data.php';

session_start();

if (!isset($_SESSION['id']) || !$_SESSION['logged_in']) {
    require_once '../../login.php';
    exit;
}

$error = null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $content = $_POST['content'];
    $creator = $_SESSION['id'];
    
    $visibility = isset($_POST['visibility']) ? intval($_POST['visibility']) : 0;

    if (empty($title) || empty($subtitle) || empty($content)) {
        $error = "All fields are required.";
    } elseif (strlen($title) > 250 || strlen($subtitle) > 250) {
        $error = "Title and subtitle cannot exceed 100 characters each.";
    } else {
        createPost($title, $subtitle, $content, $creator, $visibility);
        header('Location: ../../index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu Studio - Create Post</title>

    <script src="https://cdn.tiny.cloud/1/j3fjx0g2ta78d2x4a0371gfxnzirn8t5xv075ykerqvinnro/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
    <h1>Create Post</h1>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <label for="title">Title:</label> <br>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($title ?? '') ?>"> <br><br>
        <label for="subtitle">Subtitle:</label> <br>
        <input type="text" name="subtitle" id="subtitle" value="<?= htmlspecialchars($subtitle ?? '') ?>"> <br><br>

        <label for="visibility">Visibility:</label> <br>
        <select name="visibility" id="visibility">
            <option value="1">Public</option>
            <option value="0">Private</option>
        </select> <br><br>

        <textarea name="content" id="content"><?= htmlspecialchars($content ?? '') ?></textarea> <br>

        <input type="submit" value="Publish">
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