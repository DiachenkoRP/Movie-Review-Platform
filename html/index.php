<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="src/php/register.php" method="post">
        <input type="text" name="username">
        <input type="email" name="email">
        <input type="password" name="password">
        <input type="text" name="role">
        <input type="submit" value="Register">
    </form>

    <form action="src/php/login.php" method="post">
        <input type="text" name="username">
        <input type="password" name="password">
        <input type="submit" value="Login">
    </form>

    <h1>Hello, <?php if (isset($_SESSION['username'])) { echo $_SESSION['username']; }?></h1>
</body>
</html>