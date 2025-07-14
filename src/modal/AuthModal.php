<?php
class AuthModal {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $state;
    public $district;
    public $block;

    public function __construct($db)
    {
        $this->conn = $db;
        if ($this->conn == null) {
        http_response_code(500);
        throw new Exception("Database connection is not established.");
    }
    }

    public function create() {
        // Check if the email already exists
        $checkEmailQuery = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email = :email";
        $checkStmt = $this->conn->prepare($checkEmailQuery);
        $checkStmt->bindParam(':email', $this->email);
        $checkStmt->execute();
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists > 0) {
            // Email already exists
            return false; // or throw new Exception("Email already exists.");
        }

        // Proceed with the insert if the email does not exist
        $sql_query = "INSERT INTO " . $this->table_name . " (name, email, password,  role, state, district, block) VALUES (:name, :email, :password, :role, :state, :district, :block)";
        $stmt = $this->conn->prepare($sql_query);

        // Sanitize input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->state = htmlspecialchars(strip_tags($this->state));
        $this->district = htmlspecialchars(strip_tags($this->district));
        $this->block = htmlspecialchars(strip_tags($this->block));

        // Bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT); // Hash the password
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':district', $this->district);
        $stmt->bindParam(':block', $this->block);

        // Execute and check for errors
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Read All Employee
    public function read() {
        $sql_query = "SELECT id, name, email, state, role, state, district, block FROM". $this->table_name;
        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Read one by email
    public function readOne() {
        $sql_query = "SELECT id, name, email, state, role, state, district, block FROM ". $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql_query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            return $row;
        } 
        return null;
    }

    public function readOneWithPass() {
        $sql_query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql_query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        // Fetch the result and assign it to $row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return $row;
        } 
        return null;
    }

    // Read By Name
    public function readByName()
    {
        $sql_query = "SELECT id, name, email, state, role, state, district, block FROM " . $this->table_name . " WHERE name LIKE :searchTerm";
        $stmt = $this->conn->prepare($sql_query);

    
        $searchTerm = htmlspecialchars(strip_tags($this->query));

        $searchTerm = "%" . $searchTerm . "%";

        // Bind the search term parameter
        $stmt->bindParam(':searchTerm', $searchTerm);


        // Execute the query
        $stmt->execute();
        
        // Fetch all matching results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // update by email
    public function update(){
        $query = "UPDATE " . $this->table_name . " SET ";
        $conditions = [];
        $params = [];

        // Check each property and add to the query if it is set (not null or empty)
        if (!empty($this->name)) {
            $conditions[] = "name = :name";
            $params[':name'] = htmlspecialchars(strip_tags($this->name));
        }
        if (!empty($this->role)) {
            $conditions[] = "role = :role";
            $params[':role'] = htmlspecialchars(strip_tags($this->role));
        }
        if (!empty($this->state)) {
            $conditions[] = "state = :state";
            $params[':state'] = htmlspecialchars(strip_tags($this->state));
        }
        if (!empty($this->district)) {
            $conditions[] = "district = :district";
            $params[':district'] = htmlspecialchars(strip_tags($this->district));
        }
        if (!empty($this->block)) {
            $conditions[] = "block = :block";
            $params[':block'] = htmlspecialchars(strip_tags($this->block));
        }


        // If no properties have changed, return false
        if (empty($conditions)) {
            return false;
        }

        // Join the conditions to form the final query
        $query .= implode(", ", $conditions) . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        // Sanitize the email
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);

        // Bind the other parameters
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete an employee by email
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}

?>