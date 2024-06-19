<?php
require_once "helpers/data.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $tag = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    
    if (strpos($tag, '@') !== 0) {
        $errors[] = "Tag must start with '@'.";
    }
    
    if (empty($name)) {
        $errors[] = "Username is required.";
    } elseif (nameAlreadyExists($name)) {
        $errors[] = "Name already exists. Please choose a different one.";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match('/\d/', $password)) {
        $errors[] = "Password must contain at least one digit.";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = "Password must contain at least one special character.";
    } elseif (!preg_match("/[!@#$%^&*()_+\-=\[\]{};':\\|,.<>\/?]+/", $password)) {
        $errors[] = "Password must contain at least one special character (!@#$%^&*()_+).";
    }

    if (empty($tag)) {
        $errors[] = "Tag is required.";
    } elseif (strpos($tag, ' ') !== false) {
        $errors[] = "Tag cannot contain spaces.";
    } elseif (tagAlreadyExists($tag)) {
        $errors[] = "Tag already exists. Please choose a different one.";
    }
    
    if (empty($errors)) {
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

    <script>
        var nameElement = document.getElementById('name');
        var tagElement = document.getElementById('tag');

        nameElement.addEventListener('input', function() {
            var nameValue = nameElement.value.trim();
            var generatedTag = generateTag(nameValue); 
            tagElement.value = generatedTag; 
        });

        passwordElement.addEventListener('input', function() {

        });

        function generateTag(name) {
            return '@' + name.replace(/\s+/g, '');
        }
    </script>
</body>
</html>
