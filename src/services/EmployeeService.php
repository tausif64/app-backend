<?php
require_once __DIR__ . '/../modal/EmployeeModal.php';

class EmployeeService {
    private $employee;

    public function __construct($db) {
        $this->employee = new EmployeeModal($db);
    }

    // Create a new employee
    public function createEmployee($name, $email, $designation, $role, $mobile, $second_mobile, $address) {
        $this->employee->name = $name;
        $this->employee->email = $email;
        $this->employee->role = $role;
        $this->employee->designation = $designation;
        $this->employee->mobile = $mobile;
        $this->employee->second_mobile = $second_mobile;
        $this->employee->address = $address;

        if ($this->employee->create()) {
            return "Employee created successfully.";
        } else {
            return "Unable to create employee.";
        }
    }

    // Read all employees
    public function readEmployees() {
        $employees = $this->employee->read();
     
        return $employees;
    }
     
    public function readEmployeeByQuery($query) {
        $this->employee->query = $query;
        $employee = $this->employee->readByQuery();
     
        return $employee;
    }

    // Update an employee by email 
    public function updateEmployee($name, $email, $password, $designation, $role, $mobile, $second_mobile, $address) {
        $this->employee->name = $name;
        $this->employee->email = $email;
        $this->employee->password = $password;
        $this->employee->role = $role;
        $this->employee->designation = $designation;
        $this->employee->mobile = $mobile;
        $this->employee->second_mobile = $second_mobile;
        $this->employee->address = $address;
    
            if ($this->employee->update()) {
            return 'Employee updated successfully.';
        } else {
            return 'Unable to update employee.';
        }
    }

    // Delete an employee
    public function deleteEmployee($email) {
        $this->employee->email = $email;

        if ($this->employee->delete()) {
            return 'Employee deleted successfully.';
        } else {
            return 'Unable to delete employee.';
        }
    }

}

?>