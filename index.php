<?php
session_start();

// Get the requested URL path
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : 'auth';

// Define available routes
$routes = [
    'users'=> 'app/controller/usersController.php',
    'promptsLogs'=> 'app/controller/promptsLogsController.php',
    'prompts'=> 'app/controller/promptsController.php',
    'dashboard'=> 'app/controller/dashboardController.php',
    'profile'=> 'app/controller/profileController.php',
    'auth'=> 'app/controller/authController.php',
    'home'=> 'app/view/home.php',
    'categories' => 'app/controller/categoriesController.php',
    'promptCategory' => 'app/controller/promptCategoryController.php',
];

// Check if route exists
if (array_key_exists($url, $routes)) {
    include $routes[$url];
} else {
    http_response_code(404);
    echo "404 Not Found";
}