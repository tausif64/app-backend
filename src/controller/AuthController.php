<?php 
    require_once __DIR__.'/../services/AuthService.php';
    require __DIR__ . "/../config/Token.php";
    const KEY = 'Office_CRM';
class AuthController{
    private $authService;

    public function __construct($db)
    {
        $this->authService = new AuthService($db);
    }

    public function post($data) {
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];
        $state = $data['state'];
        $district = $data['district'];
        $block = $data['block']; 

        // Validate input
        if (empty($name)) {
            return "Invalid Input. Name is required";
        }
        if (empty($email)) {
            return "Invalid Input. Email is required";
        }
        if (empty($password)) {
            return "Invalid Input. Password is required";
        }
        if (empty($role)) {
            return "Invalid Input. Role is required";
        }
        if (empty($state)) {
            return "Invalid Input. State is required";
        }
        if (empty($district)) {
            return "Invalid Input. District is required";
        }
        if (empty($block)) {
            return "Invalid Input. Block is required";
        }
        
        return $this->authService->createUser($name, $email, $password, $role, $state, $district, $block);
    }

    public function get(){
        return json_encode($this->authService->readUser());
    }

    public function getByEmail($email){
        return json_encode($this->authService->readByEmail($email));
    }

    public function getByQuery($query){
        return json_encode($this->authService->readUserByQuery($query));
    }

    public function put($data)
    {
        $name = $data['name'];
        $email = $data['email'];
        $role = $data['role'];
        $state = $data['state'];
        $district = $data['district'];
        $block = $data['block']; 

        return $this->authService->updateUser($name, $email, $role, $state, $district, $block);
    }

    public function delete($email)
    {
        return $this->authService->deleteUser($employee);
    }

    public function login($data)
    {
        // Log the incoming data for debugging
        // error_log(print_r($data, true));
       
        $email = $data['email'] ?? null; // Use null coalescing operator
        $password = $data['password'] ?? null; // Use null coalescing operator
        
        if (empty($email)) {
            http_response_code(401);
            return json_encode(["message" => "Invalid Input. Email is required"]);
        }
        if (empty($password)) {
            http_response_code(401);
            return json_encode(["message" => "Invalid Input. Password is required"]);
        }
    
        $user = $this->authService->readWithPass($email);
    
        if (!empty($user) && password_verify($password, $user['password'])) {
            // Remove sensitive fields
            unset($user['id']);
            unset($user['password']);
            unset($user['created_at']);
            unset($user['updated_at']);
            
            // Generate the access token
            $accessToken = Token::Sign(['email' => $user['email'], "role" => $user['role']], KEY, 43200); // 12 hours
    
            // Generate the refresh token
            $refreshToken = Token::Sign(['email' => $user['email'], "role" => $user['role']], KEY, 604800); // 7 days
    
            // Return both user data and tokens
            return json_encode([
                "user" => $user,
                "accessToken" => $accessToken,
                "refreshToken" => $refreshToken,
            ]);
        }
    
        http_response_code(401);
        return json_encode(["message" => "Invalid credentials"]);
    }
    
    public function refreshToken($data)
    {
        // Log the incoming data for debugging
        // error_log(print_r($data, true));

        $refreshToken = $data['refreshToken'] ?? null; // Use null coalescing operator

        if (empty($refreshToken)) {
            http_response_code(401);
            return json_encode(["message" => "Invalid Input. Refresh token is required"]);
        }

        // Verify the refresh token
        $decoded = Token::Verify($refreshToken, KEY); // Assuming you have a Verify method

        if ($decoded) {
            // Generate a new access token
            $accessToken = Token::Sign(['email' => $decoded->email, "role" => $decoded->role], KEY, 43200); // 12 hours
            $refreshToken = Token::Sign(['email' => $decoded->email, "role" => $decoded->role], KEY, 604800); // 7 days

            // Return the new access token
            return json_encode([
                "accessToken" => $accessToken,
                "refreshToken" => $refreshToken,
            ]);
        }

        http_response_code(401);
        return json_encode(["message" => "Invalid refresh token"]); // Optional: return a message if refresh fails
    }
    
    // Logout
    public function logout()
    {
        session_start();
        // Unset all session variables
        $_SESSION = array();
        // Destroy the session
        session_destroy();
        return "Logged out successfully.";
    }

    // Verify user on request
    public function verifyUser($data)
    {
        $accessToken = $data['accessToken'];
        $refreshToken = $data['refreshToken'];
        
        $decodedAccessToken = Token::Verify($accessToken, KEY);
        $decodedRefreshToken = Token::Verify($refreshToken, KEY);
        if($decodedAccessToken != false){
            $email = json_encode($decodedAccessToken['email']);
            $email = trim(stripslashes($email), "\"");
            if ($email) {
                $user = $this->authService->readByEmail($email);
                if ($user !== null){
                    // Generate the access token
                    $accessToken = Token::Sign(['email' => $user['email'], "role" => $user['role']], KEY, 43200); // 12 hours
                    // Generate the refresh token
                    $refreshToken = Token::Sign(['email' => $user['email'], "role" => $user['role']], KEY, 604800); // 7 days
                    // Return the new access token
                    http_response_code(202);
                    return json_encode([
                        "accessToken" => $accessToken,
                        "refreshToken" => $refreshToken,
                    ]);
                }
            }
        } else if($decodedRefreshToken != false){
            $email = json_encode($decodedRefreshToken['email']);
            $email = trim(stripslashes($email), "\"");
            if ($email) {
                $user = $this->authService->readByEmail($email);
                if ($user !== null){
                    // Generate the access token
                    $accessToken = Token::Sign(['email' => $user['email'], "role" => $user['role']], KEY, 43200); // 12 hours
                    // Generate the refresh token
                    $refreshToken = Token::Sign(['email' => $user['email'], "role" => $user['role']], KEY, 604800); // 7 days
                    // Return the new access token
                    http_response_code(202);
                    return json_encode([
                        "accessToken" => $accessToken,
                        "refreshToken" => $refreshToken,
                    ]);
                }
            }
        }

        http_response_code(404);
        return null;
    }

}

?>

