<?php
class Patient {
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
    
    public function getAllPatients($limit = 100, $offset = 0) {
        $query = "SELECT p.*, o.first_name, o.last_name, o.phone, o.email 
                  FROM patients p 
                  JOIN owners o ON p.owner_id = o.owner_id 
                  ORDER BY p.name 
                  LIMIT :limit OFFSET :offset";
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
            error_log("PDO Exception in getAllPatients: " . $e->getMessage());
            return false;
        }
    }
    
    public function getPatients($search = '', $species = '', $status = '', $owner = '', $limit = 100, $offset = 0) {
        $params = [];
        $conditions = [];
        
        // Build dynamic query based on filters
        if (!empty($search)) {
            $conditions[] = "(p.name LIKE :search OR p.breed LIKE :search OR o.first_name LIKE :search OR o.last_name LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        if (!empty($species)) {
            $conditions[] = "p.species = :species";
            $params[':species'] = $species;
        }
        
        if (!empty($status)) {
            $conditions[] = "p.status = :status";
            $params[':status'] = $status;
        }
        
        if (!empty($owner)) {
            $conditions[] = "(o.first_name LIKE :owner OR o.last_name LIKE :owner)";
            $params[':owner'] = '%' . $owner . '%';
        }
        
        $whereClause = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";
        
        $query = "SELECT p.*, o.first_name, o.last_name, o.phone, o.email,
                  CONCAT(o.first_name, ' ', o.last_name) as owner_name,
                  TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) as age
                  FROM patients p 
                  JOIN owners o ON p.owner_id = o.owner_id 
                  $whereClause
                  ORDER BY p.name 
                  LIMIT :limit OFFSET :offset";
                  
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            
            // Bind all search parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
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
            error_log("PDO Exception in getPatients: " . $e->getMessage());
            return false;
        }
    }
    
    public function getPatientById($patientId) {
        $query = "SELECT p.*, o.first_name, o.last_name, o.phone, o.email,
                  CONCAT(o.first_name, ' ', o.last_name) as owner_name,
                  TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) as age
                  FROM patients p 
                  JOIN owners o ON p.owner_id = o.owner_id 
                  WHERE p.patient_id = :patient_id";
                  
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':patient_id', $patientId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getPatientById: " . $e->getMessage());
            return false;
        }
    }
}