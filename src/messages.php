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
</body>
</html>
