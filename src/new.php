<?php
require_once 'config.php';
global $db;

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    die();
}
$username = $_SESSION['username'];

$query = $db->prepare('SELECT * FROM person WHERE username=:user');
$query->bindValue(':user', $username);
$query->execute();

$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: index.php');
    die();
}

// Process form
if (isset($_POST['new'])) {
    $insert = $db->prepare('INSERT INTO message (body, username) VALUES (:body, :user)');
    $insert->bindValue(':body', $_POST['body']);
    $insert->bindValue(':user', $username);
    $insert->execute();
    header('Location: messages.php');
    die();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>New message</title>
</head>
<body>
    <div class="container">
        <h1>New message - <?= htmlentities($user['fullname']) ?></h1>
        <form method="post">
            <label for="text" class="form-label">Message body:</label><br/>
            <textarea id="text" name="body" rows="10" cols="50" class="form-control"></textarea>
            <br />
            <button type="submit" name="new" class="btn btn-success"><i class="fa fa-plus"></i> Create message</button>
            <a href="messages.php" class="btn btn-info"><i class="fa fa-arrow-left"></i> Return to message list</a>
        </form>
    </div>
</body>
</html>
