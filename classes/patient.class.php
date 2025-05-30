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
    
    public function createPatient($patientData, $ownerData) {
        try {
            // Start transaction
            $this->Conn->beginTransaction();
            
            // First, check if owner exists or create new owner
            $owner_id = $this->getOrCreateOwner($ownerData);
            if (!$owner_id) {
                throw new Exception("Failed to create or find owner");
            }
            
            // Create patient
            $query = "INSERT INTO patients (name, species, breed, gender, date_of_birth, neutered, weight, microchip_id, allergies, status, owner_id) 
                      VALUES (:name, :species, :breed, :gender, :date_of_birth, :neutered, :weight, :microchip_id, :allergies, :status, :owner_id)";
            
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':name', $patientData['name']);
            $stmt->bindParam(':species', $patientData['species']);
            $stmt->bindParam(':breed', $patientData['breed']);
            $stmt->bindParam(':gender', $patientData['gender']);
            $stmt->bindParam(':date_of_birth', $patientData['date_of_birth']);
            $stmt->bindParam(':neutered', $patientData['neutered']);
            $stmt->bindParam(':weight', $patientData['weight']);
            $stmt->bindParam(':microchip_id', $patientData['microchip_id']);
            $stmt->bindParam(':allergies', $patientData['allergies']);
            $stmt->bindParam(':status', $patientData['status']);
            $stmt->bindParam(':owner_id', $owner_id);
            
            $stmt->execute();
            $patient_id = $this->Conn->lastInsertId();
            
            // Commit transaction
            $this->Conn->commit();
            
            return $patient_id;
            
        } catch (PDOException $e) {
            // Rollback transaction
            $this->Conn->rollback();
            
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in createPatient: " . $e->getMessage());
            return false;
        }
    }
    
    public function updatePatient($patientId, $patientData, $ownerData) {
        try {
            // Start transaction
            $this->Conn->beginTransaction();
            
            // Update owner information
            $owner_id = $this->updateOwnerForPatient($patientId, $ownerData);
            if (!$owner_id) {
                throw new Exception("Failed to update owner information");
            }
            
            // Update patient
            $query = "UPDATE patients SET 
                      name = :name, 
                      species = :species, 
                      breed = :breed, 
                      gender = :gender, 
                      date_of_birth = :date_of_birth, 
                      neutered = :neutered,
                      weight = :weight, 
                      microchip_id = :microchip_id, 
                      allergies = :allergies, 
                      status = :status
                      WHERE patient_id = :patient_id";
            
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':name', $patientData['name']);
            $stmt->bindParam(':species', $patientData['species']);
            $stmt->bindParam(':breed', $patientData['breed']);
            $stmt->bindParam(':gender', $patientData['gender']);
            $stmt->bindParam(':date_of_birth', $patientData['date_of_birth']);
            $stmt->bindParam(':neutered', $patientData['neutered']);
            $stmt->bindParam(':weight', $patientData['weight']);
            $stmt->bindParam(':microchip_id', $patientData['microchip_id']);
            $stmt->bindParam(':allergies', $patientData['allergies']);
            $stmt->bindParam(':status', $patientData['status']);
            $stmt->bindParam(':patient_id', $patientId, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // Commit transaction
            $this->Conn->commit();
            
            return true;
            
        } catch (PDOException $e) {
            // Rollback transaction
            $this->Conn->rollback();
            
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in updatePatient: " . $e->getMessage());
            return false;
        }
    }
    
    public function deletePatient($patientId) {
        try {
            // For safety, we'll archive instead of hard delete
            $query = "UPDATE patients SET status = 'archived', archived_at = NOW() WHERE patient_id = :patient_id";
            
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':patient_id', $patientId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in deletePatient: " . $e->getMessage());
            return false;
        }
    }
    
    private function getOrCreateOwner($ownerData) {
        try {
            // First try to find existing owner by email
            $query = "SELECT owner_id FROM owners WHERE email = :email";
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':email', $ownerData['email']);
            $stmt->execute();
            
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($existing) {
                // Update existing owner
                $update_query = "UPDATE owners SET 
                                first_name = :first_name, 
                                last_name = :last_name, 
                                phone = :phone, 
                                address = :address 
                                WHERE owner_id = :owner_id";
                
                $update_stmt = $this->Conn->prepare($update_query);
                $update_stmt->bindParam(':first_name', $ownerData['first_name']);
                $update_stmt->bindParam(':last_name', $ownerData['last_name']);
                $update_stmt->bindParam(':phone', $ownerData['phone']);
                $update_stmt->bindParam(':address', $ownerData['address']);
                $update_stmt->bindParam(':owner_id', $existing['owner_id']);
                $update_stmt->execute();
                
                return $existing['owner_id'];
            } else {
                // Create new owner
                $insert_query = "INSERT INTO owners (first_name, last_name, email, phone, address) 
                                VALUES (:first_name, :last_name, :email, :phone, :address)";
                
                $insert_stmt = $this->Conn->prepare($insert_query);
                $insert_stmt->bindParam(':first_name', $ownerData['first_name']);
                $insert_stmt->bindParam(':last_name', $ownerData['last_name']);
                $insert_stmt->bindParam(':email', $ownerData['email']);
                $insert_stmt->bindParam(':phone', $ownerData['phone']);
                $insert_stmt->bindParam(':address', $ownerData['address']);
                $insert_stmt->execute();
                
                return $this->Conn->lastInsertId();
            }
            
        } catch (PDOException $e) {
            error_log("PDO Exception in getOrCreateOwner: " . $e->getMessage());
            return false;
        }
    }
    
    private function updateOwnerForPatient($patientId, $ownerData) {
        try {
            // Get current owner_id for the patient
            $query = "SELECT owner_id FROM patients WHERE patient_id = :patient_id";
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':patient_id', $patientId);
            $stmt->execute();
            
            $patient = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$patient) {
                return false;
            }
            
            // Update the existing owner
            $update_query = "UPDATE owners SET 
                            first_name = :first_name, 
                            last_name = :last_name, 
                            email = :email, 
                            phone = :phone, 
                            address = :address 
                            WHERE owner_id = :owner_id";
            
            $update_stmt = $this->Conn->prepare($update_query);
            $update_stmt->bindParam(':first_name', $ownerData['first_name']);
            $update_stmt->bindParam(':last_name', $ownerData['last_name']);
            $update_stmt->bindParam(':email', $ownerData['email']);
            $update_stmt->bindParam(':phone', $ownerData['phone']);
            $update_stmt->bindParam(':address', $ownerData['address']);
            $update_stmt->bindParam(':owner_id', $patient['owner_id']);
            $update_stmt->execute();
            
            return $patient['owner_id'];
            
        } catch (PDOException $e) {
            error_log("PDO Exception in updateOwnerForPatient: " . $e->getMessage());
            return false;
        }
    }
}