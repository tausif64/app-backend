<?php
// Include necessary files
require_once __DIR__ . '/../controller/EmployeeController.php';

// Create a database connection
$db = new Database();
$conn = $db->getConnection();
$employeeController = new EmployeeController($conn);

// Get the requested method
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
      if (isset($_GET['q'])) {
            $q = $_GET['q'];
            // Fetch employee by name
            $employee = $employeeController->getByName($q);
            echo json_encode($employee); // Return as JSON
        } else {
            // Read all employees
            $employees = $employeeController->get();
            echo json_encode($employees); // Return as JSON
        }
        break;
    case 'POST':
        echo var_dump($_POST);
        echo $employeeController->post($_POST);
        break;

    case 'PUT':
        $input = file_get_contents("php://input");

        // Decode the JSON data (if applicable)
        $data = json_decode($input, true);

        // Check if the data was decoded successfully
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Invalid JSON data."]);
            exit;
        }

        // Validate and sanitize the input data
        $name = isset($data['name']) ? htmlspecialchars(strip_tags($data['name'])) : null;

        $email = isset($data['email']) ? htmlspecialchars(strip_tags($data['email'])) : null;

        $password = isset($data['password']) ? htmlspecialchars(strip_tags($data['password'])) : null;

        $role = isset($data['role']) ? htmlspecialchars(strip_tags($data['role'])) : null;

        $designation = isset($data['designation']) ? htmlspecialchars(strip_tags($data['designation'])) : null;

        if ($email === null) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Employee Email is required."]);
            exit;
        }
        echo $employeeController->updateEmployee($name, $email, $password, $role, $designation);
        break;

    case 'DELETE':
        // Delete an employee
        $input = file_get_contents("php://input");

        // Decode the JSON data (if applicable)
        $data = json_decode($input, true);

        // Check if the data was decoded successfully
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Invalid JSON data."]);
            exit;
        }

        $email = isset($data['email']) ? htmlspecialchars(strip_tags($data['email'])) : null;
        echo $employeeController->deleteEmployee($email);
        break;

    default:
        http_response_code(405);
        echo "Method Not Allowed";
        break;
}

?>