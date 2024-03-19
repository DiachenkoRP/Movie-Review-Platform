<?php
session_start();
require '../../Model/UserModel.php';

$username = $_POST['username'];
$password = $_POST['password'];

$userModel = new UserModel();
$users = $userModel->getUsers();

$user = null;
foreach ($users as $i) {
    if ($i['username'] === $username) {
        $user = $i;
        break;
    }
}

if ($user) {
    if (password_verify($password, $user['password_hash'])) {
        $_SESSION['username'] = $user['username'];
        header('Location: ../../index.php');
        exit;
    } else {
        echo "Неправильное имя пользователя или пароль.";
    }
} else {
    echo "Пользователь с таким именем не найден.";
}
?>