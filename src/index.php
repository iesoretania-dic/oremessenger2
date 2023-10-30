<?php
    require_once 'config.php';
    global $db;

    session_start();
    if (isset($_POST['login'])) {
        try {
            $username = $_POST['username'];

            $query = $db->prepare('SELECT * FROM person WHERE username=:user');
            $query->bindValue(':user', $username, PDO::PARAM_STR);
            $query->execute();
            
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                if ($user['password'] === $_POST['pass']) {
                    $_SESSION['username'] = $_POST['username'];
                    header('Location: messages.php');
                    die();
                } else {
                    $error = 'Invalid username/password';
                }
            } else {
                $error = 'Invalid username/password';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>OreMessenger 2</title>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>" . $error . "</div>";
        }
        ?>
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="pass" class="form-control" />
            </div>
            <button type="submit" name="login" class="btn btn-primary">Log in</button>
        </form>
    </div>
</body>
</html>
