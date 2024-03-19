<?php
require '../../Model/UserModel.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

$userModel = new UserModel();
$userModel->addUser($username, $email, $password, $role);
?>