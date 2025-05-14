<?php
class User {
    protected $Conn;
    protected $lastError = [];
    protected $lastQuery = "";
    
    public function __construct($Conn){
        $this->Conn = $Conn;
    }
    
    public function getErrorInfo() {
        return $this->lastError;
    }
    
    public function getLastQuery() {
        return $this->lastQuery;
    }

    public function createUser($user_data){
        // Debug data for troubleshooting
        if(isset($_GET['debug'])) {
            echo "<!-- User data received: " . print_r($user_data, true) . " -->";
        }
        
        // Set default values for any missing fields
        $user_data = array_merge([
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'password' => '',
            'phone' => '',
            'role' => 'User',
            'status' => 'Active',
            'specialties' => '',
            'education' => '',
            'bio' => '',
            'profile_image' => '',
            'start_date' => date('Y-m-d')
        ], $user_data);
        
        // Log registration attempt
        error_log("Creating user with email: " . $user_data['email']);
        
        // Check if we have minimum required data
        if(empty($user_data['email']) || empty($user_data['password'])) {
            $this->lastError = ['message' => 'Email and password are required'];
            return false;
        }
        
        // Generate secure password hash
        $sec_password = password_hash($user_data['password'], PASSWORD_DEFAULT);

        // Use staff table instead of users table
        $query = "INSERT INTO staff (first_name, last_name, role, email, password, phone, specialties, education, bio, profile_image, start_date, status) 
                  VALUES (:first_name, :last_name, :role, :email, :password, :phone, :specialties, :education, :bio, :profile_image, :start_date, :status)";
        
        // Save for debugging
        $this->lastQuery = $query;
        
        $stmt = $this->Conn->prepare($query);

        try {
            // Prepare execution parameters
            $params = array(
                'first_name' => $user_data['first_name'],
                'last_name' => $user_data['last_name'],
                'role' => $user_data['role'],
                'email' => $user_data['email'],
                'password' => $sec_password,
                'phone' => $user_data['phone'],
                'specialties' => $user_data['specialties'],
                'education' => $user_data['education'],
                'bio' => $user_data['bio'],
                'profile_image' => $user_data['profile_image'],
                'start_date' => $user_data['start_date'],
                'status' => $user_data['status']
            );
            
            // For debugging
            if(isset($_GET['debug'])) {
                error_log("SQL parameters: " . print_r($params, true));
            }
            
            // Execute the statement
            $result = $stmt->execute($params);
            
            if (!$result) {
                $this->lastError = $stmt->errorInfo();
                error_log("User creation failed. Error: " . print_r($this->lastError, true));
            } else {
                // Success - get the new user ID
                $userId = $this->Conn->lastInsertId();
                error_log("User created successfully with ID: " . $userId);
            }
            
            return $result;
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in createUser: " . $e->getMessage());
            return false;
        }
    }

    public function loginUser($email, $password){
        // Save query for debugging
        $query = "SELECT * FROM staff WHERE email = :email";
        $this->lastQuery = $query;
        
        try {
            // Prepare and execute the query
            $stmt = $this->Conn->prepare($query);
            $stmt->execute(array('email' => $email));
            
            // Fetch the user data
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password and return user data if valid
            if($user_data && password_verify($password, $user_data['password'])) {
                return $user_data;
            } else {
                $this->lastError = ['message' => 'Invalid email or password'];
                return false;
            }
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            return false;
        }
    }
    
    // Add method to check if email exists
    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM staff WHERE email = :email";
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->execute(['email' => $email]);
            
            return ($stmt->fetchColumn() > 0);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            return false;
        }
    }
    
    // Add method to get user by ID with proper error handling
    public function getUserById($user_id) {
        $query = "SELECT * FROM staff WHERE staff_id = :id";
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->execute(['id' => $user_id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getUserById: " . $e->getMessage());
            return false;
        }
    }
}