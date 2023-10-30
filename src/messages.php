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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Browse messages</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fa fa-message"></i> OreMessenger</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="#"><i class="fa fa-list"></i> Messages</a>
                    <span class="navbar-text">
                        Connected as <?= $user['fullname'] ?>
                    </span>
                    <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i> Log out</a>
                </div>
            </div>
        </div>
    </nav>
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
                    echo "<td><i class='fa fa-user'></i> " . htmlentities($message['username']) . "</td>";
                    echo "<td>" . nl2br(htmlentities($message['body'])) . "</td>";
                    echo "<td>";
                    echo '<button type="submit" name="delete" class="btn btn-danger" value="' .
                        $message['id'] .
                        '"><i class="fa fa-trash"></i> Delete</button>';
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <a href="new.php" class="btn btn-success"><i class="fa fa-plus"></i> New message</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
