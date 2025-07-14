<?php

require_once 'database.php';
$db = new Database();
$conn = $db->getConnection();

$users = 'CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role  VARCHAR(50) NOT NULL,
        state VARCHAR(50) NOT NULL,
        district VARCHAR(80) NOT NULL,
        block VARCHAR(80) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )';

$employee = 'CREATE TABLE IF NOT EXISTS employee (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(50) UNIQUE NOT NULL,
        designation VARCHAR(255) NOT NULL,
        role VARCHAR(255) NOT NULL,
        mobile VARCHAR(20) UNIQUE NOT NULL,
        second_mobile VARCHAR(20),
        address VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )';

if ($conn->exec($employee) == true) {
    echo 'Table User created successfully<br>';
} else {
    echo 'Error creating table User: '.$conn->errorInfo()[2].'<br>';
}

 // Verify user on request
    // public function verifyUser()
    // {
    //     if (isset($_COOKIE['authToken'])) {
    //         $token = $_COOKIE['authToken'];
    //         $decodedToken = Token::Verify($token, KEY);
    //         $email = json_encode($decodedToken['email']);
    //         $email = trim(stripslashes($email), "\"");
    //         if ($email) {
    //             $user = $this->authService->readByEmail($email);
    //             unset($user['id']);
    //             if ($user !== null){
    //                 http_response_code(202);
    //                 return $user;
    //             }
    //         }
    //     }
    //     http_response_code(404);
    //     return false;
    // }
?>