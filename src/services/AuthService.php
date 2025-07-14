<?php
require_once __DIR__ . '/../modal/AuthModal.php';

class AuthService {
    private $authModal;

    public function __construct($db) {
        $this->authModal = new AuthModal($db);
    }

    // Create a new authModal
    public function createUser($name, $email, $password, $role, $state, $district, $block) {
        $this->authModal->name = $name;
        $this->authModal->email = $email;
        $this->authModal->password = $password;
        $this->authModal->role = $role;
        $this->authModal->state = $state;
        $this->authModal->district = $district;
        $this->authModal->block = $block;

        if ($this->authModal->create()) {
            http_response_code(201);
            return "authModal created successfully.";
        } else {
            http_response_code(500);
            return "Unable to create authModal.";
        }
    }

    // Read all users
    public function readUser() {
        $users = $this->authModal->read();
        
        return $users;
    }
     
    public function readByEmail($email) {
        $this->authModal->email = $email;
        $authModal = $this->authModal->readOne();
     
        return $authModal;
    }

    public function readWithPass($email) {
        $this->authModal->email = $email;
        $user = $this->authModal->readOneWithPass();

        return $user;
    }

    public function readUserByQuery($query) {
        $this->authModal->query = $query;
        $user = $this->authModal->readByQuery();
     
        return $user;
    }

    // Update an authModal by email 
    public function updateUser($name, $email, $role, $state, $district, $block) {
        $this->authModal->name = $name;
        $this->authModal->email = $email;
        $this->authModal->role = $role;
        $this->authModal->state = $state;
        $this->authModal->district = $district;
        $this->authModal->block = $block;
    
            if ($this->authModal->update()) {
            return 'authModal updated successfully.';
        } else {
            return 'Unable to update authModal.';
        }
    }

    // Delete an authModal
    public function deleteUser($email) {
        $this->authModal->email = $email;

        if ($this->authModal->delete()) {
            return 'authModal deleted successfully.';
        } else {
            return 'Unable to delete authModal.';
        }
    } 

}

?>