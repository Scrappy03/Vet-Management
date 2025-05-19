<?php
class Appointment {
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
    
    public function getAppointmentsForRange($start, $end) {
        $query = "SELECT a.*, p.name as pet_name, p.species, p.breed,
                  CONCAT(o.first_name, ' ', o.last_name) as owner_name
                  FROM appointments a
                  JOIN patients p ON a.patient_id = p.patient_id
                  JOIN owners o ON p.owner_id = o.owner_id
                  WHERE a.start_time BETWEEN :start AND :end
                  ORDER BY a.start_time";
                  
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':start', $start);
            $stmt->bindParam(':end', $end);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getAppointmentsForRange: " . $e->getMessage());
            return false;
        }
    }
    
    public function createAppointment($patientId, $staffId, $type, $startTime, $endTime, $status = 'upcoming', $notes = null, $careStatus = null) {
        $query = "INSERT INTO appointments 
                 (patient_id, staff_id, appointment_type, start_time, end_time, status, notes, care_status)
                 VALUES (:patient_id, :staff_id, :type, :start_time, :end_time, :status, :notes, :care_status)";
                 
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':patient_id', $patientId);
            $stmt->bindParam(':staff_id', $staffId);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':care_status', $careStatus);
            
            $result = $stmt->execute();
            
            if ($result) {
                // Update patient's last visit date
                $updateQuery = "UPDATE patients SET last_visit = CURDATE() WHERE patient_id = :patient_id";
                $updateStmt = $this->Conn->prepare($updateQuery);
                $updateStmt->bindParam(':patient_id', $patientId);
                $updateStmt->execute();
                
                return $this->Conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in createAppointment: " . $e->getMessage());
            return false;
        }
    }
}