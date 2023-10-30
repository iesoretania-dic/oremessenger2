<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    die();
}
$username = $_SESSION['username'];

$db = new PDO('mysql:host=127.0.0.1;port=33060;dbname=oremessenger',
    'root', 'ejemplo_pass');
$query = $db->prepare('SELECT * FROM person WHERE username=:user');
$query->bindValue(':user', $username);
$query->execute();

$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: index.php');
    die();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Browse messages</title>
    <style>
        table {
            border: 1px solid black;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h1>Browse messages - <?= htmlentities($user['fullname']) ?></h1>
    <table>
        <thead>
        <tr>
            <th>Username</th>
            <th>Message body</th>
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
            echo "<td>" . htmlentities($message['body']) . "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</body>
</html>
