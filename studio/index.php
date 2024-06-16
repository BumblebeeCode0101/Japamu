<?php 
session_start();

if (!isset($_SESSION['id']) || !$_SESSION['logged_in']) {
    require_once '../login.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu Studio</title>
</head>
<body>
    <header>
        <a href="post/create.php">Create Post</a>
    </header> 
</body>
</html>