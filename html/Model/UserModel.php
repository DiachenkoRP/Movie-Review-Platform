<?php
require_once "Database.php";

class UserModel extends Database
{
    public function getUsers()
    {
        return $this->select("SELECT * FROM users");
    }
    public function addUser($username, $email, $password, $role)
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
    public function deleteUser($user_id)
    {
        $data = [
            'user_id' => $user_id
        ];
        $this->delete('reviews', $data);
        return $this->delete("users", $data);
    }
    // If not exist admin
    public function addAdmin()
    {
        $users = $this->getUsers();
        
        if (!empty($users)){
            return 0;
        } else {
            $this->addUser("admin", "admin@example.com", "admin", "admin");
            return 1;
        }
    }
}
