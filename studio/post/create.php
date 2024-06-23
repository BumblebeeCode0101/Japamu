<?php
require_once '../../helpers/data.php';

session_start();

if (!isset($_SESSION['id']) || !$_SESSION['logged_in']) {
    header('Location: ../../login.php');
    exit;
}

$title = $subtitle = $content = '';
$title_error = $subtitle_error = $content_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $content = $_POST['content'];
    $creator = $_SESSION['id'];
    $visibility = isset($_POST['visibility']) ? intval($_POST['visibility']) : 0;

    if (empty($title)) {
        $title_error = "Title is required.";
    } 
    
    if (empty($subtitle)) {
        $subtitle_error = "Subtitle is required.";
    }

    if (empty($content)) {
        $content_error = "Content is required.";
    }

    if (strlen($title) > 250 || strlen($subtitle) > 250) {
        $title_error = "Title must be less than 250 characters.";
        $subtitle_error = "Subtitle must be less than 250 characters.";
    }

    if (strlen($title) > 250) {
        $title_error = "Title must be less than 250 characters.";
    }

    if (strlen($subtitle) > 250) {
        $subtitle_error = "Subtitle must be less than 250 characters.";
    }

    if (!$title_error && !$subtitle_error && !$content_error) {
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
    <script src="https://cdn.tiny.cloud/1/j3fjx0g2ta78d2x4a0371gfxnzirn8t5xv075ykerqvinnro/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="m-6">
    <nav class="navbar">
        <a href="/index.php" class="button">Home</a>
        <button onclick="switchTheme()" class="button" id="theme">Switch Theme</button>
        <?php if (!isset($_SESSION['id']) || !$_SESSION['logged_in']): ?>
            <a href="/login.php" class="button">Login</a>
            <a href="/register.php" class="button">Register</a>
        <?php else: ?>
            <a href="/studio" class="button">Japamu Studio</a>
            <a href="/logout.php" class="button">Logout</a>
        <?php endif; ?>
    </nav>

    <h1 class="title">Create Post</h1>

    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <div class="field">
            <label for="title" class="label">Title</label>
            <div class="control">
                <input type="text" name="title" id="title" class="input is-large" value="<?= htmlspecialchars($title) ?>">
            </div>

            <?php if ($title_error): ?>
                <p class="help is-danger"><?= $title_error ?></p>
            <?php else: ?>
                <p class="help is-success">Title is valid.</p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="subtitle" class="label">Subtitle</label>
            <div class="control">
                <input type="text" name="subtitle" id="subtitle" class="input" value="<?= htmlspecialchars($subtitle) ?>">
            </div>

            <?php if ($subtitle_error): ?>
                <p class="help is-danger"><?= $subtitle_error ?></p>
            <?php else: ?>
                <p class="help is-success">Subtitle is valid.</p>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="visibility" class="label">Visibility</label>
            <div class="control">
                <div class="select">
                    <select name="visibility" id="visibility">
                        <option value="1">Public</option>
                        <option value="0">Private</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label for="content" class="label">Content</label>
            <div class="control">
                <textarea name="content" id="content" rows="10"><?= htmlspecialchars($content) ?></textarea>
            </div>

            <?php if ($content_error): ?>
                <p class="help is-danger"><?= $content_error ?></p>
            <?php else: ?>
                <p class="help is-success">Content is valid.</p>
            <?php endif; ?>
        </div>

        <input type="submit" value="Publish" class="button is-primary">
    </form>

    <script>
    var currentTheme = localStorage.getItem('theme') || 'light';

    if (currentTheme === 'dark') {
        tinymce.init({
            selector: '#content',
            menubar: false,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            skin: 'oxide-dark',
            content_css: 'https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css'
        });
    } else {
        tinymce.init({
            selector: '#content',
            menubar: false,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            skin: 'oxide',
            content_css: 'https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/versions/bulma-no-dark-mode.min.css'
        });
    }
    </script>
    <script src="/js/theme_switcher.js"></script>
</body>
</html>