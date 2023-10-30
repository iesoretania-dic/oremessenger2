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
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    die();
}
if (isset($_POST['delete'])) {
    $query = $db->prepare('DELETE FROM message WHERE id=:id AND username=:user');
    $query->bindValue(':id', $_POST['delete'], PDO::PARAM_INT);
    $query->bindValue(':user', $username);
    $query->execute();
    header('Location: messages.php');
    die();
}
//var_dump($_POST);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Browse messages</title>
</head>
<body>
    <div class="container">
        <h1>Browse messages - <?= htmlentities($user['fullname']) ?></h1>
        <form method="post">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Message body</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php

                $messages = $db->prepare('SELECT * FROM message WHERE username=:user');
                $messages->bindValue(':user', $username);
                $messages->setFetchMode(PDO::FETCH_ASSOC);
                $messages->execute();

                foreach ($messages as $message) {
                    echo "<tr>";
                    echo "<td>" . htmlentities($message['username']) . "</td>";
                    echo "<td>" . nl2br(htmlentities($message['body'])) . "</td>";
                    echo "<td>";
                    echo '<button type="submit" name="delete" class="btn btn-danger" value="' .
                        $message['id'] .
                        '">Delete</button>';
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <a href="new.php" class="btn btn-success">New message</a>
            <button type="submit" name="logout" class="btn btn-info">Log out</button>
        </form>
    </div>
</body>
</html>
