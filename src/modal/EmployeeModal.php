<?php 
 
class EmployeeModal {
    private $conn;
    private $table_name = "employee";

    public $id;
    public $name;
    public $email;
    public $designation;
    public $role;
    public $mobile;
    public $second_mobile;
    public $address;
    public $query;

    
    public function __construct($db)
    {
        $this->conn = $db;
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
        $sql_query = "INSERT INTO " . $this->table_name . " (name, email, designation, role, mobile, second_mobile, address) VALUES (:name, :email, :designation, :role, :mobile, :second_mobile, :address)";
        $stmt = $this->conn->prepare($sql_query);

        // Sanitize input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->designation = htmlspecialchars(strip_tags($this->designation));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));
        $this->second_mobile = htmlspecialchars(strip_tags($this->second_mobile));
        $this->address = htmlspecialchars(strip_tags($this->address));

        // Bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':designation', $this->designation);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':mobile', $this->mobile);
        $stmt->bindParam(':second_mobile', $this->second_mobile);
        $stmt->bindParam(':address', $this->address);

        // Execute and check for errors
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    
    // Read All Employee
    public function read() {
        $sql_query = "SELECT id, name, email, designation, role, mobile, second_mobile, address FROM". $this->table_name;
        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read one by email
    public function readOne() {
        $sql_query = "SELECT id, name, email, designation, role, mobile, second_mobile, address FROM". $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql_query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $row->fetch(PDO::FETCH_ASSOC);
        if($row){
            return $row;
        } 
        return null;
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
        if (!empty($this->designation)) {
            $conditions[] = "designation = :designation";
            $params[':designation'] = htmlspecialchars(strip_tags($this->designation));
        }
        
        if (!empty($this->role)) {
            $conditions[] = "role = :role";
            $params[':role'] = htmlspecialchars(strip_tags($this->role));
        }
        if (!empty($this->mobile)) {
            $conditions[] = "mobile = :mobile";
            $params[':mobile'] = htmlspecialchars(strip_tags($this->role));
        }
        if (!empty($this->second_mobile)) {
            $conditions[] = "second_mobile = :second_mobile";
            $params[':second_mobile'] = htmlspecialchars(strip_tags($this->role));
        }
        if (!empty($this->address)) {
            $conditions[] = "address = :address";
            $params[':address'] = htmlspecialchars(strip_tags($this->role));
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

    // Read By Name
    public function readByName()
    {
        $sql_query = "SELECT id, name, email, designation, role, mobile, second_mobile, address FROM " . $this->table_name . " WHERE name LIKE :searchTerm";
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
    
    // Delete an employee by id
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