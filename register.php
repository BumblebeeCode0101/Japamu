<?php 
require_once "data.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['name'])) {
        $errors[] = "Username is required.";
    }
    
    if (empty($_POST['password'])) {
        $errors[] = "Password is required.";
    }
    
    if (empty($_POST['tag'])) {
        $errors[] = "Tag is required.";
    } elseif (tagAlreadyExists($_POST['tag'])) {
        $errors[] = "Tag already exists. Please choose a different one.";
    }
    
    if (empty($errors)) {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $tag = $_POST['tag'];
        $description = $_POST['description'];
        
        createUser($tag, $name, $password, $description, 0);
        
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japamu - Register</title>
</head>
<body> 
    <h2>Register</h2>
    
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <label for="name">Username: </label> <br>
        <input type="text" name="name" id="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>"> <br><br>
        <label for="password">Password: </label> <br>
        <input type="password" name="password" id="password"> <br><br>
        <label for="tag">Tag: </label> <br>
        <input type="text" name="tag" id="tag" value="<?= isset($_POST['tag']) ? htmlspecialchars($_POST['tag']) : '' ?>"> <br><br>
        <label for="description">Description: </label> <br>
        <textarea name="description" id="description" cols="30" rows="10"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea> <br><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
