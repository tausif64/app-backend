<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers:Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Credentials: true"); 
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    exit(0); 
}
header('Content-Type: application/json');

// Include necessary files
require_once 'src/config/database.php';
require_once 'src/router/Router.php'; 


// Create a new Router instance
$router = new Router();

// Test Route
$router->addRoute('GET', '/', function () {
    echo "Hello From Server";
});

// Auth Routes
$router->addRoute('PUT', '/users/verify', function () {
    require 'src/router/AuthRoutes.php';
});
$router->addRoute('POST', '/users/login', function () {
    require 'src/router/AuthRoutes.php';
});
$router->addRoute('DELETE', '/users/logout', function () {
    require 'src/router/AuthRoutes.php';
});

// User Routes
$router->addRoute('PUT', '/users/get', function () {
    require 'src/router/UserRoutes.php';
});
$router->addRoute('POST', '/users/create', function () {
    require 'src/router/UserRoutes.php';
});
$router->addRoute('PUT', '/users/update', function () {
    require 'src/router/UserRoutes.php';
});
$router->addRoute('DELETE', '/users/delete', function () {
    require 'src/router/UserRoutes.php';
});


// Employee Routes
$router->addRoute('GET', '/employee/get/', function () {
    require 'src/router/EmployeeRoutes.php';
});
$router->addRoute('POST', '/employee/create/', function () {
    require 'src/router/EmployeeRoutes.php';
});




// Get the requested URL and method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Extract the path
$requestMethod = $_SERVER['REQUEST_METHOD']; // Get the request method (GET, POST, etc.)

// Handle the request
$router->handleRequest($requestUri, $requestMethod);

?>