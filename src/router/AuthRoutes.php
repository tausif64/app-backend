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
    case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($authController->verifyUser($data));
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['refreshToken'])){
            echo $authController->refreshToken($_POST);
        } else{
            echo json_encode($authController->login($data));
        }
        break;
    case 'DELETE':
        $email = $_REQUEST['email'];
        echo json_encode($authController->logout($email));
        break;
    default:
        http_response_code(405);
        echo "Method Not Allowed";
        break;
}

?>