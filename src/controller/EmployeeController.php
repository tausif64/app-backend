<?php

require_once __DIR__.'/../services/EmployeeService.php';
class EmployeeController {
    private $employeeService;

    public function __construct($db)
    {
        $this->employeeService = new EmployeeService($db);
    }

    public function get(){
        $employees = $this->employeeService->readEmployees();
        return json_encode($employees);
    }

    public function getByName($query){
        $employees = $this->employeeService->readEmployees($query);
        return json_encode($employees);
    }

    public function post($data) {
        $name = $data['name'];
        $email = $data['email'];
        $designation = $data['designation'];
        $role = $data['role'];
        $mobile = $data['mobile'];
        $second_mobile = $data['second_mobile'];
        $address = $data['address']; 

        // Validate input
        if (empty($name)) {
            return "Invalid Input. Name is required";
        }
        if (empty($email)) {
            return "Invalid Input. Email is required";
        }
        if (empty($designation)) {
            return "Invalid Input. Desigination is required";
        }
        if (empty($role)) {
            return "Invalid Input. Role is required";
        }
        if (empty($mobile)) {
            return "Invalid Input. Number is required";
        }
        if (empty($address)) {
            return "Invalid Input. Address is required";
        }
        
        return $this->employeeService->createEmployee($name, $email, $designation, $role, $mobile, $second_mobile, $address);
    }

    public function put($data) {
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $designation = $data['designation'] ?? '';
        $role = $data['role'] ?? '';
        $mobile = $data['mobile'] ?? '';
        $second_mobile = $data['second_mobile'] ?? '';
        $address = $data['address'] ?? ''; 
    }

    public function delete($email)
    {
        return $this->employeeService->deleteEmployee($employee)
    }

}

?>