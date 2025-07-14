<?php
// Include necessary files
require_once __DIR__ . '/../controller/AuthController.php';

// Create a database connection
$db = new Database();
$conn = $db->getConnection();
$authController = new AuthController($conn);

// Get the requested method
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        if (isset($_GET['q'])) {
            $q = $_GET['q'];
            // Fetch employee by name
            $user = $authController->getByName($q);
            echo json_encode($user); // Return as JSON
        } else if (isset($_GET['email'])) {
            $email = $_GET['email'];
            // Fetch employee by name
            $user = $authController->getByEmail($email);
            echo json_encode($user); // Return as JSON
        } else {
            // Read all employees
            $user = $authController->get();
            echo json_encode($user); // Return as JSON
        }
        break;
    case 'POST':
        echo $authController->post($_POST);
        break;
    case 'PUT':
        echo $authController->put($_POST);
        break;
    case 'DELETE':
        $email = $_REQUEST['email'];
        echo $authController->delete($email);
        break;
    default:
        http_response_code(405);
        echo "Method Not Allowed";
        break;
}

?>