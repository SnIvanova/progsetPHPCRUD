<?php

class UserDTO{
    private PDO $conn;

    public function __construct(PDO $conn){
        $this->conn = $conn;
    }

    public function getAll(){
        $sql = 'SELECT * FROM users';
        $res = $this->conn->query($sql, PDO::FETCH_ASSOC);

        if ($res) { 
            return $res;
        }

        return null;
    }

    public function getUserByID(int $id){
        $sql = 'SELECT * FROM users WHERE id = :id';
        $stm = $this->conn->prepare($sql);
        $res = $stm->execute(['id' => $id]);

        if ($res) { 
            return $res;
        }
        return null;
    }

    public function saveUser(array $user) {
        $passwordHash = password_hash($user['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (firstname, lastname, email, password, admin) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user['firstname'], $user['lastname'], $user['email'], $passwordHash, $user['admin']]);
        return $this->conn->lastInsertId();
    }
    public function updateUser(array $user){
      
        if (!empty($user['password'])) {
            $passwordHash = password_hash($user['password'], PASSWORD_DEFAULT);
        } else {
           
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->execute(['id' => $user['id']]);
            $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($existingUser) {
                $passwordHash = $existingUser['password'];
            } else {
                return false;
            }
        }
    
        $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, password = :password, admin = :admin WHERE id = :id";
        $stm = $this->conn->prepare($sql);
        $stm->execute([
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'email' => $user['email'],
            'password' => $passwordHash, 
            'admin' => $user['admin'],
            'id' => $user['id'], 
        ]);
        return $stm->rowCount();
    }
    
    public function deleteUser(int $id){
        var_dump($id);
        $sql = "DELETE FROM users WHERE id = :id";
        $stm = $this->conn->prepare($sql);
        $stm->execute(['id' => $id]);
        return $stm->rowCount();
    }
}