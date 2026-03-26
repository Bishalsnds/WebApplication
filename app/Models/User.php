<?php
// app/Models/User.php
// User model for database operations

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, password_hash, role FROM Users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, password_hash, role FROM Users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Verify password using bcrypt
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Hash password using bcrypt
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    /**
     * Create a new user
     */
    public function create(string $name, string $email, string $password, string $role = 'student'): bool
    {
        $passwordHash = $this->hashPassword($password);
        
        $stmt = $this->db->prepare(
            'INSERT INTO Users (name, email, password_hash, role) VALUES (?, ?, ?, ?)'
        );
        
        return $stmt->execute([$name, $email, $passwordHash, $role]);
    }

    /**
     * Update user
     */
    public function update(int $id, array $data): bool
    {
        $allowedFields = ['name', 'email', 'password_hash', 'role'];
        $updateFields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updateFields[] = "{$key} = ?";
                $values[] = $value;
            }
        }

        if (empty($updateFields)) {
            return false;
        }

        $values[] = $id;
        $stmt = $this->db->prepare(
            'UPDATE Users SET ' . implode(', ', $updateFields) . ' WHERE id = ?'
        );

        return $stmt->execute($values);
    }

    /**
     * Get all users (admin only)
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT id, name, email, role, created_at FROM Users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    /**
     * Delete user
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM Users WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
