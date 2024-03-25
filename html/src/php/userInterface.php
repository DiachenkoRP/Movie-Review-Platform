<?php
require '../../Model/UserModel.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['prev_page'] = explode('?', $_SERVER['HTTP_REFERER']);
    $prev_pager = $_SESSION['prev_page'][0];
    $userModel = new UserModel();

    switch ($_POST['form_action']) {
        case 'register':
            if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                $validUser = $userModel->select("SELECT * FROM users WHERE username = :username OR email = :email", [
                    'username' => $_POST['username'],
                    'email' => $_POST['email']
                ]);
                if (!$validUser) {
                    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        if ($_POST['password'] == $_POST['confirm_password']){
                            $userModel->addUser($_POST['username'], $_POST['email'], $_POST['password'], !empty($_POST['role']) ? $_POST['role'] : 'user');
                            header("Location: " .$prev_pager);
                        } else { header("Location: " .$prev_pager ."?error=1005"); }
                    } else { header("Location: " .$prev_pager ."?error=1002"); }
                } else { header("Location: " .$prev_pager ."?error=1017"); }
            } else { header("Location: " .$prev_pager ."?error=1001"); }

            break;
        case 'login':
            $_SESSION['prev_page'] = $_SERVER['HTTP_REFERER'];

            $users = $userModel->getUsers();
            $user = null;

            foreach ($users as $temp) {
                if ($temp['username'] === $_POST['username']) {
                    $user = $temp;
                    header("Location: " .$prev_pager);
                    break;
                }
            }

            if ($user) {
                if (password_verify($_POST['password'], $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    header('Location: ../../index.php');
                } else { header("Location: " .$prev_pager ."?error=1008"); }
            } else { header("Location: " .$prev_pager . "?error=1012"); }
            break;
        case 'deleteUser':
            $users = $userModel->getUsers();

            $userFound = false;
            foreach ($users as $user) {
                if ($user['user_id'] == $_POST['user_id']) {
                    $userFound = true;
                    session_start();
                    session_unset();
                    session_destroy();
                    $userModel->deleteUser($_POST['user_id']);
                    header("Location: " .$prev_pager ."?error=1008");
                    break;
                }
            }
            if (!$userFound) { header("Location: " .$prev_pager ."?error=1012"); }
            break;
        case 'logout':
            session_unset();
            session_destroy();

            header("Location: " .$prev_pager);
            break;
    }
}
?>
