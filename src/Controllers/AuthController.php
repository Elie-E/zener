<?php

namespace App\Controllers;

use App\Models\User;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        // If already logged in, redirect to home
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);
        
        ob_start();
        include __DIR__ . '/../../views/auth/login.php';
        $content = ob_get_clean();
        
        require __DIR__ . '/../../views/layouts/main.php';
    }

    public function register() {
        // If already logged in, redirect to home
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        $error = $_SESSION['register_error'] ?? null;
        unset($_SESSION['register_error']);
        
        ob_start();
        include __DIR__ . '/../../views/auth/register.php';
        $content = ob_get_clean();
        
        require __DIR__ . '/../../views/layouts/main.php';
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'Please fill in all fields';
            header('Location: /login');
            exit;
        }

        if ($this->userModel->verifyPassword($username, $password)) {
            $user = $this->userModel->findByUsername($username);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: /');
        } else {
            $_SESSION['login_error'] = 'Invalid username or password';
            header('Location: /login');
        }
        exit;
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($username) || empty($password) || empty($confirmPassword)) {
            $_SESSION['register_error'] = 'Please fill in all fields';
            header('Location: /register');
            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['register_error'] = 'Passwords do not match';
            header('Location: /register');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['register_error'] = 'Password must be at least 6 characters long';
            header('Location: /register');
            exit;
        }

        // Try to create the user
        if ($this->userModel->create($username, $password)) {
            // Log the user in automatically
            $user = $this->userModel->findByUsername($username);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: /');
        } else {
            $_SESSION['register_error'] = 'Username already exists';
            header('Location: /register');
        }
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}
