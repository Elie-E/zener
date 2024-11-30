<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Get the current URL path
$path = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($path, PHP_URL_PATH);
$path = rtrim($path, '/');
if (empty($path)) {
    $path = '/';
}

// Basic routing
$routes = [
    '/' => ['App\Controllers\QuoteController', 'index'],
    '/quote/view' => ['App\Controllers\QuoteController', 'view'],
    '/quote/create' => ['App\Controllers\QuoteController', 'create'],
    '/quote/store' => ['App\Controllers\QuoteController', 'store'],
    '/comment/store' => ['App\Controllers\CommentController', 'store'],
    '/like/toggle' => ['App\Controllers\LikeController', 'toggle'],
    '/login' => ['App\Controllers\AuthController', 'login'],
    '/register' => ['App\Controllers\AuthController', 'register'],
    '/auth/process-login' => ['App\Controllers\AuthController', 'processLogin'],
    '/auth/process-register' => ['App\Controllers\AuthController', 'processRegister'],
    '/logout' => ['App\Controllers\AuthController', 'logout']
];

// Check if the route exists
if (array_key_exists($path, $routes)) {
    [$controller, $method] = $routes[$path];
    if (!class_exists($controller)) {
        throw new Exception("Controller class '$controller' not found");
    }
    $controllerInstance = new $controller();
    if (!method_exists($controllerInstance, $method)) {
        throw new Exception("Method '$method' not found in controller '$controller'");
    }
    $controllerInstance->$method();
} else {
    // 404 handling
    $errorController = new \App\Controllers\ErrorController();
    $errorController->notFound();
}
