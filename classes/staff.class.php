<?php
class Staff {
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
    
    public function getAllStaff($limit = 100, $offset = 0) {
        $query = "SELECT * FROM staff ORDER BY first_name, last_name LIMIT :limit OFFSET :offset";
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getAllStaff: " . $e->getMessage());
            return false;
        }
    }
    
    public function getStaff($search = '', $role = '', $status = '', $limit = 100, $offset = 0) {
        $params = [];
        $conditions = [];
        
        // Build dynamic query based on filters
        if (!empty($search)) {
            $conditions[] = "(first_name LIKE :search OR last_name LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        if (!empty($role) && $role !== 'all') {
            $conditions[] = "role = :role";
            $params[':role'] = $role;
        }
        
        if (!empty($status) && $status !== 'all') {
            $conditions[] = "status = :status";
            $params[':status'] = $status;
        }
        
        $query = "SELECT * FROM staff";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
        $query .= " ORDER BY first_name, last_name LIMIT :limit OFFSET :offset";
        
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            
            // Bind parameters
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getStaff: " . $e->getMessage());
            return false;
        }
    }
    
    public function getStaffById($staff_id) {
        $query = "SELECT * FROM staff WHERE staff_id = :staff_id";
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getStaffById: " . $e->getMessage());
            return false;
        }
    }
    
    public function addStaff($data) {
        // Validate required fields
        $required_fields = ['first_name', 'last_name', 'role', 'email', 'phone', 'start_date', 'status'];
        
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                $this->lastError = [
                    'code' => 'VALIDATION_ERROR',
                    'message' => "Field '{$field}' is required"
                ];
                return false;
            }
        }
        
        // Check if email already exists
        if ($this->emailExists($data['email'])) {
            $this->lastError = [
                'code' => 'DUPLICATE_EMAIL',
                'message' => 'Email address already exists'
            ];
            return false;
        }
        
        $query = "INSERT INTO staff (first_name, last_name, role, email, password, phone, specialties, education, bio, profile_image, start_date, status, created_at, updated_at) 
                  VALUES (:first_name, :last_name, :role, :email, :password, :phone, :specialties, :education, :bio, :profile_image, :start_date, :status, NOW(), NOW())";
        
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            
            // Hash password if provided, otherwise set a default temporary password
            $password = isset($data['password']) && !empty($data['password']) 
                       ? password_hash($data['password'], PASSWORD_DEFAULT)
                       : password_hash('temp123', PASSWORD_DEFAULT); // Temporary password
            
            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':role', $data['role']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':specialties', $data['specialties'] ?? '');
            $stmt->bindParam(':education', $data['education'] ?? '');
            $stmt->bindParam(':bio', $data['bio'] ?? '');
            $stmt->bindParam(':profile_image', $data['profile_image'] ?? '');
            $stmt->bindParam(':start_date', $data['start_date']);
            $stmt->bindParam(':status', $data['status']);
            
            $stmt->execute();
            
            return $this->Conn->lastInsertId();
            
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in addStaff: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateStaff($staff_id, $data) {
        // Check if staff member exists
        if (!$this->getStaffById($staff_id)) {
            $this->lastError = [
                'code' => 'NOT_FOUND',
                'message' => 'Staff member not found'
            ];
            return false;
        }
        
        // Build dynamic update query
        $fields = [];
        $params = [':staff_id' => $staff_id];
        
        $allowed_fields = ['first_name', 'last_name', 'role', 'email', 'phone', 'specialties', 'education', 'bio', 'profile_image', 'start_date', 'status'];
        
        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = :{$field}";
                $params[":{$field}"] = $data[$field];
            }
        }
        
        // Handle password update separately
        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (empty($fields)) {
            $this->lastError = [
                'code' => 'NO_DATA',
                'message' => 'No data provided for update'
            ];
            return false;
        }
        
        $fields[] = "updated_at = NOW()";
        
        $query = "UPDATE staff SET " . implode(', ', $fields) . " WHERE staff_id = :staff_id";
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in updateStaff: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteStaff($staff_id) {
        // Check if staff member exists
        if (!$this->getStaffById($staff_id)) {
            $this->lastError = [
                'code' => 'NOT_FOUND',
                'message' => 'Staff member not found'
            ];
            return false;
        }
        
        $query = "DELETE FROM staff WHERE staff_id = :staff_id";
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in deleteStaff: " . $e->getMessage());
            return false;
        }
    }
    
    public function emailExists($email, $exclude_staff_id = null) {
        $query = "SELECT staff_id FROM staff WHERE email = :email";
        if ($exclude_staff_id) {
            $query .= " AND staff_id != :exclude_staff_id";
        }
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':email', $email);
            if ($exclude_staff_id) {
                $stmt->bindParam(':exclude_staff_id', $exclude_staff_id, PDO::PARAM_INT);
            }
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            error_log("PDO Exception in emailExists: " . $e->getMessage());
            return false;
        }
    }
    
    public function getStaffCount($search = '', $role = '', $status = '') {
        $params = [];
        $conditions = [];
        
        if (!empty($search)) {
            $conditions[] = "(first_name LIKE :search OR last_name LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        if (!empty($role) && $role !== 'all') {
            $conditions[] = "role = :role";
            $params[':role'] = $role;
        }
        
        if (!empty($status) && $status !== 'all') {
            $conditions[] = "status = :status";
            $params[':status'] = $status;
        }
        
        $query = "SELECT COUNT(*) as total FROM staff";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
        
        try {
            $stmt = $this->Conn->prepare($query);
            
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'];
            
        } catch (PDOException $e) {
            error_log("PDO Exception in getStaffCount: " . $e->getMessage());
            return 0;
        }
    }
}
?>
