<?php

class DataController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function indexAction()
    {
        $this->checkAuthentication();

        $sql = "SELECT * FROM sensitive_data";
        $stmt = $this->db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $viewFile = 'views/data/index.php';
        require_once 'views/layout.php';
    }

    public function createAction()
    {
        $this->checkAuthentication();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data_field = $this->sanitizeInput($_POST['data_field']);

            $sql = "INSERT INTO sensitive_data (data_field) VALUES (:data_field)";
            $stmt = $this->db->query($sql, ['data_field' => $data_field]);

            if ($stmt->rowCount() > 0) {
                header('Location: /data');
                exit;
            } else {
                $error = 'Failed to create data record';
                $viewFile = 'views/data/create.php';
                require_once 'views/layout.php';
            }
        } else {
            $viewFile = 'views/data/create.php';
            require_once 'views/layout.php';
        }
    }

    public function updateAction()
    {
        $this->checkAuthentication();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data_field = $this->sanitizeInput($_POST['data_field']);

            $sql = "UPDATE sensitive_data SET data_field = :data_field WHERE id = :id";
            $stmt = $this->db->query($sql, ['data_field' => $data_field, 'id' => $id]);

            if ($stmt->rowCount() > 0) {
                header('Location: /data');
                exit;
            } else {
                $error = 'Failed to update data record';
                $viewFile = 'views/data/update.php';
                require_once 'views/layout.php';
            }
        } else {
            $id = $_GET['id'];
            $sql = "SELECT * FROM sensitive_data WHERE id = :id";
            $stmt = $this->db->query($sql, ['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $viewFile = 'views/data/update.php';
                require_once 'views/layout.php';
            } else {
                $error = 'Data record not found';
                $viewFile = 'views/data/index.php';
                require_once 'views/layout.php';
            }
        }
    }

    public function deleteAction()
    {
        $this->checkAuthentication();

        $id = $_GET['id'];
        $sql = "DELETE FROM sensitive_data WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);

        if ($stmt->rowCount() > 0) {
            header('Location: /data');
            exit;
        } else {
            $error = 'Failed to delete data record';
            $viewFile = 'views/data/index.php';
            require_once 'views/layout.php';
        }
    }

    private function checkAuthentication()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
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