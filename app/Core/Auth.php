<?php
// app/Core/Auth.php
// Authentication helpers

namespace App\Core;

class Auth
{
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken(?string $token): bool
    {
        if (!isset($_SESSION['csrf_token']) || $token === null) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        return self::check() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /WebApplication/public/admin-login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            header('Location: /WebApplication/public/');
            exit;
        }
    }

    public static function user(): ?array
    {
        if (self::check()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['name'] ?? '',
                'email' => $_SESSION['email'] ?? '',
                'role' => $_SESSION['role'] ?? 'student',
            ];
        }
        return null;
    }

    public static function logout(): void
    {
        session_destroy();
        $_SESSION = [];
        header('Location: /WebApplication/public/');
        exit;
    }
}

