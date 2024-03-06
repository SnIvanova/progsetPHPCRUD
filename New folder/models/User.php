<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function authenticate($username, $password)
    {
        $username = $this->sanitizeInput($username);

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->query($sql, ['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['LAST_ACTIVITY'] = time(); // Set the last activity time
            return true;
        }

        return false;
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
    }

    public function register($username, $password)
    {
        $username = $this->sanitizeInput($username);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $this->db->query($sql, ['username' => $username, 'password' => $hashedPassword]);

        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function resetPassword($username, $newPassword)
    {
        $username = $this->sanitizeInput($username);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->query($sql, ['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $sql = "UPDATE users SET password = :password WHERE username = :username";
            $stmt = $this->db->query($sql, ['password' => $hashedPassword, 'username' => $username]);

            if ($stmt->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    private function sanitizeInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }
}
?>