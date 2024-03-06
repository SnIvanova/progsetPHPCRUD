<?php
require_once 'config/database.php';
class Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $conn;

    public function __construct()
    {
        

        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname",  $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $this->handleError($e->getMessage());
        }
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->handleError($e->getMessage());
        }
    }

    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    private function handleError($message)
    {
       
        error_log("Database Error: $message");
       
       /*  header('Location: /error'); */
        /* exit; */
    }
}
?>