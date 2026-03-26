<?php
// app/Core/Router.php
// URL routing system

namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\StaffController;
use App\Controllers\StudentController;

class Router
{
    private array $routes = [];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove the base path /WebApplication/public
        $basePath = '/WebApplication/public';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        // Default to home if root.
        if ($path === '' || $path === '/') {
            $path = '/';
        }

        // Find matching route
        if (isset($this->routes[$method][$path])) {
            $action = $this->routes[$method][$path];
            $this->executeAction($action);
        } else {
            // Route not found - redirect to home or show error
            header('Location: /WebApplication/public/programmes');
            exit;
        }
    }

    private function executeAction(string $action): void
    {
        [$controllerName, $methodName] = explode('@', $action);

        // Map controller names to classes
        $controllers = [
            'AuthController' => AuthController::class,
            'AdminController' => AdminController::class,
            'StaffController' => StaffController::class,
            'StudentController' => StudentController::class,
        ];

        if (!isset($controllers[$controllerName])) {
            die("Controller {$controllerName} not found");
        }

        $controllerClass = $controllers[$controllerName];
        
        if (!method_exists($controllerClass, $methodName)) {
            die("Method {$methodName} not found in {$controllerClass}");
        }

        $controller = new $controllerClass();
        $controller->$methodName();
    }
}

