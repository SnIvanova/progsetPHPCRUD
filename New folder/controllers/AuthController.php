<?php

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->sanitizeInput($_POST['username']);
            $password = $_POST['password'];

            if ($this->userModel->authenticate($username, $password)) {
                header('Location: /data');
                exit;
            } else {
                $error = 'Invalid username or password';
                $viewFile = 'views/auth/login.php';
                require_once 'views/layout.php';
            }
        } else {
            $viewFile = 'views/auth/login.php';
            require_once 'views/layout.php';
        }
    }

    public function logoutAction()
    {
        $this->userModel->logout();
        header('Location: /login');
        exit;
    }

    public function registerAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->sanitizeInput($_POST['username']);
            $password = $_POST['password'];

            if ($this->userModel->register($username, $password)) {
                header('Location: /login');
                exit;
            } else {
                $error = 'Registration failed. Username already exists.';
                $viewFile = 'views/auth/register.php';
                require_once 'views/layout.php';
            }
        } else {
            $viewFile = 'views/auth/register.php';
            require_once 'views/layout.php';
        }
    }

    public function resetAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->sanitizeInput($_POST['username']);
            $newPassword = $_POST['new_password'];

            if ($this->userModel->resetPassword($username, $newPassword)) {
                $success = 'Password reset successful. You can now login with your new password.';
                $viewFile = 'views/auth/login.php';
                require_once 'views/layout.php';
            } else {
                $error = 'Password reset failed. Invalid username.';
                $viewFile = 'views/auth/reset.php';
                require_once 'views/layout.php';
            }
        } else {
            $viewFile = 'views/auth/reset.php';
            require_once 'views/layout.php';
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