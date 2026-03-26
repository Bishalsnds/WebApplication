<?php
// app/Controllers/AuthController.php
// Authentication controller for login/logout

namespace App\Controllers;

use App\Models\User;
use App\Core\Auth;

class AuthController
{
    /**
     * Show login form
     */
    public function showLoginForm(): void
    {
        // Redirect if already logged in
        if (Auth::check()) {
            header('Location: /WebApplication/public/admin-dashboard');
            exit;
        }

        require __DIR__ . '/../Views/auth/login.php';
    }

    /**
     * Handle login submission
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die('Method not allowed');
        }

        if (!Auth::verifyCsrfToken($_POST['_token'] ?? null)) {
            http_response_code(400);
            die('Invalid form token');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $error = '';

        // Validation
        if (empty($email) || empty($password)) {
            $error = 'Email and password are required.';
        } else {
            // Find user by email
            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if (!$user) {
                $error = 'Invalid email or password.';
            } elseif (!$userModel->verifyPassword($password, $user['password_hash'])) {
                $error = 'Invalid email or password.';
            } else {
                // Login successful - set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /WebApplication/public/admin-dashboard');
                } else {
                    header('Location: /WebApplication/public/');
                }
                exit;
            }
        }

        // Show login form with error
        require __DIR__ . '/../Views/auth/login.php';
    }

    /**
     * Handle logout
     */
    public function logout(): void
    {
        Auth::logout();
    }
}

