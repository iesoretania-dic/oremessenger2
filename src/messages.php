<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    die();
}
$username = $_SESSION['username'];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Browse messages</title>
</head>
<body>
    <h1>Browse messages - <?= htmlentities($username) ?></h1>
    <table border="1px">
        <thead>
        <tr>
            <th>Username</th>
            <th>Message body</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $db = new PDO('mysql:host=127.0.0.1;port=33060;dbname=oremessenger',
            'root', 'ejemplo_pass');

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
