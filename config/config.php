<?php
// config/config.php
// Application configuration for database and app settings

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'student_course_hub',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'base_url' => 'http://localhost/WebApplication/public',
        'env' => 'development',
    ],
];

