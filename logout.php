<?php 
session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm = $_POST['confirm'] ?? null;

    if ($confirm === 'yes') {
        session_destroy();
        header('Location: index.php');
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu - Logout</title>
</head>
<body>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <h1>Are you sure you want to logout?</h1>
        <input type="submit" name="confirm" value="yes">
        <input type="submit" name="confirm" value="no">
    </form>
</body>
</html>
