<?php
require_once "Database.php";

class UserModel extends Database
{
    public function getUsers()
    {
        return $this->select("SELECT * FROM users");
    }
    public function addUser($username, $email, $password, $role = 'user')
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $data = [
            'username' => $username,
            'email' => $email,
            'password_hash' => $password_hash,
            'role' => $role
        ];

        return $this->insert("users", $data);
    }
}
