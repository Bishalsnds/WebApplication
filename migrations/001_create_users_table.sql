-- migrations/001_create_users_table.sql
-- Create Users table for authentication

CREATE TABLE IF NOT EXISTS Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'student') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- INSERT default admin user (password: Admin@123)
-- This is for development only
INSERT IGNORE INTO Users (id, name, email, password_hash, role) VALUES (
    1,
    'Administrator',
    'admin@example.com',
    '$2y$10$VlgM.X2XHp1R3W9Z9N8VGuZs8BQKKqKUqK1BH6X4QZ8L2xZXL6v5q',
    'admin'
);
